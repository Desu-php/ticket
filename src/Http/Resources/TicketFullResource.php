<?php

namespace Maxdev\Tickets\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TicketFullResource extends JsonResource
{

	public function toArray(Request $request): array
	{
		return [
			'id'         => $this->id,
			'subject'    => $this->subject,
			'status'     => $this->status,
			'created_at' => $this->created_at,
			'updated_at' => $this->updated_at,
			'user'       => [
				'email' => $this->user->email
			],
			'support'    => [
				'name' => $this->support?->title ?? config('max_tickets.support.default_slug')
			],
			'messages'   => TicketMessageResource::collection($this->messages),
		];
	}
}