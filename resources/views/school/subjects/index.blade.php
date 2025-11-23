@extends('layouts.school')
@section('title', __('messages.subjects.title') . ' - ' . __('messages.app.name'))

@section('content')
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Add Subject Form -->
    <div class="lg:col-span-1">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-visible">
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                    <svg class="h-5 w-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    {{ __('messages.subjects.add_new_subject') }}
                </h3>
                <p class="text-sm text-gray-600 mt-1">{{ __('messages.subjects.create_subjects_for_grades') }}</p>
            </div>
            <div class="p-6">
                <form method="POST" action="{{ tenant_route('school.admin.subjects.store', $school) }}" class="space-y-6">
                    @csrf
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('messages.subjects.subject_name') }}
                        </label>
                        <input type="text" id="name" name="name" required
                            placeholder="{{ __('messages.subjects.subject_placeholder') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200">
                        @error('name')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <button type="submit"
                        class="w-full bg-gradient-to-r from-blue-600 to-indigo-700 text-white py-3 px-4 rounded-lg hover:from-blue-700 hover:to-indigo-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-200 font-medium">
                        {{ __('messages.subjects.create_subject') }}
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Subjects List -->
    <div class="lg:col-span-2">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-visible">
            <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                    <svg class="h-5 w-5 text-gray-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                    {{ __('messages.subjects.current_subjects') }}
                </h3>
                <div class="flex items-center space-x-2">
                    <span class="bg-blue-100 text-blue-800 text-xs font-medium px-3 py-1 rounded-full">
                        {{ trans_choice('messages.subjects.total_subjects', $subjects->count(), ['count' => $subjects->count()]) }}
                    </span>
                    <span class="bg-gray-100 text-gray-700 text-xs font-medium px-3 py-1 rounded-full">
                        {{ trans_choice('messages.archived.total', $archivedSubjects->count(), ['count' => $archivedSubjects->count()]) }}
                    </span>
                </div>
            </div>

            <div class="p-6">
                @if($subjects->count() > 0)
                    <div class="grid gap-4">
                        @foreach ($subjects as $subject)
                            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg border border-gray-200 hover:bg-gray-100 transition duration-200"
                                x-data="{ confirmOpen: false, countdown: 3, interval: null }">
                                <div class="flex items-center space-x-3">
                                    <div class="h-10 w-10 bg-gradient-to-br from-blue-100 to-indigo-200 rounded-lg flex items-center justify-center">
                                        <svg class="h-5 w-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 6h16M4 12h16M4 18h16"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h4 class="font-medium text-gray-900">{{ $subject->name }}</h4>
                                        <p class="text-sm text-gray-500">
                                            {{ __('messages.subjects.created') }} {{ $subject->created_at->diffForHumans() }}
                                        </p>
                                    </div>
                                </div>

                                <div class="relative z-50" :class="{'text-right': '{{ app()->getLocale() == 'ar' }}'}">
                                    <!-- Archive Trigger -->
                                    <button type="button"
                                        @click="
                                            confirmOpen = true;
                                            countdown = 3;
                                            clearInterval(interval);
                                            interval = setInterval(() => {
                                                if(countdown > 0) countdown--;
                                                if(countdown === 0) clearInterval(interval);
                                            }, 1000);
                                        "
                                        class="text-amber-600 hover:text-amber-800 p-2 rounded-lg hover:bg-amber-50 transition duration-200"
                                        title="{{ __('messages.actions.archive') ?? 'Archive' }}">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M20 13V7a2 2 0 00-2-2h-3l-1-1h-4l-1 1H6a2 2 0 00-2 2v6m16 0v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2m16 0H4"></path>
                                        </svg>
                                    </button>

                                    <!-- Popup -->
                                    <div
                                        x-show="confirmOpen"
                                        x-transition:enter="transition ease-out duration-200"
                                        x-transition:enter-start="opacity-0 scale-95"
                                        x-transition:enter-end="opacity-100 scale-100"
                                        x-transition:leave="transition ease-in duration-150"
                                        x-transition:leave-start="opacity-100 scale-100"
                                        x-transition:leave-end="opacity-0 scale-95"
                                        class="absolute {{ app()->getLocale() == 'ar' ? 'left-0' : 'right-0' }} mt-2 w-72 bg-white border border-gray-100 rounded-xl shadow-2xl p-4 z-[9999]"
                                    >
                                        <h4 class="font-semibold text-gray-900 mb-1">
                                            {{ __('messages.confirmations.generic_title') }}
                                        </h4>
                                        <p class="text-sm text-gray-600 mb-3">
                                            {{ __('messages.confirmations.generic_body') }}
                                        </p>
                                        <p class="text-xs text-gray-500 mb-3"
                                            x-text="countdown > 0 ? '{{ __('messages.confirmations.delete_countdown_prefix') }}' + countdown : '{{ __('messages.confirmations.delete_countdown_ready') }}'"></p>
                                        <div class="flex justify-end space-x-2">
                                            <button @click="confirmOpen = false"
                                                class="px-3 py-1.5 text-sm rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-100">
                                                {{ __('messages.actions.cancel') }}
                                            </button>
                                            <form method="POST"
                                                action="{{ tenant_route('school.admin.subjects.archive', $school, ['subject' => $subject->id]) }}">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit"
                                                    :disabled="countdown > 0"
                                                    class="px-3 py-1.5 text-sm rounded-lg text-white transition duration-200"
                                                    :class="countdown > 0 ? 'bg-amber-300 cursor-not-allowed' : 'bg-amber-600 hover:bg-amber-700'">
                                                    {{ __('messages.actions.archive') ?? __('messages.actions.delete') }}
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12 text-gray-500">
                        {{ __('messages.subjects.no_subjects_message') }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Archived Subjects -->
    <div class="lg:col-span-3">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-visible">
            <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                <div class="flex items-center space-x-2">
                    <svg class="h-5 w-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-6a2 2 0 012-2h6" />
                    </svg>
                    <h3 class="text-lg font-semibold text-gray-900">{{ __('messages.subjects.archived_subjects') }}</h3>
                </div>
                <span class="bg-gray-100 text-gray-700 text-xs font-medium px-3 py-1 rounded-full">
                    {{ trans_choice('messages.archived.total', $archivedSubjects->count(), ['count' => $archivedSubjects->count()]) }}
                </span>
            </div>
            <div class="p-6">
                @if($archivedSubjects->count() > 0)
                    <div class="grid gap-4">
                        @foreach ($archivedSubjects as $subject)
                            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg border border-gray-200">
                                <div class="flex items-center space-x-3">
                                    <div class="h-10 w-10 bg-gradient-to-br from-gray-100 to-gray-200 rounded-lg flex items-center justify-center">
                                        <svg class="h-5 w-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-6a2 2 0 012-2h6" />
                                        </svg>
                                    </div>
                                    <div>
                                        <h4 class="font-medium text-gray-900">{{ $subject->name }}</h4>
                                        <p class="text-sm text-gray-500">
                                            {{ __('messages.archived.archived_on', ['date' => $subject->deleted_at->diffForHumans()]) }}
                                        </p>
                                    </div>
                                </div>
                                <form method="POST" action="{{ tenant_route('school.admin.subjects.restore', $school, ['subject' => $subject->id]) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="px-3 py-1.5 text-sm rounded-lg bg-emerald-600 text-white hover:bg-emerald-700 transition duration-200">
                                        {{ __('messages.actions.restore') ?? 'Restore' }}
                                    </button>
                                </form>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8 text-gray-500">
                        {{ __('messages.archived.none') }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
