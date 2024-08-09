<?php

namespace Maxdev\Tickets\Repositories;

use Illuminate\Foundation\Auth\User;
use Maxdev\Tickets\Contracts\Repositories\UserRepositoryContract;

class UserRepository implements UserRepositoryContract
{
	public function findByEmail(string $email): ?User
	{
		return config('max_tickets.user')::where('email', $email)
		                                 ->first();
	}
}