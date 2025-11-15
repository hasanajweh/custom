<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('supervisor_subjects')) {
            Schema::create('supervisor_subjects', function (Blueprint $table) {
                $table->id();
                $table->foreignId('supervisor_id')->constrained('users')->onDelete('cascade');
                $table->foreignId('subject_id')->constrained()->onDelete('cascade');
                $table->foreignId('school_id')->constrained()->onDelete('cascade');
                $table->timestamps();

                // Ensure unique combination
                $table->unique(['supervisor_id', 'subject_id']);
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('supervisor_subjects');
    }
};
