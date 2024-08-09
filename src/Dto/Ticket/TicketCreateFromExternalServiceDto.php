<?php

namespace Maxdev\Tickets\Dto\Ticket;

use Maxdev\Tickets\Enums\TicketStatusEnum;
use Maxdev\Tickets\Traits\IsClientTrait;
use Spatie\LaravelData\Attributes\WithoutValidation;
use Spatie\LaravelData\Data;

class TicketCreateFromExternalServiceDto extends Data
{
	use IsClientTrait;

	public function __construct(
		public int $id,
		public ?string $owner_email,
		public ?string $owner_name,
		public int $owner_id,
		public string $subject,
		public string $updated_at,
		public int $user_id,
		public int $last_post_user_id,
		#[WithoutValidation]
		public TicketStatusEnum $status = TicketStatusEnum::Created,
		public ?int $user_external_id = null,
		public ?string $user_type = null
	)
	{
		$this->setStatus();

		if ($this->status == TicketStatusEnum::WaitingForManager) {
			$this->user_external_id = $this->user_id;
		}
	}

	private function setStatus(): void
	{
		if ($this->user_type) {
			if ($this->isClient()) {
				$this->status = TicketStatusEnum::WaitingForManager;
			} else {
				$this->status = TicketStatusEnum::WaitingForClient;
			}

			return;
		}

		if ($this->owner_id == 0 || $this->owner_id == $this->last_post_user_id) {
			$this->status = TicketStatusEnum::WaitingForClient;
		} else {
			$this->status = TicketStatusEnum::WaitingForManager;
		}
	}

}