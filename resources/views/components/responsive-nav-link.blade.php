@props(['active'])

@php
    // ENHANCEMENT: Completely re-themed to use brand colors instead of the default indigo.
    $classes = ($active ?? false)
                ? 'block w-full ps-3 pe-4 py-2 border-l-4 border-brand-primary text-start text-base font-medium text-brand-primary bg-brand-background focus:outline-none'
                : 'block w-full ps-3 pe-4 py-2 border-l-4 border-transparent text-start text-base font-medium text-brand-text-light hover:text-brand-text hover:bg-brand-background hover:border-brand-border focus:outline-none transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
