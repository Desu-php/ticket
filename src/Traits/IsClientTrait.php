<?php

namespace Maxdev\Tickets\Traits;

use Maxdev\Tickets\Enums\UserTypeEnum;

trait IsClientTrait
{
	public function getUserType(): ?string
	{
		return $this->user_type;
	}

	public function isClient(): bool
	{
		return $this->getUserType() === UserTypeEnum::Client->value;
	}
}