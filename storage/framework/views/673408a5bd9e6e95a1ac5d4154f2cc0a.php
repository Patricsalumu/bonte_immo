
<?php $__env->startSection('title', 'Détail du Compte Financier'); ?>
<?php $__env->startSection('content'); ?>
<div class="container">
    <h1 class="mb-4">Détail du Compte Financier</h1>
    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title"><?php echo e($compte->nom); ?></h5>
            <p><strong>Type :</strong> <?php echo e(ucfirst($compte->type)); ?></p>
            <p><strong>Solde :</strong> <?php echo e(number_format($compte->solde, 2, ',', ' ')); ?> $</p>
            <p><strong>Gestionnaire :</strong> <?php echo e($compte->gestionnaire ? $compte->gestionnaire->name : 'Aucun'); ?></p>
            <p><strong>Description :</strong> <?php echo e($compte->description); ?></p>
            <p><strong>Actif :</strong> <?php echo e($compte->actif ? 'Oui' : 'Non'); ?></p>
            <p><strong>Autoriser le découvert :</strong> <?php echo e($compte->autoriser_decouvert ? 'Oui' : 'Non'); ?></p>
        </div>
    </div>
    <a href="<?php echo e(route('comptes-financiers.edit', $compte)); ?>" class="btn btn-warning">Modifier</a>
    <form method="POST" action="<?php echo e(route('comptes-financiers.destroy', $compte)); ?>" class="d-inline">
        <?php echo csrf_field(); ?>
        <?php echo method_field('DELETE'); ?>
        <button type="submit" class="btn btn-danger" onclick="return confirm('Voulez-vous vraiment supprimer ce compte ?')">Supprimer</button>
    </form>
    <a href="<?php echo e(route('comptes-financiers.index')); ?>" class="btn btn-secondary">Retour</a>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\immo\resources\views/comptes-financiers/show.blade.php ENDPATH**/ ?>