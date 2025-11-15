<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('activity_log')) {
            return;
        }

        Schema::table('activity_log', function (Blueprint $table) {
            if (!Schema::hasColumn('activity_log', 'school_id')) {
                $table->foreignId('school_id')
                    ->nullable()
                    ->after('batch_uuid')
                    ->constrained()
                    ->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('activity_log')) {
            return;
        }

        Schema::table('activity_log', function (Blueprint $table) {
            if (Schema::hasColumn('activity_log', 'school_id')) {
                $table->dropConstrainedForeignId('school_id');
            }
        });
    }
};