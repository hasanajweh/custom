<?php
// database/migrations/2025_07_28_000002_create_custom_notifications_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('custom_notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('username');
            $table->string('file_title');
            $table->string('grade')->nullable();
            $table->string('subject')->nullable();
            $table->string('submission_type'); // exam, worksheet, summary, plan
            $table->string('plan_type')->nullable(); // daily, weekly, monthly
            $table->foreignId('file_submission_id')->constrained()->onDelete('cascade');
            $table->boolean('is_read')->default(false);
            $table->timestamps();

            $table->index(['school_id', 'is_read']);
            $table->index(['school_id', 'created_at']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('custom_notifications');
    }
};
