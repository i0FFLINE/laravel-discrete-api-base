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
        Schema::create('notification_alerts', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')->index()->references('id')->on('users')->cascadeOnDelete();
            $table->string('type')->index()->default('toast');
            $table->string('dismissable')->index()->default('yes');
            $table->integer('auto_dismiss')->index()->nullable();
            $table->text('message');
            $table->timestamp('read_at')->index()->nullable();
            $table->timestamps();
        });
        /**
         * COUNT
         *
         *      return Notifications::where('user_id', '<UUID>')
         *          ->select('type', DB::raw("count(*) as total") )
         *          ->groupBy('type')
         *          ->pluck('total', 'type');
         *
         * GET:
         *
         *      return Notifications::whereNull('read_at')->orderBy('created_at');
         *
         * SET READ, preventing dirty reading:
         *
         *      Notifications::where('user_id', '<UUID>')
         *          ->whereNull('read_at')
         *          ->whereDate('created_at', '<', now()->toDateTimeString())
         *          ->update();
         *
         * REMOVE OLD (date range: week old default)
         *
         *      $defaultDays = 7;
         *      $time = now()->subDays($defaultDays)->setTime(0, 0, 0)->toDateTimeString();
         *
         *      Notifications::where('user_id', '<UUID>')
         *          ->whereNotNull('read_at')
         *          ->whereDate('created_at', '<', now()->toDateTimeString())
         *          ->delete()
         *
         * REMOVE ALL READ
         *
         *      Notifications::where('user_id', '<UUID>')
         *          ->whereNotNull('read_at')
         *          ->delete()
         *
         */
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notification_alerts');
    }
};
