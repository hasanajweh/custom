<?php
// app/Console/Commands/FixFileSubmissions.php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\FileSubmission;
use App\Models\Subject;
use App\Models\Grade;
use App\Models\School;

class FixFileSubmissions extends Command
{
    protected $signature = 'fix:files {school_slug} {--dry-run : Show what would be changed without making changes}';
    protected $description = 'Fix file submissions with missing subjects and grades';

    public function handle()
    {
        $schoolSlug = $this->argument('school_slug');
        $dryRun = $this->option('dry-run');

        $school = School::where('slug', $schoolSlug)->first();

        if (!$school) {
            $this->error("School with slug '{$schoolSlug}' not found!");
            return;
        }

        if ($dryRun) {
            $this->info("🔍 DRY RUN MODE - No changes will be made");
        }

        $this->info("Processing school: {$school->name}");

        // Get or create default subject and grade
        $defaultSubject = Subject::firstOrCreate(
            ['school_id' => $school->id, 'name' => 'General'],
            ['name' => 'General']
        );

        $defaultGrade = Grade::firstOrCreate(
            ['school_id' => $school->id, 'name' => 'All Grades'],
            ['name' => 'All Grades']
        );

        $this->info("Default Subject: {$defaultSubject->name} (ID: {$defaultSubject->id})");
        $this->info("Default Grade: {$defaultGrade->name} (ID: {$defaultGrade->id})");

        // Find academic files without subject or grade
        $problematicFiles = FileSubmission::where('school_id', $school->id)
            ->whereIn('submission_type', ['exam', 'worksheet', 'summary'])
            ->where(function($query) {
                $query->whereNull('subject_id')
                    ->orWhereNull('grade_id');
            })
            ->get();

        $this->info("\nFound {$problematicFiles->count()} academic files with missing subject or grade");

        foreach ($problematicFiles as $file) {
            $changes = [];

            if (!$file->subject_id) {
                $changes['subject_id'] = $defaultSubject->id;
                $this->line("  📚 File '{$file->title}' - Setting subject to '{$defaultSubject->name}'");
            }

            if (!$file->grade_id) {
                $changes['grade_id'] = $defaultGrade->id;
                $this->line("  🎓 File '{$file->title}' - Setting grade to '{$defaultGrade->name}'");
            }

            if (!$dryRun && !empty($changes)) {
                $file->update($changes);
            }
        }

        // Check for orphaned references
        $this->info("\n🔍 Checking for orphaned references...");

        $validSubjectIds = Subject::where('school_id', $school->id)->pluck('id');
        $validGradeIds = Grade::where('school_id', $school->id)->pluck('id');

        $orphanedSubjectFiles = FileSubmission::where('school_id', $school->id)
            ->whereNotNull('subject_id')
            ->whereNotIn('subject_id', $validSubjectIds)
            ->get();

        $orphanedGradeFiles = FileSubmission::where('school_id', $school->id)
            ->whereNotNull('grade_id')
            ->whereNotIn('grade_id', $validGradeIds)
            ->get();

        if ($orphanedSubjectFiles->count() > 0) {
            $this->warn("Found {$orphanedSubjectFiles->count()} files with invalid subject_id");
            foreach ($orphanedSubjectFiles as $file) {
                $this->line("  Fixing '{$file->title}' - Setting subject to default");
                if (!$dryRun) {
                    $file->update(['subject_id' => $defaultSubject->id]);
                }
            }
        }

        if ($orphanedGradeFiles->count() > 0) {
            $this->warn("Found {$orphanedGradeFiles->count()} files with invalid grade_id");
            foreach ($orphanedGradeFiles as $file) {
                $this->line("  Fixing '{$file->title}' - Setting grade to default");
                if (!$dryRun) {
                    $file->update(['grade_id' => $defaultGrade->id]);
                }
            }
        }

        if ($dryRun) {
            $this->info("\n✅ DRY RUN complete - run without --dry-run to apply changes");
        } else {
            $this->info("\n✅ All files have been fixed!");
        }
    }
}
