<?php
declare(strict_types=1);

namespace Maxdev\Tickets\Services;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Maxdev\Tickets\Dto\CreateTicketCommentDto;
use Maxdev\Tickets\Dto\Ticket\HdeCreateTicketDto;
use Maxdev\Tickets\Dto\TicketMessage\HdeCreateTicketMessageDto;
use Maxdev\Tickets\Dto\UpdateTicketDto;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Http\Client\Request;
use Illuminate\Http\Client\RequestException;
use Maxdev\Tickets\Exceptions\HDELimitExceptions;
use Maxdev\Tickets\Exceptions\TicketIsClosedException;
use Psr\Http\Message\ResponseInterface;

class HDEClient
{
	protected PendingRequest $client;

	const BANNED_CACHE_KEY = 'hde_banned_cache_key';

	private const RATE_LIMIT_HEADER = 'X-Rate-Limit-Remaining';

	public function __construct()
	{
		$this->client = Http::withBasicAuth(config('max_tickets.hde.username'), config('max_tickets.hde.password'))
		                    ->beforeSending(function (Request $request) {
			                    if ($this->isBanned()) {
				                    throw new HDELimitExceptions('HDE query limit (300) exceeded');
			                    }
		                    })
		                    ->baseUrl(config('max_tickets.hde.base_url'))
		                    ->withResponseMiddleware(function (ResponseInterface $response) {
			                    $this->prepareResponse($response);

			                    return $response;
		                    })
		                    ->throw(function (Response $response, RequestException $e) {
			                    if ($response->forbidden()) {
				                    $this->blockRequest(now()->addMinutes(config('max_tickets.request_ban_minutes'))->toDateTimeString());
				                    throw new HDELimitExceptions('HDE query limit (300) exceeded');
			                    }
		                    });
	}

	public function getDepartments(int $page = 1): Response
	{
		return $this->client->get('departments/', ['page' => $page]);
	}

	public function getTickets(int $page = 1, array $query = []): Response
	{
		$query['page'] = $page;

		return $this->client->get('tickets', $query);
	}

	public function createTicket(HdeCreateTicketDto $dto): Response
	{
		return $this->client
			->when(!empty($dto->files), fn(PendingRequest $request) => $request->attach($dto->files))
			->post('tickets', $dto->toBody());
	}

	public function updateTicket(int $ticketId, UpdateTicketDto $dto): Response
	{
		return $this->client->put('tickets/' . $ticketId, $dto->toArray());
	}

	public function deleteTicket(int $ticketId): Response
	{
		return $this->client->delete('tickets/' . $ticketId);
	}

	public function getTicketMessages(int $ticketId, int $page = 1): Response
	{
		return $this->client->get('tickets/' . $ticketId . '/posts/', ['page' => $page]);
	}

	/**
	 * @throws TicketIsClosedException
	 * @throws RequestException
	 */
	public function createTicketMessage(int $ticketId, HdeCreateTicketMessageDto $dto): Response
	{
		try {
			return $this->client
				->when(!empty($dto->files), fn(PendingRequest $request) => $request->attach($dto->files))
				->post('tickets/' . $ticketId . '/posts/', Arr::except($dto->toArray(), 'files'));
		} catch (RequestException $exception) {
			if ($exception->response->badRequest()) {
				throw new TicketIsClosedException('Ticket is closed');
			}

			throw $exception;
		}
	}

	public function updateTicketMessage(int $ticketId, int $messageId, string $text): Response
	{
		return $this->client->put("tickets/$ticketId/posts/$messageId", ['text' => $text]);
	}

	public function deleteTicketMessage(int $ticketId, int $messageId): Response
	{
		return $this->client->delete("tickets/$ticketId/posts/$messageId");
	}

	public function getTicketComments(int $ticketId, int $page = 1): Response
	{
		return $this->client->get('tickets/' . $ticketId . '/comments/', ['page' => $page]);
	}

	public function createTicketComment(int $ticketId, CreateTicketCommentDto $dto): Response
	{
		return $this->client->post('tickets/' . $ticketId . '/comments/', $dto->toArray());
	}

	public function updateTicketComment(int $ticketId, int $commentId, string $text): Response
	{
		return $this->client->put('tickets/' . $ticketId . '/comments/' . $commentId, ['text' => $text]);
	}

	public function deleteTicketComment(int $ticketId, int $commentId): Response
	{
		return $this->client->delete('tickets/' . $ticketId . '/comments/' . $commentId);
	}

	public function searchUser(string $search): Response
	{
		return $this->client->get('users', ['search' => $search, 'exact_search' => 0]);
	}

	private function isBanned(): bool
	{
		$dateTime = Cache::get(self::BANNED_CACHE_KEY);

		if (!$dateTime || now()->greaterThan($dateTime)) {
			return false;
		}

		return true;
	}

	private function prepareResponse(ResponseInterface $response): void
	{
		$limit = (int)Arr::first($response->getHeader(self::RATE_LIMIT_HEADER));

		if ($limit <= config('max_tickets.hde.safe_limit')) {
			$this->blockRequest(now()->addSeconds(config('max_tickets.hde.block_duration'))->toDateTimeString());
		}
	}

	private function blockRequest(string $dateTime): void
	{
		Cache::put(self::BANNED_CACHE_KEY, $dateTime);
	}
}
