<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Maxdev\Tickets\Models\TicketMessage;

return new class extends Migration {
	/**
	 * Run the migrations.
	 */
	public function up(): void
	{
		Schema::create('ticket_attachments', function (Blueprint $table) {
			$table->id();
			$table->foreignIdFor(TicketMessage::class)->index();
			$table->string('original_name');
			$table->string('path');
			$table->string('extension');
			$table->string('mimes');
			$table->string('disk');
			$table->unsignedBigInteger('size');
			$table->softDeletes();
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('ticket_attachments');
	}
};
