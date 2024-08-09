<?php

namespace Maxdev\Tickets\Contracts\Repositories;

use Illuminate\Foundation\Auth\User;

interface UserRepositoryContract
{
	public function findByEmail(string $email): ?User;
}