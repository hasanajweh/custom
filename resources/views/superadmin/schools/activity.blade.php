{{-- resources/views/superadmin/schools/activity.blade.php --}}
@extends('layouts.superadmin')

@section('page-title', 'Activity Log')

@section('content')
    <div class="space-y-6">
        <!-- Page Header -->
        <div>
            <h1 class="text-3xl font-bold text-white">Activity Log</h1>
            <p class="mt-2 text-gray-400">Activity history for <span class="text-indigo-400">{{ $school->name }}</span></p>
        </div>

        <!-- Activity Timeline -->
        <div class="glass rounded-lg p-6">
            <ul role="list" class="space-y-6">
                @forelse ($activities as $activity)
                    <li class="relative flex gap-x-4">
                        {{-- Timeline line --}}
                        @if (!$loop->last)
                            <div class="absolute left-3 top-8 -bottom-2 w-0.5 bg-gray-700"></div>
                        @endif

                        {{-- Icon for event type --}}
                        <div class="relative flex h-6 w-6 flex-none items-center justify-center rounded-full
                        @if(Str::contains($activity->description, 'created'))
                            bg-green-900/30 ring-1 ring-green-600
                        @elseif(Str::contains($activity->description, 'updated'))
                            bg-yellow-900/30 ring-1 ring-yellow-600
                        @elseif(Str::contains($activity->description, 'deleted'))
                            bg-red-900/30 ring-1 ring-red-600
                        @else
                            bg-gray-800 ring-1 ring-gray-700
                        @endif">
                            @if(Str::contains($activity->description, 'created'))
                                <svg class="h-3 w-3 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                            @elseif(Str::contains($activity->description, 'updated'))
                                <svg class="h-3 w-3 text-yellow-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            @elseif(Str::contains($activity->description, 'deleted'))
                                <svg class="h-3 w-3 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            @else
                                <div class="h-1.5 w-1.5 bg-gray-400 rounded-full"></div>
                            @endif
                        </div>

                        {{-- Activity Details --}}
                        <div class="flex-auto">
                            <div class="flex items-start justify-between">
                                <div>
                                    <p class="text-sm text-gray-300">
                                        <span class="font-semibold text-white">{{ $activity->causer->name ?? 'System' }}</span>
                                        {{ $activity->description }}
                                    </p>
                                    @if($activity->subject)
                                        <p class="text-xs text-gray-500 mt-1">
                                            {{ Str::afterLast($activity->subject_type, '\\') }}:
                                            <span class="text-gray-400">{{ $activity->subject->name ?? $activity->subject->title ?? 'N/A' }}</span>
                                        </p>
                                    @endif

                                    @if($activity->properties && count($activity->properties) > 0)
                                        <details class="mt-2">
                                            <summary class="text-xs text-indigo-400 cursor-pointer hover:text-indigo-300">View changes</summary>
                                            <div class="mt-2 text-xs bg-gray-800 rounded p-2 text-gray-400">
                                                <pre>{{ json_encode($activity->properties, JSON_PRETTY_PRINT) }}</pre>
                                            </div>
                                        </details>
                                    @endif
                                </div>
                                <time datetime="{{ $activity->created_at->toIso8601String() }}"
                                      class="flex-none text-xs text-gray-500">
                                    {{ $activity->created_at->diffForHumans() }}
                                </time>
                            </div>
                        </div>
                    </li>
                @empty
                    <li class="text-center text-gray-400 py-8">
                        <svg class="mx-auto h-12 w-12 text-gray-600 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p>No activity recorded for this school yet.</p>
                    </li>
                @endforelse
            </ul>

            @if($activities->hasPages())
                <div class="mt-6 pt-6 border-t border-gray-700">
                    {{ $activities->links() }}
                </div>
            @endif
        </div>

        <!-- Back Button -->
        <div class="flex justify-start">
            <a href="{{ route('superadmin.schools.index') }}"
               class="px-4 py-2 bg-gray-700 hover:bg-gray-600 text-white rounded-lg transition">
                ← Back to Schools
            </a>
        </div>
    </div>
@endsection
