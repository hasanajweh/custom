{{-- ENHANCEMENT: Changed from text-brand-text-light to the darker text-brand-text for better contrast --}}
<th {{ $attributes->merge(['class' => 'px-6 py-3 text-left text-xs font-bold text-brand-text uppercase tracking-wider']) }}>
    {{ $slot }}
</th>
