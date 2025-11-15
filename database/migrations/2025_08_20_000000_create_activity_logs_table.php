<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
         if (!Schema::hasTable('activity_logs')) {
            return;
        }

        if (!Schema::hasTable('activity_log')) {
            Schema::rename('activity_logs', 'activity_log');
            return;
        }

        $legacyLogs = DB::table('activity_logs')->orderBy('id')->get();

        foreach ($legacyLogs as $log) {
            if (DB::table('activity_log')->where('id', $log->id)->exists()) {
                continue;
            }

            $context = null;
            if (!empty($log->context)) {
                $decoded = json_decode($log->context, true);
                $context = json_last_error() === JSON_ERROR_NONE ? $decoded : $log->context;
            }

            $properties = array_filter([
                'action' => $log->action,
                'url' => $log->url,
                'ip_address' => $log->ip_address,
                'user_agent' => $log->user_agent,
                'response_code' => $log->response_code,
                'success' => $log->success,
                'context' => $context,
                'school_id' => $log->school_id,
            ], static fn ($value) => $value !== null && $value !== '');

            DB::table('activity_log')->insert([
                'id' => $log->id,
                'log_name' => $log->event ?: 'legacy',
                'description' => $log->description,
                'subject_type' => null,
                'subject_id' => null,
                'causer_type' => $log->user_id ? 'App\\Models\\User' : null,
                'causer_id' => $log->user_id,
                'properties' => !empty($properties) ? json_encode($properties) : null,
                'batch_uuid' => null,
                'event' => $log->event,
                'created_at' => $log->created_at,
                'updated_at' => $log->updated_at,
                'school_id' => Schema::hasColumn('activity_log', 'school_id') ? $log->school_id : null,
            ]);
        }

        Schema::dropIfExists('activity_logs');
    }

    public function down(): void
    {
        if (Schema::hasTable('activity_logs')) {
            return;
        }
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('school_id')->nullable()->constrained()->nullOnDelete();
            $table->string('event')->nullable();
            $table->string('action', 20)->nullable();
            $table->text('url')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->integer('response_code')->nullable();
            $table->text('description')->nullable();
            $table->json('context')->nullable();
            $table->boolean('success')->default(true);
            $table->timestamps();

            $table->index('event');
            $table->index('action');
            $table->index('user_id');
            $table->index('school_id');
            $table->index('ip_address');
            $table->index('success');
            $table->index('created_at');
        });
    }

};