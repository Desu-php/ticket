<?php
declare(strict_types=1);

namespace Maxdev\Tickets\Dto\Ticket\Webhook;

use Maxdev\Tickets\Enums\HdeStatusEnum;
use Spatie\LaravelData\Data;

class ChangeStatusTicketWebhookDto extends Data
{
	public function __construct(
		public int $id,
		public string $project,
		public string $product,
		public HdeStatusEnum $status
	)
	{
	}
}