<?php
declare(strict_types=1);

namespace Maxdev\Tickets\Validators;

use Maxdev\Tickets\Contracts\Webhook\ChangeStatusTicketWebhookValidatorContract;
use Maxdev\Tickets\Dto\Ticket\Webhook\ChangeStatusTicketWebhookDto;
use Maxdev\Tickets\Dto\Validator\BaseValidatorDto;

class HdeChangeStatusTicketWebhookValidator extends BaseValidator implements ChangeStatusTicketWebhookValidatorContract
{
	public function validate(ChangeStatusTicketWebhookDto $dto): bool
	{
		return $this->check(BaseValidatorDto::from($dto->toArray()));
	}
}