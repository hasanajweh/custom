<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('file_submissions', function (Blueprint $table) {
            // Add our new columns
            $table->string('submission_type')->after('user_id'); // exam, worksheet, plan, etc.
            $table->string('plan_type')->nullable()->after('submission_type'); // daily, weekly, monthly

            // Make the old columns nullable
            $table->string('subject')->nullable()->change();
            $table->unsignedTinyInteger('grade')->nullable()->change();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('file_submissions', function (Blueprint $table) {
            //
        });
    }
};
