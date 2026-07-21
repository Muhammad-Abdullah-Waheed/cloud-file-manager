<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('tier', 20)->default('normal')->after('role_id');
            // New users now default to the Normal quota (5 KB).
            $table->unsignedBigInteger('storage_limit')->default(5 * 1024)->change();
        });

        // Bring existing users in line with the new tiers.
        DB::table('users')->update([
            'tier'          => 'normal',
            'storage_limit' => 5 * 1024,
        ]);
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('tier');
            $table->unsignedBigInteger('storage_limit')->default(5368709120)->change();
        });
    }
};
