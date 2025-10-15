

<?php $__env->startSection('title', 'Modifier le Contrat de Loyer #' . $loyer->id); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">Modifier le Contrat de Loyer #<?php echo e($loyer->id); ?></h1>
    <a href="<?php echo e(route('loyers.index')); ?>" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Retour
    </a>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <form method="POST" action="<?php echo e(route('loyers.update', $loyer)); ?>">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>

                    <h5 class="mb-3">Informations du contrat</h5>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="locataire" class="form-label">Locataire</label>
                            <input type="text" 
                                   class="form-control" 
                                   id="locataire" 
                                   value="<?php echo e($loyer->locataire->nom); ?> <?php echo e($loyer->locataire->prenom); ?>" 
                                   readonly>
                            <small class="form-text text-muted">Le locataire ne peut pas être modifié après création</small>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="appartement" class="form-label">Appartement</label>
                            <input type="text" 
                                   class="form-control" 
                                   id="appartement" 
                                   value="<?php echo e($loyer->appartement->immeuble->nom); ?> - Apt <?php echo e($loyer->appartement->numero); ?>" 
                                   readonly>
                            <small class="form-text text-muted">L'appartement ne peut pas être modifié après création</small>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="date_debut" class="form-label">Date de début <span class="text-danger">*</span></label>
                            <input type="date" 
                                   class="form-control <?php $__errorArgs = ['date_debut'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                   id="date_debut" 
                                   name="date_debut" 
                                   value="<?php echo e(old('date_debut', $loyer->date_debut->format('Y-m-d'))); ?>" 
                                   required>
                            <?php $__errorArgs = ['date_debut'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="date_fin" class="form-label">Date de fin (optionnel)</label>
                            <input type="date" 
                                   class="form-control <?php $__errorArgs = ['date_fin'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                   id="date_fin" 
                                   name="date_fin" 
                                   value="<?php echo e(old('date_fin', $loyer->date_fin?->format('Y-m-d'))); ?>">
                            <?php $__errorArgs = ['date_fin'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            <small class="form-text text-muted">Laisser vide pour un contrat à durée indéterminée</small>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="montant" class="form-label">Montant du loyer ($) <span class="text-danger">*</span></label>
                            <input type="number" 
                                   class="form-control <?php $__errorArgs = ['montant'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                   id="montant" 
                                   name="montant" 
                                   value="<?php echo e(old('montant', $loyer->montant)); ?>" 
                                   min="0" 
                                   step="0.01"
                                   required>
                            <?php $__errorArgs = ['montant'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="garantie_locative" class="form-label">Garantie locative ($)</label>
                            <input type="number" 
                                   class="form-control <?php $__errorArgs = ['garantie_locative'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                   id="garantie_locative" 
                                   name="garantie_locative" 
                                   value="<?php echo e(old('garantie_locative', $loyer->garantie_locative)); ?>" 
                                   min="0" 
                                   step="0.01">
                            <?php $__errorArgs = ['garantie_locative'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="statut" class="form-label">Statut du contrat <span class="text-danger">*</span></label>
                        <select class="form-select <?php $__errorArgs = ['statut'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                id="statut" 
                                name="statut" 
                                required>
                            <option value="actif" <?php echo e(old('statut', $loyer->statut) === 'actif' ? 'selected' : ''); ?>>
                                Actif
                            </option>
                            <option value="inactif" <?php echo e(old('statut', $loyer->statut) === 'inactif' ? 'selected' : ''); ?>>
                                Inactif
                            </option>
                        </select>
                        <?php $__errorArgs = ['statut'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="mb-3">
                        <label for="notes" class="form-label">Notes / Conditions particulières</label>
                        <textarea class="form-control <?php $__errorArgs = ['notes'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                  id="notes" 
                                  name="notes" 
                                  rows="3" 
                                  placeholder="Conditions spéciales, remarques..."><?php echo e(old('notes', $loyer->notes)); ?></textarea>
                        <?php $__errorArgs = ['notes'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="<?php echo e(route('loyers.index')); ?>" class="btn btn-secondary">Annuler</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Mettre à jour le contrat
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Informations du contrat</h5>
                <div class="alert alert-info">
                    <h6><i class="fas fa-info-circle"></i> Contrat #<?php echo e($loyer->id); ?></h6>
                    <ul class="mb-0">
                        <li><strong>Créé le :</strong> <?php echo e($loyer->created_at->format('d/m/Y à H:i')); ?></li>
                        <li><strong>Dernière modification :</strong> <?php echo e($loyer->updated_at->format('d/m/Y à H:i')); ?></li>
                        <li><strong>Statut actuel :</strong> 
                            <?php if($loyer->statut === 'actif'): ?>
                                <span class="badge bg-success">Actif</span>
                            <?php else: ?>
                                <span class="badge bg-secondary">Inactif</span>
                            <?php endif; ?>
                        </li>
                        <?php if($loyer->estEnCours()): ?>
                            <li><strong>Durée :</strong> <?php echo e($loyer->duree); ?></li>
                        <?php endif; ?>
                    </ul>
                </div>
                
                <div class="mt-3">
                    <h6>Actions disponibles</h6>
                    <div class="d-grid gap-2">
                        <a href="<?php echo e(route('loyers.show', $loyer)); ?>" class="btn btn-outline-info btn-sm">
                            <i class="fas fa-eye"></i> Voir les détails
                        </a>
                        <?php if($loyer->statut === 'actif'): ?>
                            <form action="<?php echo e(route('loyers.desactiver', $loyer)); ?>" method="POST">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('PATCH'); ?>
                                <button type="submit" class="btn btn-outline-danger btn-sm w-100" 
                                        onclick="return confirm('Êtes-vous sûr de vouloir désactiver ce contrat ?')">
                                    <i class="fas fa-times-circle"></i> Désactiver le contrat
                                </button>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Calculer la durée automatiquement
    const dateDebutInput = document.getElementById('date_debut');
    const dateFinInput = document.getElementById('date_fin');
    
    function calculateDuration() {
        if (dateDebutInput.value && dateFinInput.value) {
            const dateDebut = new Date(dateDebutInput.value);
            const dateFin = new Date(dateFinInput.value);
            
            if (dateFin > dateDebut) {
                const diffTime = Math.abs(dateFin - dateDebut);
                const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
                const diffMonths = Math.round(diffDays / 30);
                
                // Afficher la durée quelque part si nécessaire
                console.log(`Durée: ${diffMonths} mois environ`);
            }
        }
    }
    
    dateDebutInput.addEventListener('change', calculateDuration);
    dateFinInput.addEventListener('change', calculateDuration);
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\immo\resources\views/loyers/edit.blade.php ENDPATH**/ ?>