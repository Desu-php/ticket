<?php

namespace Maxdev\Tickets\Commands;

use Illuminate\Console\Command;
use Maxdev\Tickets\Actions\TicketNotificationAction;
use Maxdev\Tickets\Enums\TicketNotificationTypeEnum;

class TicketNotificationCommand extends Command
{
	protected $signature = 'tickets:notifications {type}';

	public function handle(TicketNotificationAction $action): void
	{
		$type = TicketNotificationTypeEnum::from($this->argument('type'));

		$action->execute($type);
	}
}