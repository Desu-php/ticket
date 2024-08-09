<?php

namespace Maxdev\Tickets\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Maxdev\Tickets\Enums\TicketFailedRequestActionEnum;
use Maxdev\Tickets\Enums\TicketFailedRequestStatusEnum;

class TicketFailedRequest extends Model
{
	use SoftDeletes;

	protected $guarded = ['id'];

	protected $casts = [
		'action' => TicketFailedRequestActionEnum::class,
		'status' => TicketFailedRequestStatusEnum::class,
		'data'   => 'json',
		'next_request_at' => 'datetime'
	];

	public function model(): MorphTo
	{
		return $this->morphTo();
	}
}