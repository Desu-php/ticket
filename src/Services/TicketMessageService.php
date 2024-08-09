<?php

namespace Maxdev\Tickets\Services;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Maxdev\Tickets\Contracts\Repositories\UserRepositoryContract;
use Maxdev\Tickets\Contracts\TicketDriverContract;
use Maxdev\Tickets\Dto\TicketMessage\CreateTicketMessageDto;
use Maxdev\Tickets\Dto\TicketMessage\MessageCreateFromExternalServiceDto;
use Maxdev\Tickets\Dto\TicketMessage\TicketMessagesResponseDto;
use Maxdev\Tickets\Dto\TicketService\TicketMessageCreateDto;
use Maxdev\Tickets\Dto\TicketSupport\TicketSupportDto;
use Maxdev\Tickets\Enums\TicketMessageStatusEnum;
use Maxdev\Tickets\Events\CreateTicketMessageFailedEvent;
use Maxdev\Tickets\Exceptions\HDELimitExceptions;
use Maxdev\Tickets\Helpers\FileHelper;
use Maxdev\Tickets\Models\Ticket;
use Maxdev\Tickets\Models\TicketMessage;

class TicketMessageService
{
	public function __construct(
		protected TicketDriverContract $driver,
		protected UserRepositoryContract $userRepository,
	)
	{
	}

	/**
	 * @throws \Throwable
	 */
	public function createAndSendToExternalService(TicketMessageCreateDto $dto): TicketMessage
	{
		$message = $this->create($dto);

		$this->sendMessageToExternalServiceByEmail($message, $dto->ticket->external_id);

		return $message;
	}

	public function create(TicketMessageCreateDto $dto): TicketMessage
	{
		return DB::transaction(function () use ($dto) {
			$ticketMessage = $dto->ticket->messages()->create($dto->toArray());

			foreach ($dto->attachments as $attachment) {
				$ticketMessage->uploadFile($attachment);
			}

			if ($dto->ticket != $dto->ticketStatus) {
				$dto->ticket->update(['status' => $dto->ticketStatus]);
			}

			return $ticketMessage;
		});
	}

	/**
	 * @throws \Throwable
	 */
	public function sendMessageToExternalServiceByEmail(TicketMessage $message, int $ticketId): void
	{
		$hdeUserService = app(HdeUserService::class);

		$hdeUser = $hdeUserService->findUser($message->user);

		$this->sendMessageToExternalService($message, $ticketId, $hdeUser->external_id);
	}

	public function sendMessageToExternalService(TicketMessage $message, int $ticketId, int $user_external_id): void
	{
		$dto = $this->createMessageDtoByMessage($message, $user_external_id);

		try {
			$this->createExternalMessage($message, $dto, $ticketId);
		} catch (\Throwable $throwable) {
			CreateTicketMessageFailedEvent::dispatchIf(
				$throwable instanceof HDELimitExceptions,
				$message,
				$dto->toArray()
			);

			throw $throwable;
		}
	}

	public function createMessageDtoByMessage(TicketMessage $message, int $user_external_id): CreateTicketMessageDto
	{
		return new CreateTicketMessageDto(
			text: $message->message,
			files: $message->attachments,
			user_id: $user_external_id
		);
	}

	public function createExternalMessage(TicketMessage $message, CreateTicketMessageDto $dto, $ticketId): void
	{
		$response = $this->driver->createTicketMessage($ticketId, $dto);

		$message->update([
			'status'      => TicketMessageStatusEnum::Sent,
			'external_id' => $response->id,
			'user_external_id' => $response->user_id
		]);
	}

	public function createFromExternalService(MessageCreateFromExternalServiceDto $dto, Ticket $ticket): TicketMessage
	{
		$ticketSupport = null;
		$user          = null;

		if ($dto->isSupportSent()) {
			$ticketSupportService = app(TicketSupportService::class);

			$ticketSupport = $ticketSupportService->firstOrCreate(TicketSupportDto::from($dto->toArray()));
		} else {
			$user = $this->userRepository->findByEmail($dto->user_email);

			if ($ticket->user_external_id != $dto->user_id) {
				$ticket->update([
					'user_external_id' => $dto->user_id
				]);
			}
		}

		return $this->create(
			new TicketMessageCreateDto(
				message: $dto->message ?? '',
				user_id: $user?->id,
				ticket: $ticket,
				attachments: FileHelper::uploadFromStringUrl($dto->files),
				status: TicketMessageStatusEnum::Sent,
				message_created_at: Carbon::parse($dto->created_at)->toDateTimeString(),
				external_id: $dto->id,
				ticketStatus: $dto->ticketStatus,
				ticket_support_id: $ticketSupport?->id,
				user_external_id: $dto->last_post_user_id
			)
		);
	}

	public function firstMessageSync(Ticket $ticket): void
	{
		$response = $this->getMessages($ticket);

		$this->firstMessageUpdate($ticket, $response->data->first()->id, TicketMessageStatusEnum::Sent);
	}

	public function firstMessageUpdate(Ticket $ticket, int $external_id, TicketMessageStatusEnum $status): void
	{
		$ticket->messages()->first()
		       ->update([
			       'external_id' => $external_id,
			       'status'      => $status
		       ]);
	}

	public function getMessages(Ticket $ticket): TicketMessagesResponseDto
	{
		return $this->driver->getTicketMessages($ticket->external_id);
	}
}