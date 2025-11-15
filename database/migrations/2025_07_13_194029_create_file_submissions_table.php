<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('file_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained('schools')->onDelete('cascade');
            $table->foreignId('user_id')->comment('The ID of the teacher who uploaded')->constrained('users')->onDelete('cascade');

            $table->string('title');
            $table->string('subject');
            $table->unsignedTinyInteger('grade');
            $table->string('file_path');
            $table->string('original_filename');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('file_submissions');
    }
};
