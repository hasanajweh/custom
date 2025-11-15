{{-- ENHANCEMENT: Using brand text and background colors for consistent link styling --}}
<a {{ $attributes->merge(['class' => 'block w-full px-4 py-2 text-start text-sm leading-5 text-brand-text hover:bg-brand-background focus:outline-none focus:bg-brand-background transition duration-150 ease-in-out']) }}>
    {{ $slot }}
</a>
