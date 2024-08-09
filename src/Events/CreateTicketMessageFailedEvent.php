<?php

namespace Maxdev\Tickets\Events;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Events\Dispatchable;
use Maxdev\Tickets\Contracts\RequestFailedEventContract;
use Maxdev\Tickets\Enums\TicketFailedRequestActionEnum;

class CreateTicketMessageFailedEvent implements RequestFailedEventContract
{
	use Dispatchable;

	public function __construct(
		protected Model $model,
		protected ?array $data = null
	)
	{
	}

	public function getData(): ?array
	{
		return $this->data;
	}

	public function getModel(): Model
	{
		return $this->model;
	}

	public function getAction(): TicketFailedRequestActionEnum
	{
		return TicketFailedRequestActionEnum::CreateMessage;
	}
}