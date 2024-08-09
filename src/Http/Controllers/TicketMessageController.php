<?php

namespace Maxdev\Tickets\Http\Controllers;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Maxdev\Tickets\Dto\TicketMessage\NewMessageWebhookDto;
use Maxdev\Tickets\Dto\TicketService\TicketMessageCreateDto;
use Maxdev\Tickets\Enums\TicketStatusEnum;
use Maxdev\Tickets\Exceptions\HDELimitExceptions;
use Maxdev\Tickets\Exceptions\InvalidUrlException;
use Maxdev\Tickets\Exceptions\TicketIsClosedException;
use Maxdev\Tickets\Handlers\NewMessageWebhookHandler;
use Maxdev\Tickets\Http\Requests\Ticket\CreateTicketMessageRequest;
use Maxdev\Tickets\Http\Resources\TicketMessageResource;
use Maxdev\Tickets\Http\Responses\SuccessResponse;
use Maxdev\Tickets\Models\Ticket;
use Maxdev\Tickets\Models\TicketMessage;
use Maxdev\Tickets\Services\TicketMessageService;

class TicketMessageController extends Controller
{
	public function __construct(
		protected TicketMessageService $service
	)
	{
	}

	public function store(CreateTicketMessageRequest $request, Ticket $ticket): JsonResource
	{
		abort_if($ticket->isClosed(), 400, 'Ticket is closed');

		$dto = new TicketMessageCreateDto(
			message: $request->message,
			user_id: auth()->id(),
			ticket: $ticket,
			attachments: $request->file('attachments', []),
		);

		DB::beginTransaction();
		$ticketMessage = $this->service->create($dto);

		try {
			$this->service->sendMessageToExternalServiceByEmail($ticketMessage, $dto->ticket->external_id);

			DB::commit();

			return TicketMessageResource::make($ticketMessage);
		} catch (\Throwable $exception) {
			if ($exception instanceof HDELimitExceptions) {
				return TicketMessageResource::make($ticketMessage);
			}

			DB::rollBack();

			if ($exception instanceof TicketIsClosedException) {
				$ticket->update([
					'status' => TicketStatusEnum::Closed
				]);

				abort(400, 'Ticket is closed');
			}

			throw $exception;
		}
	}


	public function getByTicket(int $ticketId): JsonResource
	{
		return TicketMessageResource::collection(
			TicketMessage::where('ticket_id', $ticketId)
			             ->with('attachments')
			             ->whereRelation('ticket', 'user_id', auth()->id())
			             ->get()
		);
	}

	public function getLastMessages(int $ticketId, int $last_id): JsonResource
	{
		return TicketMessageResource::collection(
			TicketMessage::where('ticket_id', $ticketId)
			             ->with('attachments')
			             ->where('id', '>', $last_id)
			             ->whereRelation('ticket', 'user_id', auth()->id())
			             ->get()
		);
	}

	/**
	 * @throws InvalidUrlException
	 */
	public function webhook(NewMessageWebhookDto $dto, NewMessageWebhookHandler $handler): SuccessResponse
	{
		$handler->handle($dto);

		return new SuccessResponse();
	}
}