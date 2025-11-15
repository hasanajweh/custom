<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add features to plans
        if (Schema::hasTable('plans') && !Schema::hasColumn('plans', 'features')) {
            Schema::table('plans', function (Blueprint $table) {
                $table->json('features')->nullable()->after('storage_limit_in_gb');
            });
        }

        // Add columns to schools
        if (Schema::hasTable('schools') && !Schema::hasColumn('schools', 'is_active')) {
            Schema::table('schools', function (Blueprint $table) {
                $table->boolean('is_active')->default(true)->after('slug');
                $table->json('settings')->nullable()->after('is_active');
                $table->bigInteger('storage_limit')->default(10737418240)->after('settings');
                $table->bigInteger('storage_used')->default(0)->after('storage_limit');
            });
        }

        // Create custom notifications table
        if (!Schema::hasTable('custom_notifications')) {
            Schema::create('custom_notifications', function (Blueprint $table) {
                $table->id();
                $table->foreignId('school_id')->constrained()->onDelete('cascade');
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->string('username');
                $table->string('file_title');
                $table->string('grade')->nullable();
                $table->string('subject')->nullable();
                $table->string('submission_type');
                $table->string('plan_type')->nullable();
                $table->foreignId('file_submission_id')->constrained()->onDelete('cascade');
                $table->boolean('is_read')->default(false);
                $table->timestamps();

                $table->index(['school_id', 'is_read']);
                $table->index(['school_id', 'created_at']);
            });
        }

        // Add missing columns to file_submissions
        if (Schema::hasTable('file_submissions')) {
            Schema::table('file_submissions', function (Blueprint $table) {
                $columns = Schema::getColumnListing('file_submissions');

                if (!in_array('deleted_at', $columns)) {
                    $table->softDeletes();
                }
                if (!in_array('file_size', $columns)) {
                    $table->bigInteger('file_size')->default(0);
                }
                if (!in_array('file_hash', $columns)) {
                    $table->string('file_hash')->nullable();
                }
                if (!in_array('mime_type', $columns)) {
                    $table->string('mime_type')->default('application/octet-stream');
                }
                if (!in_array('download_count', $columns)) {
                    $table->integer('download_count')->default(0);
                }
                if (!in_array('last_accessed_at', $columns)) {
                    $table->timestamp('last_accessed_at')->nullable();
                }
                if (!in_array('status', $columns)) {
                    $table->string('status')->nullable();
                }
                if (!in_array('rejection_reason', $columns)) {
                    $table->text('rejection_reason')->nullable();
                }
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('custom_notifications');

        if (Schema::hasTable('plans')) {
            Schema::table('plans', function (Blueprint $table) {
                $table->dropColumn('features');
            });
        }

        if (Schema::hasTable('schools')) {
            Schema::table('schools', function (Blueprint $table) {
                $table->dropColumn(['is_active', 'settings', 'storage_limit', 'storage_used']);
            });
        }
    }
};
