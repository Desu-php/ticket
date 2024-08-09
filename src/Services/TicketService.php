<?php
declare(strict_types=1);

namespace Maxdev\Tickets\Services;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Maxdev\Tickets\Contracts\TicketDriverContract;
use Maxdev\Tickets\Contracts\TicketServiceContract;
use Maxdev\Tickets\Dto\Ticket\CreateTicketDto;
use Maxdev\Tickets\Dto\Ticket\TicketCreateFromExternalServiceDto;
use Maxdev\Tickets\Dto\TicketService\TicketCreateDto;
use Maxdev\Tickets\Dto\TicketService\TicketMessageCreateDto;
use Maxdev\Tickets\Dto\TicketSupport\TicketSupportDto;
use Maxdev\Tickets\Enums\TicketStatusEnum;
use Maxdev\Tickets\Events\CloseTicketFailedEvent;
use Maxdev\Tickets\Events\CreateTicketFailedEvent;
use Maxdev\Tickets\Exceptions\HDELimitExceptions;
use Maxdev\Tickets\Models\Ticket;
use Maxdev\Tickets\Models\TicketHdeUser;
use Illuminate\Foundation\Auth\User;

class TicketService implements TicketServiceContract
{
	public function __construct(
		protected TicketDriverContract $driver,
		protected TicketMessageService $messageService
	)
	{
	}

	/**
	 * @throws \Throwable
	 */
	public function create(TicketCreateDto $dto): Ticket
	{
		return DB::transaction(function () use ($dto) {
			$ticket = Ticket::create($dto->toArray());

			$this->messageService->create(new TicketMessageCreateDto(
				message: $dto->message,
				user_id: $dto->user_id,
				ticket: $ticket,
				attachments: $dto->attachments,
				ticketStatus: $dto->status
			));

			try {
				$this->sendToExternalService($ticket);
			} catch (HDELimitExceptions $exception) {

			}

			return $ticket;
		});
	}

	/**
	 * @throws \Throwable
	 */
	public function sendToExternalService(Ticket $ticket): void
	{
		$dto = $this->createTicketDtoByTicket($ticket);

		try {
			$this->createExternalTicket($ticket, $dto);
		} catch (\Throwable $exception) {
			CreateTicketFailedEvent::dispatchIf($exception instanceof HDELimitExceptions,
				$ticket,
				$dto->toArray()
			);

			throw $exception;
		}
	}

	public function createTicketDtoByTicket(Ticket $ticket): CreateTicketDto
	{
		$message = $ticket->messages->first();

		return new CreateTicketDto(
			title: $ticket->subject,
			description: $message->message,
			files: $message->attachments,
			user_email: $ticket->user->email
		);
	}

	public function createExternalTicket(Ticket $ticket, CreateTicketDto $dto): void
	{
		$response = $this->driver->createTicket($dto);

		$ticket->update([
			'external_id'                 => $response->id,
			'status'                      => TicketStatusEnum::WaitingForManager,
			'external_service_updated_at' => Carbon::parse($response->date_updated),
			'user_external_id'            => $response->user_id
		]);

		if ($response->user_id > 0) {
			TicketHdeUser::firstOrCreate([
				'user_id' => $ticket->user_id,
			], [
				'external_id' => $response->user_id
			]);
		}
	}

	public function createFromExternalService(TicketCreateFromExternalServiceDto $dto): Ticket
	{
		$service = app(TicketSupportService::class);

		$ticketSupport = $service->firstOrCreate(TicketSupportDto::from($dto->toArray()));

		return Ticket::create([
			'external_id'                 => $dto->id,
			'subject'                     => $dto->subject,
			'ticket_support_id'           => $ticketSupport->id,
			'external_service_updated_at' => Carbon::parse($dto->updated_at),
			'status'                      => $dto->status,
			'user_external_id'            => $dto->user_external_id
		]);
	}

	public function close(Ticket $ticket): void
	{
		try {
			DB::beginTransaction();
			$ticket->update([
				'status' => TicketStatusEnum::Closed
			]);

			$this->driver->closeTicket($ticket->external_id);

			DB::commit();
		} catch (\Throwable $throwable) {
			if ($throwable instanceof HDELimitExceptions) {
				CloseTicketFailedEvent::dispatch($ticket);
				DB::commit();
			} else {
				DB::rollBack();
			}

			throw  $throwable;
		}
	}

	public function bindUser(Ticket $ticket, User $user): void
	{
		$message = $ticket->messages()
		                  ->whereNull('ticket_support_id')
		                  ->where('user_external_id', '>', 0)
		                  ->first();

		$hdeUserService = app(HdeUserService::class);

		DB::transaction(function () use ($hdeUserService, $message, $ticket, $user) {
			$hdeUser = $hdeUserService->findUserByExternalId($user, $message->user_external_id);

			$ticket->update([
				'user_id' => $user->id
			]);

			$ticket
				->messages()
				->whereNull('ticket_support_id')
				->where('user_external_id', $hdeUser->external_id)
				->update([
					'user_id' => $user->id
				]);
		});
	}
}