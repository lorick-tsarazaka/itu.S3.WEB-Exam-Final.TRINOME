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
                                    <form method="post" action="/achats/recouvrir" class="recouvrir-form d-inline-block">
                                        <input type="hidden" name="ville" class="input-ville" value="<?= (int)$b['ville_id'] ?>" />
                                        <input type="hidden" name="besoin" class="input-besoin" value="<?= (int)$b['besoin_id'] ?>" />
                                        <input type="hidden" name="quantite" class="input-quantite" value="<?= $reste ?>" />
                                        <input type="hidden" name="percent" class="input-percent" value="0" />
                                        <button type="submit" class="btn btn-sm btn-primary">Recouvrir</button>
                                    </form>
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

    function isValidPercent(v) {
        if (v === '' || v === null || Number.isNaN(Number(v))) return false;
        const n = Number(v);
        return isFinite(n) && n >= 0 && n <= 100;
    }

    function updateRows() {
        const percent = parseFloat(percentInput.value) || 0;
        let totalPrixAvec = 0;
        document.querySelectorAll('#table-besoins tbody tr').forEach(row => {
            const q = parseFloat(row.getAttribute('data-quantite')) || 0;
            const pu = parseFloat(row.getAttribute('data-prixunitaire')) || 0;
            const prixSans = q * pu;
            const prixAvec = prixSans * (1 + percent / 100);
            const prixSansEl = row.querySelector('.col-prixSans');
            const prixAvecEl = row.querySelector('.col-prixAvec');
            if (prixSansEl) prixSansEl.textContent = prixSans.toFixed(2);
            if (prixAvecEl) prixAvecEl.textContent = prixAvec.toFixed(2);
            const form = row.querySelector('form');
            if (form) {
                const inp = form.querySelector('.input-percent');
                if (inp) inp.value = percent;
                const iq = form.querySelector('.input-quantite');
                if (iq) iq.value = q;
            }
            totalPrixAvec += prixAvec;
        });
    }

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

    // Empêcher l'envoi si fonds insuffisants côté client (simple vérif heuristique)
    document.querySelectorAll('.recouvrir-form').forEach(f => {
        f.addEventListener('submit', function (e) {
            const percent = parseFloat(percentInput.value) || 0;
            const quantite = parseFloat(this.querySelector('.input-quantite').value) || 0;
            const prixUnitaire = parseFloat(this.closest('tr').getAttribute('data-prixunitaire')) || 0;
            const prixAvec = quantite * prixUnitaire * (1 + percent / 100);
            const totalArgent = parseFloat(totalArgentEl.textContent.replace(/\s/g, '')) || 0;
            if (prixAvec > totalArgent) {
                e.preventDefault();
                alert('Fonds insuffisants pour cet achat (estimé: ' + prixAvec.toFixed(2) + ' MGA).');
                return false;
            }
            return true;
        });
    });

    // initial
    updateRows();
})();
</script>
