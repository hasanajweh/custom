@php
    $school = $school
        ?? request()->attributes->get('branch')
        ?? request()->attributes->get('school');
@endphp

<nav class="space-y-1">
    <a href="{{ $school ? tenant_route('school.admin.dashboard', $school) : '#' }}"
       class="sidebar-item {{ request()->routeIs('school.admin.dashboard') ? 'active' : '' }}">
        <i class="ri-dashboard-3-line"></i>
        <span class="sidebar-text">{{ __('messages.navigation.dashboard') }}</span>
    </a>

    <div class="sidebar-divider">
        <div class="sidebar-divider-line"></div>
        <div class="sidebar-divider-title">
            {{ __('messages.navigation.manage') }}
        </div>
    </div>

    <a href="{{ $school ? tenant_route('school.admin.users.index', $school) : '#' }}"
       class="sidebar-item {{ request()->routeIs('school.admin.users.*') ? 'active' : '' }}">
        <i class="ri-team-line"></i>
        <span class="sidebar-text">{{ __('messages.navigation.manage_users') }}</span>
    </a>

    <a href="{{ $school ? tenant_route('school.admin.subjects.index', $school) : '#' }}"
       class="sidebar-item {{ request()->routeIs('school.admin.subjects.*') ? 'active' : '' }}">
        <i class="ri-book-2-line"></i>
        <span class="sidebar-text">{{ __('messages.navigation.subjects') }}</span>
    </a>

    <a href="{{ $school ? tenant_route('school.admin.grades.index', $school) : '#' }}"
       class="sidebar-item {{ request()->routeIs('school.admin.grades.*') ? 'active' : '' }}">
        <i class="ri-bar-chart-grouped-line"></i>
        <span class="sidebar-text">{{ __('messages.navigation.grades') }}</span>
    </a>

    <a href="{{ $school ? tenant_route('school.admin.file-browser.index', $school) : '#' }}"
       class="sidebar-item {{ request()->routeIs('school.admin.file-browser.*') ? 'active' : '' }}">
        <i class="ri-folder-3-line"></i>
        <span class="sidebar-text">{{ __('messages.navigation.file_browser') }}</span>
    </a>

    <a href="{{ $school ? tenant_route('school.admin.plans.index', $school) : '#' }}"
       class="sidebar-item {{ request()->routeIs('school.admin.plans.*') ? 'active' : '' }}">
        <i class="ri-calendar-check-line"></i>
        <span class="sidebar-text">{{ __('messages.navigation.plans') }}</span>
    </a>

    <a href="{{ $school ? tenant_route('school.admin.supervisors.index', $school) : '#' }}"
       class="sidebar-item {{ request()->routeIs('school.admin.supervisors.*') ? 'active' : '' }}">
        <i class="ri-user-star-line"></i>
        <span class="sidebar-text">{{ __('messages.navigation.supervisors') }}</span>
    </a>
</nav>
