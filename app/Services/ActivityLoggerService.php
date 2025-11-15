<?php

namespace App\Services;

use App\Models\ActivityLog;
use App\Models\School;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;

class ActivityLoggerService
{
    /**
     * Log custom activity using the unified activity_log table.
     */
    public static function log(
        string $description,
        ?School $school = null,
        ?User $user = null,
        string $logName = 'default',
        array $properties = [],
        ?string $event = null,
        ?Model $subject = null
    ): void {
        $causer = $user ?? auth()->user();
        $request = App::has('request') ? request() : null;

        $defaultProperties = array_filter([
            'school_id' => $school?->id,
            'school_name' => $school?->name,
        ], static fn ($value) => $value !== null);

        if ($request) {
            $defaultProperties = array_merge($defaultProperties, array_filter([
                'ip_address' => $request->ip(),
                'user_agent' => method_exists($request, 'userAgent') ? $request->userAgent() : null,
                'url' => method_exists($request, 'fullUrl') ? $request->fullUrl() : null,
            ], static fn ($value) => $value !== null));
        }

        $activity = activity($logName);

        if ($causer) {
            $activity->causedBy($causer);
        }

        if ($subject) {
            $activity->performedOn($subject);
        }

        if ($event) {
            $activity->event($event);
        }

        $mergedProperties = array_merge($defaultProperties, $properties);
        $filteredProperties = array_filter(
            $mergedProperties,
            static fn ($value) => $value !== null
        );

        $activityModel = $activity
            ->withProperties($filteredProperties)
            ->log($description);

        if ($school && $activityModel->school_id !== $school->id) {
            $activityModel->school_id = $school->id;
            $activityModel->save();
        }
    }

    /**
     * Get activities for a specific school.
     */
    public static function getSchoolActivities(School $school, int $perPage = 50)
    {
        return ActivityLog::where('school_id', $school->id)
            ->with(['causer', 'subject'])
            ->latest()
            ->paginate($perPage);
    }

    /**
     * Get all activities with optional filters.
     */
    public static function getAllActivities(array $filters = [], int $perPage = 50)
    {
        $query = ActivityLog::with(['causer', 'subject']);

        if (!empty($filters['school_id'])) {
            $query->where('school_id', $filters['school_id']);
        }

        if (!empty($filters['date_from'])) {
            $query->whereDate('created_at', '>=', $filters['date_from']);
        }

        if (!empty($filters['date_to'])) {
            $query->whereDate('created_at', '<=', $filters['date_to']);
        }

        if (!empty($filters['log_name'])) {
            $query->where('log_name', $filters['log_name']);
        }

        if (!empty($filters['user_id'])) {
            $query->where('causer_id', $filters['user_id']);
        }

        if (!empty($filters['event'])) {
            $query->where('event', $filters['event']);
        }

        if (!empty($filters['search'])) {
            $query->where(function ($inner) use ($filters) {
                $inner->where('description', 'like', '%' . $filters['search'] . '%')
                    ->orWhere('log_name', 'like', '%' . $filters['search'] . '%');
            });
        }

        return $query->latest()->paginate($perPage);
    }

    /**
     * Get activity statistics.
     */
    public static function getStatistics(?School $school = null): array
    {
        $query = ActivityLog::query();

        if ($school) {
            $query->where('school_id', $school->id);
        }

        return [
            'total_activities' => $query->count(),
            'today' => (clone $query)->whereDate('created_at', today())->count(),
            'this_week' => (clone $query)->whereBetween('created_at', [
                now()->startOfWeek(),
                now()->endOfWeek(),
            ])->count(),
            'this_month' => (clone $query)->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count(),
            'by_type' => (clone $query)->select('log_name', DB::raw('count(*) as count'))
                ->groupBy('log_name')
                ->pluck('count', 'log_name')
                ->toArray(),
            'by_event' => (clone $query)->select('event', DB::raw('count(*) as count'))
                ->whereNotNull('event')
                ->groupBy('event')
                ->pluck('count', 'event')
                ->toArray(),
        ];
    }

    /**
     * Get most active users.
     */
    public static function getMostActiveUsers(?School $school = null, int $limit = 10): array
    {
        $query = ActivityLog::query()
            ->select('causer_id', DB::raw('count(*) as activity_count'))
            ->whereNotNull('causer_id')
            ->groupBy('causer_id')
            ->orderByDesc('activity_count')
            ->limit($limit);

        if ($school) {
            $query->where('school_id', $school->id);
        }

        return $query->get()
            ->map(function ($activity) {
                $user = User::find($activity->causer_id);

                return [
                    'user' => $user,
                    'activity_count' => $activity->activity_count,
                ];
            })
            ->filter(static fn ($item) => $item['user'] !== null)
            ->toArray();
    }

    /**
     * Get recent critical activities.
     */
    public static function getCriticalActivities(?School $school = null, int $limit = 20): array
    {
        $criticalEvents = ['deleted', 'updated', 'created'];

        $query = ActivityLog::whereIn('event', $criticalEvents)
            ->with(['causer', 'subject'])
            ->latest()
            ->limit($limit);

        if ($school) {
            $query->where('school_id', $school->id);
        }

        return $query->get()->toArray();
    }

    /**
     * Clean old activities according to retention policy.
     */
    public static function cleanOldActivities(int $daysToKeep = 90): int
    {
        return ActivityLog::where('created_at', '<', now()->subDays($daysToKeep))
            ->delete();
    }
}