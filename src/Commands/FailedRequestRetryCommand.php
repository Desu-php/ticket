<?php

namespace Maxdev\Tickets\Commands;

use Illuminate\Console\Command;
use Maxdev\Tickets\Actions\TicketFailedRequestAction;

class FailedRequestRetryCommand extends Command
{
	protected $signature = 'ticket:failed:requests';

	public function handle(TicketFailedRequestAction $action): void
	{
		$action->execute();
	}
}