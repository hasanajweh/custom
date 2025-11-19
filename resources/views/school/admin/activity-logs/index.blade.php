@extends('layouts.school')

@php
    use Illuminate\Support\Str;

    $isRtl = app()->getLocale() === 'ar';
    $stats = $statistics ?? [];
    $eventBreakdown = $stats['by_event'] ?? [];
    $channelBreakdown = $stats['by_type'] ?? [];

    $eventLabels = [
        'created' => __('messages.activity_logs.event_labels.created'),
        'updated' => __('messages.activity_logs.event_labels.updated'),
        'deleted' => __('messages.activity_logs.event_labels.deleted'),
    ];
    $metricCards = [
        [
            'value' => number_format($stats['total_activities'] ?? 0),
            'label' => __('messages.activity_logs.metrics.total'),
        ],
        [
            'value' => number_format($stats['today'] ?? 0),
            'label' => __('messages.activity_logs.metrics.today'),
        ],
        [
            'value' => number_format($stats['this_week'] ?? 0),
            'label' => __('messages.activity_logs.metrics.this_week'),
        ],
        [
            'value' => number_format($stats['this_month'] ?? 0),
            'label' => __('messages.activity_logs.metrics.this_month'),
        ],
    ];
@endphp

@section('title', __('messages.activity_logs.title') . ' - ' . __('messages.app.name'))

