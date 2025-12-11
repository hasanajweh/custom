<!-- Off-canvas menu for mobile -->
<div x-show="sidebarOpen" class="lg:hidden" x-ref="dialog" aria-modal="true">
    <div @click="sidebarOpen = false" class="fixed inset-0 bg-gray-900/80"></div>
    <div class="fixed inset-0 flex">
        <div x-show="sidebarOpen"
             x-transition:enter="transition ease-in-out duration-300 transform" x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0"
             x-transition:leave="transition ease-in-out duration-300 transform" x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full"
             class="relative mr-16 flex w-full max-w-xs flex-1">
            <div class="absolute top-0 left-full flex w-16 justify-center pt-5">
                <button @click="sidebarOpen = false" type="button" class="-m-2.5 p-2.5">
                    <span class="sr-only">Close sidebar</span>
                    <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>
            <div class="flex grow flex-col gap-y-5 overflow-y-auto bg-brand-primary px-6 pb-4">
                <?php echo $__env->make('layouts.partials.superadmin-sidebar-content', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            </div>
        </div>
    </div>
</div>

<!-- Static sidebar for desktop -->
<div class="hidden lg:fixed lg:inset-y-0 lg:z-50 lg:flex lg:w-72 lg:flex-col">
    <div class="flex grow flex-col gap-y-5 overflow-y-auto border-r border-brand-border bg-brand-primary px-6 pb-4">
        <?php echo $__env->make('layouts.partials.superadmin-sidebar-content', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </div>
</div>
<?php /**PATH C:\Users\eXtreme\Documents\custom\resources\views\layouts\superadmin-navigation.blade.php ENDPATH**/ ?>