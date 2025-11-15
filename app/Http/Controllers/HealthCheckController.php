<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Queue;
use Illuminate\Http\JsonResponse;

class HealthCheckController extends Controller
{
    public function __invoke(): JsonResponse
    {
        $checks = [
            'database' => $this->checkDatabase(),
            'cache' => $this->checkCache(),
            'storage' => $this->checkStorage(),
            'queue' => $this->checkQueue(),
        ];

        $healthy = collect($checks)->every(fn($check) => $check['status'] === 'ok');

        return response()->json([
            'status' => $healthy ? 'healthy' : 'unhealthy',
            'timestamp' => now()->toIso8601String(),
            'checks' => $checks,
            'version' => config('app.version', '1.0.0'),
        ], $healthy ? 200 : 503);
    }

    private function checkDatabase(): array
    {
        try {
            DB::connection()->getPdo();
            $time = microtime(true);
            DB::select('SELECT 1');
            $responseTime = (microtime(true) - $time) * 1000;

            return [
                'status' => 'ok',
                'response_time_ms' => round($responseTime, 2),
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => 'Database connection failed',
            ];
        }
    }


    private function checkStorage(): array
    {
        try {
            $testFile = 'health_check.txt';
            Storage::disk('local')->put($testFile, 'test');
            $exists = Storage::disk('local')->exists($testFile);
            Storage::disk('local')->delete($testFile);

            return [
                'status' => $exists ? 'ok' : 'error',
                'driver' => config('filesystems.default'),
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => 'Storage not accessible',
            ];
        }
    }

    private function checkQueue(): array
    {
        try {
            $connection = config('queue.default');
            $size = Queue::size();

            return [
                'status' => 'ok',
                'connection' => $connection,
                'pending_jobs' => $size,
                'warning' => $size > 1000 ? 'Queue backlog detected' : null,
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => 'Queue connection failed',
            ];
        }
    }
}
