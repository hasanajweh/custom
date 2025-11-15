{{-- This is the off-canvas menu for mobile --}}
<div x-show="sidebarOpen" class="relative z-50 lg:hidden" x-ref="dialog" aria-modal="true">
    <div x-show="sidebarOpen" x-transition:enter="transition-opacity ease-linear duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-linear duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" @click="sidebarOpen = false" class="fixed inset-0 bg-brand-text/80"></div>

    <div class="fixed inset-0 flex">
        <div x-show="sidebarOpen"
             x-transition:enter="transition ease-in-out duration-300 transform" x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0"
             x-transition:leave="transition ease-in-out duration-300 transform" x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full"
             class="relative mr-16 flex w-full max-w-xs flex-1">

            <div class="absolute left-full top-0 flex w-16 justify-center pt-5">
                <button @click="sidebarOpen = false" type="button" class="-m-2.5 p-2.5">
                    <span class="sr-only">Close sidebar</span>
                    <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>

            {{-- CORRECTION: Using light background for mobile sidebar --}}
            <div class="flex grow flex-col gap-y-5 overflow-y-auto bg-brand-secondary px-6 pb-4">
                @include('layouts.partials.sidebar-content')
            </div>
        </div>
    </div>
</div>

{{-- This is the static sidebar for desktop --}}
<div class="hidden lg:fixed lg:inset-y-0 lg:z-50 lg:flex lg:w-72 lg:flex-col">
    {{-- CORRECTION: Using light background for desktop sidebar to match the app theme --}}
    <div class="flex grow flex-col gap-y-5 overflow-y-auto border-r border-brand-border bg-brand-secondary px-6 pb-4">
        @include('layouts.partials.sidebar-content')
    </div>
</div>
