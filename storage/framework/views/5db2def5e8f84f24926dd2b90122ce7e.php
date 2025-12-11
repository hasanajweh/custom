<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['active']));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter((['active']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    // ENHANCEMENT: Re-themed to use your brand's primary color for the active state.
    $classes = ($active ?? false)
                ? 'bg-brand-primary text-white group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold'
                : 'text-brand-text-light hover:text-brand-text hover:bg-brand-background group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold';
?>

<a <?php echo e($attributes->merge(['class' => $classes])); ?>>
    <?php echo e($slot); ?>

</a>
<?php /**PATH C:\Users\eXtreme\Documents\custom\resources\views\components\nav-link.blade.php ENDPATH**/ ?>