<?php

// ============================================
// ARTISAN COMMAND TO DEBUG S3 DOWNLOADS
// Laravel 11+ - Auto-discovered, no registration needed!
// ============================================
// app/Console/Commands/S3DebugCommand.php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use App\Models\FileSubmission;
use App\Models\School;

class S3DebugCommand extends Command
{
    protected $signature = 'debug:s3 {file_id?}';
    protected $description = 'Debug S3 download issues';

    public function handle()
    {
        $this->info('🔍 S3 DOWNLOAD DEBUGGER');
        $this->info('========================');
        $this->newLine();

        // Step 1: Check configuration
        $this->checkConfiguration();
        $this->newLine();

        // Step 2: Test S3 connection
        $this->testS3Connection();
        $this->newLine();

        // Step 3: Check specific file if provided
        $fileId = $this->argument('file_id');
        if ($fileId) {
            $this->checkSpecificFile($fileId);
        } else {
            $this->checkRandomFile();
        }
    }

    protected function checkConfiguration()
    {
        $this->info('📋 CHECKING CONFIGURATION');
        $this->line('─────────────────────────');

        $disk = config('filesystems.default');
        $this->line("Default Disk: <fg=yellow>{$disk}</>");

        if ($disk === 's3') {
            $bucket = config('filesystems.disks.s3.bucket');
            $region = config('filesystems.disks.s3.region');
            $key = config('filesystems.disks.s3.key');
            $hasKey = !empty($key);
            $hasSecret = !empty(config('filesystems.disks.s3.secret'));

            $this->line("S3 Bucket: <fg=yellow>{$bucket}</>");
            $this->line("S3 Region: <fg=yellow>{$region}</>");
            $this->line("AWS Key Present: " . ($hasKey ? '<fg=green>✓ Yes</>' : '<fg=red>✗ No</>'));
            $this->line("AWS Secret Present: " . ($hasSecret ? '<fg=green>✓ Yes</>' : '<fg=red>✗ No</>'));

            if (!$hasKey || !$hasSecret) {
                $this->error('⚠️  AWS credentials are missing!');
                $this->line('Check your .env file for:');
                $this->line('  AWS_ACCESS_KEY_ID');
                $this->line('  AWS_SECRET_ACCESS_KEY');
            }
        }
    }

    protected function testS3Connection()
    {
        $this->info('🔌 TESTING S3 CONNECTION');
        $this->line('─────────────────────────');

        if (config('filesystems.default') !== 's3') {
            $this->warn('⚠️  Not using S3 storage');
            return;
        }

        try {
            // FIXED: Use exists() instead of files() for Flysystem v3
            $testPath = 'test-connection-' . time() . '.txt';

            // Try to check if bucket is accessible
            Storage::disk('s3')->put($testPath, 'test');
            $exists = Storage::disk('s3')->exists($testPath);
            Storage::disk('s3')->delete($testPath);

            if ($exists) {
                $this->line('<fg=green>✓ Successfully connected to S3</>');
                $this->line('✓ Read/Write permissions verified');
            }
        } catch (\Exception $e) {
            $this->error('✗ Failed to connect to S3');
            $this->error('Error: ' . $e->getMessage());
            $this->newLine();
            $this->line('Common causes:');
            $this->line('  1. Wrong AWS credentials');
            $this->line('  2. Wrong bucket name');
            $this->line('  3. Wrong region');
            $this->line('  4. IAM permissions missing');
            $this->line('  5. Bucket does not exist');
        }
    }

    protected function checkSpecificFile($fileId)
    {
        $this->info("📄 CHECKING FILE ID: {$fileId}");
        $this->line('─────────────────────────');

        $file = FileSubmission::find($fileId);

        if (!$file) {
            $this->error("✗ File #{$fileId} not found in database");
            return;
        }

        $this->line("Title: <fg=yellow>{$file->title}</>");
        $this->line("Path: <fg=yellow>{$file->file_path}</>");
        $this->line("Size: <fg=yellow>" . $this->formatBytes($file->file_size) . "</>");
        $this->line("Type: <fg=yellow>{$file->mime_type}</>");
        $this->newLine();

        // Check if file exists in S3
        try {
            $exists = Storage::disk('s3')->exists($file->file_path);

            if ($exists) {
                $this->line('<fg=green>✓ File EXISTS in S3</>');
                $this->newLine();

                // Try to generate download URL
                $this->line('Generating signed URL...');
                $url = Storage::disk('s3')->temporaryUrl(
                    $file->file_path,
                    now()->addMinutes(5),
                    [
                        'ResponseContentDisposition' => 'attachment; filename="' . basename($file->original_filename) . '"',
                        'ResponseContentType' => $file->mime_type
                    ]
                );

                $this->line('<fg=green>✓ Signed URL generated successfully</>');
                $this->newLine();
                $this->line('🔗 Test URL (valid for 5 minutes):');
                $this->line($url);
                $this->newLine();
                $this->line('<fg=yellow>Copy this URL and test it in your browser!</>');

            } else {
                $this->error('✗ File DOES NOT EXIST in S3');
                $this->newLine();
                $this->line('Possible causes:');
                $this->line('  1. File was uploaded but path is wrong in database');
                $this->line('  2. File was deleted from S3 but record remains');
                $this->line('  3. Wrong bucket or region in config');
                $this->newLine();
                $this->line('Checking all files in S3...');
                $allFiles = Storage::disk('s3')->allFiles();
                $this->line("Total files in S3: " . count($allFiles));

                if (count($allFiles) > 0) {
                    $this->line('Sample files:');
                    foreach (array_slice($allFiles, 0, 5) as $f) {
                        $this->line("  - {$f}");
                    }
                }
            }
        } catch (\Exception $e) {
            $this->error('✗ Error checking file');
            $this->error('Error: ' . $e->getMessage());
        }
    }

    protected function checkRandomFile()
    {
        $this->info('🎲 CHECKING RANDOM FILE');
        $this->line('─────────────────────────');

        $file = FileSubmission::latest()->first();

        if (!$file) {
            $this->warn('No files found in database');
            return;
        }

        $this->line("Using file: <fg=yellow>{$file->title}</> (ID: {$file->id})");
        $this->newLine();

        $this->checkSpecificFile($file->id);
    }

    protected function formatBytes($bytes)
    {
        if ($bytes >= 1073741824) {
            return number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        } else {
            return $bytes . ' B';
        }
    }
}
