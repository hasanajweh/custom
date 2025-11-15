@props(['href'])

<a href="{{ $href }}" {{ $attributes->merge([
    'class' => 'inline-flex items-center justify-center px-4 py-2 bg-brand-primary border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-opacity-90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-primary transition-all duration-150 ease-in-out'
    ]) }}>
    {{ $slot }}
</a>
