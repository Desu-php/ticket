<?php
declare(strict_types=1);

namespace Maxdev\Tickets\Handlers;

use Maxdev\Tickets\Contracts\Webhook\ChangeOwnerTicketWebhookValidatorContract;
use Maxdev\Tickets\Dto\Ticket\Webhook\ChangeOwnerTicketWebhookDto;
use Maxdev\Tickets\Dto\TicketSupport\TicketSupportDto;
use Maxdev\Tickets\Models\Ticket;
use Maxdev\Tickets\Services\TicketSupportService;

class ChangeOwnerTicketWebhookHandler
{
	public function __construct(
		protected ChangeOwnerTicketWebhookValidatorContract $validator,
		protected TicketSupportService $ticketSupportService
	)
	{
	}

	public function handle(ChangeOwnerTicketWebhookDto $dto): void
	{
		if (!$this->validator->validate($dto)) {
			return;
		}

		$ticketSupport = $this->ticketSupportService->firstOrCreate(TicketSupportDto::from($dto->toArray()));

		$ticket = Ticket::where('external_id', $dto->id)
		                ->firstOrFail();

		$ticket->update(['ticket_support_id' => $ticketSupport->id]);
	}
}