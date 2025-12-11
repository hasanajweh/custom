<?php if(session()->has('success') || session()->has('error') || session()->has('warning') || session()->has('info')): ?>
    
    <script type="module">
        import toastr from 'toastr';

        toastr.options = {
            "closeButton": true,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "timeOut": "5000",
        };

        <?php if(session()->has('success')): ?>
        toastr.success("<?php echo e(session('success')); ?>");
        <?php endif; ?>

        <?php if(session()->has('error')): ?>
        toastr.error("<?php echo e(session('error')); ?>");
        <?php endif; ?>

        <?php if(session()->has('warning')): ?>
        toastr.warning("<?php echo e(session('warning')); ?>");
        <?php endif; ?>

        <?php if(session()->has('info')): ?>
        toastr.info("<?php echo e(session('info')); ?>");
        <?php endif; ?>
    </script>
<?php endif; ?>
<?php /**PATH C:\Users\eXtreme\Documents\custom\resources\views\components\session-toasts.blade.php ENDPATH**/ ?>