<?php
declare(strict_types=1);

namespace Maxdev\Tickets\Services\Attachment;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Maxdev\Tickets\Dto\AttachmentCreateDto;
use Maxdev\Tickets\Exceptions\InvalidUrlException;
use Maxdev\Tickets\Models\TicketAttachment;
use Maxdev\Tickets\Models\TicketMessage;
use Spatie\ImageOptimizer\OptimizerChainFactory;

class AttachmentService
{
	const PATH = 'tickets';

	public function __construct(
		protected TicketMessage $message
	)
	{
	}

	/**
	 * @throws InvalidUrlException
	 */

	public function uploadFile(UploadedFile $file): TicketAttachment
	{
		$filePath = $file->store(self::PATH, ['disk' => config('max_tickets.storage_disk')]);

		$attachment = $this->create(new AttachmentCreateDto(
			path: $filePath,
			file: $file
		));

		$this->optimizeImage($attachment);

		return $attachment;
	}

	private function optimizeImage(TicketAttachment $attachment): void
	{
		if ($attachment->shouldOptimize()) {
			$optimizerChain = OptimizerChainFactory::create();

			$optimizerChain->optimize($attachment->getFullPath());
		}
	}

	private function create(AttachmentCreateDto $dto): TicketAttachment
	{
		return TicketAttachment::create([
			'path'              => $dto->path,
			'original_name'     => $dto->file->getClientOriginalName(),
			'size'              => $dto->file->getSize(),
			'extension'         => Str::afterLast($dto->path, '.'),
			'mimes'             => $dto->file->getClientMimeType(),
			'disk'              => config('max_tickets.storage_disk'),
			'ticket_message_id' => $this->message->id
		]);
	}
}