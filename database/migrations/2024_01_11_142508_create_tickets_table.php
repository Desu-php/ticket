<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Maxdev\Tickets\Models\TicketSupport;

return new class extends Migration {
	/**
	 * Run the migrations.
	 */
	public function up(): void
	{
		Schema::create('tickets', function (Blueprint $table) {
			$table->id();
			$table->foreignIdFor(config('max_tickets.user'))->nullable()->index();
			$table->foreignIdFor(TicketSupport::class)->nullable()->index();
			$table->bigInteger('user_external_id')->nullable()->index();
			$table->unsignedBigInteger('external_id')->nullable()->unique();
			$table->string('subject');
			$table->string('status')->index();
			$table->timestamp('external_service_updated_at')->nullable();
			$table->softDeletes();
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('tickets');
	}
};
