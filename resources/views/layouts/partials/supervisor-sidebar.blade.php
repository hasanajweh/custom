@php
    $school = $school
        ?? request()->attributes->get('branch')
        ?? request()->attributes->get('school');
@endphp

<nav class="space-y-1">
    <a href="{{ $school ? tenant_route('supervisor.dashboard', $school) : '#' }}"
       class="sidebar-item {{ request()->routeIs('supervisor.dashboard') ? 'active' : '' }}">
        <i class="ri-dashboard-3-line"></i>
        <span class="sidebar-text">{{ __('messages.navigation.dashboard') }}</span>
    </a>

    <div class="sidebar-divider">
        <div class="sidebar-divider-line"></div>
        <div class="sidebar-divider-title">
            {{ __('messages.navigation.files') }}
        </div>
    </div>

    <a href="{{ $school ? tenant_route('supervisor.reviews.index', $school) : '#' }}"
       class="sidebar-item {{ request()->routeIs('supervisor.reviews.*') ? 'active' : '' }}">
        <i class="ri-file-search-line"></i>
        <span class="sidebar-text">{{ __('messages.navigation.review_files') }}</span>
    </a>

    <a href="{{ $school ? tenant_route('supervisor.files.create', $school) : '#' }}"
       class="sidebar-item {{ request()->routeIs('supervisor.files.create') ? 'active' : '' }}">
        <i class="ri-upload-cloud-2-line"></i>
        <span class="sidebar-text">{{ __('messages.navigation.upload_file') }}</span>
    </a>

    <a href="{{ $school ? tenant_route('notifications.index', $school) : '#' }}"
       class="sidebar-item {{ request()->routeIs('notifications.*') ? 'active' : '' }}">
        <i class="ri-notification-2-line"></i>
        <span class="sidebar-text">{{ __('messages.navigation.notifications') }}</span>
    </a>
</nav>
