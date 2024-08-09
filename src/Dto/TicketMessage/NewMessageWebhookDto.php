<?php
declare(strict_types=1);

namespace Maxdev\Tickets\Dto\TicketMessage;

use Maxdev\Tickets\Enums\UserTypeEnum;
use Maxdev\Tickets\Traits\IsClientTrait;
use Spatie\LaravelData\Data;

class NewMessageWebhookDto extends Data
{
	use IsClientTrait;

	public function __construct(
		public int $id,
		public int $ticket_id,
		public string $user_email,
		public ?string $message,
		public ?string $files,
		public ?string $owner_email,
		public ?string $owner_name,
		public int $owner_id,
		public string $project,
		public string $product,
		public int $user_id,
		public int $last_post_user_id,
		public string $status_id,
		public string $created_at,
		public string $creator_group,
		public string $user_type,
	)
	{
	}
}