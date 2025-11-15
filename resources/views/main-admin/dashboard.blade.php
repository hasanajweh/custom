@extends('layouts.school')

@section('title', __('messages.dashboard'))

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">{{ $network->name }}</h1>
        <p class="text-gray-600">@lang('messages.network_overview')</p>
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
</div>
@endsection
