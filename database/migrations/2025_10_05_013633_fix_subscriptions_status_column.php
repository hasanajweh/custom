<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Check current column type
        $columnType = DB::select("SHOW COLUMNS FROM subscriptions WHERE Field = 'status'");

        if (!empty($columnType)) {
            // Drop the existing status column if it exists
            Schema::table('subscriptions', function (Blueprint $table) {
                if (Schema::hasColumn('subscriptions', 'status')) {
                    $table->dropColumn('status');
                }
            });
        }

        // Add the status column with all needed values
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->enum('status', ['pending', 'active', 'paused', 'cancelled', 'expired'])
                ->default('pending')
                ->after('plan_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            if (Schema::hasColumn('subscriptions', 'status')) {
                $table->dropColumn('status');
            }
        });

        // Restore original if needed
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->enum('status', ['active', 'paused', 'cancelled'])
                ->default('active')
                ->after('plan_id');
        });
    }
};
