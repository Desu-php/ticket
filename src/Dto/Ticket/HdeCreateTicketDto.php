<?php
declare(strict_types=1);

namespace Maxdev\Tickets\Dto\Ticket;

use Illuminate\Support\Arr;
use Spatie\LaravelData\Data;

class HdeCreateTicketDto extends Data
{
	public function __construct(
		public string $title,
		public string $description,
		public array $files,
		public ?int $pid = null,
		public ?string $sla_date = null,
		public ?string $status_id = null,
		public ?int $priority_id = null,
		public ?int $type_id = null,
		public ?int $department_id = null,
		public ?bool $ticket_lock = null,
		public ?int $owner_id = null,
		public ?int $user_id = null,
		public ?string $user_email = null,
		public ?array $cc = null,
		public ?array $bcc = null,
		public ?array $followers = null,
		public ?int $create_from_user = null,
		public ?array $custom_fields = null,
		public ?array $tags = null,
	)
	{
	}

	public function toArray(): array
	{
		return Arr::whereNotNull(parent::toArray());
	}

	public function toBody(): array
	{
		if (empty($this->files)) {
			return Arr::except($this->toArray(), 'files');
		}

		return $this->toMultiPart();
	}

	public function toMultiPart(): array
	{
		$multipartData = [];

		foreach (Arr::except($this->toArray(), 'files') as $key => $value) {
			if ($key !== 'custom_fields') {
				$multipartData[] = [
					'name'     => $key,
					'contents' => $value
				];
			} else {
				foreach ($value as $customKey => $customValue) {
					$multipartData[] = [
						'name'     => "custom_fields[$customKey]",
						'contents' => $customValue
					];
				}
			}
		}

		return $multipartData;
	}
}
