<?php

namespace Maxdev\Tickets\Exceptions;

use Exception;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class TicketIsClosedException extends Exception implements HttpExceptionInterface
{
	public function __construct(string $message = 'Ticket is closed', int $code = 0, ?Throwable $previous = null)
	{
		parent::__construct($message, $code, $previous);
	}

	public function getStatusCode(): int
	{
		return Response::HTTP_BAD_REQUEST;
	}

	public function getHeaders(): array
	{
		return [];
	}
}