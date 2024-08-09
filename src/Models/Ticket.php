<?php
declare(strict_types=1);

namespace Maxdev\Tickets\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Maxdev\Tickets\Enums\TicketStatusEnum;

class Ticket extends Model
{
	use SoftDeletes;

	protected $guarded = ['id'];

	protected $casts = [
		'status'                      => TicketStatusEnum::class,
		'external_service_updated_at' => 'datetime'
	];

	public function messages(): HasMany
	{
		return $this->hasMany(TicketMessage::class);
	}

	public function user(): BelongsTo
	{
		return $this->belongsTo(config('max_tickets.user'));
	}

	public function support(): BelongsTo
	{
		return $this->belongsTo(TicketSupport::class, 'ticket_support_id');
	}

	public function isClosed(): bool
	{
		return $this->status == TicketStatusEnum::Closed;
	}
}