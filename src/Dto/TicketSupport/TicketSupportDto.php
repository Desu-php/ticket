<?php

namespace Maxdev\Tickets\Dto\TicketSupport;

use Spatie\LaravelData\Data;

class TicketSupportDto extends Data
{
	public function __construct(
		public ?string $owner_email,
		public ?string $owner_name,
		public int $owner_id,
	)
	{
	}
}