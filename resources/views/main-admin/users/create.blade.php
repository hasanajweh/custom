@extends('layouts.network')

@section('title', __('Create user'))

@php
    $networkSlug = auth()->user()->network->slug ?? $network->slug;
@endphp

@section('content')
<div class="container mx-auto px-4 py-6 space-y-4">
    <h1 class="text-2xl font-bold">@lang('Create new user')</h1>

    <form action="{{ route('main-admin.users.store', ['network' => $networkSlug]) }}" method="post" class="bg-white p-6 rounded shadow space-y-4">
        @csrf
        @include('main-admin.users.partials.form')
    </form>
</div>
@endsection
