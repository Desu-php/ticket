<?php
declare(strict_types=1);

namespace Maxdev\Tickets\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TicketSupport extends Model
{
	protected $guarded = ['id'];

	public function tickets(): HasMany
	{
		return $this->hasMany(Ticket::class);
	}

	public function messages(): HasMany
	{
		return $this->hasMany(TicketMessage::class);
	}

	public function getTitleAttribute(): string
	{
		if ($this->slug) {
			return $this->slug;
		}

		return config('max_tickets.support.default_slug');
	}
}