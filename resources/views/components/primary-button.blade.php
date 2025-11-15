<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 bg-brand-primary border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-opacity-90 focus:outline-none focus:ring-2 focus:ring-brand-primary focus:ring-offset-2 transition-all duration-150 ease-in-out shadow-sm hover:shadow-md hover:-translate-y-px disabled:opacity-50 disabled:cursor-not-allowed']) }}>
    {{ $slot }}
</button>
