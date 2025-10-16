<?php if($paginator->hasPages()): ?>
<nav role="navigation" aria-label="Pagination Navigation" class="d-flex justify-content-center">
    <style>
        /* Styles responsives pour la pagination personnalisée */
        .pagination-custom { display: inline-flex; flex-wrap: wrap; gap: .25rem; font-size: .9rem; list-style: none; padding: 0; margin: 0; }
        .pagination-custom .page-item { margin: 0; }
        .pagination-custom .page-link { display: inline-block; padding: .375rem .625rem; border: 1px solid #dee2e6; background: #fff; color: #333; border-radius: .25rem; }
        .pagination-custom .page-item.active .page-link { background: #0d6efd; color: #fff; border-color: #0d6efd; }
        .pagination-custom .page-item.disabled .page-link { color: #6c757d; background: #fff; border-color: #dee2e6; cursor: default; }
        @media (max-width: 576px) {
            .pagination-custom { overflow-x: auto; -webkit-overflow-scrolling: touch; white-space: nowrap; }
            .pagination-custom .page-item { display: inline-block; }
        }
    </style>
    <ul class="pagination pagination-custom" style="align-items:center;">
        
        <?php if($paginator->onFirstPage()): ?>
            <li class="page-item disabled" aria-disabled="true" aria-label="Précédent">
                <span class="page-link">Précédent</span>
            </li>
        <?php else: ?>
            <li class="page-item">
                <a class="page-link" href="<?php echo e($paginator->previousPageUrl()); ?>" rel="prev" aria-label="Précédent">Précédent</a>
            </li>
        <?php endif; ?>

        
        <?php $__currentLoopData = $elements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $element): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            
            <?php if(is_string($element)): ?>
                <li class="page-item disabled" aria-disabled="true"><span class="page-link"><?php echo e($element); ?></span></li>
            <?php endif; ?>

            
            <?php if(is_array($element)): ?>
                <?php $__currentLoopData = $element; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $page => $url): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php if($page == $paginator->currentPage()): ?>
                        <li class="page-item active" aria-current="page"><span class="page-link"><?php echo e($page); ?></span></li>
                    <?php else: ?>
                        <li class="page-item"><a class="page-link" href="<?php echo e($url); ?>"><?php echo e($page); ?></a></li>
                    <?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endif; ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

        
        <?php if($paginator->hasMorePages()): ?>
            <li class="page-item">
                <a class="page-link" href="<?php echo e($paginator->nextPageUrl()); ?>" rel="next" aria-label="Suivant">Suivant</a>
            </li>
        <?php else: ?>
            <li class="page-item disabled" aria-disabled="true" aria-label="Suivant">
                <span class="page-link">Suivant</span>
            </li>
        <?php endif; ?>
    </ul>
</nav>
<?php endif; ?>
<?php /**PATH C:\xampp\htdocs\immo\resources\views/vendor/pagination/custom.blade.php ENDPATH**/ ?>