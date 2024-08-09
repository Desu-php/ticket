<?php

namespace Maxdev\Tickets\Dto\User;

use Spatie\LaravelData\Data;

class UserResponseDto extends Data
{
	public function __construct(
		public int $id,
		public string $email,
	)
	{
	}
}