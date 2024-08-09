<?php

namespace Maxdev\Tickets\Actions;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\DB;
use Maxdev\Tickets\Enums\TicketNotificationTypeEnum;
use Maxdev\Tickets\Enums\TicketStatusEnum;
use Maxdev\Tickets\Models\Ticket;
use Maxdev\Tickets\Models\TicketMessage;
use Maxdev\Tickets\Models\TicketNotification;
use Maxdev\Tickets\Services\TelegramService;

class TicketNotificationAction
{
	public function execute(TicketNotificationTypeEnum $type): void
	{
		$tickets = $this->getTickets($type);

		if ($tickets->isEmpty()) {
			return;
		}

		DB::transaction(function () use ($tickets, $type) {
			$data = [];

			foreach ($tickets as $ticket) {
				$data[] = [
					'ticket_id'         => $ticket->id,
					'ticket_message_id' => $ticket->last_message_id,
					'type'              => $type,
					'created_at'        => now(),
					'updated_at'        => now()
				];
			}

			TicketNotification::query()->insert($data);

			$this->sendMessage($type, $tickets->count());
		});
	}

	private function getTickets(TicketNotificationTypeEnum $type): Collection
	{
		$ticketNotificationQuery = TicketNotification::query()
		                                             ->select([
			                                             DB::raw('MAX(ticket_message_id) as last_message_id'),
			                                             'ticket_id'
		                                             ])
		                                             ->where('type', $type)
		                                             ->groupBy('ticket_id');

		$messageQuery = TicketMessage::query()
		                             ->select([
			                             DB::raw('MAX(message_created_at) as last_messaged_at'),
			                             DB::raw('MAX(id) as last_message_id'),
			                             'ticket_id'
		                             ])
		                             ->where('message_created_at', '<=', now()->addMinutes($type->getMinutes()))
		                             ->groupBy('ticket_id');

		return Ticket::query()
		             ->select([
			             'tickets.id as id',
			             'm.last_message_id as last_message_id'
		             ])
		             ->where('status', TicketStatusEnum::WaitingForManager)
		             ->leftJoinSub($ticketNotificationQuery, 'n', 'n.ticket_id', '=', 'tickets.id')
		             ->joinSub($messageQuery, 'm', 'm.ticket_id', '=', 'tickets.id')
		             ->where('m.last_message_id', '>', DB::raw('IFNULL(n.last_message_id, 0)'))
		             ->get();
	}

	/**
	 * @throws RequestException
	 */
	private function sendMessage(TicketNotificationTypeEnum $type, int $count): void
	{
		$telegram = app(TelegramService::class, [
			'token' => $type->getTelegramToken()
		]);

		$telegram->sendMessage($type->getChatId(), $type->getMinutes() . ' минут ' . $count . ' не отвеченных тикета, нужна реакция');
	}
}