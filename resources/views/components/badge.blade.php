@props(['color' => 'gray'])

@php
    // ENHANCEMENT: Added a 'primary' color and updated 'gray' to use your brand theme colors.
    $colorClasses = [
        'gray' => 'bg-brand-border text-brand-text-light',
        'primary' => 'bg-brand-primary text-white',
        'green' => 'bg-green-100 text-green-800',
        'yellow' => 'bg-yellow-100 text-yellow-800',
        'red' => 'bg-red-100 text-red-800',
    ][$color] ?? 'bg-brand-border text-brand-text-light';
@endphp

{{-- This creates a nicely padded, rounded badge. The base classes are great. --}}
<span {{ $attributes->merge(['class' => 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ' . $colorClasses]) }}>
    {{ $slot }}
</span>
