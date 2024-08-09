<?php

namespace Maxdev\Tickets\Enums;

enum HdeStatusEnum: string
{
	case WaitingForCustomerResponse = 'Waiting for customer response';
	case WaitingForManagerResponse = "Waiting for manager's reply";
	case Closed = 'Closed';
	case InProgress = 'In progress';
	case Open = 'Open';
	case Test = 'Test';

	public function getTicketStatus(): TicketStatusEnum
	{
		return match ($this) {
			self::WaitingForCustomerResponse => TicketStatusEnum::WaitingForClient,
			self::WaitingForManagerResponse => TicketStatusEnum::WaitingForManager,
			self::Closed => TicketStatusEnum::Closed,
			self::InProgress => TicketStatusEnum::InProgress,
			self::Open, self::Test => TicketStatusEnum::Created
		};
	}
}
