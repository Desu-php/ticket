<?php
declare(strict_types=1);

namespace Maxdev\Tickets\Dto\Ticket\Webhook;


use Maxdev\Tickets\Traits\IsClientTrait;
use Spatie\LaravelData\Data;

class NewTicketWebhookDto extends Data
{
	use IsClientTrait;

	public function __construct(
		public int $id,
		public int $message_id,
		public string $subject,
		public string $user_email,
		public ?string $user_name,
		public ?string $owner_email,
		public ?string $owner_name,
		public int $owner_id,
		public ?string $message,
		public ?string $files,
		public string $project,
		public string $product,
		public int $user_id,
		public int $last_post_user_id,
		public string $status_id,
		public string $message_created_at,
		public string $updated_at,
		public string $creator_group,
		public string $user_type,
	)
	{
	}
}