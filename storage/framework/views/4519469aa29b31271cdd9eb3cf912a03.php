<?php $__env->startSection('title', __('Edit user')); ?>

<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 py-6 space-y-4">
    <h1 class="text-2xl font-bold"><?php echo app('translator')->get('Edit user'); ?></h1>

    <form action="<?php echo e(route('main-admin.users.update', ['network' => $network->slug, 'user' => $user])); ?>" method="post" class="bg-white p-6 rounded shadow space-y-4">
        <?php echo csrf_field(); ?>
        <?php echo method_field('put'); ?>
        <?php echo $__env->make('main-admin.users.partials.form', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </form>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.network', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\eXtreme\Documents\custom\resources\views\main-admin\users\edit.blade.php ENDPATH**/ ?>