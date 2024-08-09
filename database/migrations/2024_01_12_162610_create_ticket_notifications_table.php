<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Maxdev\Tickets\Models\Ticket;
use Maxdev\Tickets\Models\TicketMessage;

return new class extends Migration {
	/**
	 * Run the migrations.
	 */
	public function up(): void
	{
		Schema::create('ticket_notifications', function (Blueprint $table) {
			$table->id();
			$table->foreignIdFor(Ticket::class)->index();
			$table->foreignIdFor(TicketMessage::class)->index()->comment('ticket last message id');
			$table->string('type');
			$table->softDeletes();
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('ticket_notifications');
	}
};
