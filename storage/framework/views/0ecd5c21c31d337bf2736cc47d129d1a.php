

<?php $__env->startSection('title', 'Modifier l\'Immeuble'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">Modifier l'Immeuble</h1>
    <div>
        <a href="<?php echo e(route('immeubles.show', $immeuble)); ?>" class="btn btn-info">
            <i class="fas fa-eye"></i> Voir
        </a>
        <a href="<?php echo e(route('immeubles.index')); ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Retour
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <form method="POST" action="<?php echo e(route('immeubles.update', $immeuble)); ?>">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>

                    <h5 class="mb-3">Informations de base</h5>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="nom" class="form-label">Nom de l'immeuble <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control <?php $__errorArgs = ['nom'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                   id="nom" 
                                   name="nom" 
                                   value="<?php echo e(old('nom', $immeuble->nom)); ?>" 
                                   required>
                            <?php $__errorArgs = ['nom'];
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
                            <label for="nombre_etages" class="form-label">Nombre d'étages <span class="text-danger">*</span></label>
                            <input type="number" 
                                   class="form-control <?php $__errorArgs = ['nombre_etages'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                   id="nombre_etages" 
                                   name="nombre_etages" 
                                   value="<?php echo e(old('nombre_etages', $immeuble->nombre_etages)); ?>" 
                                   min="1" 
                                   required>
                            <?php $__errorArgs = ['nombre_etages'];
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
                        <label for="adresse" class="form-label">Adresse complète <span class="text-danger">*</span></label>
                        <textarea class="form-control <?php $__errorArgs = ['adresse'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                  id="adresse" 
                                  name="adresse" 
                                  rows="2" 
                                  required><?php echo e(old('adresse', $immeuble->adresse)); ?></textarea>
                        <?php $__errorArgs = ['adresse'];
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
                            <label for="quartier" class="form-label">Quartier <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control <?php $__errorArgs = ['quartier'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                   id="quartier" 
                                   name="quartier" 
                                   value="<?php echo e(old('quartier', $immeuble->quartier)); ?>" 
                                   required>
                            <?php $__errorArgs = ['quartier'];
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
                            <label for="commune" class="form-label">Commune <span class="text-danger">*</span></label>
                            <select class="form-select <?php $__errorArgs = ['commune'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                    id="commune" 
                                    name="commune" 
                                    required>
                                <option value="">Sélectionner une commune</option>
                                <option value="Bandalungwa" <?php echo e(old('commune', $immeuble->commune) == 'Bandalungwa' ? 'selected' : ''); ?>>Bandalungwa</option>
                                <option value="Barumbu" <?php echo e(old('commune', $immeuble->commune) == 'Barumbu' ? 'selected' : ''); ?>>Barumbu</option>
                                <option value="Bumbu" <?php echo e(old('commune', $immeuble->commune) == 'Bumbu' ? 'selected' : ''); ?>>Bumbu</option>
                                <option value="Gombe" <?php echo e(old('commune', $immeuble->commune) == 'Gombe' ? 'selected' : ''); ?>>Gombe</option>
                                <option value="Kalamu" <?php echo e(old('commune', $immeuble->commune) == 'Kalamu' ? 'selected' : ''); ?>>Kalamu</option>
                                <option value="Kasa-Vubu" <?php echo e(old('commune', $immeuble->commune) == 'Kasa-Vubu' ? 'selected' : ''); ?>>Kasa-Vubu</option>
                                <option value="Kimbanseke" <?php echo e(old('commune', $immeuble->commune) == 'Kimbanseke' ? 'selected' : ''); ?>>Kimbanseke</option>
                                <option value="Kinshasa" <?php echo e(old('commune', $immeuble->commune) == 'Kinshasa' ? 'selected' : ''); ?>>Kinshasa</option>
                                <option value="Kintambo" <?php echo e(old('commune', $immeuble->commune) == 'Kintambo' ? 'selected' : ''); ?>>Kintambo</option>
                                <option value="Lemba" <?php echo e(old('commune', $immeuble->commune) == 'Lemba' ? 'selected' : ''); ?>>Lemba</option>
                                <option value="Limete" <?php echo e(old('commune', $immeuble->commune) == 'Limete' ? 'selected' : ''); ?>>Limete</option>
                                <option value="Lingwala" <?php echo e(old('commune', $immeuble->commune) == 'Lingwala' ? 'selected' : ''); ?>>Lingwala</option>
                                <option value="Makala" <?php echo e(old('commune', $immeuble->commune) == 'Makala' ? 'selected' : ''); ?>>Makala</option>
                                <option value="Maluku" <?php echo e(old('commune', $immeuble->commune) == 'Maluku' ? 'selected' : ''); ?>>Maluku</option>
                                <option value="Masina" <?php echo e(old('commune', $immeuble->commune) == 'Masina' ? 'selected' : ''); ?>>Masina</option>
                                <option value="Matete" <?php echo e(old('commune', $immeuble->commune) == 'Matete' ? 'selected' : ''); ?>>Matete</option>
                                <option value="Mont-Ngafula" <?php echo e(old('commune', $immeuble->commune) == 'Mont-Ngafula' ? 'selected' : ''); ?>>Mont-Ngafula</option>
                                <option value="Ndjili" <?php echo e(old('commune', $immeuble->commune) == 'Ndjili' ? 'selected' : ''); ?>>Ndjili</option>
                                <option value="Ngaba" <?php echo e(old('commune', $immeuble->commune) == 'Ngaba' ? 'selected' : ''); ?>>Ngaba</option>
                                <option value="Ngaliema" <?php echo e(old('commune', $immeuble->commune) == 'Ngaliema' ? 'selected' : ''); ?>>Ngaliema</option>
                                <option value="Ngiri-Ngiri" <?php echo e(old('commune', $immeuble->commune) == 'Ngiri-Ngiri' ? 'selected' : ''); ?>>Ngiri-Ngiri</option>
                                <option value="Nsele" <?php echo e(old('commune', $immeuble->commune) == 'Nsele' ? 'selected' : ''); ?>>Nsele</option>
                                <option value="Selembao" <?php echo e(old('commune', $immeuble->commune) == 'Selembao' ? 'selected' : ''); ?>>Selembao</option>
                            </select>
                            <?php $__errorArgs = ['commune'];
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
                                  placeholder="Description de l'immeuble, équipements, etc."><?php echo e(old('description', $immeuble->description)); ?></textarea>
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

                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" 
                                   type="checkbox" 
                                   id="actif" 
                                   name="actif" 
                                   value="1" 
                                   <?php echo e(old('actif', $immeuble->actif) ? 'checked' : ''); ?>>
                            <label class="form-check-label" for="actif">
                                Immeuble actif
                            </label>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="<?php echo e(route('immeubles.show', $immeuble)); ?>" class="btn btn-secondary">Annuler</a>
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
                    <li>Le nom de l'immeuble doit être unique</li>
                    <li>Vous pouvez désactiver temporairement un immeuble</li>
                    <li>La modification affectera tous les appartements liés</li>
                </ul>
            </div>
        </div>

        <div class="card bg-warning text-dark mt-3">
            <div class="card-body">
                <h6 class="card-title">
                    <i class="fas fa-exclamation-triangle"></i> Attention
                </h6>
                <p class="small mb-0">
                    Désactiver un immeuble n'affectera pas les contrats en cours, mais empêchera la création de nouveaux contrats.
                </p>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\immo\resources\views/immeubles/edit.blade.php ENDPATH**/ ?>