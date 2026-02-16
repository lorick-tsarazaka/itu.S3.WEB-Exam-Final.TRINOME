<?php $pageTitle = "Saisie Distribution"; ?>
<?php include ("inc/header.php"); ?>

<main class="main-content">
    <!-- Hero Section -->
    <section class="hero-section mb-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <h1 class="hero-title mb-3">
                        <i class="bi bi-truck me-2"></i>Saisie Distribution
                    </h1>
                    <p class="hero-subtitle mb-0">
                        Enregistrer une nouvelle distribution de dons aux villes sinistrées
                    </p>
                </div>
                <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                    <a href="/" class="btn btn-outline-light">
                        <i class="bi bi-arrow-left me-1"></i> Retour au tableau de bord
                    </a>
                </div>
            </div>
        </div>
    </section>

    <div class="container">
        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>
                Distribution enregistrée avec succès !
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                <?= $_GET['error'] == 1 ? 'Veuillez remplir tous les champs obligatoires.' : 'Une erreur est survenue lors de l\'enregistrement.' ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="card shadow-sm border-0">
            <div class="card-header bg-white border-bottom">
                <h5 class="card-title mb-0">
                    <i class="bi bi-clipboard-plus me-2 text-primary"></i>Nouvelle distribution
                </h5>
            </div>
            <div class="card-body p-4">
                <form action="/distribution/saisie" method="POST" id="formDistribution">
                    <!-- Date unique -->
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <label for="date" class="form-label fw-semibold">
                                <i class="bi bi-calendar-event me-1"></i>Date de distribution
                            </label>
                            <input type="date" class="form-control" id="date" name="date" required 
                                   value="<?= date('Y-m-d') ?>">
                        </div>
                    </div>

                    <hr class="my-4">

                    <!-- Conteneur des lignes de détails -->
                    <div id="detailsContainer">
                        <div class="detail-row row g-3 mb-3 align-items-end" data-index="0">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-box-seam me-1"></i>Besoin
                                </label>
                                <select class="form-select" name="dd_besoin[]" required>
                                    <option value="">-- Choisir un besoin --</option>
                                    <?php foreach ($besoins as $besoin): ?>
                                        <option value="<?= $besoin['b_id'] ?>">
                                            <?= htmlspecialchars($besoin['b_libelle']) ?> (<?= htmlspecialchars($besoin['unite']) ?>)
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-geo-alt me-1"></i>Ville
                                </label>
                                <select class="form-select" name="dd_ville[]" required>
                                    <option value="">-- Choisir une ville --</option>
                                    <?php foreach ($villes as $ville): ?>
                                        <option value="<?= $ville['v_id'] ?>">
                                            <?= htmlspecialchars($ville['v_nom']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-123 me-1"></i>Quantité
                                </label>
                                <input type="number" class="form-control" name="dd_quantite[]" min="1" required placeholder="Quantité">
                            </div>
                            <div class="col-md-2">
                                <button type="button" class="btn btn-outline-danger w-100 btn-remove-row" style="display:none;">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Bouton ajouter une ligne -->
                    <div class="mb-4">
                        <button type="button" class="btn btn-outline-primary" id="btnAddRow">
                            <i class="bi bi-plus-circle me-1"></i> Ajouter un autre besoin
                        </button>
                    </div>

                    <hr class="my-4">

                    <!-- Bouton valider -->
                    <div class="d-flex justify-content-end">
                        <button type="reset" class="btn btn-outline-secondary me-2">
                            <i class="bi bi-arrow-counterclockwise me-1"></i> Réinitialiser
                        </button>
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="bi bi-check-lg me-1"></i> Valider la distribution
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>

<script nonce="<?= htmlspecialchars($csp_nonce ?? '') ?>">
    let rowIndex = 1;

    document.getElementById('btnAddRow').addEventListener('click', function() {
        const container = document.getElementById('detailsContainer');
        const firstRow = container.querySelector('.detail-row');
        const newRow = firstRow.cloneNode(true);

        newRow.setAttribute('data-index', rowIndex);
        rowIndex++;

        // Réinitialiser les valeurs
        newRow.querySelectorAll('select').forEach(select => select.selectedIndex = 0);
        newRow.querySelectorAll('input[type="number"]').forEach(input => input.value = '');

        // Afficher le bouton supprimer
        const removeBtn = newRow.querySelector('.btn-remove-row');
        removeBtn.style.display = 'block';

        container.appendChild(newRow);
        updateRemoveButtons();
    });

    // Délégation d'événements pour les boutons supprimer
    document.getElementById('detailsContainer').addEventListener('click', function(e) {
        const btn = e.target.closest('.btn-remove-row');
        if (btn) {
            const row = btn.closest('.detail-row');
            row.remove();
            updateRemoveButtons();
        }
    });

    function updateRemoveButtons() {
        const rows = document.querySelectorAll('.detail-row');
        rows.forEach((row) => {
            const removeBtn = row.querySelector('.btn-remove-row');
            if (rows.length <= 1) {
                removeBtn.style.display = 'none';
            } else {
                removeBtn.style.display = 'block';
            }
        });
    }
</script>

<?php include ("inc/footer.php"); ?>
