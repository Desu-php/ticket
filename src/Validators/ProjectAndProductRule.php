<?php

namespace Maxdev\Tickets\Validators;

use Maxdev\Tickets\Contracts\HdeConfigServiceContract;
use Maxdev\Tickets\Contracts\ProductAndProjectRuleContract;
use Maxdev\Tickets\Dto\Validator\BaseValidatorDto;

class ProjectAndProductRule implements ProductAndProjectRuleContract
{
	public function __construct(
		protected HdeConfigServiceContract $config
	)
	{
	}

	public function check(BaseValidatorDto $dto): bool
	{
		return $dto->project == $this->config->getProjectFieldValue() && $dto->product == $this->config->getProductFieldValue();
	}
}