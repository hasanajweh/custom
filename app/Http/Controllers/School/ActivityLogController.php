<?php

namespace App\Http\Controllers\School;

use App\Http\Controllers\Controller;
use App\Models\School;
use App\Models\User;
use App\Services\ActivityLoggerService;
use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $schoolSlug = $request->route('school');
        $school = is_string($schoolSlug)
            ? School::where('slug', $schoolSlug)->firstOrFail()
            : $schoolSlug;

        $query = Activity::with(['causer'])
            ->where('school_id', $school->id)
            ->latest();

        if ($request->filled('user_id')) {
            $query->where('causer_id', $request->input('user_id'));
        }

        if ($request->filled('event')) {
            $query->where('event', $request->input('event'));
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->input('date_from'));
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->input('date_to'));
        }

        $activities = $query->paginate(20)->withQueryString();

        $users = User::where('school_id', $school->id)
            ->orderBy('name')
            ->get();

        $events = Activity::where('school_id', $school->id)
            ->whereNotNull('event')
            ->select('event')
            ->distinct()
            ->orderBy('event')
            ->pluck('event');

        $statistics = ActivityLoggerService::getStatistics($school);
        $topUsers = ActivityLoggerService::getMostActiveUsers($school, 5);

        return view('school.admin.activity-logs.index', [
            'school' => $school,
            'activities' => $activities,
            'users' => $users,
            'events' => $events,
            'filters' => $request->only(['user_id', 'event', 'date_from', 'date_to']),
        'statistics' => $statistics,
            'topUsers' => $topUsers,
        ]);
    }
}