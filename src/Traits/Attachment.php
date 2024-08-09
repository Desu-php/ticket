<?php
declare(strict_types=1);

namespace Maxdev\Tickets\Traits;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\UploadedFile;
use Maxdev\Tickets\Models\TicketAttachment;
use Maxdev\Tickets\Services\Attachment\AttachmentService;

trait Attachment
{
	public function attachments(): HasMany
	{
		return $this->hasMany(TicketAttachment::class, 'ticket_message_id');
	}

	public function uploadFile(UploadedFile $file): TicketAttachment
	{
		return $this->attachmentService()->uploadFile($file);
	}

	public function attachmentsToMultipartFormData(string $name): array
	{
		return $this->attachmentService()->toMultipartFormData($name);
	}

	private function attachmentService(): AttachmentService
	{
		return app(AttachmentService::class, [
			'message' => $this
		]);
	}
}