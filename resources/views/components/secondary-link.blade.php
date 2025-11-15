@props(['href'])

<a href="{{ $href }}" {{ $attributes->merge([
    'class' => 'inline-flex items-center justify-center px-4 py-2 border-2 border-brand-primary rounded-md font-semibold text-xs text-brand-primary uppercase tracking-widest focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-primary transition-colors duration-150 ease-in-out ' .
               'hover:bg-brand-border' // CORRECTION: On hover, use a subtle gray background instead of a solid fill.
    ]) }}>
    {{ $slot }}
</a>
