<?php
declare(strict_types=1);

namespace Maxdev\Tickets\Dto\Ticket;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Arr;
use Spatie\LaravelData\Data;

class CreateTicketDto extends Data
{
	public function __construct(
		public string $title,
		public string $description,
		public Collection $files,
		public ?int $pid = null,
		public ?string $sla_date = null,
		public ?string $status_id = null,
		public ?int $priority_id = null,
		public ?int $type_id = null,
		public ?int $department_id = null,
		public ?bool $ticket_lock = null,
		public ?int $owner_id = null,
		public ?int $user_id = null,
		public ?string $user_email = null,
		public ?array $cc = null,
		public ?array $bcc = null,
		public ?array $followers = null,
		public ?int $create_from_user = null,
		public ?array $custom_fields = null,
		public ?array $tags = null,
	)
	{
	}

	public function toArray(): array
	{
		return Arr::whereNotNull(parent::toArray());
	}
}
