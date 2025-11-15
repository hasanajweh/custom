<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        DB::statement("ALTER TABLE `users` MODIFY `role` ENUM('admin','teacher','supervisor','main_admin') NOT NULL DEFAULT 'teacher'");
    }

    public function down(): void
    {
        DB::table('users')->where('role', 'main_admin')->update(['role' => 'admin']);
        DB::statement("ALTER TABLE `users` MODIFY `role` ENUM('admin','teacher','supervisor') NOT NULL DEFAULT 'teacher'");
    }
};
