<?php

namespace Maxdev\Tickets\Actions;

use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\DB;
use Maxdev\Tickets\Contracts\TicketDriverContract;
use Maxdev\Tickets\Contracts\TicketServiceContract;
use Maxdev\Tickets\Enums\TicketFailedRequestActionEnum;
use Maxdev\Tickets\Enums\TicketFailedRequestStatusEnum;
use Maxdev\Tickets\Exceptions\HDELimitExceptions;
use Maxdev\Tickets\Models\Ticket;
use Maxdev\Tickets\Models\TicketFailedRequest;
use Maxdev\Tickets\Models\TicketMessage;
use Maxdev\Tickets\Services\TicketMessageService;

class TicketFailedRequestAction
{
	public function __construct(
		protected TicketDriverContract $driver,
		protected TicketServiceContract $service,
		protected TicketMessageService $messageService,
	)
	{
	}

	public function execute(): void
	{
		$tickets = TicketFailedRequest::where('status', TicketFailedRequestStatusEnum::Created)
		                              ->where('next_request_at', '<=', now())
		                              ->with(['model' =>
			                                      fn(MorphTo $morphTo) => $morphTo->morphWith([
				                                      Ticket::class        => 'messages.attachments',
				                                      TicketMessage::class => ['attachments', 'ticket']
			                                      ])
		                              ])
		                              ->get();

		foreach ($tickets as $ticket) {
			try {
				$this->request($ticket);

				$ticket->update([
					'try'    => DB::raw('try + 1'),
					'status' => TicketFailedRequestStatusEnum::Success
				]);
			} catch (\Throwable $throwable) {
				if ($throwable instanceof HDELimitExceptions) {
					$ticket->update([
						'next_request_at' => now()->addMinutes(config('max_tickets.request_ban_minutes'))
					]);
					continue;
				}

				$ticket->increment('try');

				if ($ticket->try >= config('max_tickets.failed_request_number_tries')) {
					$ticket->update([
						'status' => TicketFailedRequestStatusEnum::Frozen
					]);
					continue;
				}

				$ticket->update([
					'next_request_at' => now()->addMinutes($ticket->period_minutes)
				]);

				dump($throwable);
			}
		}
	}

	private function request(TicketFailedRequest $ticket): void
	{
		match ($ticket->action) {
			TicketFailedRequestActionEnum::CloseTicket => $this->close($ticket),
			TicketFailedRequestActionEnum::CreateTicket => $this->create($ticket),
			TicketFailedRequestActionEnum::CreateMessage => $this->createMessage($ticket)
		};
	}

	private function close(TicketFailedRequest $ticket): void
	{
		$this->driver->closeTicket($ticket->model->external_id);
	}

	private function create(TicketFailedRequest $ticket): void
	{
		$dto = $this->service->createTicketDtoByTicket($ticket->model);

		$this->service->createExternalTicket($ticket->model, $dto);
	}

	private function createMessage(TicketFailedRequest $ticket): void
	{
		$dto = $this->messageService->createMessageDtoByMessage($ticket->model, $ticket->data['user_id']);

		$this->messageService->createExternalMessage($ticket->model, $dto, $ticket->model->ticket->external_id);
	}
}