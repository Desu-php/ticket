<?php
declare(strict_types=1);

namespace Maxdev\Tickets\Listeners;

use Maxdev\Tickets\Contracts\RequestFailedEventContract;
use Maxdev\Tickets\Dto\TicketFailedRequest\TicketFailedRequestCreateDto;
use Maxdev\Tickets\Services\TicketFailedRequestService;

class CreateRequestFailedEventListener
{
	public function __construct(
		protected TicketFailedRequestService $service
	)
	{
	}

	public function handle(RequestFailedEventContract $event): void
	{
		$this->service->create(
			new TicketFailedRequestCreateDto(
				model: $event->getModel(),
				action: $event->getAction(),
				data: $event->getData(),
			)
		);
	}
}