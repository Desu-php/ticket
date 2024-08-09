<?php
declare(strict_types=1);

namespace Maxdev\Tickets\Contracts\Webhook;

use Maxdev\Tickets\Dto\TicketMessage\NewMessageWebhookDto;

interface NewMessageWebhookValidatorContract
{
	public function validate(NewMessageWebhookDto $dto): bool;
}