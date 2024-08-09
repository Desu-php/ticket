<?php

namespace Maxdev\Tickets\Dto\Ticket\Webhook;

use Spatie\LaravelData\Data;

class ChangeOwnerTicketWebhookDto extends Data
{
	public function __construct(
		public int $id,
		public ?string $owner_email,
		public ?string $owner_name,
		public int $owner_id,
		public string $project,
		public string $product,
	)
	{

	}
}