@section('content')
    <div class="space-y-10">
        <section class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-indigo-600 via-blue-600 to-purple-600 text-white shadow-xl">
            <div class="absolute inset-0 opacity-25">
                <div class="absolute -left-16 -top-16 h-64 w-64 rounded-full bg-white/20 blur-3xl"></div>
                <div class="absolute -right-10 bottom-0 h-56 w-56 rounded-full bg-purple-400/30 blur-3xl"></div>
            </div>

            <div class="relative px-8 py-10 sm:px-12 sm:py-12">
                <div class="flex flex-col gap-8 lg:flex-row lg:items-end lg:justify-between">
                    <div class="space-y-4 max-w-2xl">
                        <div class="inline-flex items-center rounded-full bg-white/15 px-3 py-1 text-sm font-semibold backdrop-blur">
                            <span class="flex items-center gap-2">
                                <i class="ri-history-line text-lg"></i>
                                {{ __('messages.activity_logs.premium_badge') }}
                            </span>
                        </div>

                        <div class="space-y-2">
                            <h1 class="text-3xl font-bold tracking-tight sm:text-4xl">
                                {{ __('messages.activity_logs.title') }}
                            </h1>
                            <p class="text-lg text-indigo-100">
                                {{ __('messages.activity_logs.subtitle') }}
                            </p>
                        </div>
                    </div>

                    <div class="grid w-full grid-cols-2 gap-4 sm:grid-cols-4 lg:w-auto">
                        @foreach($metricCards as $metric)
                            <div class="rounded-2xl border border-white/20 bg-white/10 p-4 text-center backdrop-blur">
                                <div class="text-2xl font-bold">{{ $metric['value'] }}</div>
                                <p class="mt-1 text-xs font-medium uppercase tracking-wider text-indigo-100">
                                    {{ $metric['label'] }}
                                </p>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>

        <section class="grid gap-6 lg:grid-cols-[2fr_1fr]">
            <div class="space-y-6">
                <div class="rounded-3xl border border-slate-100 bg-white/80 p-6 shadow-lg backdrop-blur">
                    <div class="flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
                        <div>
                            <h2 class="text-xl font-semibold text-slate-900">
                                {{ __('messages.activity_logs.filters.title') }}
                            </h2>
                            <p class="mt-1 text-sm text-slate-500">
                                {{ __('messages.activity_logs.filters.subtitle') }}
                            </p>
                        </div>
                        <form method="GET" action="{{ tenant_route('school.admin.activity-logs.index', $school) }}" class="w-full lg:w-auto">
                            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
                                <div class="space-y-1">
                                    <label for="user_id" class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                                        {{ __('messages.activity_logs.filters.user') }}
                                    </label>
                                    <select id="user_id" name="user_id" class="w-full rounded-xl border-slate-200 bg-white px-3 py-2 text-sm shadow-sm focus:border-indigo-400 focus:ring-2 focus:ring-indigo-200">
                                        <option value="">{{ __('messages.activity_logs.filters.all_users') }}</option>
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}" @selected(($filters['user_id'] ?? null) == $user->id)>
                                                {{ $user->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="space-y-1">
                                    <label for="event" class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                                        {{ __('messages.activity_logs.filters.event') }}
                                    </label>
                                    <select id="event" name="event" class="w-full rounded-xl border-slate-200 bg-white px-3 py-2 text-sm shadow-sm focus:border-indigo-400 focus:ring-2 focus:ring-indigo-200">
                                        <option value="">{{ __('messages.activity_logs.filters.all_events') }}</option>
                                        @foreach($events as $event)
                                            <option value="{{ $event }}" @selected(($filters['event'] ?? null) == $event)>
                                                {{ $eventLabels[$event] ?? Str::headline($event) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="space-y-1">
                                    <label for="date_from" class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                                        {{ __('messages.activity_logs.filters.from') }}
                                    </label>
                                    <input type="date" id="date_from" name="date_from" value="{{ $filters['date_from'] ?? '' }}" class="w-full rounded-xl border-slate-200 bg-white px-3 py-2 text-sm shadow-sm focus:border-indigo-400 focus:ring-2 focus:ring-indigo-200">
                                </div>
                                <div class="space-y-1">
                                    <label for="date_to" class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                                        {{ __('messages.activity_logs.filters.to') }}
                                    </label>
                                    <input type="date" id="date_to" name="date_to" value="{{ $filters['date_to'] ?? '' }}" class="w-full rounded-xl border-slate-200 bg-white px-3 py-2 text-sm shadow-sm focus:border-indigo-400 focus:ring-2 focus:ring-indigo-200">
                                </div>
                            </div>
                            <div class="mt-4 flex flex-col gap-3 sm:flex-row sm:items-center">
                                <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-xl bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-md transition hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-200">
                                    <i class="ri-filter-3-line text-base"></i>
                                    {{ __('messages.actions.apply_filters') }}
                                </button>
                                <a href="{{ tenant_route('school.admin.activity-logs.index', $school) }}" class="inline-flex items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-600 shadow-sm transition hover:bg-slate-50">
                                    <i class="ri-refresh-line text-base"></i>
                                    {{ __('messages.actions.reset') }}
                                </a>
                            </div>
                        </form>
                    </div>

                    @if(!empty($eventBreakdown) || !empty($channelBreakdown))
                        <div class="mt-6 grid grid-cols-1 gap-4 sm:grid-cols-2">
                            @foreach($eventBreakdown as $event => $count)
                                <div class="flex items-center justify-between rounded-2xl border border-indigo-100 bg-indigo-50/70 px-4 py-3 text-sm font-medium text-indigo-700">
                                    <span class="flex items-center gap-2">
                                        <span class="h-2 w-2 rounded-full bg-indigo-400"></span>
                                        {{ $eventLabels[$event] ?? Str::headline($event) }}
                                    </span>
                                    <span class="font-semibold">{{ number_format($count) }}</span>
                                </div>
                            @endforeach

                            @foreach($channelBreakdown as $channel => $count)
                                <div class="flex items-center justify-between rounded-2xl border border-slate-100 bg-slate-50 px-4 py-3 text-sm font-medium text-slate-600">
                                    <span class="flex items-center gap-2">
                                        <span class="h-2 w-2 rounded-full bg-slate-300"></span>
                                        {{ Str::headline($channel) }}
                                    </span>
                                    <span class="font-semibold">{{ number_format($count) }}</span>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <div class="rounded-3xl border border-slate-100 bg-white shadow-xl">
                    <div class="p-6 sm:p-8">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-xl font-semibold text-slate-900">{{ __('messages.activity_logs.timeline.title') }}</h3>
                                <p class="mt-1 text-sm text-slate-500">{{ __('messages.activity_logs.timeline.subtitle') }}</p>
                            </div>
                            <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-500">
                                {{ trans_choice('messages.activity_logs.timeline.total_count', $activities->total(), ['count' => number_format($activities->total())]) }}
                            </span>
                        </div>

                        <div class="mt-6">
                            <div class="relative {{ $isRtl ? 'pr-6 sm:pr-12' : 'pl-6 sm:pl-12' }}">
                                <div class="absolute top-2 bottom-2 {{ $isRtl ? 'right-3 sm:right-6' : 'left-3 sm:left-6' }} w-px bg-gradient-to-b from-indigo-200 via-slate-200 to-transparent"></div>

                                @forelse($activities as $activity)
                                    @php
                                        $eventKey = $activity->event ? Str::lower($activity->event) : null;
                                        $badgeColor = match($eventKey) {
                                            'created' => 'bg-emerald-100 text-emerald-700 ring-emerald-500/40',
                                            'updated' => 'bg-amber-100 text-amber-700 ring-amber-500/40',
                                            'deleted' => 'bg-rose-100 text-rose-700 ring-rose-500/40',
                                            default => 'bg-slate-100 text-slate-600 ring-slate-400/30',
                                        };
                                        $timestamp = $activity->created_at->timezone(auth()->user()->timezone ?? config('app.timezone'));
                                        $causerName = optional($activity->causer)->name ?? __('messages.activity_logs.system');
                                    @endphp

                                    <article class="relative {{ $isRtl ? 'mr-8 sm:mr-12' : 'ml-8 sm:ml-12' }} pb-10">
                                        <div class="absolute top-1 {{ $isRtl ? 'right-[-38px] sm:right-[-52px]' : 'left-[-38px] sm:left-[-52px]' }} flex h-10 w-10 items-center justify-center rounded-full border border-white bg-indigo-600 text-white shadow-lg">
                                            <i class="ri-flashlight-line"></i>
                                        </div>

                                        <div class="rounded-2xl border border-slate-100 bg-white p-5 shadow-sm transition hover:-translate-y-1 hover:shadow-lg">
                                            <div class="flex flex-wrap items-center justify-between gap-3">
                                                <div class="space-y-1">
                                                    <p class="text-sm font-semibold text-slate-900">{{ $activity->description ?? __('messages.activity_logs.timeline.no_description') }}</p>
                                                    <p class="text-xs text-slate-500">{{ $activity->subject_type ? Str::afterLast($activity->subject_type, '\\') : __('messages.activity_logs.timeline.unknown_subject') }}</p>
                                                </div>
                                                <span class="inline-flex items-center gap-2 rounded-full px-3 py-1 text-xs font-semibold ring-1 {{ $badgeColor }}">
                                                    <span class="h-2 w-2 rounded-full bg-current/40"></span>
                                                    {{ $eventKey ? ($eventLabels[$eventKey] ?? Str::headline($eventKey)) : __('messages.activity_logs.timeline.no_event') }}
                                                </span>
                                            </div>

                                            <div class="mt-4 grid gap-4 md:grid-cols-2">
                                                <div class="space-y-1">
                                                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                                                        {{ __('messages.activity_logs.timeline.performed_by') }}
                                                    </p>
                                                    <p class="text-sm font-medium text-slate-900">{{ $causerName }}</p>
                                                </div>
                                                <div class="space-y-1">
                                                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                                                        {{ __('messages.activity_logs.timeline.when') }}
                                                    </p>
                                                    <p class="text-sm font-medium text-slate-900">{{ $timestamp->format('M d, Y • h:i A') }}</p>
                                                    <p class="text-xs text-slate-500">{{ $timestamp->diffForHumans() }}</p>
                                                </div>
                                            </div>

                                            <div class="mt-4 flex flex-wrap items-center gap-4 text-xs text-slate-500">
                                                @if($activity->properties && $activity->properties->get('ip_address'))
                                                    <span class="inline-flex items-center gap-2">
                                                        <i class="ri-map-pin-user-line text-base text-indigo-400"></i>
                                                        {{ __('messages.activity_logs.timeline.from_ip') }}: {{ $activity->properties->get('ip_address') }}
                                                    </span>
                                                @endif
                                                @if($activity->properties && $activity->properties->get('user_agent'))
                                                    <span class="inline-flex items-center gap-2">
                                                        <i class="ri-device-line text-base text-indigo-400"></i>
                                                        {{ __('messages.activity_logs.timeline.device') }}:
                                                        <span class="max-w-xs truncate" title="{{ $activity->properties->get('user_agent') }}">
                                                            {{ Str::limit($activity->properties->get('user_agent'), 60) }}
                                                        </span>
                                                    </span>
                                                @endif
                                            </div>

                                            @if($activity->properties && count($activity->properties) > 0)
                                                <details class="mt-4 rounded-2xl border border-slate-100 bg-slate-50/80 p-4 text-sm text-slate-600">
                                                    <summary class="cursor-pointer font-semibold text-slate-700">
                                                        {{ __('messages.activity_logs.timeline.view_properties') }}
                                                    </summary>
                                                    <pre class="mt-3 max-h-60 overflow-auto rounded-xl bg-white p-4 text-xs text-slate-600 shadow-inner">{{ json_encode($activity->properties, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                                </details>
                                            @endif
                                        </div>
                                    </article>
                                @empty
                                    <div class="rounded-3xl border border-dashed border-slate-200 bg-slate-50/60 p-12 text-center">
                                        <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-white shadow">
                                            <i class="ri-inbox-archive-line text-2xl text-slate-300"></i>
                                        </div>
                                        <h4 class="mt-6 text-lg font-semibold text-slate-700">{{ __('messages.activity_logs.empty.title') }}</h4>
                                        <p class="mt-2 text-sm text-slate-500">{{ __('messages.activity_logs.empty.description') }}</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>

                        <div class="mt-8 border-t border-slate-100 pt-6">
                            {{ $activities->links() }}
                        </div>
                    </div>
                </div>
            </div>

            <aside class="space-y-6">
                @if(!empty($topUsers))
                    <div class="rounded-3xl border border-indigo-100 bg-gradient-to-br from-white via-indigo-50/60 to-white p-6 shadow-lg">
                        <h3 class="text-lg font-semibold text-slate-900">
                            {{ __('messages.activity_logs.top_users.title') }}
                        </h3>
                        <p class="mt-1 text-sm text-slate-500">
                            {{ __('messages.activity_logs.top_users.subtitle') }}
                        </p>
                        <div class="mt-5 space-y-4">
                            @foreach($topUsers as $position => $topUser)
                                <div class="flex items-center justify-between rounded-2xl border border-white bg-white/80 px-4 py-3 shadow-sm">
                                    <div class="flex items-center gap-3">
                                        <div class="flex h-10 w-10 items-center justify-center rounded-full bg-indigo-600 text-sm font-bold text-white">
                                            {{ $position + 1 }}
                                        </div>
                                        <div>
                                            <p class="text-sm font-semibold text-slate-900">{{ $topUser['user']->name }}</p>
                                            <p class="text-xs text-slate-500">{{ $topUser['user']->email }}</p>
                                        </div>
                                    </div>
                                    <span class="rounded-full bg-indigo-100 px-3 py-1 text-xs font-semibold text-indigo-700">
                                        {{ trans_choice('messages.activity_logs.top_users.count', $topUser['activity_count'], ['count' => number_format($topUser['activity_count'])]) }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <div class="rounded-3xl border border-slate-100 bg-white p-6 shadow-lg">
                    <h3 class="text-lg font-semibold text-slate-900">
                        {{ __('messages.activity_logs.tips.title') }}
                    </h3>
                    <p class="mt-2 text-sm text-slate-500">
                        {{ __('messages.activity_logs.tips.subtitle') }}
                    </p>
                    <ul class="mt-4 space-y-3 text-sm text-slate-600">
                        <li class="flex items-start gap-3">
                            <span class="mt-1 text-indigo-500"><i class="ri-time-line"></i></span>
                            <div>
                                <p class="font-semibold">{{ __('messages.activity_logs.tips.timeline') }}</p>
                                <p class="text-xs text-slate-500">{{ __('messages.activity_logs.tips.timeline_hint') }}</p>
                            </div>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="mt-1 text-indigo-500"><i class="ri-lock-line"></i></span>
                            <div>
                                <p class="font-semibold">{{ __('messages.activity_logs.tips.audit') }}</p>
                                <p class="text-xs text-slate-500">{{ __('messages.activity_logs.tips.audit_hint') }}</p>
                            </div>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="mt-1 text-indigo-500"><i class="ri-bar-chart-2-line"></i></span>
                            <div>
                                <p class="font-semibold">{{ __('messages.activity_logs.tips.analytics') }}</p>
                                <p class="text-xs text-slate-500">{{ __('messages.activity_logs.tips.analytics_hint') }}</p>
                            </div>
                        </li>
                    </ul>
                </div>
            </aside>
        </section>
    </div>
@endsection