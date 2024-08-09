<?php
declare(strict_types=1);

namespace Maxdev\Tickets\Http\Controllers;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Routing\Controller;
use Maxdev\Tickets\Contracts\TicketServiceContract;
use Maxdev\Tickets\Dto\Ticket\Webhook\ChangeOwnerTicketWebhookDto;
use Maxdev\Tickets\Dto\Ticket\Webhook\ChangeStatusTicketWebhookDto;
use Maxdev\Tickets\Dto\Ticket\Webhook\NewTicketWebhookDto;
use Maxdev\Tickets\Enums\TicketStatusEnum;
use Maxdev\Tickets\Exceptions\HDELimitExceptions;
use Maxdev\Tickets\Handlers\ChangeOwnerTicketWebhookHandler;
use Maxdev\Tickets\Handlers\ChangeStatusTicketWebhookHandler;
use Maxdev\Tickets\Handlers\NewTicketWebhookHandler;
use Maxdev\Tickets\Http\Requests\Ticket\CreateTicketRequest;
use Maxdev\Tickets\Http\Resources\TicketFullResource;
use Maxdev\Tickets\Http\Resources\TicketResource;
use Maxdev\Tickets\Http\Responses\SuccessResponse;
use Maxdev\Tickets\Models\Ticket;

class TicketController extends Controller
{
	public function __construct(protected TicketServiceContract $service)
	{
	}

	/**
	 * @throws \Throwable
	 */
	public function store(CreateTicketRequest $request): SuccessResponse
	{
		try {
			$this->service->create($request->getDto());
		} catch (\Throwable $exception) {
			if ($exception instanceof HDELimitExceptions) {
				return new SuccessResponse();
			}

			throw $exception;
		}

		return new SuccessResponse();
	}

	public function index(): JsonResource
	{
		return TicketResource::collection(
			Ticket::whereUserId(auth()->id())
			      ->latest('id')
			      ->paginate(config('max_tickets.per_page'))
		);
	}

	public function show(int $id): JsonResource
	{
		return TicketFullResource::make(
			Ticket::whereUserId(auth()->id())
			      ->with('messages.attachments')
			      ->findOrFail($id)
		);
	}

	public function close(int $ticketId): SuccessResponse
	{
		$ticket = Ticket::where('user_id', auth()->id())
		                ->where('status', '!=', TicketStatusEnum::Closed)
		                ->findOrFail($ticketId);

		try {
			$this->service->close($ticket);
		} catch (\Throwable $exception) {
			if ($exception instanceof HDELimitExceptions) {
				return new SuccessResponse();
			}

			throw $exception;
		}


		return new SuccessResponse();
	}

	public function bindUser(Ticket $ticket): SuccessResponse
	{
		$this->service->bindUser($ticket, auth()->user());

		return new SuccessResponse();
	}

	public function newTicketWebhook(NewTicketWebhookDto $dto, NewTicketWebhookHandler $handler): SuccessResponse
	{
		$handler->handle($dto);

		return new SuccessResponse();
	}

	public function changeTicketStatusWebhook(ChangeStatusTicketWebhookDto $dto, ChangeStatusTicketWebhookHandler $handler): SuccessResponse
	{
		$handler->handle($dto);

		return new SuccessResponse();
	}

	public function changeTicketOwnerWebhook(ChangeOwnerTicketWebhookDto $dto, ChangeOwnerTicketWebhookHandler $handler): SuccessResponse
	{
		$handler->handle($dto);

		return new SuccessResponse();
	}
}