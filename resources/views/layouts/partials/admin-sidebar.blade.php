@php
    $activeSchool = \App\Services\ActiveContext::getSchool();
    $school = $activeSchool
        ?? $school
        ?? request()->attributes->get('branch')
        ?? request()->attributes->get('school')
        ?? auth()->user()?->school;
    
    // Safely generate URLs with try-catch
    try {
        $dashboardUrl = $school ? tenant_route('school.admin.dashboard', $school) : '#';
        $usersUrl = $school ? tenant_route('school.admin.users.index', $school) : '#';
        $subjectsUrl = $school ? tenant_route('school.admin.subjects.index', $school) : '#';
        $gradesUrl = $school ? tenant_route('school.admin.grades.index', $school) : '#';
        $fileBrowserUrl = $school ? tenant_route('school.admin.file-browser.index', $school) : '#';
        $plansUrl = $school ? tenant_route('school.admin.plans.index', $school) : '#';
        $supervisorsUrl = $school ? tenant_route('school.admin.supervisors.index', $school) : '#';
    } catch (\Exception $e) {
        $dashboardUrl = '#';
        $usersUrl = '#';
        $subjectsUrl = '#';
        $gradesUrl = '#';
        $fileBrowserUrl = '#';
        $plansUrl = '#';
        $supervisorsUrl = '#';
    }
@endphp

<nav class="space-y-1">
    <a href="{{ $dashboardUrl }}"
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

    <a href="{{ $usersUrl }}"
       class="sidebar-item {{ request()->routeIs('school.admin.users.*') ? 'active' : '' }}">
        <i class="ri-team-line"></i>
        <span class="sidebar-text">{{ __('messages.navigation.manage_users') }}</span>
    </a>

    <a href="{{ $subjectsUrl }}"
       class="sidebar-item {{ request()->routeIs('school.admin.subjects.*') ? 'active' : '' }}">
        <i class="ri-book-2-line"></i>
        <span class="sidebar-text">{{ __('messages.navigation.subjects') }}</span>
    </a>

    <a href="{{ $gradesUrl }}"
       class="sidebar-item {{ request()->routeIs('school.admin.grades.*') ? 'active' : '' }}">
        <i class="ri-bar-chart-grouped-line"></i>
        <span class="sidebar-text">{{ __('messages.navigation.grades') }}</span>
    </a>

    <a href="{{ $fileBrowserUrl }}"
       class="sidebar-item {{ request()->routeIs('school.admin.file-browser.*') ? 'active' : '' }}">
        <i class="ri-folder-3-line"></i>
        <span class="sidebar-text">{{ __('messages.navigation.file_browser') }}</span>
    </a>

    <a href="{{ $plansUrl }}"
       class="sidebar-item {{ request()->routeIs('school.admin.plans.*') ? 'active' : '' }}">
        <i class="ri-calendar-check-line"></i>
        <span class="sidebar-text">{{ __('messages.navigation.plans') }}</span>
    </a>

    <a href="{{ $supervisorsUrl }}"
       class="sidebar-item {{ request()->routeIs('school.admin.supervisors.*') ? 'active' : '' }}">
        <i class="ri-user-star-line"></i>
        <span class="sidebar-text">{{ __('messages.navigation.supervisors') }}</span>
    </a>
</nav>
