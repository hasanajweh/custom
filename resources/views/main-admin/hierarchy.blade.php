@extends('layouts.school')

@section('title', __('Network hierarchy'))

@section('content')
<div class="container mx-auto px-4 py-6 space-y-4">
    <h1 class="text-2xl font-bold">{{ $network->name }} @lang('Hierarchy')</h1>
    <div class="grid md:grid-cols-2 gap-4">
        @foreach($branches as $branch)
            <div class="bg-white rounded shadow p-4">
                <div class="flex items-center justify-between mb-2">
                    <div>
                        <h2 class="text-lg font-semibold">{{ $branch->name }}</h2>
                        <p class="text-sm text-gray-500">{{ $branch->city }}</p>
                    </div>
                    <span class="px-2 py-1 text-xs rounded {{ $branch->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-200 text-gray-700' }}">{{ $branch->is_active ? __('Active') : __('Archived') }}</span>
                </div>
                <div class="grid grid-cols-2 gap-2 text-sm">
                    <div class="p-2 bg-gray-50 rounded">@lang('Admins'): <strong>{{ $branch->admins_count }}</strong></div>
                    <div class="p-2 bg-gray-50 rounded">@lang('Supervisors'): <strong>{{ $branch->supervisors_count }}</strong></div>
                    <div class="p-2 bg-gray-50 rounded">@lang('Teachers'): <strong>{{ $branch->teachers_count }}</strong></div>
                    <div class="p-2 bg-gray-50 rounded">@lang('Subjects'): <strong>{{ $branch->subjects_count }}</strong></div>
                    <div class="p-2 bg-gray-50 rounded">@lang('Grades'): <strong>{{ $branch->grades_count }}</strong></div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
