<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Maxdev\Tickets\Models\Ticket;
use Maxdev\Tickets\Models\TicketSupport;

return new class extends Migration {
	/**
	 * Run the migrations.
	 */
	public function up(): void
	{
		Schema::create('ticket_messages', function (Blueprint $table) {
			$table->id();
			$table->foreignIdFor(Ticket::class)->index();
			$table->foreignIdFor(config('max_tickets.user'))->nullable()->index();
			$table->foreignIdFor(TicketSupport::class)->nullable()->index();
			$table->unsignedBigInteger('external_id')->nullable()->unique();
			$table->bigInteger('user_external_id')->nullable()->index();
			$table->text('message');
			$table->string('status')->index();
			$table->timestamp('message_created_at');
			$table->softDeletes();
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('ticket_messages');
	}
};
