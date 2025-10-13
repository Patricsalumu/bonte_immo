

<?php $__env->startSection('title', 'Créer un Contrat de Loyer'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">Créer un Contrat de Loyer</h1>
    <a href="<?php echo e(route('loyers.index')); ?>" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Retour
    </a>
</div>

<?php if(count($appartements) == 0): ?>
    <div class="alert alert-warning">
        <h5><i class="fas fa-exclamation-triangle"></i> Aucun appartement disponible</h5>
        <p>Tous les appartements ont déjà un contrat actif. Vous devez d'abord libérer un appartement pour créer un nouveau contrat.</p>
        <a href="<?php echo e(route('appartements.index')); ?>" class="btn btn-primary">Voir les appartements</a>
    </div>
<?php elseif(count($locataires) == 0): ?>
    <div class="alert alert-warning">
        <h5><i class="fas fa-exclamation-triangle"></i> Aucun locataire disponible</h5>
        <p>Tous les locataires ont déjà un contrat actif. Vous devez d'abord ajouter un nouveau locataire ou libérer un contrat existant.</p>
        <a href="<?php echo e(route('locataires.create')); ?>" class="btn btn-primary">Ajouter un locataire</a>
    </div>
<?php else: ?>
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <form method="POST" action="<?php echo e(route('loyers.store')); ?>">
                    <?php echo csrf_field(); ?>

                    <h5 class="mb-3">Informations du contrat</h5>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="locataire_id" class="form-label">Locataire disponible <span class="text-danger">*</span></label>
                            <select class="form-select <?php $__errorArgs = ['locataire_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                    id="locataire_id" 
                                    name="locataire_id" 
                                    required>
                                <option value="">Sélectionner un locataire</option>
                                <?php $__currentLoopData = $locataires; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $locataire): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($locataire->id); ?>" <?php echo e(old('locataire_id') == $locataire->id ? 'selected' : ''); ?>>
                                        <?php echo e($locataire->nom); ?> <?php echo e($locataire->prenom); ?> - <?php echo e($locataire->telephone); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <?php $__errorArgs = ['locataire_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            <small class="form-text text-muted">Seuls les locataires sans contrat actif sont affichés</small>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="appartement_id" class="form-label">Appartement disponible <span class="text-danger">*</span></label>
                            <select class="form-select <?php $__errorArgs = ['appartement_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                    id="appartement_id" 
                                    name="appartement_id" 
                                    required>
                                <option value="">Sélectionner un appartement</option>
                                <?php $__currentLoopData = $appartements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $appartement): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($appartement->id); ?>" 
                                            data-loyer="<?php echo e($appartement->loyer_mensuel); ?>"
                                            data-garantie="<?php echo e($appartement->garantie_locative); ?>"
                                            <?php echo e(old('appartement_id') == $appartement->id ? 'selected' : ''); ?>>
                                        <?php echo e($appartement->immeuble->nom); ?> - Apt <?php echo e($appartement->numero); ?> (<?php echo e($appartement->type); ?>)
                                        - <?php echo e(number_format($appartement->loyer_mensuel, 0, ',', ' ')); ?> $/mois
                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <?php $__errorArgs = ['appartement_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            <small class="form-text text-muted">Seuls les appartements sans contrat actif sont affichés</small>
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
                                   value="<?php echo e(old('date_debut', date('Y-m-d'))); ?>" 
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
                                   value="<?php echo e(old('date_fin')); ?>">
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
                                   value="<?php echo e(old('montant')); ?>" 
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
                                   value="<?php echo e(old('garantie_locative')); ?>" 
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
                                  placeholder="Conditions spéciales, remarques..."><?php echo e(old('notes')); ?></textarea>
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
                            <i class="fas fa-save"></i> Créer le contrat
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Informations</h5>
                <div class="alert alert-info">
                    <h6><i class="fas fa-info-circle"></i> Contrat de loyer</h6>
                    <ul class="mb-0">
                        <li>Seuls les appartements et locataires <strong>disponibles</strong> sont affichés</li>
                        <li>La date de fin est optionnelle (contrat à durée indéterminée)</li>
                        <li>La garantie locative peut être saisie séparément</li>
                        <li>Le contrat sera automatiquement marqué comme <strong>actif</strong></li>
                    </ul>
                </div>
                
                <div id="apartment-details" class="mt-3" style="display: none;">
                    <h6>Détails de l'appartement</h6>
                    <div id="apartment-info"></div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const appartementSelect = document.getElementById('appartement_id');
    const montantInput = document.getElementById('montant');
    const garantieInput = document.getElementById('garantie_locative');
    const apartmentDetails = document.getElementById('apartment-details');
    const apartmentInfo = document.getElementById('apartment-info');

    if (appartementSelect) {
        appartementSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            if (selectedOption.value) {
                const loyer = selectedOption.dataset.loyer;
                const garantie = selectedOption.dataset.garantie;
                
                if (loyer) {
                    montantInput.value = loyer;
                }
                if (garantie) {
                    garantieInput.value = garantie;
                }
                
                apartmentInfo.innerHTML = `
                    <p><strong>Loyer suggéré:</strong> ${Number(loyer).toLocaleString()} $</p>
                    <p><strong>Garantie suggérée:</strong> ${Number(garantie).toLocaleString()} $</p>
                `;
                apartmentDetails.style.display = 'block';
            } else {
                apartmentDetails.style.display = 'none';
                montantInput.value = '';
                garantieInput.value = '';
            }
        });
    }
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\immo\resources\views/loyers/create.blade.php ENDPATH**/ ?>