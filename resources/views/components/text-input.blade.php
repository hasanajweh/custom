@props(['disabled' => false])

{{-- ENHANCEMENT: Re-themed input fields to match the brand and have proper focus states. --}}
<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge([
    'class' => 'w-full border-gray-300 bg-white text-gray-900 rounded-md shadow-sm focus:border-brand-primary focus:ring focus:ring-brand-primary focus:ring-opacity-50 transition ease-in-out duration-150 disabled:opacity-50'
    ]) !!}>
