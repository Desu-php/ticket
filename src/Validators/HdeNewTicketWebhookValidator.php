<?php
declare(strict_types=1);

namespace Maxdev\Tickets\Validators;

use Maxdev\Tickets\Contracts\Webhook\NewTicketWebhookValidatorContract;
use Maxdev\Tickets\Dto\Ticket\Webhook\NewTicketWebhookDto;
use Maxdev\Tickets\Dto\Validator\BaseValidatorDto;

class HdeNewTicketWebhookValidator extends BaseValidator implements NewTicketWebhookValidatorContract
{
	public function validate(NewTicketWebhookDto $dto): bool
	{
		return $this->check(BaseValidatorDto::from($dto->toArray()));
	}
}