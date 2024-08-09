<?php

namespace Maxdev\Tickets\Dto\Ticket;

use Spatie\LaravelData\Data;

class TicketParamsDto extends Data
{
	public function __construct(
		public ?string $search = null,
		public ?int $exact_search = null,
		public ?array $user_list = [],
		public ?array $owner_list = [],
		public ?array $status_list = [],
		public ?array $priority_list = [],
		public ?array $source_list = [],
		public ?int $pid = null,
		public ?array $type_list = [],
		public ?int $freeze = null,
		public ?int $deleted = 0,
		public ?string $from_date_created = null,
		public ?string $to_date_created = null,
		public ?string $from_date_updated = null,
		public ?string $to_date_updated = null,
		public ?array $department_list = [],
		public ?string $order_by = 'date_created{desc}',
	)
	{
	}
}