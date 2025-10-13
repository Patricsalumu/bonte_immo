

<?php $__env->startSection('title', 'Créer un Compte Financier'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="bi bi-plus-circle"></i> Créer un Nouveau Compte Financier
                    </h4>
                    <a href="<?php echo e(route('caisse.index')); ?>" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left"></i> Retour
                    </a>
                </div>

                <div class="card-body">
                    <?php if($errors->any()): ?>
                        <div class="alert alert-danger">
                            <h6><i class="bi bi-exclamation-triangle"></i> Erreurs de validation :</h6>
                            <ul class="mb-0">
                                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <li><?php echo e($error); ?></li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <form action="<?php echo e(route('comptes-financiers.store')); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        
                        <div class="row">
                            <!-- Informations de base -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="nom" class="form-label">
                                        <i class="bi bi-tag"></i> Nom du Compte <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" 
                                           class="form-control <?php $__errorArgs = ['nom_compte'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                           id="nom_compte" 
                                           name="nom_compte" 
                                           value="<?php echo e(old('nom_compte')); ?>"
                                           placeholder="Ex: Caisse Principale, Banque BCB, Charges Locatives"
                                           required>
                                    <?php $__errorArgs = ['nom_compte'];
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

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="type" class="form-label">
                                        <i class="bi bi-bookmark"></i> Type de Compte <span class="text-danger">*</span>
                                    </label>
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
                                        <option value="">Sélectionner le type...</option>
                                        <option value="caisse" <?php echo e(old('type') == 'caisse' ? 'selected' : ''); ?>>
                                            💰 Caisse (Liquidités)
                                        </option>
                                        <option value="banque" <?php echo e(old('type') == 'banque' ? 'selected' : ''); ?>>
                                            🏦 Banque (Compte bancaire)
                                        </option>
                                        <option value="charge" <?php echo e(old('type') == 'charge' ? 'selected' : ''); ?>>
                                            📋 Charge (Compte de dépenses)
                                        </option>
                                        <option value="epargne" <?php echo e(old('type') == 'epargne' ? 'selected' : ''); ?>>
                                            🔒 Épargne (Compte d'épargne)
                                        </option>
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
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="solde_initial" class="form-label">
                                        <i class="bi bi-cash"></i> Solde Initial (FC)
                                    </label>
                                    <input type="number" 
                                           class="form-control <?php $__errorArgs = ['solde_initial'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                           id="solde_initial" 
                                           name="solde_initial" 
                                           value="<?php echo e(old('solde_initial', 0)); ?>"
                                           min="0"
                                           step="0.01"
                                           placeholder="0.00">
                                    <?php $__errorArgs = ['solde_initial'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    <div class="form-text">Le solde de départ du compte</div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="gestionnaire_id" class="form-label">
                                        <i class="bi bi-person"></i> Gestionnaire Associé
                                    </label>
                                    <select class="form-select <?php $__errorArgs = ['gestionnaire_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                            id="gestionnaire_id" 
                                            name="gestionnaire_id">
                                        <option value="">Aucun gestionnaire spécifique</option>
                                        <?php $__currentLoopData = $utilisateurs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $utilisateur): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($utilisateur->id); ?>" 
                                                    <?php echo e(old('gestionnaire_id') == $utilisateur->id ? 'selected' : ''); ?>>
                                                <?php echo e($utilisateur->nom); ?> <?php echo e($utilisateur->prenom); ?>

                                                <small>(<?php echo e($utilisateur->role); ?>)</small>
                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                    <?php $__errorArgs = ['gestionnaire_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    <div class="form-text">Utilisateur responsable de ce compte</div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="description" class="form-label">
                                        <i class="bi bi-card-text"></i> Description
                                    </label>
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
                                              placeholder="Description optionnelle du compte (objectif, utilisation, etc.)"><?php echo e(old('description')); ?></textarea>
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
                            </div>
                        </div>

                        <!-- Paramètres avancés -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" 
                                               type="checkbox" 
                                               id="actif" 
                                               name="actif" 
                                               value="1"
                                               <?php echo e(old('actif', true) ? 'checked' : ''); ?>>
                                        <label class="form-check-label" for="actif">
                                            <i class="bi bi-toggle-on"></i> Compte Actif
                                        </label>
                                        <div class="form-text">Les comptes inactifs ne peuvent pas effectuer de transactions</div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" 
                                               type="checkbox" 
                                               id="autoriser_decouvert" 
                                               name="autoriser_decouvert" 
                                               value="1"
                                               <?php echo e(old('autoriser_decouvert') ? 'checked' : ''); ?>>
                                        <label class="form-check-label" for="autoriser_decouvert">
                                            <i class="bi bi-exclamation-triangle"></i> Autoriser Découvert
                                        </label>
                                        <div class="form-text">Permet d'avoir un solde négatif</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="<?php echo e(route('caisse.index')); ?>" class="btn btn-secondary">
                                <i class="bi bi-x-circle"></i> Annuler
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle"></i> Créer le Compte
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Aide contextuelle -->
            <div class="card mt-4">
                <div class="card-header">
                    <h6 class="mb-0"><i class="bi bi-info-circle"></i> Aide - Types de Comptes</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-primary">💰 Caisse</h6>
                            <p class="small mb-3">Pour gérer les liquidités (espèces, petite caisse)</p>
                            
                            <h6 class="text-success">🏦 Banque</h6>
                            <p class="small mb-3">Pour les comptes bancaires et virements</p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-warning">📋 Charge</h6>
                            <p class="small mb-3">Pour enregistrer les dépenses et charges</p>
                            
                            <h6 class="text-info">🔒 Épargne</h6>
                            <p class="small mb-0">Pour les fonds de réserve et épargne</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const typeSelect = document.getElementById('type');
    const soldeInitialInput = document.getElementById('solde_initial');
    const decouvertCheckbox = document.getElementById('autoriser_decouvert');
    
    // Ajuster les options selon le type de compte
    typeSelect.addEventListener('change', function() {
        const type = this.value;
        
        if (type === 'charge') {
            soldeInitialInput.value = 0;
            soldeInitialInput.readOnly = true;
            decouvertCheckbox.checked = false;
            decouvertCheckbox.disabled = true;
        } else {
            soldeInitialInput.readOnly = false;
            decouvertCheckbox.disabled = false;
        }
    });
    
    // Trigger initial state
    if (typeSelect.value) {
        typeSelect.dispatchEvent(new Event('change'));
    }
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\immo\resources\views/comptes-financiers/create.blade.php ENDPATH**/ ?>