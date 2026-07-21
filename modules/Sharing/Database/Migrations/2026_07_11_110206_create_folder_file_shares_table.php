<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('folder_file_shares', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sender_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('receiver_id')->constrained('users');
            $table->enum('permission', ['read', 'write'])->default('read');
            $table->string('shared_type', 64);
            $table->unsignedBigInteger('shared_id');
            $table->dateTime('created_at');
            $table->dateTime('updated_at');

            $table->index(['shared_type', 'shared_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('folder_file_shares');
    }
};
