@auth
<div class="flex h-16 shrink-0 items-center">
    @php
        $school = $school
            ?? request()->attributes->get('branch')
            ?? request()->attributes->get('school')
            ?? Auth::user()?->school;
        $network = $network ?? $school?->network ?? Auth::user()?->network;
        $hasTenantContext = $school && $network;
        $schoolSlug = $school?->slug;
    @endphp
    @php
        $dashboardRouteName = Auth::user()?->role === 'admin'
            ? 'school.admin.dashboard'
            : 'dashboard';
        $dashboardUrl = $hasTenantContext ? tenant_route($dashboardRouteName, $school) : null;
    @endphp
    @if($hasTenantContext)
        <a href="{{ $dashboardUrl }}">
            <x-application-logo class="h-8 w-auto" />
        </a>
    @else
        <a href="{{ route('superadmin.dashboard') }}">
            <x-application-logo class="h-8 w-auto" />
        </a>
    @endif
</div>

<nav class="flex flex-1 flex-col">
    <ul role="list" class="flex flex-1 flex-col gap-y-7">
        <li>
            <ul role="list" class="-mx-2 space-y-1">
                {{-- Tenant Links --}}                    
                @if ($hasTenantContext)
                    <li><x-nav-link :href="$dashboardUrl" :active="request()->routeIs('dashboard') || request()->routeIs('school.admin.dashboard')">Dashboard</x-nav-link></li>
                    @if (auth()->user()->role === 'teacher')
                        <li><x-nav-link :href="tenant_route('teacher.files.index', $school)" :active="request()->routeIs('teacher.files.*')">My Files</x-nav-link></li>
                        <li><x-nav-link :href="tenant_route('notifications.index', $school)" :active="request()->routeIs('notifications.*')" class="flex items-center">Notifications @if(auth()->user()->unreadNotifications->count() > 0)<span class="ml-auto w-6 min-w-max whitespace-nowrap rounded-full bg-red-500 px-2.5 py-0.5 text-center text-xs font-medium leading-5 text-white ring-1 ring-inset ring-red-500">{{ auth()->user()->unreadNotifications->count() }}</span>@endif</x-nav-link></li>
                    @endif
                    @if (auth()->user()->role === 'supervisor')
                        <li><x-nav-link :href="tenant_route('supervisor.reviews.index', $school)" :active="request()->routeIs('supervisor.reviews.*')">Review Files</x-nav-link></li>
                    @endif
                    @if (auth()->user()->role === 'admin')
                        <li><x-nav-link :href="tenant_route('school.admin.users.index', $school)" :active="request()->routeIs('school.admin.users.*')">Manage Users</x-nav-link></li>
                        <li><x-nav-link :href="tenant_route('school.admin.subjects.index', $school)" :active="request()->routeIs('school.admin.subjects.*')">Manage Subjects</x-nav-link></li>
                        <li><x-nav-link :href="tenant_route('school.admin.grades.index', $school)" :active="request()->routeIs('school.admin.grades.*')">Manage Grades</x-nav-link></li>
                        <li><x-nav-link :href="tenant_route('school.admin.file-browser.index', $school)" :active="request()->routeIs('school.admin.file-browser.*')">File Browser</x-nav-link></li>
                        <li><x-nav-link :href="tenant_route('school.admin.plans.index', $school)" :active="request()->routeIs('school.admin.plans.*')">Plans</x-nav-link></li>
                        <li><x-nav-link :href="tenant_route('school.admin.supervisors.index', $school)" :active="request()->routeIs('school.admin.supervisors.*')">Supervisors</x-nav-link></li>
                    @endif
                @endif

                {{-- Super Admin Links --}}
                @if (auth()->user() && auth()->user()->is_super_admin)
                    <li><x-nav-link :href="route('superadmin.dashboard')" :active="request()->routeIs('superadmin.dashboard')">Admin Dashboard</x-nav-link></li>
                    <li><x-nav-link :href="route('superadmin.schools.index')" :active="request()->routeIs('superadmin.schools.*')">Manage Schools</x-nav-link></li>
                    <li><x-nav-link :href="route('superadmin.subscriptions.index')" :active="request()->routeIs('superadmin.subscriptions.*')">Manage Subscriptions</x-nav-link></li>
                    <li><x-nav-link :href="route('superadmin.plans.index')" :active="request()->routeIs('superadmin.plans.*')">Manage Plans</x-nav-link></li>
                    <li><x-nav-link :href="route('superadmin.settings.index')" :active="request()->routeIs('superadmin.settings.index')">Settings</x-nav-link></li>
                @endif
            </ul>
        </li>

        {{-- User Menu --}}
        <li class="mt-auto">
            <div class="-mx-6 mt-6 border-t border-white/20 p-2">
                <x-dropdown align="top" width="60">
                    <x-slot name="trigger">
                        <div class="flex items-center gap-x-4 px-4 py-3 text-sm font-semibold leading-6 text-white hover:bg-black/10 rounded-md cursor-pointer">
                            <div class="h-8 w-8 rounded-full bg-gray-700 flex items-center justify-center font-bold">
                                {{ substr(Auth::user()->name, 0, 1) }}
                            </div>
                            <span class="truncate">{{ Auth::user()->name }}</span>
                        </div>
                    </x-slot>

                    <x-slot name="content">
                        {{-- THIS IS THE FIX: This section now checks if you are a Super Admin or a regular user --}}

                        @if(auth()->user()->is_super_admin)
                            {{-- Super Admin Logout Form --}}
                            <form method="POST" action="{{ route('superadmin.logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('superadmin.logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        @elseif ($hasTenantContext)
                            {{-- Tenant User Links --}}                    
                            @php
                                $profileUrl = tenant_route('profile.edit', $school);
                                $logoutUrl = tenant_route('logout', $school);
                            @endphp
                            <x-dropdown-link :href="$profileUrl">
                                {{ __('My Profile') }}
                            </x-dropdown-link>
                            <form method="POST" action="{{ $logoutUrl }}">
                                @csrf
                                <x-dropdown-link :href="$logoutUrl" onclick="event.preventDefault(); this.closest('form').submit();">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        @endif
                    </x-slot>
                </x-dropdown>
            </div>
        </li>
    </ul>
</nav>
@endauth
