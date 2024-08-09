<?php
declare(strict_types=1);

namespace Maxdev\Tickets\Contracts;

use Illuminate\Database\Eloquent\Model;
use Maxdev\Tickets\Enums\TicketFailedRequestActionEnum;

interface RequestFailedEventContract
{
	public function getData(): ?array;

	public function getModel(): Model;

	public function getAction(): TicketFailedRequestActionEnum;
}