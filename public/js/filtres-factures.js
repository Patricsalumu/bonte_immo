// Système de filtrage et recherche pour les factures
document.addEventListener('DOMContentLoaded', function() {
    console.log("🚀 Initialisation du système de filtrage...");
    
    // Vérifier que les éléments existent
    const filtreStatut = document.getElementById('filtreStatut');
    const rechercheFacture = document.getElementById('rechercheFacture');
    const btnClearSearch = document.getElementById('btnClearSearch');
    
    if (!filtreStatut || !rechercheFacture || !btnClearSearch) {
        console.error("❌ Éléments manquants:", {
            filtreStatut: !!filtreStatut,
            rechercheFacture: !!rechercheFacture,
            btnClearSearch: !!btnClearSearch
        });
        return;
    }
    
    console.log("✅ Tous les éléments trouvés");
    
    // Fonction de filtrage principale
    function filtrerFactures() {
        console.log("🔍 Début du filtrage...");
        
        const filtreStatutValue = filtreStatut.value;
        const rechercheText = rechercheFacture.value.toLowerCase().trim();
        
        console.log("Critères:", { statut: filtreStatutValue, recherche: rechercheText });
        
        const lignes = document.querySelectorAll('tbody tr:not(#messageNoResult)');
        console.log("Lignes trouvées:", lignes.length);
        
        let compteurVisible = 0;
        let statNonPayees = 0, statEnRetard = 0, statPayees = 0;
        let statMontantTotal = 0, statMontantPaye = 0, statMontantNonPaye = 0;
        
        lignes.forEach((ligne, index) => {
            let afficher = true;
            
            // Déterminer le statut de la ligne
            const classes = ligne.className || '';
            const isEnRetard = classes.includes('table-danger');
            const isPayee = classes.includes('table-success');
            const isNonPayee = !isEnRetard && !isPayee;
            
            // Appliquer le filtre par statut
            if (filtreStatutValue) {
                switch(filtreStatutValue) {
                    case 'en_retard':
                        if (!isEnRetard) afficher = false;
                        break;
                    case 'paye':
                        if (!isPayee) afficher = false;
                        break;
                    case 'non_paye':
                        if (!isNonPayee) afficher = false;
                        break;
                }
            }
            
            // Appliquer la recherche textuelle
            if (rechercheText && afficher) {
                const texte = ligne.textContent.toLowerCase();
                if (!texte.includes(rechercheText)) {
                    afficher = false;
                }
            }
            
            // Appliquer l'affichage
            ligne.style.display = afficher ? '' : 'none';
            
            // Calculer les statistiques
            if (afficher) {
                compteurVisible++;
                
                // Extraire le montant
                const celluleMontant = ligne.querySelector('td:nth-child(5)');
                const montantText = celluleMontant ? celluleMontant.textContent.replace(/[^0-9]/g, '') : '0';
                const montant = parseInt(montantText) || 0;
                
                statMontantTotal += montant;
                
                if (isPayee) {
                    statPayees++;
                    statMontantPaye += montant;
                } else if (isEnRetard) {
                    statEnRetard++;
                    statMontantNonPaye += montant;
                } else {
                    statNonPayees++;
                    statMontantNonPaye += montant;
                }
            }
        });
        
        // Mettre à jour les statistiques
        const mettreAJourStat = (id, valeur) => {
            const element = document.getElementById(id);
            if (element) element.textContent = valeur;
        };
        
        mettreAJourStat('stat-non-payees', statNonPayees);
        mettreAJourStat('stat-en-retard', statEnRetard);
        mettreAJourStat('stat-payees', statPayees);
        mettreAJourStat('stat-montant-total', new Intl.NumberFormat('fr-FR').format(statMontantTotal) + ' $');
        mettreAJourStat('stat-montant-paye', new Intl.NumberFormat('fr-FR').format(statMontantPaye) + ' $');
        mettreAJourStat('stat-montant-non-paye', new Intl.NumberFormat('fr-FR').format(statMontantNonPaye) + ' $');
        
        // Gérer le message "aucun résultat"
        let messageNoResult = document.getElementById('messageNoResult');
        if (compteurVisible === 0) {
            if (!messageNoResult) {
                messageNoResult = document.createElement('tr');
                messageNoResult.id = 'messageNoResult';
                messageNoResult.innerHTML = '<td colspan="7" class="text-center py-4"><i class="fas fa-search fa-2x text-muted mb-2"></i><br>Aucune facture ne correspond à vos critères de recherche.</td>';
                document.querySelector('tbody').appendChild(messageNoResult);
            }
        } else if (messageNoResult) {
            messageNoResult.remove();
        }
        
        console.log("✅ Filtrage terminé:", { visible: compteurVisible, stats: { statNonPayees, statEnRetard, statPayees } });
    }
    
    // Attacher les event listeners
    filtreStatut.addEventListener('change', function() {
        console.log("📝 Changement de filtre statut:", this.value);
        filtrerFactures();
    });
    
    rechercheFacture.addEventListener('input', function() {
        console.log("📝 Changement de recherche:", this.value);
        filtrerFactures();
    });
    
    btnClearSearch.addEventListener('click', function() {
        console.log("🧹 Nettoyage des filtres");
        filtreStatut.value = '';
        rechercheFacture.value = '';
        filtrerFactures();
    });
    
    // Fonctions globales pour le debug
    window.debugFiltres = {
        test: filtrerFactures,
        reset: () => {
            filtreStatut.value = '';
            rechercheFacture.value = '';
            filtrerFactures();
        },
        info: () => {
            console.log("Informations debug:", {
                filtreStatut: filtreStatut.value,
                rechercheFacture: rechercheFacture.value,
                lignes: document.querySelectorAll('tbody tr:not(#messageNoResult)').length
            });
        }
    };
    
    console.log("🎉 Système de filtrage initialisé avec succès!");
    console.log("💡 Utilisez 'debugFiltres.test()' pour tester le filtrage");
});