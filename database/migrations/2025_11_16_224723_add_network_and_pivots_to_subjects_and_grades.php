<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('subjects', function (Blueprint $table) {
            if (!Schema::hasColumn('subjects', 'network_id')) {
                $table->foreignId('network_id')->nullable()->after('school_id')->constrained('networks')->nullOnDelete();
            }
        });

        Schema::table('grades', function (Blueprint $table) {
            if (!Schema::hasColumn('grades', 'network_id')) {
                $table->foreignId('network_id')->nullable()->after('school_id')->constrained('networks')->nullOnDelete();
            }
        });

        Schema::create('grade_school', function (Blueprint $table) {
            $table->id();
            $table->foreignId('grade_id')->constrained()->cascadeOnDelete();
            $table->foreignId('school_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['grade_id', 'school_id']);
        });

        Schema::create('grade_network', function (Blueprint $table) {
            $table->id();
            $table->foreignId('grade_id')->constrained()->cascadeOnDelete();
            $table->foreignId('network_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['grade_id', 'network_id']);
        });

        Schema::create('subject_school', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subject_id')->constrained()->cascadeOnDelete();
            $table->foreignId('school_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['subject_id', 'school_id']);
        });

        Schema::create('subject_network', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subject_id')->constrained()->cascadeOnDelete();
            $table->foreignId('network_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['subject_id', 'network_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subject_network');
        Schema::dropIfExists('subject_school');
        Schema::dropIfExists('grade_network');
        Schema::dropIfExists('grade_school');

        Schema::table('grades', function (Blueprint $table) {
            if (Schema::hasColumn('grades', 'network_id')) {
                $table->dropConstrainedForeignId('network_id');
            }
        });

        Schema::table('subjects', function (Blueprint $table) {
            if (Schema::hasColumn('subjects', 'network_id')) {
                $table->dropConstrainedForeignId('network_id');
            }
        });
    }
};
