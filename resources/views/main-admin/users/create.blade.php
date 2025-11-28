@extends('layouts.network')

@section('title', __('Create user'))

@section('content')
<div class="container mx-auto px-4 py-6 space-y-4">
    <h1 class="text-2xl font-bold">@lang('Create new user')</h1>

    @if (session('status'))
        <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-xl text-sm">
            {{ session('status') }}
        </div>
    @endif

    <form action="{{ route('main-admin.users.store', ['network' => $network->slug]) }}" method="post" class="bg-white p-6 rounded shadow space-y-4">
        @csrf
        @include('main-admin.users.partials.form')
    </form>
</div>
@endsection
