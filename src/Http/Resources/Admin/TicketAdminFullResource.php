<?php

namespace Maxdev\Tickets\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Maxdev\Tickets\Http\Resources\TicketMessageResource;

class TicketAdminFullResource extends JsonResource
{

	public function toArray(Request $request): array
	{
		return [
			'id'               => $this->id,
			'subject'          => $this->subject,
			'status'           => $this->status,
			'external_id'      => $this->external_id,
			'user_external_id' => $this->user_external_id,
			'created_at'       => $this->created_at,
			'updated_at'       => $this->updated_at,
			'user'             => [
				'email' => $this->user?->email,
				'id'    => $this->user?->id,
			],
			'support'          => [
				'id'    => $this?->id,
				'name'  => $this->support?->name,
				'email' => $this->support?->email
			],
			'messages'         => TicketMessageAdminResource::collection($this->messages),
		];
	}
}