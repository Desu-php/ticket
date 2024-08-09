<?php
declare(strict_types=1);

namespace Maxdev\Tickets\Contracts;

use Maxdev\Tickets\Dto\Ticket\CreateTicketDto;
use Maxdev\Tickets\Dto\Ticket\TicketParamsDto;
use Maxdev\Tickets\Dto\Ticket\TicketResponseDto;
use Maxdev\Tickets\Dto\Ticket\TicketsResponseDto;
use Maxdev\Tickets\Dto\TicketMessage\CreateTicketMessageDto;
use Maxdev\Tickets\Dto\TicketMessage\TicketMessageResponseDto;
use Maxdev\Tickets\Dto\TicketMessage\TicketMessagesResponseDto;
use Maxdev\Tickets\Dto\User\UserResponseDto;

interface TicketDriverContract
{
	public function createTicket(CreateTicketDto $dto): TicketResponseDto;

	public function getTickets(TicketParamsDto $dto, int $page = 1): TicketsResponseDto;

	public function getTicketMessages(int $ticketId, int $page = 1): TicketMessagesResponseDto;

	public function createTicketMessage(int $ticketId, CreateTicketMessageDto $dto): TicketMessageResponseDto;

	public function searchUser(string $search): UserResponseDto;

	public function closeTicket(int $ticketId);
}