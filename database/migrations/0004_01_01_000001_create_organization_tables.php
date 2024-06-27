<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use IOF\Utils\Sorter;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('organizations', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('owner_id')->index()->references('id')->on('users')->cascadeOnDelete();
            $table->integer(Sorter::FIELD)->index()->default(1);
            $table->string('title')->index();
            $table->text('description')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::create('organizations_members', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('organization_id')->index()->references('id')->on('organizations')->cascadeOnDelete();
            $table->foreignUuid('user_id')->index()->references('id')->on('users')->cascadeOnDelete();
            $table->integer('role')->index()->nullable();
            $table->foreignUuid('updated_by')->index()->references('id')->on('users')->cascadeOnDelete();
            // invite
            $table->integer('invite_role')->index()->nullable();
            $table->foreignUuid('invited_by')->index()->nullable()->references('id')->on('users')->nullOnDelete();
            $table->timestamp('invited_at')->index()->nullable();
            // confirmation
            $table->foreignUuid('invite_confirmed_by')->index()->nullable()->references('id')->on('users')->nullOnDelete();
            $table->timestamp('invite_confirmed_at')->index()->nullable();
            // timestamps
            $table->timestamps();
            $table->softDeletes();
            $table->unique(['organization_id', 'user_id']);
        });
        Schema::table('profiles', function (Blueprint $table) {
            $table->foreignUuid(config('discreteapibase.organization.singular_name') . '_id')->nullable()->index()->references('id')->on('organizations')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('profiles', function (Blueprint $table) {
            $table->dropColumn('organization_id');
        });
        Schema::dropIfExists('organizations_members');
        Schema::dropIfExists('organizations');
    }
};
