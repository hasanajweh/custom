@php
    $activeSchool = \App\Services\ActiveContext::getSchool();
    $school = $activeSchool
        ?? $school
        ?? request()->attributes->get('branch')
        ?? request()->attributes->get('school')
        ?? auth()->user()?->school;
    
    // Safely generate URLs with try-catch
    try {
        $dashboardUrl = $school ? tenant_route('supervisor.dashboard', $school) : '#';
        $reviewsUrl = $school ? tenant_route('supervisor.reviews.index', $school) : '#';
        $filesCreateUrl = $school ? tenant_route('supervisor.files.create', $school) : '#';
    } catch (\Exception $e) {
        $dashboardUrl = '#';
        $reviewsUrl = '#';
        $filesCreateUrl = '#';
    }
@endphp

<nav class="space-y-1">
    <a href="{{ $dashboardUrl }}"
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

    <a href="{{ $reviewsUrl }}"
       class="sidebar-item {{ request()->routeIs('supervisor.reviews.*') ? 'active' : '' }}">
        <i class="ri-file-search-line"></i>
        <span class="sidebar-text">{{ __('messages.navigation.review_files') }}</span>
    </a>

    <a href="{{ $filesCreateUrl }}"
       class="sidebar-item {{ request()->routeIs('supervisor.files.create') ? 'active' : '' }}">
        <i class="ri-upload-cloud-2-line"></i>
        <span class="sidebar-text">{{ __('messages.navigation.upload_file') }}</span>
    </a>
</nav>
