<?php

namespace Maxdev\Tickets\Http\Middlewares;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAccessKeyMiddleware
{
	public function handle(Request $request, Closure $next): Response
	{
		abort_if(
			$request->header('ticket-access-key') != config('max_tickets.webhook_access_key'),
			403,
			'Access denied'
		);

		return $next($request);
	}
}