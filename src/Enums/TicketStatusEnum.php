<?php
declare(strict_types=1);

namespace Maxdev\Tickets\Enums;

enum TicketStatusEnum: string
{
	case Created = 'created';
	case WaitingForManager = 'waiting_for_manager';
	case WaitingForClient = 'waiting_for_user';
	case Closed = 'closed';
	case InProgress = 'in_progress';

}
