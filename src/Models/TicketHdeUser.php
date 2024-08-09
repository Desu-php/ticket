<?php

namespace Maxdev\Tickets\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TicketHdeUser extends Model
{
	protected $guarded = ['id'];

	public function user(): BelongsTo
	{
		return $this->belongsTo(config('max_tickets.user'));
	}
}