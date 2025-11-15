<?php
// app/Console/Commands/DiagnoseFileSubmissions.php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\FileSubmission;
use App\Models\Subject;
use App\Models\Grade;
use App\Models\School;

class DiagnoseFileSubmissions extends Command
{
    protected $signature = 'diagnose:files {school_slug}';
    protected $description = 'Diagnose file submission issues with subjects and grades';

    public function handle()
    {
        $schoolSlug = $this->argument('school_slug');
        $school = School::where('slug', $schoolSlug)->first();

        if (!$school) {
            $this->error("School with slug '{$schoolSlug}' not found!");
            return;
        }

        $this->info("Diagnosing school: {$school->name} (ID: {$school->id})");
        $this->line("=" . str_repeat("=", 60));

        // Check subjects and grades
        $subjects = Subject::where('school_id', $school->id)->get();
        $grades = Grade::where('school_id', $school->id)->get();

        $this->info("\n📚 Subjects in this school:");
        if ($subjects->isEmpty()) {
            $this->warn("  ⚠️ No subjects found! This is a problem!");
        } else {
            foreach ($subjects as $subject) {
                $this->line("  - ID: {$subject->id}, Name: {$subject->name}");
            }
        }

        $this->info("\n🎓 Grades in this school:");
        if ($grades->isEmpty()) {
            $this->warn("  ⚠️ No grades found! This is a problem!");
        } else {
            foreach ($grades as $grade) {
                $this->line("  - ID: {$grade->id}, Name: {$grade->name}");
            }
        }

        // Check academic files
        $this->info("\n📄 Academic Files Analysis:");
        $academicFiles = FileSubmission::where('school_id', $school->id)
            ->whereIn('submission_type', ['exam', 'worksheet', 'summary'])
            ->get();

        $totalAcademic = $academicFiles->count();
        $withSubject = $academicFiles->whereNotNull('subject_id')->count();
        $withGrade = $academicFiles->whereNotNull('grade_id')->count();
        $withBoth = $academicFiles->filter(function ($file) {
            return $file->subject_id && $file->grade_id;
        })->count();

        $this->line("  Total academic files: {$totalAcademic}");
        $this->line("  Files with subject_id: {$withSubject}");
        $this->line("  Files with grade_id: {$withGrade}");
        $this->line("  Files with both: {$withBoth}");

        if ($totalAcademic > 0 && $withBoth < $totalAcademic) {
            $this->warn("  ⚠️ Some academic files are missing subject or grade!");
        }

        // Check for orphaned references
        $this->info("\n🔍 Checking for orphaned references:");
        $orphanedSubjects = FileSubmission::where('school_id', $school->id)
            ->whereNotNull('subject_id')
            ->whereNotIn('subject_id', $subjects->pluck('id'))
            ->count();

        $orphanedGrades = FileSubmission::where('school_id', $school->id)
            ->whereNotNull('grade_id')
            ->whereNotIn('grade_id', $grades->pluck('id'))
            ->count();

        if ($orphanedSubjects > 0) {
            $this->error("  ❌ Found {$orphanedSubjects} files with non-existent subject_id!");
        }

        if ($orphanedGrades > 0) {
            $this->error("  ❌ Found {$orphanedGrades} files with non-existent grade_id!");
        }

        if ($orphanedSubjects == 0 && $orphanedGrades == 0) {
            $this->info("  ✅ No orphaned references found");
        }

        // Sample recent academic files
        $this->info("\n📋 Recent Academic Files (Last 5):");
        $recentFiles = FileSubmission::where('school_id', $school->id)
            ->whereIn('submission_type', ['exam', 'worksheet', 'summary'])
            ->with(['subject', 'grade'])
            ->latest()
            ->take(5)
            ->get();

        foreach ($recentFiles as $file) {
            $this->line("\n  File: {$file->title}");
            $this->line("    Type: {$file->submission_type}");
            $this->line("    Subject ID: " . ($file->subject_id ?: 'NULL'));
            $this->line("    Subject Name: " . ($file->subject ? $file->subject->name : 'NOT LOADED'));
            $this->line("    Grade ID: " . ($file->grade_id ?: 'NULL'));
            $this->line("    Grade Name: " . ($file->grade ? $file->grade->name : 'NOT LOADED'));
        }

        // Recommendations
        $this->info("\n💡 Recommendations:");
        if ($subjects->isEmpty() || $grades->isEmpty()) {
            $this->warn("  1. Create subjects and grades for this school first!");
        }

        if ($totalAcademic > 0 && $withBoth < $totalAcademic) {
            $this->warn("  2. Fix existing files without subject/grade assignments");
            $this->line("     Run: php artisan tinker");
            $this->line("     Then: App\Models\FileSubmission::where('school_id', {$school->id})");
            $this->line("           ->whereIn('submission_type', ['exam', 'worksheet', 'summary'])");
            $this->line("           ->whereNull('subject_id')");
            $this->line("           ->update(['subject_id' => <subject_id>, 'grade_id' => <grade_id>]);");
        }

        if ($orphanedSubjects > 0 || $orphanedGrades > 0) {
            $this->warn("  3. Clean up orphaned references or recreate missing subjects/grades");
        }

        $this->info("\n✅ Diagnosis complete!");
    }
}
