<?php

namespace Maxdev\Tickets\Enums;

enum TicketMessageStatusEnum: string
{
	case Created = 'created';
	case Sent = 'sent';
}
