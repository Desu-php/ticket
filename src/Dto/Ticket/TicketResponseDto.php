<?php
declare(strict_types=1);

namespace Maxdev\Tickets\Dto\Ticket;

use Spatie\LaravelData\Data;

class TicketResponseDto extends Data
{
	public function __construct(
		public int $id,
		public string $unique_id,
		public string $date_created,
		public string $date_updated,
		public string $title,
		public ?string $description,
		public ?string $source,
		public ?int $pid = null,
		public ?string $sla_date = null,
		public ?int $sla_flag = null,
		public ?string $status_id = null,
		public ?int $priority_id = null,
		public ?int $type_id = null,
		public ?int $department_id = null,
		public ?string $department_name = null,
		public ?bool $ticket_lock = null,
		public ?int $owner_id = null,
		public ?string $owner_name = null,
		public ?string $owner_lastname = null,
		public ?string $owner_email = null,
		public ?int $user_id = null,
		public ?string $user_name = null,
		public ?string $user_lastname = null,
		public ?string $user_email = null,
		public array $cc = [],
		public array $bcc = [],
		public array $followers = [],
		public ?int $create_from_user = null,
		public array $custom_fields = [],
		public array $files = [],
		public array $tags = [],
		public ?string $freeze_date = null,
		public ?int $freeze = null,
		public ?int $deleted = null,
		public ?int $viewed_by_staff = null,
		public ?int $viewed_by_client = null,
		public ?string $rate = null,
		public ?string $rate_comment = null,
		public ?string $date_date = null,
		public array $jira_issues = []
	)
	{
	}
}