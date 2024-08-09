<?php
declare(strict_types=1);

namespace Maxdev\Tickets\Dto\TicketMessage;

use Illuminate\Database\Eloquent\Collection;
use Spatie\LaravelData\Data;

class CreateTicketMessageDto extends Data
{
	public function __construct(
		public string $text,
		public Collection $files,
		public ?int $user_id = null,
	)
	{
	}
}
