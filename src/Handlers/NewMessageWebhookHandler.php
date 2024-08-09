<?php
declare(strict_types=1);

namespace Maxdev\Tickets\Handlers;

use Maxdev\Tickets\Contracts\Webhook\NewMessageWebhookValidatorContract;
use Maxdev\Tickets\Dto\TicketMessage\MessageCreateFromExternalServiceDto;
use Maxdev\Tickets\Dto\TicketMessage\NewMessageWebhookDto;
use Maxdev\Tickets\Exceptions\InvalidUrlException;
use Maxdev\Tickets\Models\Ticket;
use Maxdev\Tickets\Models\TicketMessage;
use Maxdev\Tickets\Services\TicketMessageService;

class NewMessageWebhookHandler
{
	public function __construct(
		protected TicketMessageService $service,
		protected NewMessageWebhookValidatorContract $validator,
	)
	{
	}

	/**
	 * @throws InvalidUrlException
	 */
	public function handle(NewMessageWebhookDto $dto): void
	{
		if (!$this->validator->validate($dto)) {
			return;
		}

		$ticket = Ticket::where('external_id', $dto->ticket_id)
		                ->firstOrFail();

		$message = TicketMessage::where('external_id', $dto->id)
		                        ->where('ticket_id', $ticket->id)
		                        ->first();

		if ($message) {
			$this->updateExternalUserId($message, $dto);

			return;
		}

		$this->service->createFromExternalService(MessageCreateFromExternalServiceDto::from($dto->toArray()), $ticket);
	}

	private function updateExternalUserId(TicketMessage $message, NewMessageWebhookDto $dto): void
	{
		if (!$message->user_external_id) {
			$message->update([
				'user_external_id' => $dto->user_id,
			]);
		}
	}
}