<?php $__env->startSection('content'); ?>
    <?php echo e($slot); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.guest', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\eXtreme\Documents\custom\resources\views\components\layouts\guest.blade.php ENDPATH**/ ?>