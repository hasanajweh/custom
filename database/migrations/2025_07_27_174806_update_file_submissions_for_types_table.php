<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('file_submissions', function (Blueprint $table) {
            if (!Schema::hasColumn('file_submissions', 'deleted_at')) {
                $table->softDeletes();
            }
            if (!Schema::hasColumn('file_submissions', 'file_size')) {
                $table->bigInteger('file_size')->after('original_filename')->default(0);
            }
            if (!Schema::hasColumn('file_submissions', 'file_hash')) {
                $table->string('file_hash')->nullable()->after('file_size');
            }
            if (!Schema::hasColumn('file_submissions', 'mime_type')) {
                $table->string('mime_type')->after('file_hash')->default('application/octet-stream');
            }
            if (!Schema::hasColumn('file_submissions', 'download_count')) {
                $table->integer('download_count')->default(0)->after('mime_type');
            }
            if (!Schema::hasColumn('file_submissions', 'last_accessed_at')) {
                $table->timestamp('last_accessed_at')->nullable()->after('download_count');
            }
            if (!Schema::hasColumn('file_submissions', 'status')) {
                $table->string('status')->nullable()->after('last_accessed_at');
            }
            if (!Schema::hasColumn('file_submissions', 'rejection_reason')) {
                $table->text('rejection_reason')->nullable()->after('status');
            }
        });
    }

    public function down(): void
    {
        Schema::table('file_submissions', function (Blueprint $table) {
            $table->dropSoftDeletes();
            $table->dropColumn([
                'file_size',
                'file_hash',
                'mime_type',
                'download_count',
                'last_accessed_at',
                'status',
                'rejection_reason'
            ]);
        });
    }
};
