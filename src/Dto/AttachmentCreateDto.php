<?php
declare(strict_types=1);

namespace Maxdev\Tickets\Dto;

use Illuminate\Http\UploadedFile;
use Spatie\LaravelData\Data;

class AttachmentCreateDto extends Data
{
	public function __construct(
		public string $path,
		public UploadedFile $file
	)
	{
	}
}