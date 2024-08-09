<?php
use Maxdev\Tickets\Http\Middlewares\CheckAccessKeyMiddleware;
use Maxdev\Tickets\Http\Controllers\TicketController;
use Maxdev\Tickets\Http\Controllers\TicketMessageController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => [CheckAccessKeyMiddleware::class], 'prefix' => 'webhook'], function () {
	Route::post('messages', [TicketMessageController::class, 'webhook']);

	Route::controller(TicketController::class)->group(function () {
		Route::post('new/ticket', 'newTicketWebhook');
		Route::post('change/ticket/status', 'changeTicketStatusWebhook');
		Route::post('change/ticket/owner', 'changeTicketOwnerWebhook');
	});
});