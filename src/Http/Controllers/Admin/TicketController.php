<?php
declare(strict_types=1);

namespace Maxdev\Tickets\Http\Controllers\Admin;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\URL;
use Maxdev\Tickets\Http\Resources\Admin\TicketAdminFullResource;
use Maxdev\Tickets\Http\Resources\Admin\TicketAdminResource;
use Maxdev\Tickets\Http\Responses\SuccessResponse;
use Maxdev\Tickets\Models\Ticket;

class TicketController extends Controller
{
	public function index(): JsonResource
	{
		return TicketAdminResource::collection(
			Ticket::query()
			      ->latest('id')
			      ->paginate(config('max_tickets.admin_per_page'))
		);
	}

	public function getWithoutUserTickets(): JsonResource
	{
		return TicketAdminResource::collection(
			Ticket::query()
			      ->latest('id')
			      ->whereNull('user_id')
			      ->paginate(config('max_tickets.admin_per_page'))
		);
	}

	public function show(Ticket $ticket): JsonResource
	{
		$ticket->load('messages.user', 'messages.attachments');

		return TicketAdminFullResource::make(
			$ticket
		);
	}

	public function generateUrl(Ticket $ticket): SuccessResponse
	{
		return new SuccessResponse(200, [
			'url' => URL::temporarySignedRoute('ticket.bind.user', now()->addMinutes(20), ['ticket' => $ticket])
		]);
	}
}