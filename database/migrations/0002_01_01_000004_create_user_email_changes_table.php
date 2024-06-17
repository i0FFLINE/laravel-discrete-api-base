<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('user_email_changes', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')->index()->references('id')->on('users')->cascadeOnDelete();
            $table->string('old_email')->index();
            $table->string('new_email')->index();
            $table->timestamp('old_email_verified_at')->index()->nullable();
            $table->timestamp('new_email_verified_at')->index()->nullable();
            $table->timestamp('valid_until')->index()->default(now()->addHour());
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_email_changes');
    }
};
