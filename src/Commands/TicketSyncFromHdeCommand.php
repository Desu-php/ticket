<?php

namespace Maxdev\Tickets\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Maxdev\Tickets\Actions\TicketSyncFromHdeAction;
use Maxdev\Tickets\Models\Ticket;

class TicketSyncFromHdeCommand extends Command
{
	protected $signature = 'tickets:sync:hde {from_date?} {to_date?}';

	public function handle(TicketSyncFromHdeAction $action): void
	{
		$from_date = $this->argument('from_date');
		$to_date = $this->argument('to_date') ;

		if (!$from_date) {
			$lastTicket = Ticket::query()
			                    ->whereNotNull('external_service_updated_at')
			                    ->latest('id')
			                    ->first();

			$from_date  = $lastTicket->external_service_updated_at;
		} else {
			$from_date = Carbon::parse($from_date);

			if ($to_date) {
				$to_date = Carbon::parse($to_date);
			}
		}

		$action->execute($from_date, $to_date);
	}
}