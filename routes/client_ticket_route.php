<?php

use Illuminate\Support\Facades\Route;
use Maxdev\Tickets\Http\Controllers\TicketController;
use Maxdev\Tickets\Http\Controllers\TicketMessageController;



Route::group(['prefix' => 'tickets', 'controller' => TicketController::class], function () {
	Route::post('', 'store');
	Route::get('', 'index');
	Route::get('{ticket}', 'show');
	Route::post('{ticket}/close', 'close');
	Route::post('{ticket}/bind', 'bindUser')
		->middleware('signed')
	     ->name('ticket.bind.user');
});

Route::group(['prefix' => 'messages', 'controller' => TicketMessageController::class], function () {
	Route::post('{ticket}', 'store');
	Route::get('{ticket}', 'getByTicket');
	Route::get('{ticket}/last/{id}', 'getLastMessages');
});
