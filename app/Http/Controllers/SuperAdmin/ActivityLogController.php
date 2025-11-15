<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Helpers\QueryHelper;
use App\Models\School;
use App\Models\User;
use App\Services\ActivityLoggerService;
use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;

class ActivityLogController extends Controller
{
    /**
     * Display all activities across all schools
     */
    public function index(Request $request)
    {
        // Get all schools for filter dropdown
        $schools = School::orderBy('name')->get();

        // Build filters
        $filters = [
            'school_id' => $request->input('school_id'),
            'date_from' => $request->input('date_from'),
            'date_to' => $request->input('date_to'),
            'log_name' => $request->input('log_name'),
            'event' => $request->input('event'),
            'search' => $request->input('search'),
        ];

        // Get activities with filters
        $activities = ActivityLoggerService::getAllActivities($filters, 50);

        // Get statistics
        $selectedSchool = $filters['school_id']
            ? School::find($filters['school_id'])
            : null;

        $stats = ActivityLoggerService::getStatistics($selectedSchool);

        // Get most active users
        $mostActiveUsers = ActivityLoggerService::getMostActiveUsers($selectedSchool, 10);

        // Get available log types for filter
        $logTypes = Activity::select('log_name')
            ->distinct()
            ->whereNotNull('log_name')
            ->pluck('log_name');

        // Get available events for filter
        $events = Activity::select('event')
            ->distinct()
            ->whereNotNull('event')
            ->pluck('event');

        return view('superadmin.activity-logs.index', compact(
            'activities',
            'schools',
            'stats',
            'mostActiveUsers',
            'logTypes',
            'events',
            'filters'
        ));
    }

    /**
     * Display activities for a specific school
     */
    public function school(School $school, Request $request)
    {
        $filters = [
            'school_id' => $school->id,
            'date_from' => $request->input('date_from'),
            'date_to' => $request->input('date_to'),
            'log_name' => $request->input('log_name'),
            'event' => $request->input('event'),
            'search' => $request->input('search'),
        ];

        $activities = ActivityLoggerService::getAllActivities($filters, 50);
        $stats = ActivityLoggerService::getStatistics($school);
        $mostActiveUsers = ActivityLoggerService::getMostActiveUsers($school, 10);

        $logTypes = Activity::where('school_id', $school->id)
            ->select('log_name')
            ->distinct()
            ->whereNotNull('log_name')
            ->pluck('log_name');

        $events = Activity::where('school_id', $school->id)
            ->select('event')
            ->distinct()
            ->whereNotNull('event')
            ->pluck('event');

        return view('superadmin.activity-logs.school', compact(
            'school',
            'activities',
            'stats',
            'mostActiveUsers',
            'logTypes',
            'events',
            'filters'
        ));
    }

    /**
     * Show detailed view of a single activity
     */
    public function show(Activity $activity)
    {
        $activity->load(['causer', 'subject']);

        return view('superadmin.activity-logs.show', compact('activity'));
    }

    /**
     * Export activities to CSV
     */
    public function export(Request $request)
    {
        $filters = [
            'school_id' => $request->input('school_id'),
            'date_from' => $request->input('date_from'),
            'date_to' => $request->input('date_to'),
            'log_name' => $request->input('log_name'),
            'event' => $request->input('event'),
            'search' => $request->input('search'),
        ];

        $activities = ActivityLoggerService::getAllActivities($filters, 10000);

        $filename = 'activity-logs-' . now()->format('Y-m-d-His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function() use ($activities) {
            $file = fopen('php://output', 'w');

            // Headers
            fputcsv($file, [
                'ID',
                'School',
                'User',
                'Description',
                'Event',
                'Log Type',
                'IP Address',
                'Created At'
            ]);

            // Data
            foreach ($activities as $activity) {
                fputcsv($file, [
                    $activity->id,
                    $activity->properties['school_name'] ?? 'N/A',
                    $activity->causer?->name ?? 'System',
                    $activity->description,
                    $activity->event ?? 'N/A',
                    $activity->log_name,
                    $activity->properties['ip_address'] ?? 'N/A',
                    $activity->created_at->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Clean old activities (maintenance)
     */
    public function cleanup(Request $request)
    {
        $request->validate([
            'days' => 'required|integer|min:30|max:365'
        ]);

        $deletedCount = ActivityLoggerService::cleanOldActivities($request->days);

        return back()->with('success', "Cleaned up {$deletedCount} old activity records.");
    }
}
