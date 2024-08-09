<?php

namespace Maxdev\Tickets;

use Illuminate\Support\Traits\Macroable;

class TicketPackage
{
	use Macroable;
	public static function path(string $path = ''): string
	{
		$current = dirname(__DIR__);

		return realpath($current . ($path ? DIRECTORY_SEPARATOR . $path : $path));
	}
}