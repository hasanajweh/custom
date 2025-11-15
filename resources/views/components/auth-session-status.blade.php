@props(['status'])

@if ($status)
    {{-- ENHANCEMENT: Restyled this from simple text to a proper alert --}}
    <div {{ $attributes->merge([
        'class' => 'p-4 mb-4 text-sm font-medium text-green-800 bg-green-100 rounded-lg'
    ]) }}>
        {{ $status }}
    </div>
@endif
