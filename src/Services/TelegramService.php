<?php

namespace Maxdev\Tickets\Services;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;

class TelegramService
{
	protected PendingRequest $client;

	public function __construct(protected string $token)
	{
		$this->client = Http::baseUrl(
			'https://api.telegram.org/bot' . $this->token
		);
	}

	/**
	 * @throws RequestException
	 */
	public function sendMessage(int $chatId, string $message): void
	{
		$this->client->get('sendMessage', [
			'chat_id' => $chatId,
			'text'    => $message
		])->throw();
	}
}