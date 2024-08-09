<?php

namespace Maxdev\Tickets\Dto\TicketFailedRequest;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Maxdev\Tickets\Enums\TicketFailedRequestActionEnum;
use Maxdev\Tickets\Enums\TicketFailedRequestStatusEnum;
use Spatie\LaravelData\Data;

class TicketFailedRequestCreateDto extends Data
{
	public Carbon $next_request_at;

	public function __construct(
		public Model $model,
		public TicketFailedRequestActionEnum $action,
		public int $period_minutes = 20,
		public ?array $data = null,
		public TicketFailedRequestStatusEnum $status = TicketFailedRequestStatusEnum::Created,
	)
	{
		$this->next_request_at = now()->addMinutes($this->period_minutes);
	}

	public function toArray(): array
	{
		return [
			...parent::toArray(),
			'model_type' => $this->model->getMorphClass(),
			'model_id'   => $this->model->id
		];
	}
}