<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('network_id')->nullable()->after('school_id')->constrained('networks')->nullOnDelete();
            $afterColumn = Schema::hasColumn('users', 'is_super_admin') ? 'is_super_admin' : 'role';

            $table->boolean('is_main_admin')->default(false)->after($afterColumn);
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['network_id']);
            $table->dropColumn(['network_id', 'is_main_admin']);
        });
    }
};
