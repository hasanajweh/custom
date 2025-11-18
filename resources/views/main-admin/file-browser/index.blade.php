@extends('layouts.network')

@section('title', __('messages.navigation.file_browser'))

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">@lang('messages.navigation.file_browser')</h1>
            <p class="text-gray-600">@lang('messages.network_overview')</p>
        </div>
    </div>

    <form method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4 bg-white p-4 rounded shadow">
        <div>
            <label class="text-sm text-gray-600 block">@lang('messages.search')</label>
            <input type="text" name="search" value="{{ request('search') }}" class="w-full border rounded p-2" placeholder="@lang('messages.search')">
        </div>
        <div>
            <label class="text-sm text-gray-600 block">@lang('messages.branch')</label>
            <select name="branch_id" class="w-full border rounded p-2">
                <option value="">@lang('messages.all')</option>
                @foreach($branches as $branch)
                    <option value="{{ $branch->id }}" @selected(request('branch_id')==$branch->id)>{{ $branch->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="text-sm text-gray-600 block">@lang('messages.main_admin.users.subjects_label')</label>
            <select name="subject_id" class="w-full border rounded p-2">
                <option value="">@lang('messages.all')</option>
                @foreach($subjects as $subject)
                    <option value="{{ $subject->id }}" @selected(request('subject_id')==$subject->id)>{{ $subject->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="text-sm text-gray-600 block">@lang('messages.main_admin.users.grades_label')</label>
            <select name="grade_id" class="w-full border rounded p-2">
                <option value="">@lang('messages.all')</option>
                @foreach($grades as $grade)
                    <option value="{{ $grade->id }}" @selected(request('grade_id')==$grade->id)>{{ $grade->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="text-sm text-gray-600 block">@lang('messages.users.teacher')</label>
            <select name="teacher_id" class="w-full border rounded p-2">
                <option value="">@lang('messages.all')</option>
                @foreach($teachers as $teacher)
                    <option value="{{ $teacher->id }}" @selected(request('teacher_id')==$teacher->id)>{{ $teacher->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="text-sm text-gray-600 block">@lang('messages.type')</label>
            <select name="type" class="w-full border rounded p-2">
                <option value="">@lang('messages.all')</option>
                <option value="plan" @selected(request('type')==='plan')>@lang('messages.plan')</option>
                <option value="exam" @selected(request('type')==='exam')>@lang('messages.exam')</option>
                <option value="worksheet" @selected(request('type')==='worksheet')>@lang('messages.worksheet')</option>
                <option value="summary" @selected(request('type')==='summary')>@lang('messages.summary')</option>
            </select>
        </div>
        <div class="md:col-span-5 flex justify-end">
            <button class="bg-indigo-600 text-white px-4 py-2 rounded shadow">@lang('messages.actions.filter')</button>
        </div>
    </form>

    <div class="bg-white shadow rounded overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left">@lang('messages.title')</th>
                    <th class="px-4 py-3 text-left">@lang('messages.branch')</th>
                    <th class="px-4 py-3 text-left">@lang('messages.user')</th>
                    <th class="px-4 py-3 text-left">@lang('messages.main_admin.users.subjects_label')</th>
                    <th class="px-4 py-3 text-left">@lang('messages.main_admin.users.grades_label')</th>
                    <th class="px-4 py-3 text-left">@lang('messages.submission_type')</th>
                    <th class="px-4 py-3 text-left">@lang('messages.date')</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($files as $file)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 font-semibold">{{ $file->title }}</td>
                        <td class="px-4 py-3">{{ $file->school?->name }}</td>
                        <td class="px-4 py-3">{{ $file->user?->name }}</td>
                        <td class="px-4 py-3">{{ $file->subject?->name ?? '-' }}</td>
                        <td class="px-4 py-3">{{ $file->grade?->name ?? '-' }}</td>
                        <td class="px-4 py-3 capitalize">{{ $file->submission_type }}</td>
                        <td class="px-4 py-3">{{ $file->created_at->format('Y-m-d H:i') }}</td>
                        <td class="px-4 py-3 space-x-2 text-right {{ app()->getLocale() === 'ar' ? 'space-x-reverse' : '' }}">
                            <a class="text-indigo-600" href="{{ route('main-admin.file-browser.preview', ['network' => $network->slug, 'file' => $file->id]) }}">@lang('messages.preview')</a>
                            <a class="text-gray-700" href="{{ route('main-admin.file-browser.download', ['network' => $network->slug, 'file' => $file->id]) }}">@lang('messages.download')</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td class="px-4 py-3" colspan="8">@lang('messages.no_data')</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div>
        {{ $files->links() }}
    </div>
</div>
@endsection
