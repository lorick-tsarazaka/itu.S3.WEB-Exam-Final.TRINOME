<?php $pageTitle = 'Achats'; ?>
<?php
    $breadcrumbs = [
        ['label' => 'Accueil', 'url' => '/'],
        ['label' => 'Achats']
    ];
?>
<?php include (__DIR__ . '/inc/header.php'); ?>

<main class="main-content">
    <section class="hero-section hero-section-primary mb-4">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <?php if (file_exists(__DIR__ . '/inc/breadcrumb.php')) include('inc/breadcrumb.php'); ?>
                    <h1 class="hero-title mb-2"><i class="bi bi-cart3 me-2"></i>Couverture achats</h1>
                    <p class="hero-subtitle mb-0">Gérer les achats depuis la trésorerie et recouvrir les besoins.</p>
                </div>
                <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                    <div class="d-inline-block small text-white">Total argent disponible: <strong id="total-argent" class="text-white fw-bold fs-4"><?= number_format($totalArgent, 2, '.', ' ') ?></strong> MGA</div>
                </div>
            </div>
        </div>
    </section>

    <div class="container">
        <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-danger">Erreur: <?php
                $err = (int)($_GET['error']);
                echo $err === 1 ? 'Paramètres manquants' : ($err === 2 ? 'Erreur base de données' : ($err === 3 ? 'Pourcentage invalide' : ($err === 4 ? 'Pourcentage hors plage (0-100)' : 'Fonds insuffisants'))); ?>
            </div>
        <?php elseif (isset($_GET['success'])): ?>
            <div class="alert alert-success">Achat enregistré avec succès.</div>
        <?php endif; ?>

        <div class="card modern-card mb-4">
            <div class="card-body">
                <div class="row g-3 align-items-center">
                    <div class="col-auto">
                        <label for="percent" class="form-label mb-0">Pourcentage (%) :</label>
                    </div>
                    <div class="col-auto">
                        <input id="percent" name="percent" type="number" class="form-control" value="10" step="0.1" style="width:120px;" />
                        <div id="percent-feedback" class="invalid-feedback d-none">Entrez un pourcentage valide entre 0 et 100.</div>
                    </div>
                    <div class="col text-end">
                        <small class="text-muted">Le prix restant avec % est recalculé pour chaque ligne.</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Panier des besoins à recouvrir -->
        <div class="card modern-card mb-4" id="panier-card" style="display:none;">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="bi bi-cart-check me-2"></i>Liste des besoins à recouvrir
                    <span class="badge bg-primary ms-2" id="panier-count">0</span>
                </h5>
                <div>
                    <span class="fw-bold text-danger me-3" id="panier-total-prix">Total : 0.00 MGA</span>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover modern-table mb-0" id="table-panier">
                        <thead>
                            <tr>
                                <th>Ville</th>
                                <th>Besoin</th>
                                <th class="text-center">Quantité</th>
                                <th class="text-center">Prix (avec %)</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer d-flex justify-content-between align-items-center">
                <div class="text-muted">
                    <small><i class="bi bi-info-circle me-1"></i>Ces achats ne sont pas encore enregistrés.</small>
                </div>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-outline-secondary" id="btn-reinitialiser">
                        <i class="bi bi-arrow-counterclockwise me-1"></i>Réinitialiser
                    </button>
                    <button type="button" class="btn btn-success" id="btn-enregistrer">
                        <i class="bi bi-check-lg me-1"></i>Enregistrer
                    </button>
                </div>
            </div>
        </div>

        <!-- Formulaire caché pour l'envoi groupé -->
        <form id="form-enregistrer" method="POST" action="/achats/enregistrer" style="display:none;">
            <input type="hidden" name="panier" id="input-panier" value="" />
            <input type="hidden" name="percent" id="input-panier-percent" value="0" />
        </form>

        <div class="card modern-card">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover modern-table mb-0" id="table-besoins">
                        <thead>
                            <tr>
                                <th>Ville</th>
                                <th>Besoin</th>
                                <th>Total demandé</th>
                                <th>Total distribué</th>
                                <th>Reste</th>
                                <th>Prix restant (sans %)</th>
                                <th>Prix restant (avec %)</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach (($besoins ?? []) as $b):
                                $reste = max(0, (float)($b['reste'] ?? 0));
                                $prixUnitaire = (float)($b['prix_unitaire'] ?? 0);
                                $prixSans = $reste * $prixUnitaire;
                            ?>
                            <tr data-besoin="<?= (int)$b['besoin_id'] ?>" data-ville="<?= (int)$b['ville_id'] ?>" data-quantite="<?= $reste ?>" data-prixunitaire="<?= $prixUnitaire ?>">
                                <td><?= htmlspecialchars($b['ville_nom']) ?></td>
                                <td><?= htmlspecialchars($b['besoin_libelle']) ?></td>
                                <td><?= htmlspecialchars($b['quantite_demandee']) ?></td>
                                <td><?= htmlspecialchars($b['quantite_distribuee']) ?></td>
                                <td class="col-reste"><?= number_format($reste, 2, '.', ' ') ?></td>
                                <td class="col-prixSans"><?= number_format($prixSans, 2, '.', ' ') ?></td>
                                <td class="col-prixAvec">0.00</td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-sm btn-primary btn-recouvrir"
                                        data-ville="<?= (int)$b['ville_id'] ?>"
                                        data-ville-nom="<?= htmlspecialchars($b['ville_nom']) ?>"
                                        data-besoin="<?= (int)$b['besoin_id'] ?>"
                                        data-besoin-nom="<?= htmlspecialchars($b['besoin_libelle']) ?>"
                                        data-quantite="<?= $reste ?>"
                                        data-prixunitaire="<?= $prixUnitaire ?>">
                                        <i class="bi bi-cart-plus me-1"></i>Recouvrir
                                    </button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</main>
