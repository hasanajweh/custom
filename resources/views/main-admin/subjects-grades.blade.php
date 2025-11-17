@extends('layouts.school')

@section('title', __('messages.main_admin.subjects_grades.title'))

@section('content')
<div class="container mx-auto px-4 py-6 space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold">@lang('messages.main_admin.subjects_grades.heading')</h1>
            <p class="text-gray-600">@lang('messages.main_admin.subjects_grades.subtitle')</p>
        </div>
    </div>

    <div class="grid md:grid-cols-2 gap-6">
        <div class="bg-white rounded shadow p-4">
            <h2 class="text-lg font-semibold mb-3">@lang('messages.main_admin.subjects_grades.create_title')</h2>
            <form method="post" action="{{ route('main-admin.subjects-grades.store', $network) }}" class="space-y-3">
                @csrf
                <div>
                    <label class="text-sm text-gray-600 block">@lang('messages.main_admin.subjects_grades.type')</label>
                    <select name="type" class="w-full border rounded p-2" required>
                        <option value="subject">@lang('messages.labels.subject')</option>
                        <option value="grade">@lang('messages.labels.grade')</option>
                    </select>
                </div>
                <div>
                    <label class="text-sm text-gray-600 block">@lang('messages.labels.name')</label>
                    <input type="text" name="name" class="w-full border rounded p-2" required>
                </div>
                <div>
                    <label class="text-sm text-gray-600 block mb-2">@lang('messages.main_admin.subjects_grades.assign')</label>
                    <select name="branches[]" multiple class="w-full border rounded p-2 h-32">
                        @foreach($branches as $branch)
                            <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex justify-end">
                    <button class="bg-indigo-600 text-white px-4 py-2 rounded">@lang('messages.actions.save')</button>
                </div>
            </form>
        </div>

        <div class="bg-white rounded shadow p-4 space-y-4">
            <h2 class="text-lg font-semibold">@lang('messages.main_admin.subjects_grades.existing_subjects')</h2>
            <div class="space-y-2">
                @foreach($subjects as $subject)
                    <div class="border rounded p-3 space-y-2">
                        <form action="{{ route('main-admin.subjects-grades.update', [$network, 'subject', $subject->id]) }}" method="post" class="space-y-2">
                            @csrf
                            @method('put')
                            <div class="flex items-center justify-between">
                                <input type="text" name="name" value="{{ $subject->name }}" class="border rounded p-2 w-full mr-2">
                                <button class="bg-gray-800 text-white px-3 py-1 rounded">@lang('messages.actions.update')</button>
                            </div>
                            <select name="branches[]" multiple class="w-full border rounded p-2 h-24">
                                @foreach($branches as $branch)
                                    <option value="{{ $branch->id }}" @selected($subject->schools->pluck('id')->contains($branch->id))>{{ $branch->name }}</option>
                                @endforeach
                            </select>
                        </form>
                        <form action="{{ route('main-admin.subjects-grades.destroy', [$network, 'subject', $subject->id]) }}" method="post" onsubmit="return confirm('@lang('messages.main_admin.common.confirm_archive')')">
                            @csrf
                            @method('delete')
                            <button class="text-red-600 text-sm">@lang('messages.actions.archive')</button>
                        </form>
                    </div>
                @endforeach
            </div>

            <h2 class="text-lg font-semibold">@lang('messages.main_admin.subjects_grades.existing_grades')</h2>
            <div class="space-y-2">
                @foreach($grades as $grade)
                    <div class="border rounded p-3 space-y-2">
                        <form action="{{ route('main-admin.subjects-grades.update', [$network, 'grade', $grade->id]) }}" method="post" class="space-y-2">
                            @csrf
                            @method('put')
                            <div class="flex items-center justify-between">
                                <input type="text" name="name" value="{{ $grade->name }}" class="border rounded p-2 w-full mr-2">
                                <button class="bg-gray-800 text-white px-3 py-1 rounded">@lang('messages.actions.update')</button>
                            </div>
                            <select name="branches[]" multiple class="w-full border rounded p-2 h-24">
                                @foreach($branches as $branch)
                                    <option value="{{ $branch->id }}" @selected($grade->schools->pluck('id')->contains($branch->id))>{{ $branch->name }}</option>
                                @endforeach
                            </select>
                        </form>
                        <form action="{{ route('main-admin.subjects-grades.destroy', [$network, 'grade', $grade->id]) }}" method="post" onsubmit="return confirm('@lang('messages.main_admin.common.confirm_archive')')">
                            @csrf
                            @method('delete')
                            <button class="text-red-600 text-sm">@lang('messages.actions.archive')</button>
                        </form>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
