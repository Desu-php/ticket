<?php

namespace Maxdev\Tickets\Dto\TicketService;

use Maxdev\Tickets\Enums\TicketStatusEnum;
use Spatie\LaravelData\Data;

class TicketCreateDto extends Data
{
	public function __construct(
		public int $user_id,
		public string $subject,
		public string $message,
		public TicketStatusEnum $status = TicketStatusEnum::Created,
		public array $attachments = []
	)
	{
	}
}