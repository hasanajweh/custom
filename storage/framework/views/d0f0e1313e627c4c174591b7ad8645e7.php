
<div class="border border-brand-border rounded-lg overflow-hidden">
    <table <?php echo e($attributes->merge(['class' => 'min-w-full divide-y divide-brand-border'])); ?>>
        <thead class="bg-brand-background">
        <tr>
            <?php echo e($head); ?>

        </tr>
        </thead>
        <tbody class="bg-brand-secondary divide-y divide-brand-border">
        <?php echo e($body); ?>

        </tbody>
    </table>
</div>
<?php /**PATH C:\Users\eXtreme\Documents\custom\resources\views\components\table.blade.php ENDPATH**/ ?>