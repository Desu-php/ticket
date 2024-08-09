<?php
declare(strict_types=1);

namespace Maxdev\Tickets\Dto;

use DateTime;
use Illuminate\Support\Arr;
use Spatie\LaravelData\Data;

class UpdateTicketDto extends Data
{
	public function __construct(
		public ?int $pid = null,
		public ?string $title = null,
		public ?DateTime $sla_date = null,
		public ?DateTime $freeze_date = null,
		public ?string $status_id = null,
		public ?int $priority_id = null,
		public ?int $type_id = null,
		public ?int $department_id = null,
		public array $cc = [],
		public array $bcc = [],
		public array $followers = [],
		public ?bool $ticket_lock = null,
		public ?int $owner_id = null,
		public ?int $user_id = null,
		public array $custom_fields = [],
		public array $tags = [],
	)
	{
	}

	public function toArray(): array
	{
		return Arr::whereNotNull(parent::toArray());
	}
}
