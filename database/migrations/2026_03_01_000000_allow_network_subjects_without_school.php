<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('subjects', function (Blueprint $table) {
            $table->foreignId('school_id')->nullable()->change();
        });

        Schema::table('grades', function (Blueprint $table) {
            $table->foreignId('school_id')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('subjects', function (Blueprint $table) {
            $table->foreignId('school_id')->constrained('schools')->onDelete('cascade')->change();
        });

        Schema::table('grades', function (Blueprint $table) {
            $table->foreignId('school_id')->constrained('schools')->onDelete('cascade')->change();
        });
    }
};
