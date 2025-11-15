<?php
// app/Jobs/ProcessFileUpload.php
// FIXED VERSION - Works with S3

namespace App\Jobs;

use App\Models\FileSubmission;
use App\Models\User;
use App\Models\School;
use App\Notifications\NewFileUploaded;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ProcessFileUpload implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    public $timeout = 300;

    /**
     * Create a new job instance.
     */
    public function __construct(
        private FileSubmission $submission,
        private int $adminId
    ) {
        $this->onQueue('default');
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            Log::info('Processing file upload', [
                'file_id' => $this->submission->id,
                'current_path' => $this->submission->file_path,
                'status' => $this->submission->status,
            ]);

            // Check if file needs to be moved from temp
            if (str_starts_with($this->submission->file_path, 'temp/')) {
                $this->moveFromTempToS3();
            } else {
                // File might already be in final location
                Log::info('File already in final location', [
                    'path' => $this->submission->file_path
                ]);
            }

            // Update submission status
            $this->submission->update(['status' => 'completed']);

            // Update school storage usage
            $school = School::find($this->submission->school_id);
            if ($school) {
                $school->increment('storage_used', $this->submission->file_size);
            }

            // Notify admin
            $admin = User::find($this->adminId);
            if ($admin) {
                try {
                    $admin->notify(new NewFileUploaded($this->submission));
                } catch (\Exception $e) {
                    Log::error('Failed to send notification', [
                        'error' => $e->getMessage()
                    ]);
                }
            }

            Log::info('File processing completed', [
                'file_id' => $this->submission->id,
                'final_path' => $this->submission->file_path,
            ]);

        } catch (\Exception $e) {
            Log::error('File processing failed', [
                'file_id' => $this->submission->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            $this->submission->update(['status' => 'failed']);
            throw $e;
        }
    }

    /**
     * Move file from temp to final S3 location
     */
    private function moveFromTempToS3(): void
    {
        try {
            $tempPath = $this->submission->file_path;

            // Check which disk has the file
            $sourceFile = null;
            $sourceDisk = null;

            // Check local first (for temp files)
            if (Storage::disk('local')->exists($tempPath)) {
                $sourceFile = Storage::disk('local')->get($tempPath);
                $sourceDisk = 'local';
                Log::info('Found file in local storage', ['path' => $tempPath]);
            }
            // Then check S3
            elseif (Storage::disk('s3')->exists($tempPath)) {
                $sourceFile = Storage::disk('s3')->get($tempPath);
                $sourceDisk = 's3';
                Log::info('Found file in S3 storage', ['path' => $tempPath]);
            }
            else {
                throw new \Exception("File not found in any storage: {$tempPath}");
            }

            // Determine final path
            $finalPath = $this->determineFinalPath();

            // Store in S3 (final location)
            if ($sourceDisk === 'local') {
                $stream = Storage::disk('local')->readStream($tempPath);

                if ($stream === false) {
                    throw new \RuntimeException("Unable to read stream for {$tempPath}");
                }

                try {
                    $writeResult = Storage::disk('s3')->writeStream($finalPath, $stream);
                } finally {
                    if (is_resource($stream)) {
                        fclose($stream);
                    }
                }

                if ($writeResult === false) {
                    throw new \RuntimeException("Failed to upload stream to {$finalPath}");
                }
            } else {
                $copied = Storage::disk('s3')->copy($tempPath, $finalPath);

                if ($copied === false) {
                    throw new \RuntimeException("Failed to copy {$tempPath} to {$finalPath}");
                }
            }
            Log::info('File moved to S3', [
                'from' => $tempPath,
                'to' => $finalPath,
                'source_disk' => $sourceDisk
            ]);

            // Delete temp file
            if ($sourceDisk === 'local') {
                Storage::disk('local')->delete($tempPath);
            } elseif ($sourceDisk === 's3' && $tempPath !== $finalPath) {
                Storage::disk('s3')->delete($tempPath);
            }

            // Update submission with new path
            $this->submission->update(['file_path' => $finalPath]);

        } catch (\Exception $e) {
            Log::error('Failed to move file to S3', [
                'file_id' => $this->submission->id,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Determine final storage path
     */
    private function determineFinalPath(): string
    {
        $school = School::find($this->submission->school_id);
        $schoolSlug = $school ? $school->slug : 'default';

        $directory = match($this->submission->submission_type) {
            'exam' => 'exams',
            'worksheet' => 'worksheets',
            'summary' => 'summaries',
            'daily_plan' => 'plans/daily',
            'weekly_plan' => 'plans/weekly',
            'monthly_plan' => 'plans/monthly',
            'supervisor_upload' => 'supervisor',
            default => 'uploads',
        };

        $filename = $this->submission->id . '_' . $this->submission->original_filename;

        return "schools/{$schoolSlug}/{$directory}/{$filename}";
    }

    /**
     * Handle job failure
     */
    public function failed(\Throwable $exception): void
    {
        Log::error('File upload job failed permanently', [
            'file_id' => $this->submission->id,
            'error' => $exception->getMessage(),
        ]);

        $this->submission->update(['status' => 'failed']);
    }
}
