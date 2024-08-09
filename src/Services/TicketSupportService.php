<?php

namespace Maxdev\Tickets\Services;

use Maxdev\Tickets\Dto\TicketSupport\TicketSupportDto;
use Maxdev\Tickets\Models\TicketSupport;

class TicketSupportService
{
	public function firstOrCreate(TicketSupportDto $dto): TicketSupport
	{
		return TicketSupport::firstOrCreate([
			'external_id' => $dto->owner_id
		], [
			'email' => $dto->owner_email,
			'name'  => $dto->owner_name
		]);
	}
}