<?php

namespace Maxdev\Tickets\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AttachmentResource extends JsonResource
{
	public function toArray(Request $request): array
	{
		return  [
			'id' => $this->id,
			'original_name' => $this->original_name,
			'url' => $this->url,
			'size' => $this->size,
			'extension' => $this->extension
		];
	}
}