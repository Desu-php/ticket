<?php
declare(strict_types=1);

namespace Maxdev\Tickets\Providers;

use Illuminate\Support\ServiceProvider;
use Maxdev\Tickets\Commands\FailedRequestRetryCommand;
use Maxdev\Tickets\Commands\TicketNotificationCommand;
use Maxdev\Tickets\Commands\TicketSyncFromHdeCommand;
use Maxdev\Tickets\TicketPackage;

class ConsoleServiceProvider extends ServiceProvider
{
	/**
	 * The available command shortname.
	 *
	 * @var array
	 */
	protected array $commands = [
		FailedRequestRetryCommand::class,
		TicketSyncFromHdeCommand::class,
		TicketNotificationCommand::class
	];

	public function boot(): void
	{

		$this
			->registerMigrationsPublisher()
			->registerConfigPublisher()
			->registerRoutesPublisher()
			->commands($this->commands);
	}


	protected function registerMigrationsPublisher(): self
	{
		$this->publishes([
			TicketPackage::path('database/migrations') => database_path('migrations'),
		], 'max-tickets-migrations');


		return $this;
	}


	protected function registerConfigPublisher(): self
	{
		$this->publishes([
			TicketPackage::path('config/max_tickets.php') => config_path('max_tickets.php'),
		], 'max-tickets-config');

		return $this;
	}

	protected function registerRoutesPublisher(): self
	{
		$this->publishes([
			TicketPackage::path('routes/client_ticket_route.php') => base_path('routes/client_ticket_route.php'),
			TicketPackage::path('routes/admin_ticket_route.php') => base_path('routes/admin_ticket_route.php'),
			TicketPackage::path('routes/webhook_ticket_route.php') => base_path('routes/webhook_ticket_route.php'),
		], 'max-tickets-routes');

		return $this;
	}
}