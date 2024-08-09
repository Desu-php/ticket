<?php
declare(strict_types=1);

namespace Maxdev\Tickets\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Maxdev\Tickets\Contracts\RequestFailedEventContract;
use Maxdev\Tickets\Listeners\CreateRequestFailedEventListener;

class EventServiceProvider extends ServiceProvider
{
	protected $listen = [
		RequestFailedEventContract::class => [
			CreateRequestFailedEventListener::class
		]
	];
}