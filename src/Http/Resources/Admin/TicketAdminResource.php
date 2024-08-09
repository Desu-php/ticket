<?php

namespace Maxdev\Tickets\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TicketAdminResource extends JsonResource
{

	public function toArray(Request $request): array
	{
		return [
			'id'          => $this->id,
			'subject'     => $this->subject,
			'status'      => $this->status,
			'external_id' => $this->external_id,
			'created_at'  => $this->created_at,
			'updated_at'  => $this->updated_at,
		];
	}
}