<?php include (__DIR__ . '/inc/footer.php'); ?>

<script nonce="<?= htmlspecialchars($csp_nonce) ?>">
(function () {
    const percentInput = document.getElementById('percent');
    const feedback = document.getElementById('percent-feedback');
    const totalArgentEl = document.getElementById('total-argent');
    const panierCard = document.getElementById('panier-card');
    const panierCount = document.getElementById('panier-count');
    const panierTotalPrix = document.getElementById('panier-total-prix');
    const panierTbody = document.querySelector('#table-panier tbody');
    const btnReinit = document.getElementById('btn-reinitialiser');
    const btnEnregistrer = document.getElementById('btn-enregistrer');
    const formEnregistrer = document.getElementById('form-enregistrer');
    const inputPanier = document.getElementById('input-panier');
    const inputPanierPercent = document.getElementById('input-panier-percent');

    let panier = []; // [{ville, villeNom, besoin, besoinNom, quantite, prixUnitaire, prixAvec}]

    function isValidPercent(v) {
        if (v === '' || v === null || Number.isNaN(Number(v))) return false;
        const n = Number(v);
        return isFinite(n) && n >= 0 && n <= 100;
    }

    function getPercent() {
        return parseFloat(percentInput.value) || 0;
    }

    function updateRows() {
        const percent = getPercent();
        document.querySelectorAll('#table-besoins tbody tr').forEach(row => {
            const q = parseFloat(row.getAttribute('data-quantite')) || 0;
            const pu = parseFloat(row.getAttribute('data-prixunitaire')) || 0;
            const prixSans = q * pu;
            const prixAvec = prixSans * (1 + percent / 100);
            const prixSansEl = row.querySelector('.col-prixSans');
            const prixAvecEl = row.querySelector('.col-prixAvec');
            if (prixSansEl) prixSansEl.textContent = prixSans.toFixed(2);
            if (prixAvecEl) prixAvecEl.textContent = prixAvec.toFixed(2);
        });
        // Recalculer les prix du panier
        recalcPanier();
    }

    function recalcPanier() {
        const percent = getPercent();
        let total = 0;
        panier.forEach(item => {
            item.prixAvec = item.quantite * item.prixUnitaire * (1 + percent / 100);
            total += item.prixAvec;
        });
        renderPanier();
    }

    function renderPanier() {
        panierTbody.innerHTML = '';
        if (panier.length === 0) {
            panierCard.style.display = 'none';
            return;
        }
        panierCard.style.display = '';
        panierCount.textContent = panier.length;

        let totalPrix = 0;
        panier.forEach((item, idx) => {
            totalPrix += item.prixAvec;
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td>${escapeHtml(item.villeNom)}</td>
                <td>${escapeHtml(item.besoinNom)}</td>
                <td class="text-center">${Number(item.quantite).toFixed(2)}</td>
                <td class="text-center">${item.prixAvec.toFixed(2)} MGA</td>
                <td class="text-center">
                    <button type="button" class="btn btn-sm btn-outline-danger btn-retirer" data-index="${idx}">
                        <i class="bi bi-trash"></i>
                    </button>
                </td>
            `;
            panierTbody.appendChild(tr);
        });

        panierTotalPrix.textContent = 'Total : ' + totalPrix.toFixed(2) + ' MGA';

        // Bind retirer
        panierTbody.querySelectorAll('.btn-retirer').forEach(btn => {
            btn.addEventListener('click', function() {
                const idx = parseInt(this.getAttribute('data-index'));
                retirerDuPanier(idx);
            });
        });

        // Activer/désactiver les boutons Recouvrir déjà dans le panier
        updateBtnStates();
    }

    function updateBtnStates() {
        document.querySelectorAll('.btn-recouvrir').forEach(btn => {
            const ville = btn.getAttribute('data-ville');
            const besoin = btn.getAttribute('data-besoin');
            const inPanier = panier.some(p => p.ville == ville && p.besoin == besoin);
            btn.disabled = inPanier;
            if (inPanier) {
                btn.classList.remove('btn-primary');
                btn.classList.add('btn-secondary');
                btn.innerHTML = '<i class="bi bi-check me-1"></i>Ajouté';
            } else {
                btn.classList.remove('btn-secondary');
                btn.classList.add('btn-primary');
                btn.innerHTML = '<i class="bi bi-cart-plus me-1"></i>Recouvrir';
            }
        });
    }

    function ajouterAuPanier(ville, villeNom, besoin, besoinNom, quantite, prixUnitaire) {
        // Vérifier si déjà dans le panier
        if (panier.some(p => p.ville == ville && p.besoin == besoin)) return;

        const percent = getPercent();
        const prixAvec = quantite * prixUnitaire * (1 + percent / 100);

        panier.push({ ville, villeNom, besoin, besoinNom, quantite, prixUnitaire, prixAvec });
        renderPanier();
    }

    function retirerDuPanier(idx) {
        panier.splice(idx, 1);
        renderPanier();
    }

    function escapeHtml(str) {
        const div = document.createElement('div');
        div.textContent = str;
        return div.innerHTML;
    }

    // Boutons Recouvrir
    document.querySelectorAll('.btn-recouvrir').forEach(btn => {
        btn.addEventListener('click', function() {
            ajouterAuPanier(
                this.getAttribute('data-ville'),
                this.getAttribute('data-ville-nom'),
                this.getAttribute('data-besoin'),
                this.getAttribute('data-besoin-nom'),
                parseFloat(this.getAttribute('data-quantite')) || 0,
                parseFloat(this.getAttribute('data-prixunitaire')) || 0
            );
        });
    });

    // Réinitialiser (recharger la page)
    btnReinit.addEventListener('click', function() {
        window.location.href = '/achats';
    });

    // Enregistrer
    btnEnregistrer.addEventListener('click', function() {
        if (panier.length === 0) {
            alert('Le panier est vide.');
            return;
        }
        const percent = getPercent();
        const totalArgent = parseFloat(totalArgentEl.textContent.replace(/\s/g, '')) || 0;
        let totalPrix = 0;
        panier.forEach(item => { totalPrix += item.quantite * item.prixUnitaire * (1 + percent / 100); });

        if (totalPrix > totalArgent) {
            alert('Fonds insuffisants pour l\'ensemble des achats (Total: ' + totalPrix.toFixed(2) + ' MGA, Disponible: ' + totalArgent.toFixed(2) + ' MGA).');
            return;
        }

        // Envoyer le panier
        const data = panier.map(p => ({ ville: parseInt(p.ville), besoin: parseInt(p.besoin), quantite: p.quantite }));
        inputPanier.value = JSON.stringify(data);
        inputPanierPercent.value = percent;
        formEnregistrer.submit();
    });

    percentInput.addEventListener('input', function () {
        if (!isValidPercent(this.value)) {
            this.classList.add('is-invalid');
            feedback.classList.remove('d-none');
        } else {
            this.classList.remove('is-invalid');
            feedback.classList.add('d-none');
        }
        updateRows();
    });

    // initial
    updateRows();
})();
</script>
