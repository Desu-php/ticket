<?php
declare(strict_types=1);

namespace Maxdev\Tickets\Dto\TicketService;

use Maxdev\Tickets\Enums\TicketMessageStatusEnum;
use Maxdev\Tickets\Enums\TicketStatusEnum;
use Maxdev\Tickets\Models\Ticket;
use Spatie\LaravelData\Data;

class TicketMessageCreateDto extends Data
{
	public function __construct(
		public string $message,
		public ?int $user_id,
		public Ticket $ticket,
		public array $attachments = [],
		public TicketMessageStatusEnum $status = TicketMessageStatusEnum::Created,
		public ?string $message_created_at = null,
		public ?int $external_id = null,
		public TicketStatusEnum $ticketStatus = TicketStatusEnum::WaitingForManager,
		public ?int $ticket_support_id = null,
		public ?int $user_external_id = null

	)
	{
		if (!$this->message_created_at) {
			$this->message_created_at = now()->toDateTimeString();
		}
	}
}