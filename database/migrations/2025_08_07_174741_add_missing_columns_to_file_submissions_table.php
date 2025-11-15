<?php
// database/migrations/[timestamp]_add_missing_columns_to_file_submissions_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('file_submissions', function (Blueprint $table) {
            // Add subject_id if it doesn't exist
            if (!Schema::hasColumn('file_submissions', 'subject_id')) {
                $table->foreignId('subject_id')->nullable()->after('mime_type')->constrained()->onDelete('cascade');
            }

            // Add grade_id if it doesn't exist
            if (!Schema::hasColumn('file_submissions', 'grade_id')) {
                $table->foreignId('grade_id')->nullable()->after('subject_id')->constrained()->onDelete('cascade');
            }

            // Add other potentially missing columns
            if (!Schema::hasColumn('file_submissions', 'description')) {
                $table->text('description')->nullable()->after('title');
            }

            if (!Schema::hasColumn('file_submissions', 'rejection_reason')) {
                $table->text('rejection_reason')->nullable()->after('status');
            }

            if (!Schema::hasColumn('file_submissions', 'download_count')) {
                $table->integer('download_count')->default(0)->after('rejection_reason');
            }

            if (!Schema::hasColumn('file_submissions', 'last_accessed_at')) {
                $table->timestamp('last_accessed_at')->nullable()->after('download_count');
            }
        });
    }

    public function down()
    {
        Schema::table('file_submissions', function (Blueprint $table) {
            $table->dropForeign(['subject_id']);
            $table->dropColumn('subject_id');
            $table->dropForeign(['grade_id']);
            $table->dropColumn('grade_id');
            $table->dropColumn(['description', 'rejection_reason', 'download_count', 'last_accessed_at']);
        });
    }
};
