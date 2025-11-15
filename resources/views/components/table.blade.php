{{-- ENHANCEMENT: Themed the entire table with brand background and border colors. --}}
<div class="border border-brand-border rounded-lg overflow-hidden">
    <table {{ $attributes->merge(['class' => 'min-w-full divide-y divide-brand-border']) }}>
        <thead class="bg-brand-background">
        <tr>
            {{ $head }}
        </tr>
        </thead>
        <tbody class="bg-brand-secondary divide-y divide-brand-border">
        {{ $body }}
        </tbody>
    </table>
</div>
