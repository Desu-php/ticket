<?php

namespace Maxdev\Tickets\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Maxdev\Tickets\Http\Resources\AttachmentResource;

class TicketMessageAdminResource extends JsonResource
{
	public function toArray(Request $request): array
	{
		return [
			'id'                => $this->id,
			'message'           => $this->message,
			'created_at'        => $this->created_at,
			'updated_at'        => $this->updated_at,
			'files'             => AttachmentResource::collection($this->attachments),
			'user_id'           => $this->user_id,
			'user_external_id'  => $this->user_external_id,
			'ticket_support_id' => $this->ticket_support_id,
			'is_support'        => $this->is_support,
			'external_id'       => $this->external_id
		];
	}
}