@props(['active'])

@php
    // ENHANCEMENT: Re-themed to use your brand's primary color for the active state.
    $classes = ($active ?? false)
                ? 'bg-brand-primary text-white group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold'
                : 'text-brand-text-light hover:text-brand-text hover:bg-brand-background group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
