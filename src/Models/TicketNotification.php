<?php

namespace Maxdev\Tickets\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Maxdev\Tickets\Enums\TicketNotificationTypeEnum;

class TicketNotification extends Model
{
	protected $guarded = ['id'];

	protected $casts = [
		'type' => TicketNotificationTypeEnum::class
	];

	public function ticket(): BelongsTo
	{
		return $this->belongsTo(Ticket::class);
	}

	public function ticketMessage(): BelongsTo
	{
		return $this->belongsTo(TicketMessage::class);
	}
}