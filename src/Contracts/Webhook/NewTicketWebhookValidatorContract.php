<?php
declare(strict_types=1);

namespace Maxdev\Tickets\Contracts\Webhook;

use Maxdev\Tickets\Dto\Ticket\Webhook\NewTicketWebhookDto;

interface NewTicketWebhookValidatorContract
{
	public function validate(NewTicketWebhookDto $dto): bool;
}