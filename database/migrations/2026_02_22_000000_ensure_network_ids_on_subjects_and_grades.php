<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
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

        if (Schema::hasColumn('subjects', 'network_id')) {
            DB::table('subjects')
                ->join('schools', 'subjects.school_id', '=', 'schools.id')
                ->whereNull('subjects.network_id')
                ->update(['subjects.network_id' => DB::raw('schools.network_id')]);
        }

        if (Schema::hasColumn('grades', 'network_id')) {
            DB::table('grades')
                ->join('schools', 'grades.school_id', '=', 'schools.id')
                ->whereNull('grades.network_id')
                ->update(['grades.network_id' => DB::raw('schools.network_id')]);
        }
    }

    public function down(): void
    {
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
