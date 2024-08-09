<?php

use Illuminate\Support\Facades\Route;
use Maxdev\Tickets\Http\Controllers\Admin\TicketController;
use Maxdev\Tickets\Http\Controllers\Admin\TicketSupportController;

Route::group(['prefix' => 'tickets', 'controller' => TicketController::class], function () {
	Route::get('', 'index');
	Route::post('{ticket}/user/unknown/url', 'generateUrl');
	Route::get('user/unknown', 'getWithoutUserTickets');
	Route::get('{ticket}', 'show');
});

Route::group(['prefix' => 'ticket-supports', 'controller' => TicketSupportController::class], function () {
	Route::get('', 'index');
	Route::get('{ticket_support}', 'show');
	Route::put('{ticket_support}', 'update');
	Route::delete('{ticket_support}', 'destroy');
});