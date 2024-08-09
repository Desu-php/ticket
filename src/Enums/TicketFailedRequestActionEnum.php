<?php

namespace Maxdev\Tickets\Enums;

enum TicketFailedRequestActionEnum: string
{
	case CreateTicket = 'CreateTicket';
	case CreateMessage = 'CreateMessage';
	case CloseTicket = 'CloseTicket';
}
