<?php
declare(strict_types=1);

namespace Maxdev\Tickets\Providers;

use Illuminate\Support\ServiceProvider;
use Maxdev\Tickets\Contracts\Attachment\AttachmentUrlGeneratorContract;
use Maxdev\Tickets\Contracts\HdeConfigServiceContract;
use Maxdev\Tickets\Contracts\ProductAndProjectRuleContract;
use Maxdev\Tickets\Contracts\Repositories\UserRepositoryContract;
use Maxdev\Tickets\Contracts\TicketDriverContract;
use Maxdev\Tickets\Contracts\TicketServiceContract;
use Maxdev\Tickets\Contracts\Webhook\ChangeOwnerTicketWebhookValidatorContract;
use Maxdev\Tickets\Contracts\Webhook\ChangeStatusTicketWebhookValidatorContract;
use Maxdev\Tickets\Contracts\Webhook\NewMessageWebhookValidatorContract;
use Maxdev\Tickets\Contracts\Webhook\NewTicketWebhookValidatorContract;
use Maxdev\Tickets\Repositories\UserRepository;
use Maxdev\Tickets\Services\Attachment\AttachmentUrlGenerator;
use Maxdev\Tickets\Services\HdeConfigService;
use Maxdev\Tickets\Services\TicketService;
use Maxdev\Tickets\TicketPackage;
use Maxdev\Tickets\Validators\ProjectAndProductRule;

class TicketServiceProvider extends ServiceProvider
{
	public function register(): void
	{
		$this->app->singleton(TicketDriverContract::class, function () {
			$driver = config('max_tickets.driver');
			$class  = config('max_tickets.' . $driver . '.service');

			return app($class);
		});

		$this->app->bind(NewMessageWebhookValidatorContract::class, function () {
			$driver = config('max_tickets.driver');
			$class  = config('max_tickets.' . $driver . '.validator.new_message');

			return app($class);
		});

		$this->app->bind(NewTicketWebhookValidatorContract::class, function () {
			$driver = config('max_tickets.driver');
			$class  = config('max_tickets.' . $driver . '.validator.new_ticket');

			return app($class);
		});

		$this->app->bind(ChangeStatusTicketWebhookValidatorContract::class, function () {
			$driver = config('max_tickets.driver');
			$class  = config('max_tickets.' . $driver . '.validator.change_status_ticket');

			return app($class);
		});

		$this->app->bind(ChangeOwnerTicketWebhookValidatorContract::class, function () {
			$driver = config('max_tickets.driver');
			$class  = config('max_tickets.' . $driver . '.validator.change_owner_ticket');

			return app($class);
		});

		$this->app->singleton(HdeConfigServiceContract::class, HdeConfigService::class);
		$this->app->bind(ProductAndProjectRuleContract::class, ProjectAndProductRule::class);

		$this->app->bind(TicketServiceContract::class, TicketService::class);

		$this->app->bind(AttachmentUrlGeneratorContract::class, AttachmentUrlGenerator::class);
		$this->app->bind(UserRepositoryContract::class, UserRepository::class);

		$this->app->register(EventServiceProvider::class);

		if ($this->app->runningInConsole()) {
			$this->app->register(ConsoleServiceProvider::class);
		}
	}

	public function boot(): void
	{
		$this->mergeConfigFrom(TicketPackage::path('config/max_tickets.php'), 'max_tickets');
		$this->loadMigrationsFrom(TicketPackage::path('database/migrations'));
	}
}