@extends('layouts.school')

@section('title', __('messages.dashboard'))

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">{{ $network->name }}</h1>
        <p class="text-gray-600">@lang('messages.network_overview')</p>
    </div>

    <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white shadow rounded-lg p-4 border border-gray-200">
            <p class="text-sm text-gray-500 mb-1">@lang('messages.total_branches')</p>
            <p class="text-2xl font-semibold text-gray-900">{{ $summary['branches'] }}</p>
        </div>
        <div class="bg-white shadow rounded-lg p-4 border border-gray-200">
            <p class="text-sm text-gray-500 mb-1">@lang('messages.total_files_across_branches')</p>
            <p class="text-2xl font-semibold text-gray-900">{{ $summary['files'] }}</p>
            <p class="text-xs text-gray-500">@lang('messages.last_72_hours_label'): {{ $summary['recent_files'] }}</p>
        </div>
        <div class="bg-white shadow rounded-lg p-4 border border-gray-200">
            <p class="text-sm text-gray-500 mb-1">@lang('messages.plans_across_branches')</p>
            <p class="text-2xl font-semibold text-gray-900">{{ $summary['plans'] }}</p>
        </div>
        <div class="bg-white shadow rounded-lg p-4 border border-gray-200">
            <p class="text-sm text-gray-500 mb-1">@lang('messages.team_distribution')</p>
            <p class="text-sm text-gray-700">@lang('messages.admins'): <span class="font-semibold">{{ $summary['admins'] }}</span></p>
            <p class="text-sm text-gray-700">@lang('messages.supervisors'): <span class="font-semibold">{{ $summary['supervisors'] }}</span></p>
            <p class="text-sm text-gray-700">@lang('messages.teachers'): <span class="font-semibold">{{ $summary['teachers'] }}</span></p>
        </div>
    </div>

    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-4">
        @foreach($branches as $branch)
            <div class="bg-white shadow rounded-lg p-4 border border-gray-200">
                <div class="flex items-center justify-between mb-2">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-800">{{ $branch->name }}</h2>
                        <p class="text-sm text-gray-500">{{ $branch->city ?? __('messages.city_not_set') }}</p>
                    </div>
                    <span class="px-2 py-1 text-xs rounded-full {{ $branch->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">
                        {{ $branch->is_active ? __('messages.active') : __('messages.archived') }}
                    </span>
                </div>

                <div class="space-y-2 text-sm text-gray-700">
                    <div class="flex justify-between">
                        <span>@lang('messages.files_count')</span>
                        <span class="font-semibold">{{ $branch->file_submissions_count ?? 0 }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>@lang('messages.recent_uploads_72h')</span>
                        <span class="font-semibold">{{ $branch->recent_files_count ?? 0 }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>@lang('messages.plans_label')</span>
                        <span class="font-semibold">{{ $branch->plans_count ?? 0 }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>@lang('messages.admins')</span>
                        <span class="font-semibold">{{ $branch->admins_count }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>@lang('messages.supervisors')</span>
                        <span class="font-semibold">{{ $branch->supervisors_count }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>@lang('messages.teachers')</span>
                        <span class="font-semibold">{{ $branch->teachers_count }}</span>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="bg-white shadow rounded-lg p-4 border border-gray-200 mt-6">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h2 class="text-lg font-semibold text-gray-800">@lang('messages.recent_branch_uploads')</h2>
                <p class="text-sm text-gray-500">@lang('messages.last_72_hours_label')</p>
            </div>
        </div>

        @if($recentUploads->isEmpty())
            <p class="text-sm text-gray-600">@lang('messages.no_recent_uploads')</p>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">@lang('messages.title')</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">@lang('messages.branch')</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">@lang('messages.user')</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">@lang('messages.submitted_at')</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($recentUploads as $upload)
                            <tr>
                                <td class="px-4 py-2 text-sm text-gray-900">{{ $upload->title }}</td>
                                <td class="px-4 py-2 text-sm text-gray-700">{{ $upload->school?->name }}</td>
                                <td class="px-4 py-2 text-sm text-gray-700">{{ $upload->user?->name }}</td>
                                <td class="px-4 py-2 text-sm text-gray-500">{{ $upload->created_at->format('Y-m-d H:i') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection
