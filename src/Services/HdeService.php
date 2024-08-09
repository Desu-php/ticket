<?php
declare(strict_types=1);

namespace Maxdev\Tickets\Services;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Arr;
use Maxdev\Tickets\Contracts\HdeConfigServiceContract;
use Maxdev\Tickets\Contracts\TicketDriverContract;
use Maxdev\Tickets\Dto\Ticket\CreateTicketDto;
use Maxdev\Tickets\Dto\Ticket\HdeCreateTicketDto;
use Maxdev\Tickets\Dto\TicketMessage\CreateTicketMessageDto;
use Maxdev\Tickets\Dto\TicketMessage\HdeCreateTicketMessageDto;
use Maxdev\Tickets\Dto\TicketMessage\TicketMessageResponseDto;
use Maxdev\Tickets\Dto\TicketMessage\TicketMessagesResponseDto;
use Maxdev\Tickets\Dto\Ticket\TicketResponseDto;
use Maxdev\Tickets\Dto\Ticket\TicketParamsDto;
use Maxdev\Tickets\Dto\Ticket\TicketsResponseDto;
use Maxdev\Tickets\Dto\UpdateTicketDto;
use Maxdev\Tickets\Dto\User\UserResponseDto;
use Maxdev\Tickets\Enums\TicketStatusEnum;
use Maxdev\Tickets\Exceptions\HDEUserNotFoundException;
use Maxdev\Tickets\Models\TicketAttachment;

class HdeService implements TicketDriverContract
{
	public function __construct(
		protected HDEClient $client,
	)
	{
	}

	public function createTicket(CreateTicketDto $dto): TicketResponseDto
	{
		$configService = app(HdeConfigServiceContract::class);

		$dto = HdeCreateTicketDto::from([
			...$dto->toArray(),
			'custom_fields' => $configService->toCustomFields(),
			'files'         => $this->filesToFormData($dto->files)
		]);

		$response = $this->client->createTicket($dto);

		return TicketResponseDto::from($response->json('data'));
	}

	public function getTickets(?TicketParamsDto $dto = null, int $page = 1): TicketsResponseDto
	{
		$response = $this->client->getTickets($page, $dto?->toArray() ?? []);

		return TicketsResponseDto::from($response->json());
	}

	public function getTicketMessages(int $ticketId, int $page = 1): TicketMessagesResponseDto
	{
		$response = $this->client->getTicketMessages($ticketId, $page);

		return TicketMessagesResponseDto::from($response->json());
	}

	public function createTicketMessage(int $ticketId, CreateTicketMessageDto $dto): TicketMessageResponseDto
	{
		$response = $this->client->createTicketMessage($ticketId,
			new HdeCreateTicketMessageDto(
				text: $dto->text,
				user_id: $dto->user_id,
				files: $this->filesToFormData($dto->files)
			)
		);

		return TicketMessageResponseDto::from($response->json(['data']));
	}


	private function filesToFormData(Collection $files): array
	{
		return $files->map(
			fn(TicketAttachment $attachment) => [
				'name'     => 'files[]',
				'contents' => file_get_contents($attachment->full_path),
				'filename' => $attachment->original_name
			],
		)->all();
	}

	/**
	 * @throws HDEUserNotFoundException
	 */
	public function searchUser(string $search): UserResponseDto
	{
		$response = $this->client->searchUser($search);

		$data = $response->json('data', []);

		$user = Arr::where($data, fn(array $item) => $item['email'] == $search);

		if (!$user) {
			throw  new HDEUserNotFoundException('User not found');
		}

		return UserResponseDto::from($user[0]);
	}

	public function closeTicket(int $ticketId): void
	{
		$dto = new UpdateTicketDto(
			status_id: TicketStatusEnum::Closed->value
		);

		$this->client->updateTicket($ticketId,
			$dto
		);
	}
}