<?php

namespace Maxdev\Tickets\Services\Attachment;

use Illuminate\Support\Facades\Storage;
use Maxdev\Tickets\Contracts\Attachment\AttachmentUrlGeneratorContract;
use Maxdev\Tickets\Models\TicketAttachment;

class AttachmentUrlGenerator implements AttachmentUrlGeneratorContract
{
	public function getUrl(TicketAttachment $attachment): string
	{
		return Storage::disk($attachment->disk)->url($attachment->path);
	}
}