

<?php $__env->startSection('title', 'Modifier le Locataire'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">Modifier le locataire</h1>
    <a href="<?php echo e(route('locataires.show', $locataire)); ?>" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Retour
    </a>
</div>

<form action="<?php echo e(route('locataires.update', $locataire)); ?>" method="POST">
    <?php echo csrf_field(); ?>
    <?php echo method_field('PUT'); ?>
    
    <div class="row">
        <div class="col-md-8">
            <!-- Informations personnelles -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Informations personnelles</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="nom" class="form-label">Nom <span class="text-danger">*</span></label>
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
                                   value="<?php echo e(old('nom', $locataire->nom)); ?>" 
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
                            <label for="prenom" class="form-label">Prénom <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control <?php $__errorArgs = ['prenom'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                   id="prenom" 
                                   name="prenom" 
                                   value="<?php echo e(old('prenom', $locataire->prenom)); ?>" 
                                   required>
                            <?php $__errorArgs = ['prenom'];
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
                            <label for="date_naissance" class="form-label">Date de naissance</label>
                            <input type="date" 
                                   class="form-control <?php $__errorArgs = ['date_naissance'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                   id="date_naissance" 
                                   name="date_naissance" 
                                   value="<?php echo e(old('date_naissance', $locataire->date_naissance)); ?>">
                            <?php $__errorArgs = ['date_naissance'];
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
                            <label for="numero_carte_identite" class="form-label">Numéro carte d'identité</label>
                            <input type="text" 
                                   class="form-control <?php $__errorArgs = ['numero_carte_identite'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                   id="numero_carte_identite" 
                                   name="numero_carte_identite" 
                                   value="<?php echo e(old('numero_carte_identite', $locataire->numero_carte_identite)); ?>">
                            <?php $__errorArgs = ['numero_carte_identite'];
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
                            <label for="telephone" class="form-label">Téléphone <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control <?php $__errorArgs = ['telephone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                   id="telephone" 
                                   name="telephone" 
                                   value="<?php echo e(old('telephone', $locataire->telephone)); ?>" 
                                   required>
                            <?php $__errorArgs = ['telephone'];
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
                            <label for="email" class="form-label">Email</label>
                            <input type="email" 
                                   class="form-control <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                   id="email" 
                                   name="email" 
                                   value="<?php echo e(old('email', $locataire->email)); ?>">
                            <?php $__errorArgs = ['email'];
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
                        
                        <div class="col-md-12 mb-3">
                            <label for="adresse" class="form-label">Adresse</label>
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
                                      rows="3"><?php echo e(old('adresse', $locataire->adresse)); ?></textarea>
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
                    </div>
                </div>
            </div>

            <!-- Informations professionnelles -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0">Informations professionnelles</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="profession" class="form-label">Profession</label>
                            <input type="text" 
                                   class="form-control <?php $__errorArgs = ['profession'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                   id="profession" 
                                   name="profession" 
                                   value="<?php echo e(old('profession', $locataire->profession)); ?>">
                            <?php $__errorArgs = ['profession'];
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
                            <label for="employeur" class="form-label">Employeur</label>
                            <input type="text" 
                                   class="form-control <?php $__errorArgs = ['employeur'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                   id="employeur" 
                                   name="employeur" 
                                   value="<?php echo e(old('employeur', $locataire->employeur)); ?>">
                            <?php $__errorArgs = ['employeur'];
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
                            <label for="revenu_mensuel" class="form-label">Revenu mensuel (CDF)</label>
                            <input type="number" 
                                   class="form-control <?php $__errorArgs = ['revenu_mensuel'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                   id="revenu_mensuel" 
                                   name="revenu_mensuel" 
                                   value="<?php echo e(old('revenu_mensuel', $locataire->revenu_mensuel)); ?>"
                                   min="0"
                                   step="1000">
                            <?php $__errorArgs = ['revenu_mensuel'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            <div class="form-text">
                                <span id="capacite_paiement_text"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contact d'urgence -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0">Contact d'urgence</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="contact_urgence_nom" class="form-label">Nom du contact</label>
                            <input type="text" 
                                   class="form-control <?php $__errorArgs = ['contact_urgence_nom'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                   id="contact_urgence_nom" 
                                   name="contact_urgence_nom" 
                                   value="<?php echo e(old('contact_urgence_nom', $locataire->contact_urgence_nom)); ?>">
                            <?php $__errorArgs = ['contact_urgence_nom'];
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
                            <label for="contact_urgence_telephone" class="form-label">Téléphone du contact</label>
                            <input type="text" 
                                   class="form-control <?php $__errorArgs = ['contact_urgence_telephone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                   id="contact_urgence_telephone" 
                                   name="contact_urgence_telephone" 
                                   value="<?php echo e(old('contact_urgence_telephone', $locataire->contact_urgence_telephone)); ?>">
                            <?php $__errorArgs = ['contact_urgence_telephone'];
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
            </div>

            <!-- Notes -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0">Notes</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="notes" class="form-label">Notes additionnelles</label>
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
                                  rows="4" 
                                  placeholder="Notes sur le locataire, historique, remarques particulières..."><?php echo e(old('notes', $locataire->notes)); ?></textarea>
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
                </div>
            </div>

            <!-- Boutons d'action -->
            <div class="card mt-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <button type="submit" class="btn btn-primary me-2">
                                <i class="fas fa-save"></i> Enregistrer les modifications
                            </button>
                            <a href="<?php echo e(route('locataires.show', $locataire)); ?>" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Annuler
                            </a>
                        </div>
                        
                        <button type="button" 
                                class="btn btn-danger" 
                                data-bs-toggle="modal" 
                                data-bs-target="#deleteModal">
                            <i class="fas fa-trash"></i> Supprimer
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <!-- Statut et appartement -->
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">Statut et logement</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="actif" class="form-label">Statut</label>
                        <select class="form-select <?php $__errorArgs = ['actif'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                id="actif" 
                                name="actif">
                            <option value="1" <?php echo e(old('actif', $locataire->actif) == 1 ? 'selected' : ''); ?>>Actif</option>
                            <option value="0" <?php echo e(old('actif', $locataire->actif) == 0 ? 'selected' : ''); ?>>Inactif</option>
                        </select>
                        <?php $__errorArgs = ['actif'];
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
                        <label for="appartement_id" class="form-label">Appartement</label>
                        <select class="form-select <?php $__errorArgs = ['appartement_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                id="appartement_id" 
                                name="appartement_id">
                            <option value="">Aucun appartement</option>
                            <?php $__currentLoopData = $appartements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $appartement): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($appartement->id); ?>" 
                                        data-loyer="<?php echo e($appartement->loyer_mensuel); ?>"
                                        <?php echo e(old('appartement_id', $locataire->appartement_id) == $appartement->id ? 'selected' : ''); ?>>
                                    <?php echo e($appartement->immeuble->nom); ?> - Apt <?php echo e($appartement->numero); ?>

                                    (<?php echo e(number_format($appartement->loyer_mensuel, 0, ',', ' ')); ?> CDF)
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
            </div>

            <!-- Evaluation financière -->
            <div class="card mt-3" id="evaluation_card" style="display: none;">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="fas fa-calculator"></i> Évaluation
                    </h6>
                </div>
                <div class="card-body">
                    <div id="evaluation_content">
                        <!-- Contenu généré par JavaScript -->
                    </div>
                </div>
            </div>

            <!-- Résumé actuel -->
            <?php if($locataire->appartement || $locataire->loyers->count() > 0): ?>
            <div class="card mt-3 bg-light">
                <div class="card-header">
                    <h6 class="mb-0">Résumé actuel</h6>
                </div>
                <div class="card-body">
                    <?php if($locataire->appartement): ?>
                    <div class="mb-3">
                        <small class="text-muted">Appartement actuel :</small>
                        <div class="fw-bold"><?php echo e($locataire->appartement->immeuble->nom); ?> - Apt <?php echo e($locataire->appartement->numero); ?></div>
                        <div class="text-success"><?php echo e(number_format($locataire->appartement->loyer_mensuel, 0, ',', ' ')); ?> CDF/mois</div>
                    </div>
                    <?php endif; ?>
                    
                    <?php if($locataire->loyers->count() > 0): ?>
                    <?php
                        $loyersPayes = $locataire->loyers->where('statut', 'paye')->count();
                        $loyersImpayes = $locataire->loyers->where('statut', 'impaye')->count();
                    ?>
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="text-success fw-bold"><?php echo e($loyersPayes); ?></div>
                            <small class="text-muted">Payés</small>
                        </div>
                        <div class="col-6">
                            <div class="text-danger fw-bold"><?php echo e($loyersImpayes); ?></div>
                            <small class="text-muted">Impayés</small>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>

            <!-- Historique des modifications -->
            <div class="card mt-3 bg-light">
                <div class="card-body">
                    <h6 class="card-title">
                        <i class="fas fa-history"></i> Historique
                    </h6>
                    <ul class="small text-muted mb-0">
                        <li><strong>Créé le :</strong> <?php echo e($locataire->created_at->format('d/m/Y H:i')); ?></li>
                        <li><strong>Modifié le :</strong> <?php echo e($locataire->updated_at->format('d/m/Y H:i')); ?></li>
                        <?php if($locataire->loyers): ?>
                        <li><strong>Loyers :</strong> <?php echo e($locataire->loyers->count()); ?> enregistré(s)</li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</form>

<!-- Modal de suppression -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirmer la suppression</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Êtes-vous sûr de vouloir supprimer ce locataire ?</p>
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle"></i>
                    <strong>Attention :</strong> Cette action est irréversible et supprimera également tous les loyers associés.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <form action="<?php echo e(route('locataires.destroy', $locataire)); ?>" method="POST" class="d-inline">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('DELETE'); ?>
                    <button type="submit" class="btn btn-danger">Supprimer définitivement</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const revenuInput = document.getElementById('revenu_mensuel');
    const appartementSelect = document.getElementById('appartement_id');
    const evaluationCard = document.getElementById('evaluation_card');
    const evaluationContent = document.getElementById('evaluation_content');
    const capaciteText = document.getElementById('capacite_paiement_text');

    function updateEvaluation() {
        const revenu = parseFloat(revenuInput.value) || 0;
        const selectedOption = appartementSelect.options[appartementSelect.selectedIndex];
        const loyer = parseFloat(selectedOption.dataset.loyer) || 0;

        // Mise à jour de la capacité de paiement
        if (revenu > 0) {
            const capacite = revenu * 0.33;
            capaciteText.innerHTML = `<i class="fas fa-info-circle"></i> Capacité de paiement recommandée : <strong>${formatNumber(capacite)} CDF</strong>`;
        } else {
            capaciteText.innerHTML = '';
        }

        // Mise à jour de l'évaluation
        if (revenu > 0 && loyer > 0) {
            const ratio = (loyer / revenu) * 100;
            const resteAVivre = revenu - loyer;
            const capacite = revenu * 0.33;
            
            let statusClass = '';
            let statusText = '';
            let statusIcon = '';
            
            if (ratio <= 25) {
                statusClass = 'text-success';
                statusText = 'Excellent';
                statusIcon = 'fas fa-check-circle';
            } else if (ratio <= 33) {
                statusClass = 'text-warning';
                statusText = 'Acceptable';
                statusIcon = 'fas fa-exclamation-circle';
            } else {
                statusClass = 'text-danger';
                statusText = 'Risqué';
                statusIcon = 'fas fa-times-circle';
            }

            evaluationContent.innerHTML = `
                <div class="row text-center mb-3">
                    <div class="col-12">
                        <div class="${statusClass}">
                            <i class="${statusIcon} fa-2x"></i>
                            <div class="fw-bold">${statusText}</div>
                        </div>
                    </div>
                </div>
                <table class="table table-sm">
                    <tr>
                        <td>Ratio d'endettement :</td>
                        <td class="fw-bold ${statusClass}">${ratio.toFixed(1)}%</td>
                    </tr>
                    <tr>
                        <td>Reste à vivre :</td>
                        <td class="fw-bold">${formatNumber(resteAVivre)} CDF</td>
                    </tr>
                    <tr>
                        <td>Capacité théorique :</td>
                        <td class="fw-bold">${formatNumber(capacite)} CDF</td>
                    </tr>
                </table>
                <div class="alert alert-info alert-sm p-2 mb-0">
                    <small>
                        ${ratio <= 33 ? 
                            'Le locataire répond aux critères de solvabilité.' : 
                            'Attention : le ratio d\'endettement dépasse les recommandations (33% max).'
                        }
                    </small>
                </div>
            `;
            
            evaluationCard.style.display = 'block';
        } else {
            evaluationCard.style.display = 'none';
        }
    }

    function formatNumber(num) {
        return new Intl.NumberFormat('fr-FR').format(Math.round(num));
    }

    // Event listeners
    revenuInput.addEventListener('input', updateEvaluation);
    appartementSelect.addEventListener('change', updateEvaluation);

    // Évaluation initiale
    updateEvaluation();
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\immo\resources\views/locataires/edit.blade.php ENDPATH**/ ?>