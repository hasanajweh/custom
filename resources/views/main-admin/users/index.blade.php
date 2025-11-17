@extends('layouts.school')

@section('title', __('messages.main_admin.users.title'))

@section('content')
<div class="container mx-auto px-4 py-6 space-y-4">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold">@lang('messages.main_admin.users.heading')</h1>
            <p class="text-gray-600">@lang('messages.main_admin.users.subtitle')</p>
        </div>
        <a href="{{ route('main-admin.users.create', $network) }}" class="bg-indigo-600 text-white px-4 py-2 rounded shadow">
            @lang('messages.main_admin.users.add')
        </a>
    </div>

    <form method="get" class="grid grid-cols-1 md:grid-cols-4 gap-3 bg-white p-4 rounded shadow">
        <div>
            <label class="text-sm text-gray-600 block">@lang('messages.main_admin.users.branch')</label>
            <select name="branch" class="w-full border rounded p-2">
                <option value="">@lang('messages.main_admin.common.all')</option>
                @foreach($branches as $branch)
                    <option value="{{ $branch->id }}" @selected(request('branch')==$branch->id)>{{ $branch->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="text-sm text-gray-600 block">@lang('messages.main_admin.users.role')</label>
            <select name="role" class="w-full border rounded p-2">
                <option value="">@lang('messages.main_admin.common.all')</option>
                <option value="admin" @selected(request('role')==='admin')>@lang('messages.admin')</option>
                <option value="supervisor" @selected(request('role')==='supervisor')>@lang('messages.supervisor')</option>
                <option value="teacher" @selected(request('role')==='teacher')>@lang('messages.teacher')</option>
            </select>
        </div>
        <div>
            <label class="text-sm text-gray-600 block">@lang('messages.labels.status')</label>
            <select name="status" class="w-full border rounded p-2">
                <option value="">@lang('messages.main_admin.common.all')</option>
                <option value="active" @selected(request('status')==='active')>@lang('messages.status.active')</option>
                <option value="archived" @selected(request('status')==='archived')>@lang('messages.status.archived')</option>
            </select>
        </div>
        <div class="flex items-end">
            <button class="bg-gray-800 text-white px-4 py-2 rounded">@lang('messages.actions.filter')</button>
        </div>
    </form>

    <div class="bg-white shadow rounded overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left">@lang('messages.labels.name')</th>
                    <th class="px-4 py-3 text-left">@lang('messages.labels.email')</th>
                    <th class="px-4 py-3 text-left">@lang('messages.main_admin.users.role')</th>
                    <th class="px-4 py-3 text-left">@lang('messages.main_admin.users.branch')</th>
                    <th class="px-4 py-3 text-left">@lang('messages.labels.status')</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($users as $user)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 font-semibold">{{ $user->name }}</td>
                        <td class="px-4 py-3">{{ $user->email }}</td>
                        <td class="px-4 py-3 capitalize">{{ __($user->role) }}</td>
                        <td class="px-4 py-3">{{ $user->school?->name }}</td>
                        <td class="px-4 py-3">
                            @if($user->trashed())
                                <span class="px-2 py-1 text-xs rounded bg-gray-200 text-gray-700">@lang('messages.status.archived')</span>
                            @else
                                <span class="px-2 py-1 text-xs rounded bg-green-100 text-green-700">@lang('messages.status.active')</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 space-x-2 text-right">
                            <a class="text-indigo-600" href="{{ route('main-admin.users.edit', [$network, $user]) }}">@lang('messages.actions.edit')</a>
                            @if($user->trashed())
                                <form action="{{ route('main-admin.users.restore', [$network, $user->id]) }}" method="post" class="inline">
                                    @csrf
                                    <button class="text-green-600" type="submit">@lang('messages.actions.restore')</button>
                                </form>
                            @else
                                <form action="{{ route('main-admin.users.destroy', [$network, $user]) }}" method="post" class="inline" onsubmit="return confirm('@lang('messages.main_admin.common.confirm_archive')')">
                                    @csrf
                                    @method('delete')
                                    <button class="text-red-600" type="submit">@lang('messages.actions.archive')</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td class="px-4 py-3" colspan="6">@lang('messages.main_admin.users.empty')</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div>
        {{ $users->links() }}
    </div>
</div>
@endsection
