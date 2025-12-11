<?php
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
?>

<nav class="space-y-1">
    <a href="<?php echo e($dashboardUrl); ?>"
       class="sidebar-item <?php echo e(request()->routeIs('supervisor.dashboard') ? 'active' : ''); ?>">
        <i class="ri-dashboard-3-line"></i>
        <span class="sidebar-text"><?php echo e(__('messages.navigation.dashboard')); ?></span>
    </a>

    <div class="sidebar-divider">
        <div class="sidebar-divider-line"></div>
        <div class="sidebar-divider-title">
            <?php echo e(__('messages.navigation.files')); ?>

        </div>
    </div>

    <a href="<?php echo e($reviewsUrl); ?>"
       class="sidebar-item <?php echo e(request()->routeIs('supervisor.reviews.*') ? 'active' : ''); ?>">
        <i class="ri-file-search-line"></i>
        <span class="sidebar-text"><?php echo e(__('messages.navigation.review_files')); ?></span>
    </a>

    <a href="<?php echo e($filesCreateUrl); ?>"
       class="sidebar-item <?php echo e(request()->routeIs('supervisor.files.create') ? 'active' : ''); ?>">
        <i class="ri-upload-cloud-2-line"></i>
        <span class="sidebar-text"><?php echo e(__('messages.navigation.upload_file')); ?></span>
    </a>
</nav>
<?php /**PATH C:\Users\eXtreme\Documents\custom\resources\views\layouts\partials\supervisor-sidebar.blade.php ENDPATH**/ ?>