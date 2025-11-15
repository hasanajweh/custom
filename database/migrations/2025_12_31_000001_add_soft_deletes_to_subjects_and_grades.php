<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('subjects', function (Blueprint $table) {
            if (!Schema::hasColumn('subjects', 'deleted_at')) {
                $table->softDeletes();
            }
        });

        Schema::table('grades', function (Blueprint $table) {
            if (!Schema::hasColumn('grades', 'deleted_at')) {
                $table->softDeletes();
            }
        });
    }

    public function down(): void
    {
        Schema::table('subjects', function (Blueprint $table) {
            if (Schema::hasColumn('subjects', 'deleted_at')) {
                $table->dropSoftDeletes();
            }
        });

        Schema::table('grades', function (Blueprint $table) {
            if (Schema::hasColumn('grades', 'deleted_at')) {
                $table->dropSoftDeletes();
            }
        });
    }
};
