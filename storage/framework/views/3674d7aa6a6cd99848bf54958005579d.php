

<?php $__env->startSection('title', 'Créer un Contrat de Loyer'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">Créer un Contrat de Loyer</h1>
    <a href="<?php echo e(route('loyers.index')); ?>" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Retour
    </a>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <form method="POST" action="<?php echo e(route('loyers.store')); ?>">
                    <?php echo csrf_field(); ?>

                    <h5 class="mb-3">Informations du contrat</h5>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="locataire_id" class="form-label">Locataire <span class="text-danger">*</span></label>
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
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="appartement_id" class="form-label">Appartement <span class="text-danger">*</span></label>
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
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
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

                        <div class="col-md-4 mb-3">
                            <label for="date_fin" class="form-label">Date de fin</label>
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
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="duree_mois" class="form-label">Durée (mois)</label>
                            <input type="number" 
                                   class="form-control <?php $__errorArgs = ['duree_mois'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                   id="duree_mois" 
                                   name="duree_mois" 
                                   value="<?php echo e(old('duree_mois', 12)); ?>" 
                                   min="1">
                            <?php $__errorArgs = ['duree_mois'];
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

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="montant_loyer" class="form-label">Montant du loyer (CDF) <span class="text-danger">*</span></label>
                            <input type="number" 
                                   class="form-control <?php $__errorArgs = ['montant_loyer'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                   id="montant_loyer" 
                                   name="montant_loyer" 
                                   value="<?php echo e(old('montant_loyer')); ?>" 
                                   min="0" 
                                   required>
                            <?php $__errorArgs = ['montant_loyer'];
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

                        <div class="col-md-4 mb-3">
                            <label for="garantie_versee" class="form-label">Garantie versée (CDF)</label>
                            <input type="number" 
                                   class="form-control <?php $__errorArgs = ['garantie_versee'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                   id="garantie_versee" 
                                   name="garantie_versee" 
                                   value="<?php echo e(old('garantie_versee')); ?>" 
                                   min="0">
                            <?php $__errorArgs = ['garantie_versee'];
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

                        <div class="col-md-4 mb-3">
                            <label for="jour_echeance" class="form-label">Jour d'échéance</label>
                            <select class="form-select <?php $__errorArgs = ['jour_echeance'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                    id="jour_echeance" 
                                    name="jour_echeance">
                                <?php for($i = 1; $i <= 31; $i++): ?>
                                    <option value="<?php echo e($i); ?>" <?php echo e(old('jour_echeance', 1) == $i ? 'selected' : ''); ?>>
                                        <?php echo e($i); ?>

                                    </option>
                                <?php endfor; ?>
                            </select>
                            <?php $__errorArgs = ['jour_echeance'];
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
                        <label for="conditions_particulieres" class="form-label">Conditions particulières</label>
                        <textarea class="form-control <?php $__errorArgs = ['conditions_particulieres'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                  id="conditions_particulieres" 
                                  name="conditions_particulieres" 
                                  rows="3"><?php echo e(old('conditions_particulieres')); ?></textarea>
                        <?php $__errorArgs = ['conditions_particulieres'];
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

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="form-check">
                                <input class="form-check-input" 
                                       type="checkbox" 
                                       id="charges_incluses" 
                                       name="charges_incluses" 
                                       value="1" 
                                       <?php echo e(old('charges_incluses') ? 'checked' : ''); ?>>
                                <label class="form-check-label" for="charges_incluses">
                                    Charges incluses dans le loyer
                                </label>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <div class="form-check">
                                <input class="form-check-input" 
                                       type="checkbox" 
                                       id="actif" 
                                       name="actif" 
                                       value="1" 
                                       <?php echo e(old('actif', true) ? 'checked' : ''); ?>>
                                <label class="form-check-label" for="actif">
                                    Contrat actif
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
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
        <div class="card bg-light">
            <div class="card-body">
                <h6 class="card-title">
                    <i class="fas fa-info-circle"></i> Informations
                </h6>
                <ul class="small text-muted mb-0">
                    <li>Les champs marqués d'un <span class="text-danger">*</span> sont obligatoires</li>
                    <li>Seuls les appartements disponibles sont proposés</li>
                    <li>Le montant du loyer se remplit automatiquement</li>
                    <li>La garantie recommandée est de 2-3 mois de loyer</li>
                    <li>Le jour d'échéance détermine la date de paiement mensuel</li>
                </ul>
            </div>
        </div>

        <div class="card bg-info text-white mt-3">
            <div class="card-body">
                <h6 class="card-title">
                    <i class="fas fa-lightbulb"></i> Suggestion
                </h6>
                <p class="small mb-0">
                    Une fois le contrat créé, les factures de loyer seront générées automatiquement selon la périodicité définie.
                </p>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('appartement_id').addEventListener('change', function() {
    const selectedOption = this.options[this.selectedIndex];
    const loyerMensuel = selectedOption.getAttribute('data-loyer');
    const garantie = selectedOption.getAttribute('data-garantie');
    
    if (loyerMensuel) {
        document.getElementById('montant_loyer').value = loyerMensuel;
    }
    if (garantie) {
        document.getElementById('garantie_versee').value = garantie;
    }
});

document.getElementById('duree_mois').addEventListener('change', function() {
    const dateDebut = document.getElementById('date_debut').value;
    if (dateDebut && this.value) {
        const debut = new Date(dateDebut);
        debut.setMonth(debut.getMonth() + parseInt(this.value));
        document.getElementById('date_fin').value = debut.toISOString().split('T')[0];
    }
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\immo\resources\views/loyers/create.blade.php ENDPATH**/ ?>