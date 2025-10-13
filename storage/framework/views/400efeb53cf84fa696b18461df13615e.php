
<?php $__env->startSection('title', 'Modifier le Compte Financier'); ?>
<?php $__env->startSection('content'); ?>
<div class="container">
    <h1 class="mb-4">Modifier le Compte Financier</h1>
    <form method="POST" action="<?php echo e(route('comptes-financiers.update', $compte)); ?>">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>
        <div class="mb-3">
            <label for="nom" class="form-label">Nom du compte <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="nom" name="nom" value="<?php echo e($compte->nom); ?>" required>
        </div>
        <div class="mb-3">
            <label for="type" class="form-label">Type <span class="text-danger">*</span></label>
            <select class="form-select" id="type" name="type" required>
                <option value="caisse" <?php echo e($compte->type == 'caisse' ? 'selected' : ''); ?>>Caisse</option>
                <option value="banque" <?php echo e($compte->type == 'banque' ? 'selected' : ''); ?>>Banque</option>
                <option value="epargne" <?php echo e($compte->type == 'epargne' ? 'selected' : ''); ?>>Épargne</option>
                <option value="charge" <?php echo e($compte->type == 'charge' ? 'selected' : ''); ?>>Charge</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="solde" class="form-label">Solde</label>
            <input type="number" class="form-control" id="solde" name="solde" value="<?php echo e($compte->solde); ?>" step="0.01">
        </div>
        <div class="mb-3">
            <label for="gestionnaire_id" class="form-label">Gestionnaire</label>
            <select class="form-select" id="gestionnaire_id" name="gestionnaire_id">
                <option value="">Aucun</option>
                <?php $__currentLoopData = $utilisateurs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($user->id); ?>" <?php echo e($compte->gestionnaire_id == $user->id ? 'selected' : ''); ?>><?php echo e($user->name); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea class="form-control" id="description" name="description" rows="2"><?php echo e($compte->description); ?></textarea>
        </div>
        <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" id="actif" name="actif" <?php echo e($compte->actif ? 'checked' : ''); ?>>
            <label class="form-check-label" for="actif">Compte actif</label>
        </div>
        <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" id="autoriser_decouvert" name="autoriser_decouvert" <?php echo e($compte->autoriser_decouvert ? 'checked' : ''); ?>>
            <label class="form-check-label" for="autoriser_decouvert">Autoriser le découvert</label>
        </div>
        <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
        <a href="<?php echo e(route('comptes-financiers.index')); ?>" class="btn btn-secondary">Annuler</a>
    </form>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\immo\resources\views/comptes-financiers/edit.blade.php ENDPATH**/ ?>