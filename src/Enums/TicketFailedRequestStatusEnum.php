<?php

namespace Maxdev\Tickets\Enums;

enum TicketFailedRequestStatusEnum: string
{
	case Created = 'Created';
	case Frozen = 'Frozen';
	case Success = 'Success';
}