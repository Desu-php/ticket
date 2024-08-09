<?php

namespace Maxdev\Tickets\Validators;

use Maxdev\Tickets\Contracts\ProductAndProjectRuleContract;
use Maxdev\Tickets\Dto\Validator\BaseValidatorDto;

class BaseValidator
{
	public function __construct(
		protected ProductAndProjectRuleContract $rule
	)
	{
	}

	public function check(BaseValidatorDto $dto): bool
	{
		return $this->rule->check($dto);
	}
}