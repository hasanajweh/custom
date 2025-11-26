<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('subjects', function (Blueprint $table) {
            if (!Schema::hasColumn('subjects', 'created_by')) {
                $table->foreignId('created_by')->nullable()->after('name')->constrained('users')->nullOnDelete();
            }
            if (!Schema::hasColumn('subjects', 'created_in')) {
                $table->foreignId('created_in')->nullable()->after('created_by')->constrained('schools')->nullOnDelete();
            }
        });

        Schema::table('grades', function (Blueprint $table) {
            if (!Schema::hasColumn('grades', 'created_by')) {
                $table->foreignId('created_by')->nullable()->after('name')->constrained('users')->nullOnDelete();
            }
            if (!Schema::hasColumn('grades', 'created_in')) {
                $table->foreignId('created_in')->nullable()->after('created_by')->constrained('schools')->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('subjects', function (Blueprint $table) {
            if (Schema::hasColumn('subjects', 'created_in')) {
                $table->dropConstrainedForeignId('created_in');
            }
            if (Schema::hasColumn('subjects', 'created_by')) {
                $table->dropConstrainedForeignId('created_by');
            }
        });

        Schema::table('grades', function (Blueprint $table) {
            if (Schema::hasColumn('grades', 'created_in')) {
                $table->dropConstrainedForeignId('created_in');
            }
            if (Schema::hasColumn('grades', 'created_by')) {
                $table->dropConstrainedForeignId('created_by');
            }
        });
    }
};
