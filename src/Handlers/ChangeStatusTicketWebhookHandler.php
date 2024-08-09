<?php
declare(strict_types=1);

namespace Maxdev\Tickets\Handlers;

use Maxdev\Tickets\Contracts\Webhook\ChangeStatusTicketWebhookValidatorContract;
use Maxdev\Tickets\Dto\Ticket\Webhook\ChangeStatusTicketWebhookDto;
use Maxdev\Tickets\Models\Ticket;
use Maxdev\Tickets\Services\TicketMessageService;

class ChangeStatusTicketWebhookHandler
{
	public function __construct(
		protected TicketMessageService $service,
		protected ChangeStatusTicketWebhookValidatorContract $validator
	)
	{
	}

	public function handle(ChangeStatusTicketWebhookDto $dto): void
	{
		if (!$this->validator->validate($dto)) {
			return;
		}

		$ticket = Ticket::where('external_id', $dto->id)
		                ->firstOrFail();

		$ticket->update(['status' => $dto->status->getTicketStatus()]);
	}
}