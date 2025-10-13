

<?php $__env->startSection('title', 'Modifier l\'Appartement'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">Modifier l'Appartement</h1>
    <div>
        <a href="<?php echo e(route('appartements.show', $appartement)); ?>" class="btn btn-info">
            <i class="fas fa-eye"></i> Voir
        </a>
        <a href="<?php echo e(route('appartements.index')); ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Retour
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <form method="POST" action="<?php echo e(route('appartements.update', $appartement)); ?>">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>

                    <h5 class="mb-3">Informations de base</h5>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="immeuble_id" class="form-label">Immeuble <span class="text-danger">*</span></label>
                            <select class="form-select <?php $__errorArgs = ['immeuble_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                    id="immeuble_id" 
                                    name="immeuble_id" 
                                    required>
                                <option value="">Sélectionner un immeuble</option>
                                <?php $__currentLoopData = $immeubles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $immeuble): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($immeuble->id); ?>" 
                                            <?php echo e(old('immeuble_id', $appartement->immeuble_id) == $immeuble->id ? 'selected' : ''); ?>>
                                        <?php echo e($immeuble->nom); ?> (<?php echo e($immeuble->commune); ?>)
                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <?php $__errorArgs = ['immeuble_id'];
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
                            <label for="numero" class="form-label">Numéro de l'appartement <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control <?php $__errorArgs = ['numero'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                   id="numero" 
                                   name="numero" 
                                   value="<?php echo e(old('numero', $appartement->numero)); ?>" 
                                   required>
                            <?php $__errorArgs = ['numero'];
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
                        <div class="col-md-6 mb-3">
                            <label for="type" class="form-label">Type d'appartement <span class="text-danger">*</span></label>
                            <select class="form-select <?php $__errorArgs = ['type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                    id="type" 
                                    name="type" 
                                    required>
                                <option value="">Sélectionner un type</option>
                                <option value="studio" <?php echo e(old('type', $appartement->type) == 'studio' ? 'selected' : ''); ?>>Studio</option>
                                <option value="1_chambre" <?php echo e(old('type', $appartement->type) == '1_chambre' ? 'selected' : ''); ?>>1 chambre</option>
                                <option value="2_chambres" <?php echo e(old('type', $appartement->type) == '2_chambres' ? 'selected' : ''); ?>>2 chambres</option>
                                <option value="3_chambres" <?php echo e(old('type', $appartement->type) == '3_chambres' ? 'selected' : ''); ?>>3 chambres</option>
                                <option value="4_chambres_plus" <?php echo e(old('type', $appartement->type) == '4_chambres_plus' ? 'selected' : ''); ?>>4 chambres et plus</option>
                                <option value="duplex" <?php echo e(old('type', $appartement->type) == 'duplex' ? 'selected' : ''); ?>>Duplex</option>
                            </select>
                            <?php $__errorArgs = ['type'];
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
                            <label for="superficie" class="form-label">Superficie (m²) <span class="text-danger">*</span></label>
                            <input type="number" 
                                   class="form-control <?php $__errorArgs = ['superficie'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                   id="superficie" 
                                   name="superficie" 
                                   value="<?php echo e(old('superficie', $appartement->superficie)); ?>" 
                                   min="10" 
                                   step="0.1" 
                                   required>
                            <?php $__errorArgs = ['superficie'];
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

                    <h5 class="mb-3 mt-4">Informations financières</h5>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="loyer_mensuel" class="form-label">Loyer mensuel ($) <span class="text-danger">*</span></label>
                            <input type="number" 
                                   class="form-control <?php $__errorArgs = ['loyer_mensuel'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                   id="loyer_mensuel" 
                                   name="loyer_mensuel" 
                                   value="<?php echo e(old('loyer_mensuel', $appartement->loyer_mensuel)); ?>" 
                                   min="0" 
                                   required>
                            <?php $__errorArgs = ['loyer_mensuel'];
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
                            <label for="garantie_locative" class="form-label">Garantie locative ($) <span class="text-danger">*</span></label>
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
                                   value="<?php echo e(old('garantie_locative', $appartement->garantie_locative)); ?>" 
                                   min="0" 
                                   required>
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
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                  id="description" 
                                  name="description" 
                                  rows="3" 
                                  placeholder="Description de l'appartement, équipements, état, etc."><?php echo e(old('description', $appartement->description)); ?></textarea>
                        <?php $__errorArgs = ['description'];
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
                                       id="actif" 
                                       name="actif" 
                                       value="1" 
                                       <?php echo e(old('actif', $appartement->actif) ? 'checked' : ''); ?>>
                                <label class="form-check-label" for="actif">
                                    Appartement actif
                                </label>
                            </div>
                        </div>

                        <?php if($appartement->locataire): ?>
                        <div class="col-md-6 mb-3">
                            <label for="locataire_id" class="form-label">Locataire actuel</label>
                            <select class="form-select <?php $__errorArgs = ['locataire_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                    id="locataire_id" 
                                    name="locataire_id">
                                <option value="">Aucun locataire</option>
                                <?php $__currentLoopData = $locataires; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $locataire): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($locataire->id); ?>" 
                                            <?php echo e(old('locataire_id', $appartement->locataire_id) == $locataire->id ? 'selected' : ''); ?>>
                                        <?php echo e($locataire->nom); ?> <?php echo e($locataire->prenom); ?>

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
                        <?php endif; ?>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="<?php echo e(route('appartements.show', $appartement)); ?>" class="btn btn-secondary">Annuler</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Enregistrer les modifications
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
                    <li>Le numéro doit être unique dans l'immeuble</li>
                    <li>La garantie est généralement de 2-3 mois de loyer</li>
                    <li>Vous pouvez changer le locataire si nécessaire</li>
                </ul>
            </div>
        </div>

        <?php if($appartement->locataire): ?>
        <div class="card bg-warning text-dark mt-3">
            <div class="card-body">
                <h6 class="card-title">
                    <i class="fas fa-exclamation-triangle"></i> Attention
                </h6>
                <p class="small mb-0">
                    Cet appartement est actuellement occupé par <?php echo e($appartement->locataire->nom); ?> <?php echo e($appartement->locataire->prenom); ?>. 
                    Modifier le locataire pourrait affecter les contrats en cours.
                </p>
            </div>
        </div>
        <?php endif; ?>

        <div class="card bg-info text-white mt-3">
            <div class="card-body">
                <h6 class="card-title">
                    <i class="fas fa-calculator"></i> Calculateur
                </h6>
                <div class="mb-2">
                    <label class="form-label small">Loyer/m² :</label>
                    <div id="prix_m2" class="fw-bold">-</div>
                </div>
                <div class="mb-2">
                    <label class="form-label small">Garantie recommandée (2 mois) :</label>
                    <div id="garantie_recommandee" class="fw-bold">-</div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function calculateValues() {
    const loyer = parseFloat(document.getElementById('loyer_mensuel').value) || 0;
    const superficie = parseFloat(document.getElementById('superficie').value) || 0;
    
    // Prix au m²
    if (superficie > 0) {
        const prixM2 = loyer / superficie;
        document.getElementById('prix_m2').textContent = prixM2.toFixed(0) + ' $/m²';
    } else {
        document.getElementById('prix_m2').textContent = '-';
    }
    
    // Garantie recommandée
    const garantieRecommandee = loyer * 2;
    document.getElementById('garantie_recommandee').textContent = garantieRecommandee.toLocaleString() + ' $';
    
    // Suggérer la garantie
    const garantieInput = document.getElementById('garantie_locative');
    if (garantieInput.value == '' || garantieInput.value == '0') {
        garantieInput.value = garantieRecommandee;
    }
}

document.getElementById('loyer_mensuel').addEventListener('input', calculateValues);
document.getElementById('superficie').addEventListener('input', calculateValues);

// Calculer au chargement
document.addEventListener('DOMContentLoaded', calculateValues);
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\immo\resources\views/appartements/edit.blade.php ENDPATH**/ ?>