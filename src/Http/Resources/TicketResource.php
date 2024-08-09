<?php

namespace Maxdev\Tickets\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TicketResource extends JsonResource
{

	public function toArray(Request $request): array
	{
		return  [
			'id' => $this->id,
			'subject' => $this->subject,
			'status' => $this->status,
			'created_at' => $this->created_at,
			'updated_at' => $this->updated_at,
		];
	}
}