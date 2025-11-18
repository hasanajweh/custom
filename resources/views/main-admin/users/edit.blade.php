@extends('layouts.network')

@section('title', __('Edit user'))

@php
    $networkSlug = auth()->user()->network->slug ?? $network->slug;
@endphp

@section('content')
<div class="container mx-auto px-4 py-6 space-y-4">
    <h1 class="text-2xl font-bold">@lang('Edit user')</h1>

    <form action="{{ route('main-admin.users.update', ['network' => $networkSlug, 'user' => $user]) }}" method="post" class="bg-white p-6 rounded shadow space-y-4">
        @csrf
        @method('put')
        @include('main-admin.users.partials.form')
    </form>
</div>
@endsection
