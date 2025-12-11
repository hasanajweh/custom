<?php $__env->startSection('title', __('Create user')); ?>

<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 py-6 space-y-4">
    <h1 class="text-2xl font-bold"><?php echo app('translator')->get('Create new user'); ?></h1>

    <?php if(session('status')): ?>
        <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-xl text-sm">
            <?php echo e(session('status')); ?>

        </div>
    <?php endif; ?>

    <form action="<?php echo e(route('main-admin.users.store', ['network' => $network->slug])); ?>" method="post" class="bg-white p-6 rounded shadow space-y-4">
        <?php echo csrf_field(); ?>
        <?php echo $__env->make('main-admin.users.partials.form', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </form>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.network', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\eXtreme\Documents\custom\resources\views\main-admin\users\create.blade.php ENDPATH**/ ?>