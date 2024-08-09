<?php
declare(strict_types=1);

namespace Maxdev\Tickets\Dto\TicketMessage;

use Maxdev\Tickets\Enums\TicketStatusEnum;
use Maxdev\Tickets\Traits\IsClientTrait;
use Spatie\LaravelData\Attributes\WithoutValidation;
use Spatie\LaravelData\Data;

class MessageCreateFromExternalServiceDto extends Data
{
	use IsClientTrait;

	public function __construct(
		public int $id,
		public string $user_email,
		public ?string $message,
		public ?string $files,
		public ?string $owner_email,
		public ?string $owner_name,
		public int $owner_id,
		public int $user_id,
		public int $last_post_user_id,
		public string $created_at,
		public string $creator_group,
		public string $user_type,
		#[WithoutValidation]
		public TicketStatusEnum $ticketStatus = TicketStatusEnum::Created
	)
	{
		$this->setStatus();
	}

	private function setStatus(): void
	{
		if ($this->isSupportSent()) {
			$this->ticketStatus = TicketStatusEnum::WaitingForClient;
		} else {
			$this->ticketStatus = TicketStatusEnum::WaitingForManager;
		}
	}

	public function isSupportSent(): bool
	{
		return !$this->isClient();
	}
}





