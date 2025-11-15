<?php
// database/migrations/[timestamp]_add_missing_columns_to_subscriptions_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            // Check and add missing columns
            if (!Schema::hasColumn('subscriptions', 'status')) {
                $table->string('status')->default('active')->after('plan_id');
            }

            if (!Schema::hasColumn('subscriptions', 'starts_at')) {
                $table->timestamp('starts_at')->nullable()->after('status');
            }

            if (!Schema::hasColumn('subscriptions', 'ends_at')) {
                $table->timestamp('ends_at')->nullable()->after('starts_at');
            }

            if (!Schema::hasColumn('subscriptions', 'cancelled_at')) {
                $table->timestamp('cancelled_at')->nullable()->after('ends_at');
            }

            // Add indexes for performance
            $table->index('status');
            $table->index(['status', 'ends_at']);
        });
    }

    public function down(): void
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->dropColumn(['status', 'starts_at', 'ends_at', 'cancelled_at']);
        });
    }
};
