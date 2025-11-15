<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('schools', function (Blueprint $table) {
            $table->foreignId('network_id')->nullable()->after('id')->constrained('networks')->nullOnDelete();
            $table->string('city')->nullable()->after('slug');
        });
    }

    public function down(): void
    {
        Schema::table('schools', function (Blueprint $table) {
            $table->dropForeign(['network_id']);
            $table->dropColumn(['network_id', 'city']);
        });
    }
};
