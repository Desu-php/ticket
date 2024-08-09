<?php
declare(strict_types=1);

namespace Maxdev\Tickets\Contracts\Webhook;

use Maxdev\Tickets\Dto\Ticket\Webhook\ChangeStatusTicketWebhookDto;

interface ChangeStatusTicketWebhookValidatorContract
{
	public function validate(ChangeStatusTicketWebhookDto $dto): bool;
}