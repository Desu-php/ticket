<?php
declare(strict_types=1);

namespace Maxdev\Tickets\Validators;

use Maxdev\Tickets\Contracts\Webhook\NewMessageWebhookValidatorContract;
use Maxdev\Tickets\Dto\TicketMessage\NewMessageWebhookDto;
use Maxdev\Tickets\Dto\Validator\BaseValidatorDto;

class HdeNewMessageWebhookValidator extends BaseValidator implements NewMessageWebhookValidatorContract
{
	public function validate(NewMessageWebhookDto $dto): bool
	{
		return $this->check(BaseValidatorDto::from($dto->toArray()));
	}
}