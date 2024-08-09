<?php

namespace Maxdev\Tickets\Dto\Validator;

use Spatie\LaravelData\Data;

class BaseValidatorDto extends Data
{
	public function __construct(
		public string $project,
		public string $product
	)
	{
	}
}