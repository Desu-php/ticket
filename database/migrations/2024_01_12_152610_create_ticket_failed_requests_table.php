<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
	/**
	 * Run the migrations.
	 */
	public function up(): void
	{
		Schema::create('ticket_failed_requests', function (Blueprint $table) {
			$table->id();
			$table->morphs('model');
			$table->string('action')->index();
			$table->string('status')->index();
			$table->unsignedBigInteger('try')->default(0);
			$table->unsignedInteger('period_minutes');
			$table->json('data')->nullable();
			$table->timestamp('next_request_at');
			$table->softDeletes();
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('ticket_failed_requests');
	}
};
