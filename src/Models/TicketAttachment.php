<?php
declare(strict_types=1);

namespace Maxdev\Tickets\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Maxdev\Tickets\Contracts\Attachment\AttachmentUrlGeneratorContract;

class TicketAttachment extends Model
{
	protected $guarded = ['id'];

	public function getFullPathAttribute(): string
	{
		return Storage::disk($this->disk)->path($this->path);
	}

	public function getUrlAttribute(): string
	{
		return app(AttachmentUrlGeneratorContract::class)->getUrl($this);
	}

	public function getFullPath(): string
	{
		return Storage::disk($this->disk)->path($this->path);
	}

	public function isImage(): bool
	{
		return Str::contains($this->mimes, 'image', true);
	}

	public function shouldOptimize(): bool
	{
		return $this->isImage() && config('max_tickets.attachment.image_optimize');
	}
}