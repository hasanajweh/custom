@extends('layouts.network')

@section('title', __('Network Users'))

@section('content')
<div class="container mx-auto px-4 py-6 space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">@lang('Network users')</h1>
            <p class="text-gray-600">@lang('Manage users across all branches in this network.')</p>
        </div>
        <a href="{{ route('main-admin.users.create', ['network' => $network->slug]) }}" class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white px-4 py-2 rounded-xl shadow hover:from-indigo-700 hover:to-purple-700 transition">
            @lang('Add user')
        </a>
    </div>

    <form method="get" class="grid grid-cols-1 md:grid-cols-3 gap-3 bg-white p-5 rounded-2xl shadow-lg border border-indigo-50">
        <div>
            <label class="text-sm text-gray-600 block mb-1">@lang('Role')</label>
            <select name="role" class="w-full border border-indigo-100 rounded-xl p-3 bg-white focus:ring-2 focus:ring-indigo-500">
                <option value="">@lang('All')</option>
                <option value="admin" @selected(request('role')==='admin')>@lang('Admin')</option>
                <option value="supervisor" @selected(request('role')==='supervisor')>@lang('Supervisor')</option>
                <option value="teacher" @selected(request('role')==='teacher')>@lang('Teacher')</option>
            </select>
        </div>
        <div>
            <label class="text-sm text-gray-600 block mb-1">@lang('Status')</label>
            <select name="status" class="w-full border border-indigo-100 rounded-xl p-3 bg-white focus:ring-2 focus:ring-indigo-500">
                <option value="">@lang('All')</option>
                <option value="active" @selected(request('status')==='active')>@lang('Active')</option>
                <option value="archived" @selected(request('status')==='archived')>@lang('Archived')</option>
            </select>
        </div>
        <div class="flex items-end">
            <button class="bg-gray-900 text-white px-4 py-3 rounded-xl shadow hover:bg-black transition w-full md:w-auto">@lang('Filter')</button>
        </div>
    </form>

    <div class="bg-white shadow rounded-2xl overflow-x-auto border border-indigo-50">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left">@lang('Name')</th>
                    <th class="px-4 py-3 text-left">@lang('Email')</th>
                    <th class="px-4 py-3 text-left">@lang('messages.roles_label')</th>
                    <th class="px-4 py-3 text-left">@lang('messages.branches')</th>
                    <th class="px-4 py-3 text-left">@lang('Status')</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($users as $user)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 font-semibold">{{ $user->name }}</td>
                        <td class="px-4 py-3">{{ $user->email }}</td>
                        <td class="px-4 py-3">
                            <div class="flex flex-wrap gap-2">
                                @foreach($user->schoolRoles as $role)
                                    <span class="px-2 py-1 text-xs rounded bg-indigo-50 text-indigo-700">{{ __($role->role) }}</span>
                                @endforeach
                            </div>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex flex-col gap-1">
                                @foreach($user->schoolRoles as $role)
                                    <div class="text-sm text-gray-700">{{ $role->school?->name }} — {{ __($role->role) }}</div>
                                @endforeach
                            </div>
                        </td>
                        <td class="px-4 py-3">
                            @if($user->trashed())
                                <span class="px-2 py-1 text-xs rounded bg-gray-200 text-gray-700">@lang('Archived')</span>
                            @else
                                <span class="px-2 py-1 text-xs rounded bg-green-100 text-green-700">@lang('Active')</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 space-x-2 text-right">
                            <a class="text-indigo-600" href="{{ route('main-admin.users.edit', ['network' => $network->slug, 'user' => $user]) }}">@lang('Edit')</a>
                            @if($user->trashed())
                                <form action="{{ route('main-admin.users.restore', ['network' => $network->slug, 'user' => $user->id]) }}" method="post" class="inline">
                                    @csrf
                                    <button class="text-green-600" type="submit">@lang('Restore')</button>
                                </form>
                            @else
                                <form action="{{ route('main-admin.users.destroy', ['network' => $network->slug, 'user' => $user]) }}" method="post" class="inline" onsubmit="return confirm('@lang('Are you sure?')')">
                                    @csrf
                                    @method('delete')
                                    <button class="text-red-600" type="submit">@lang('Archive')</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td class="px-4 py-3" colspan="6">@lang('No users found.')</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div>
        {{ $users->links() }}
    </div>
</div>
@endsection
