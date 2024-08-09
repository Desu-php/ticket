<?php

namespace Maxdev\Tickets\Contracts;

use Maxdev\Tickets\Dto\Validator\BaseValidatorDto;

interface ProductAndProjectRuleContract
{
	public function check(BaseValidatorDto $dto): bool;
}