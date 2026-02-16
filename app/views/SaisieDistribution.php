<?php $pageTitle = "Saisie Distribution"; ?>
<?php include ("inc/header.php"); ?>

<main class="main-content">
    <!-- Hero Section -->
    <section class="hero-section mb-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-light mb-2">
                            <li class="breadcrumb-item"><a href="/"><i class="bi bi-house-door"></i> Accueil</a></li>
                            <li class="breadcrumb-item active">Saisie Distribution</li>
                        </ol>
                    </nav>
                    <h1 class="hero-title mb-3">
                        <i class="bi bi-truck me-2"></i>Saisie Distribution
                    </h1>
                    <p class="hero-subtitle mb-0">
                        Enregistrer une nouvelle distribution de dons aux villes sinistrées
                    </p>
                </div>
                <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                    <span class="badge bg-light text-dark px-3 py-2">
                        <i class="bi bi-calendar3 me-1"></i>
                        <?= date('d F Y') ?>
                    </span>
                </div>
            </div>
        </div>
    </section>

    <div class="container">
        <!-- Alertes -->
        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show animate-fade-in mb-4" role="alert">
                <div class="d-flex align-items-center">
                    <div class="stat-icon me-3" style="width:40px;height:40px;background:var(--success-subtle);color:var(--success-color);border-radius:var(--radius);font-size:1.2rem;">
                        <i class="bi bi-check-circle-fill"></i>
                    </div>
                    <div>
                        <strong>Succès !</strong> La distribution a été enregistrée avec succès.
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show animate-fade-in mb-4" role="alert">
                <div class="d-flex align-items-center">
                    <div class="stat-icon me-3" style="width:40px;height:40px;background:var(--danger-subtle);color:var(--danger-color);border-radius:var(--radius);font-size:1.2rem;">
                        <i class="bi bi-exclamation-triangle-fill"></i>
                    </div>
                    <div>
                        <strong>Erreur !</strong>
                        <?= $_GET['error'] == 1 ? 'Veuillez remplir tous les champs obligatoires.' : 'Une erreur est survenue lors de l\'enregistrement.' ?>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- Formulaire -->
        <div class="modern-card mb-5">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h5 class="card-title mb-0">
                    <i class="bi bi-clipboard-plus me-2 text-primary"></i>Nouvelle distribution
                </h5>
                <span class="badge bg-primary-subtle text-primary">
                    <i class="bi bi-pencil-square me-1"></i>Formulaire
                </span>
            </div>
            <div class="card-body">
                <form action="/distribution/saisie" method="POST" id="formDistribution">
                    <!-- Date unique -->
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <label for="date" class="form-label">
                                <i class="bi bi-calendar-event me-1 text-primary"></i>Date de distribution <span class="text-danger">*</span>
                            </label>
                            <input type="date" class="form-control" id="date" name="date" required 
                                   value="<?= date('Y-m-d') ?>">
                        </div>
                    </div>

                    <!-- Tableau des détails -->
                    <div class="table-responsive">
                        <table class="table modern-table" id="tableDistribution">
                            <thead>
                                <tr>
                                    <th style="width:5%">#</th>
                                    <th style="width:35%"><i class="bi bi-box-seam me-1"></i>Besoin</th>
                                    <th style="width:25%"><i class="bi bi-geo-alt me-1"></i>Ville</th>
                                    <th style="width:20%"><i class="bi bi-123 me-1"></i>Quantité</th>
                                    <th style="width:15%" class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody id="detailsContainer">
                                <tr class="detail-row" data-index="0">
                                    <td class="row-number fw-bold text-primary">1</td>
                                    <td>
                                        <select class="form-select" name="dd_besoin[]" required>
                                            <option value="">-- Choisir un besoin --</option>
                                            <?php foreach ($besoins as $besoin): ?>
                                                <option value="<?= $besoin['b_id'] ?>">
                                                    <?= htmlspecialchars($besoin['b_libelle']) ?> (<?= htmlspecialchars($besoin['unite']) ?>)
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </td>
                                    <td>
                                        <select class="form-select" name="dd_ville[]" required>
                                            <option value="">-- Choisir une ville --</option>
                                            <?php foreach ($villes as $ville): ?>
                                                <option value="<?= $ville['v_id'] ?>">
                                                    <?= htmlspecialchars($ville['v_nom']) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </td>
                                    <td>
                                        <input type="number" class="form-control" name="dd_quantite[]" min="1" required placeholder="Qté">
                                    </td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-outline-danger btn-sm btn-remove-row" style="display:none;" title="Supprimer">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Actions -->
                    <div class="card-footer d-flex justify-content-between align-items-center">
                        <button type="button" class="btn btn-outline-primary" id="btnAddRow">
                            <i class="bi bi-plus-circle me-1"></i> Ajouter une ligne
                        </button>
                        <div>
                            <button type="reset" class="btn btn-outline-secondary me-2" id="btnReset">
                                <i class="bi bi-arrow-counterclockwise me-1"></i> Réinitialiser
                            </button>
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="bi bi-check-lg me-1"></i> Valider la distribution
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>

<script nonce="<?= htmlspecialchars($csp_nonce ?? '') ?>">
    let rowIndex = 1;

    function updateRowNumbers() {
        const rows = document.querySelectorAll('#detailsContainer .detail-row');
        rows.forEach((row, i) => {
            row.querySelector('.row-number').textContent = i + 1;
        });
    }

    function updateRemoveButtons() {
        const rows = document.querySelectorAll('#detailsContainer .detail-row');
        rows.forEach((row) => {
            const removeBtn = row.querySelector('.btn-remove-row');
            removeBtn.style.display = rows.length <= 1 ? 'none' : 'inline-flex';
        });
    }

    document.getElementById('btnAddRow').addEventListener('click', function() {
        const container = document.getElementById('detailsContainer');
        const firstRow = container.querySelector('.detail-row');
        const newRow = firstRow.cloneNode(true);

        newRow.setAttribute('data-index', rowIndex);
        rowIndex++;

        newRow.querySelectorAll('select').forEach(select => select.selectedIndex = 0);
        newRow.querySelectorAll('input[type="number"]').forEach(input => input.value = '');

        const removeBtn = newRow.querySelector('.btn-remove-row');
        removeBtn.style.display = 'inline-flex';

        container.appendChild(newRow);
        updateRowNumbers();
        updateRemoveButtons();

        // Animation d'entrée
        newRow.style.animation = 'fadeInUp 0.3s ease forwards';
    });

    // Délégation d'événements pour les boutons supprimer
    document.getElementById('detailsContainer').addEventListener('click', function(e) {
        const btn = e.target.closest('.btn-remove-row');
        if (btn) {
            const row = btn.closest('.detail-row');
            row.style.animation = 'fadeOut 0.2s ease forwards';
            setTimeout(() => {
                row.remove();
                updateRowNumbers();
                updateRemoveButtons();
            }, 200);
        }
    });

    // Reset : remettre à une seule ligne
    document.getElementById('btnReset').addEventListener('click', function() {
        const container = document.getElementById('detailsContainer');
        const rows = container.querySelectorAll('.detail-row');
        for (let i = rows.length - 1; i > 0; i--) {
            rows[i].remove();
        }
        updateRowNumbers();
        updateRemoveButtons();
    });
</script>

<?php include ("inc/footer.php"); ?>
