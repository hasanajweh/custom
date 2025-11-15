@props(['value'])

{{-- ENHANCEMENT: Using brand's lighter text color for labels. --}}
<label {{ $attributes->merge(['class' => 'block font-semibold text-sm text-gray-700 dark:text-gray-300'
]) }}>
    {{ $value ?? $slot }}
</label>
