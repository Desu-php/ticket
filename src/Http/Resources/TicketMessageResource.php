<?php

namespace Maxdev\Tickets\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TicketMessageResource extends JsonResource
{
	public function toArray(Request $request): array
	{
		return [
			'id'            => $this->id,
			'message'       => $this->message,
			'created_at'    => $this->created_at,
			'updated_at'    => $this->updated_at,
			'files'         => AttachmentResource::collection($this->attachments),
			'is_my_message' => !is_null($this->user_id)
		];
	}
}