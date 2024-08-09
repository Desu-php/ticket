<?php

namespace Maxdev\Tickets\Actions;

use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Maxdev\Tickets\Contracts\HdeConfigServiceContract;
use Maxdev\Tickets\Contracts\TicketServiceContract;
use Maxdev\Tickets\Dto\Ticket\TicketCreateFromExternalServiceDto;
use Maxdev\Tickets\Dto\Ticket\TicketParamsDto;
use Maxdev\Tickets\Dto\Ticket\TicketResponseDto;
use Maxdev\Tickets\Dto\TicketMessage\TicketMessageResponseDto;
use Maxdev\Tickets\Dto\TicketService\TicketMessageCreateDto;
use Maxdev\Tickets\Dto\Validator\BaseValidatorDto;
use Maxdev\Tickets\Enums\TicketMessageStatusEnum;
use Maxdev\Tickets\Helpers\FileHelper;
use Maxdev\Tickets\Models\Ticket;
use Maxdev\Tickets\Models\TicketHdeUser;
use Maxdev\Tickets\Models\TicketMessage;
use Maxdev\Tickets\Models\TicketSupport;
use Maxdev\Tickets\Services\HdeService;
use Maxdev\Tickets\Services\TicketMessageService;
use Maxdev\Tickets\Validators\ProjectAndProductRule;

class TicketSyncFromHdeAction
{
	public function __construct(
		protected HdeService $service,
		protected ProjectAndProductRule $rule,
		protected TicketServiceContract $ticketService,
		protected HdeConfigServiceContract $config,
		protected TicketMessageService $messageService
	)
	{
	}

	public function execute(Carbon $fromDate, ?Carbon $toDate = null): void
	{
		$this->getTickets($fromDate->toDateTimeString(), $toDate?->toDateTimeString());
	}

	public function getTickets(string $from_date_created, ?string $to_date_created = null): void
	{
		$page = 1;

		do {
			$response = $this->service->getTickets(
				new TicketParamsDto(
					from_date_created: $from_date_created,
					to_date_created: $to_date_created
				),
				$page
			);

			usleep(300);

			/** @var TicketResponseDto $datum */
			foreach ($response->data as $datum) {
				if ($this->isSkip($datum)) {
					continue;
				}

				$ticket = Ticket::where('external_id', $datum->id)
				                ->first();

				if (!$ticket) {
					$ticket = $this->ticketService->createFromExternalService(new TicketCreateFromExternalServiceDto(
						id: $datum->id,
						owner_email: $datum->owner_email,
						owner_name: $datum->owner_name,
						owner_id: $datum->owner_id,
						subject: $datum->title,
						updated_at: $datum->date_updated,
						user_id: $datum->user_id,
						last_post_user_id: $datum->user_id,
					));
				}

				$this->getMessages($ticket);
			}

			$page++;
		} while ($page < $response->pagination->total_pages);

	}

	private function isSkip(TicketResponseDto $datum): bool
	{
		$dto = new BaseValidatorDto(
			project: Arr::first($datum->custom_fields, fn(array $field) => $field['id'] == $this->config->getProjectFieldId())['field_value'],
			product: Arr::first($datum->custom_fields, fn(array $field) => $field['id'] == $this->config->getProductFieldId())['field_value'],
		);

		return !$this->rule->check($dto);
	}

	public function getMessages(Ticket $ticket): void
	{
		$page = 1;

		do {
			$response = $this->service->getTicketMessages($ticket->external_id);
			usleep(300);

			/** @var TicketMessageResponseDto $datum */
			foreach ($response->data as $datum) {
				$message = TicketMessage::where('external_id', $datum->id)->first();

				if ($message) {
					continue;
				}

				$user = TicketHdeUser::where('external_id', $datum->user_id)
				                     ->first();

				$support = TicketSupport::where('external_id', $datum->user_id)
				                        ->first();

				$this->messageService->create(
					new TicketMessageCreateDto(
						message: $datum->text ?? '',
						user_id: $user?->user_id,
						ticket: $ticket,
						attachments: FileHelper::uploadFromArrayByKey($datum->files),
						status: TicketMessageStatusEnum::Sent,
						message_created_at: Carbon::parse($datum->date_created)->toDateTimeString(),
						external_id: $datum->id,
						ticketStatus: $ticket->status,
						ticket_support_id: $support?->id,
						user_external_id: $datum->user_id
					)
				);
			}
			$page++;

		} while ($page < $response->pagination->total_pages);

	}
}