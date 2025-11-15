@props(['messages'])

@if ($messages)
    {{-- ENHANCEMENT: Kept standard red for errors but slightly increased font weight for emphasis. --}}
    <ul {{ $attributes->merge(['class' => 'text-sm text-red-600 font-medium space-y-1']) }}>
        @foreach ((array) $messages as $message)
            <li>{{ $message }}</li>
        @endforeach
    </ul>
@endif
