<?php

namespace Maxdev\Tickets\Services;

use Maxdev\Tickets\Dto\TicketFailedRequest\TicketFailedRequestCreateDto;
use Maxdev\Tickets\Models\TicketFailedRequest;

class TicketFailedRequestService
{
	public function create(TicketFailedRequestCreateDto $dto): TicketFailedRequest
	{
		return TicketFailedRequest::create($dto->toArray());
	}
}