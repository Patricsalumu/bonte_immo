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
    
    // Helper debounce
    function debounce(fn, wait) {
        let t;
        return function(...args) {
            clearTimeout(t);
            t = setTimeout(() => fn.apply(this, args), wait);
        };
    }

    // Construire les paramètres de la recherche à partir des inputs
    function buildParams(overrides = {}) {
        const params = new URLSearchParams();

        // Read values from the filters form so AJAX mirrors the server-side GET exactly
        const form = document.getElementById('formFiltresFactures');
        if (form) {
            const formData = new FormData(form);
            for (const [key, value] of formData.entries()) {
                if (value !== null && String(value).trim() !== '') {
                    params.append(key, value);
                }
            }
        } else {
            // Fallback to individual elements
            if (filtreStatut.value) params.append('statut', filtreStatut.value);
            if (rechercheFacture.value) params.append('search', rechercheFacture.value);
            const moisEl = document.getElementById('mois');
            const anneeEl = document.getElementById('annee');
            const immeubleEl = document.getElementById('immeuble_id');
            if (moisEl && moisEl.value) params.append('mois', moisEl.value);
            if (anneeEl && anneeEl.value) params.append('annee', anneeEl.value);
            if (immeubleEl && immeubleEl.value) params.append('immeuble_id', immeubleEl.value);
        }

        if (overrides.page) params.set('page', overrides.page);
        params.set('per_page', overrides.per_page || 20);

        // If user enters a substantial search (>=3 chars), ignore mois/annee to search whole DB
        const searchVal = params.get('search') || '';
        if (searchVal && searchVal.length >= 3) {
            if (params.has('mois')) params.delete('mois');
            if (params.has('annee')) params.delete('annee');
        }
        return params;
    }

    // Rebind handlers for dynamic rows (WhatsApp + paiement modal)
    function bindRowHandlers(container) {
        container = container || document;

        // WhatsApp buttons (data-* with onclick previously)
        container.querySelectorAll('[data-telephone]').forEach(btn => {
            // avoid double-binding
            if (btn.__whatsapp_bound) return;
            btn.__whatsapp_bound = true;
            btn.addEventListener('click', function(e) {
                if (typeof window.partagerWhatsAppData === 'function') {
                    window.partagerWhatsAppData(this);
                }
            });
        });

        // Payer buttons: populate modal (simplified copy of inline logic)
        container.querySelectorAll('.btn-open-modal-paiement').forEach(btn => {
            if (btn.__paiement_bound) return;
            btn.__paiement_bound = true;
            btn.addEventListener('click', function(e) {
                const factureId = this.dataset.factureId;
                const numero = this.dataset.numero;
                const montant = parseFloat(this.dataset.montant) || 0;
                const montantRestant = parseFloat(this.dataset.montantRestant) || montant;
                const garantie = parseFloat(this.dataset.garantie) || 0;

                document.getElementById('modalFactureNumero').textContent = numero;
                const form = document.getElementById('formModalPaiement');
                if (form) form.action = '/factures/' + factureId + '/marquer-payee';

                const inputMontant = document.getElementById('modalMontant');
                if (inputMontant) {
                    inputMontant.value = montantRestant.toFixed(2);
                    inputMontant.setAttribute('max', montantRestant);
                    const info = document.getElementById('modalMontantInfo');
                    if (info) info.textContent = 'Montant restant: ' + Number(montantRestant).toLocaleString() + ' $';
                }

                const garantieInfo = document.getElementById('modalGarantieInfo');
                const garantieDisponibleEl = document.getElementById('modalGarantieDisponible');
                if (garantie > 0) {
                    if (garantieInfo) garantieInfo.classList.remove('d-none');
                    if (garantieDisponibleEl) garantieDisponibleEl.textContent = Number(garantie).toLocaleString() + ' $';
                } else {
                    if (garantieInfo) garantieInfo.classList.add('d-none');
                    if (garantieDisponibleEl) garantieDisponibleEl.textContent = '';
                }

                const selectMode = document.getElementById('modalModePaiement');
                if (selectMode) {
                    selectMode.value = '';
                    selectMode.onchange = function() {
                        if (this.value === 'garantie_locative') {
                            const maxVal = Math.min(garantie, montantRestant);
                            if (inputMontant) inputMontant.setAttribute('max', maxVal);
                            if (parseFloat(inputMontant.value) > maxVal) inputMontant.value = maxVal.toFixed(2);
                        } else {
                            if (inputMontant) inputMontant.setAttribute('max', montantRestant);
                        }
                    };
                }
            });
        });
    }

    // Main AJAX request (returns Promise)
    function doFetch(params, updateHistory = true) {
        // allow the view to override the ajax endpoint by adding data-ajax-url on .table-responsive
        function getAjaxUrl() {
            const container = document.querySelector('.table-responsive');
            if (container && container.dataset && container.dataset.ajaxUrl) return container.dataset.ajaxUrl;
            return '/factures/ajax';
        }
        const url = getAjaxUrl() + '?' + params.toString();
        return fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(r => {
                if (!r.ok) throw new Error('Network response not ok');
                return r.json();
            })
            .then(data => {
                const table = document.querySelector('.table-responsive table');
                if (!table) return data;

                const oldTbody = table.querySelector('tbody');
                const oldTfoot = table.querySelector('tfoot');
                if (oldTbody) oldTbody.remove();
                if (oldTfoot) oldTfoot.remove();

                const container = document.createElement('div');
                container.innerHTML = data.html || '';
                const newTbody = container.querySelector('tbody');
                const newTfoot = container.querySelector('tfoot');
                if (newTbody) table.appendChild(newTbody);
                if (newTfoot) table.appendChild(newTfoot);

                // update stats
                if (data.stats) {
                    const setText = (id, value) => { const el = document.getElementById(id); if (el) el.textContent = value; };
                    setText('stat-non-payees', data.stats.non_payees + (data.stats.partielle ? data.stats.partielle : 0));
                    setText('stat-en-retard', data.stats.en_retard);
                    setText('stat-payees', data.stats.payees);
                    setText('stat-montant-total', new Intl.NumberFormat('fr-FR').format(data.stats.montant_total) + ' $');
                    setText('stat-montant-paye', new Intl.NumberFormat('fr-FR').format(data.stats.montant_paye) + ' $');
                    setText('stat-montant-non-paye', new Intl.NumberFormat('fr-FR').format(data.stats.montant_impaye) + ' $');
                }

                // Bind handlers on new content
                bindRowHandlers(table);

                // Intercept pagination links inside table
                            const pagination = table.querySelector('.pagination');
                            if (pagination) {
                                pagination.querySelectorAll('a').forEach(a => {
                                    // avoid double-binding
                                    if (a.__pag_bound) return;
                                    a.__pag_bound = true;
                                    a.addEventListener('click', function(e) {
                                        e.preventDefault();
                                        const href = this.getAttribute('href') || '';
                                        const q = href.split('?')[1] || '';
                                        const p = new URLSearchParams(q).get('page');
                                        const params2 = buildParams({ page: p });
                                        doFetch(params2, true).catch(err => console.error(err));
                                    });
                                });
                            }

                // Update browser URL (replace state)
                if (updateHistory) {
                    try {
                        const newUrl = window.location.pathname + '?' + params.toString();
                        window.history.replaceState({}, '', newUrl);
                    } catch (e) { /* ignore */ }
                }

                return data;
            });
    }

    const debouncedFilter = debounce(() => {
        const params = buildParams();
        doFetch(params).catch(err => console.error('Fetch failed', err));
    }, 300);
    
    // Attacher les event listeners
    filtreStatut.addEventListener('change', function() {
        debouncedFilter();
    });

    rechercheFacture.addEventListener('input', function() {
        debouncedFilter();
    });

    btnClearSearch.addEventListener('click', function() {
        filtreStatut.value = '';
        rechercheFacture.value = '';
        debouncedFilter();
    });
    
    // Fonctions globales pour le debug
    window.debugFiltres = {
        test: () => debouncedFilter(),
        reset: () => {
            filtreStatut.value = '';
            rechercheFacture.value = '';
            debouncedFilter();
        },
        info: () => {
            console.log("Informations debug:", {
                filtreStatut: filtreStatut.value,
                rechercheFacture: rechercheFacture.value,
                lignes: document.querySelectorAll('tbody tr:not(#messageNoResult)').length
            });
        }
    };
    
    // Initial bind for current rows
    bindRowHandlers(document.querySelector('.table-responsive table'));

    // Intercept existing pagination links on initial load (if any)
    (function interceptInitialPagination() {
        const table = document.querySelector('.table-responsive table');
        if (!table) return;
        const pagination = table.querySelector('.pagination');
        if (!pagination) return;
        pagination.querySelectorAll('a').forEach(a => {
            if (a.__pag_bound) return;
            a.__pag_bound = true;
            a.addEventListener('click', function(e) {
                e.preventDefault();
                const href = this.getAttribute('href') || '';
                const q = href.split('?')[1] || '';
                const p = new URLSearchParams(q).get('page');
                const params2 = buildParams({ page: p });
                doFetch(params2, true).catch(err => console.error(err));
            });
        });
    })();

    // Intercept clicks on any letter / quick-search links that include ?search= in href
    document.addEventListener('click', function(e) {
        const a = e.target.closest && e.target.closest('a');
        if (!a) return;
        const href = a.getAttribute('href') || '';
        try {
            // Only handle same-origin links that contain a search= query param
            if (href && href.indexOf('search=') !== -1) {
                console.debug('[filtres] interception lien de recherche detected:', href);
                // avoid intercepting links that are obviously external
                if (href.indexOf('http') === 0 && new URL(href).origin !== window.location.origin) return;
                e.preventDefault();
                const q = href.split('?')[1] || '';
                const searchValue = new URLSearchParams(q).get('search') || '';
                rechercheFacture.value = searchValue;
                // preserve other inputs (mois/annee/per_page etc) if present in current DOM
                const params = buildParams();
                doFetch(params).catch(err => console.error(err));
            }
        } catch (err) {
            // ignore malformed href
        }
    }, true); // use capture to intercept before other handlers

    console.log("🎉 Système de filtrage AJAX initialisé avec succès!");
    console.log("💡 Utilisez 'debugFiltres.test()' pour tester le filtrage");
});