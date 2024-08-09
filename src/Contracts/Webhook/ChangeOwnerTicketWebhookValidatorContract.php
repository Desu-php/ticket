<?php
declare(strict_types=1);

namespace Maxdev\Tickets\Contracts\Webhook;

use Maxdev\Tickets\Dto\Ticket\Webhook\ChangeOwnerTicketWebhookDto;

interface ChangeOwnerTicketWebhookValidatorContract
{
	public function validate(ChangeOwnerTicketWebhookDto $dto): bool;
}