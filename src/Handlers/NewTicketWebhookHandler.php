<?php

namespace Maxdev\Tickets\Handlers;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Maxdev\Tickets\Contracts\TicketServiceContract;
use Maxdev\Tickets\Contracts\Webhook\NewTicketWebhookValidatorContract;
use Maxdev\Tickets\Dto\Ticket\TicketCreateFromExternalServiceDto;
use Maxdev\Tickets\Dto\Ticket\Webhook\NewTicketWebhookDto;
use Maxdev\Tickets\Dto\TicketService\TicketMessageCreateDto;
use Maxdev\Tickets\Dto\TicketSupport\TicketSupportDto;
use Maxdev\Tickets\Enums\TicketMessageStatusEnum;
use Maxdev\Tickets\Helpers\FileHelper;
use Maxdev\Tickets\Models\Ticket;
use Maxdev\Tickets\Services\TicketMessageService;
use Maxdev\Tickets\Services\TicketSupportService;

class NewTicketWebhookHandler
{
	public function __construct(
		protected TicketMessageService $service,
		protected NewTicketWebhookValidatorContract $validator,
		protected TicketSupportService $ticketSupportService,
		protected TicketServiceContract $ticketService,
	)
	{
	}

	public function handle(NewTicketWebhookDto $dto): void
	{
		if (!$this->validator->validate($dto)) {
			return;
		}

		$ticket = Ticket::where('external_id', $dto->id)
		                ->first();

		if (!$ticket) {
			$this->createTicketFromExternalService($dto);

			return;
		}

		$this->service->firstMessageUpdate($ticket, $dto->id, TicketMessageStatusEnum::Sent);
	}

	private function createTicketFromExternalService(NewTicketWebhookDto $dto): void
	{
		DB::transaction(function () use ($dto) {
			$ticket = $this->ticketService->createFromExternalService(TicketCreateFromExternalServiceDto::from($dto->toArray()));

			$support = null;

			if (!$dto->isClient()) {
				$support = app(TicketSupportService::class)->firstOrCreate(
					new TicketSupportDto(
						owner_email: $dto->owner_email,
						owner_name: $dto->owner_name,
						owner_id: $dto->owner_id
					)
				);
			}

			$this->service->create(
				new TicketMessageCreateDto(
					message: $dto->message ?? '',
					user_id: null,
					ticket: $ticket,
					attachments: FileHelper::uploadFromStringUrl($dto->files),
					status: TicketMessageStatusEnum::Sent,
					message_created_at: Carbon::parse($dto->message_created_at)->toDateTimeString(),
					external_id: $dto->message_id,
					ticketStatus: $ticket->status,
					ticket_support_id: $support?->id,
					user_external_id: $dto->last_post_user_id
				)
			);
		});
	}
}