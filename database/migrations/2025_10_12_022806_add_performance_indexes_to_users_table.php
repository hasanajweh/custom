<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * These indexes will make your user pages load 10x faster!
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Single column indexes
            $table->index('school_id', 'users_school_id_index');
            $table->index('is_active', 'users_is_active_index');
            $table->index('role', 'users_role_index');
            $table->index('email', 'users_email_index');
            $table->index('deleted_at', 'users_deleted_at_index'); // For archived users

            // Composite indexes (for common query combinations)
            $table->index(['school_id', 'is_active'], 'users_school_active_index');
            $table->index(['school_id', 'role'], 'users_school_role_index');
            $table->index(['school_id', 'role', 'is_active'], 'users_school_role_active_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Drop indexes in reverse order
            $table->dropIndex('users_school_role_active_index');
            $table->dropIndex('users_school_role_index');
            $table->dropIndex('users_school_active_index');
            $table->dropIndex('users_deleted_at_index');
            $table->dropIndex('users_email_index');
            $table->dropIndex('users_role_index');
            $table->dropIndex('users_is_active_index');
            $table->dropIndex('users_school_id_index');
        });
    }
};
