<?php
declare(strict_types=1);

namespace Maxdev\Tickets\Dto\TicketMessage;

use Spatie\LaravelData\Data;

class HdeCreateTicketMessageDto extends Data
{
	public function __construct(
		public string $text,
		public ?int $user_id = null,
		public array $files = []
	)
	{
	}
}
