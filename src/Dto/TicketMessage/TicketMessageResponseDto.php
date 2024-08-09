<?php

namespace Maxdev\Tickets\Dto\TicketMessage;

use Spatie\LaravelData\Data;

class TicketMessageResponseDto extends Data
{
	public function __construct(
		public int $ticket_id,
		public int $id,
		public int $user_id,
		public string $text,
		public string $date_created,
		public string $date_updated,
		public array $files = [],
	)
	{

	}
}