<?php
declare(strict_types=1);

namespace Maxdev\Tickets\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Maxdev\Tickets\Enums\TicketMessageStatusEnum;
use Maxdev\Tickets\Helpers\TextHelper;
use Maxdev\Tickets\Traits\Attachment;

class TicketMessage extends Model
{
	use SoftDeletes;
	use Attachment;

	protected $guarded = ['id'];

	protected $casts = [
		'status' => TicketMessageStatusEnum::class
	];

	public function ticket(): BelongsTo
	{
		return $this->belongsTo(Ticket::class);
	}

	public function user(): BelongsTo
	{
		return $this->belongsTo(config('max_tickets.user'));
	}

	public function support(): BelongsTo
	{
		return $this->belongsTo(TicketSupport::class, 'ticket_support_id');
	}

	public function getIsSupportAttribute(): bool
	{
		return !is_null($this->ticket_support_id);
	}

	protected static function boot(): void
	{
		parent::boot();

		static::creating(function (TicketMessage $message) {
			$message->message = TextHelper::clearText($message->message);
		});
	}
}