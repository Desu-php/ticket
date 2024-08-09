<?php

namespace Maxdev\Tickets\Services;

use Illuminate\Foundation\Auth\User;
use Maxdev\Tickets\Contracts\TicketDriverContract;
use Maxdev\Tickets\Models\TicketHdeUser;

class HdeUserService
{
	public function __construct(
		protected TicketDriverContract $driver
	)
	{
	}

	public function findUser(User $user): TicketHdeUser
	{
		$hdeUser = TicketHdeUser::where('user_id', $user->id)
		                        ->first();
		if (!$hdeUser) {
			$response = $this->driver->searchUser($user->email);

			$hdeUser = TicketHdeUser::create([
				'user_id'     => $user->id,
				'external_id' => $response->id
			]);
		}

		return $hdeUser;
	}

	public function findUserByExternalId(User $user, int $external_id): TicketHdeUser
	{
		return TicketHdeUser::firstOrCreate([
			'user_id' => $user->id,
			'external_id' => $external_id
		],);
	}
}