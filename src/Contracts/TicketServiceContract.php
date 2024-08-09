<?php

namespace Maxdev\Tickets\Contracts;

use Illuminate\Foundation\Auth\User;
use Maxdev\Tickets\Dto\Ticket\CreateTicketDto;
use Maxdev\Tickets\Dto\Ticket\TicketCreateFromExternalServiceDto;
use Maxdev\Tickets\Dto\TicketService\TicketCreateDto;
use Maxdev\Tickets\Models\Ticket;

interface TicketServiceContract
{
	public function create(TicketCreateDto $dto): Ticket;

	public function sendToExternalService(Ticket $ticket): void;

	public function createTicketDtoByTicket(Ticket $ticket): CreateTicketDto;

	public function createExternalTicket(Ticket $ticket, CreateTicketDto $dto): void;

	public function createFromExternalService(TicketCreateFromExternalServiceDto $dto): Ticket;

	public function close(Ticket $ticket): void;

	public function bindUser(Ticket $ticket, User $user): void;
}