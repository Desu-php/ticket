<?php
declare(strict_types=1);

namespace Maxdev\Tickets\Validators;

use Maxdev\Tickets\Contracts\Webhook\ChangeOwnerTicketWebhookValidatorContract;
use Maxdev\Tickets\Dto\Ticket\Webhook\ChangeOwnerTicketWebhookDto;
use Maxdev\Tickets\Dto\Validator\BaseValidatorDto;

class HdeChangeOwnerTicketWebhookValidator extends BaseValidator implements ChangeOwnerTicketWebhookValidatorContract
{
	public function validate(ChangeOwnerTicketWebhookDto $dto): bool
	{
		return $this->check(BaseValidatorDto::from($dto->toArray()));
	}
}