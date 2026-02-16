<?php $pageTitle = "Liste des Collectes"; ?>
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
                            <li class="breadcrumb-item active">Collectes</li>
                        </ol>
                    </nav>
                    <h1 class="hero-title mb-3">
                        <i class="bi bi-collection me-2"></i>Liste des Collectes
                    </h1>
                    <p class="hero-subtitle mb-0">
                        Historique de toutes les collectes de dons enregistrées
                    </p>
                </div>
                <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                    <a href="/collecte/add" class="btn btn-light btn-lg shadow-sm">
                        <i class="bi bi-plus-circle me-2"></i>Créer une collecte
                    </a>
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
                        <strong>Succès !</strong> La collecte a été enregistrée avec succès.
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- Résumé -->
        <div class="d-flex gap-2 flex-wrap flex-md-nowrap mb-4">
            <div class="col-md-4">
                <div class="stat-card stat-card-primary">
                    <div class="stat-card-body">
                        <div class="stat-icon"><i class="bi bi-collection-fill"></i></div>
                        <div class="stat-info">
                            <h3 class="stat-number"><?= count($collectes) ?></h3>
                            <p class="stat-label">Collectes</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card stat-card-success">
                    <div class="stat-card-body">
                        <div class="stat-icon"><i class="bi bi-box-seam-fill"></i></div>
                        <div class="stat-info">
                            <h3 class="stat-number"><?php $tp = 0; foreach($collectes as $c) $tp += (int)$c['nb_details']; echo $tp; ?></h3>
                            <p class="stat-label">Produits collectés</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card stat-card-info">
                    <div class="stat-card-body">
                        <div class="stat-icon"><i class="bi bi-123"></i></div>
                        <div class="stat-info">
                            <h3 class="stat-number"><?php $tq = 0; foreach($collectes as $c) $tq += (int)$c['total_quantite']; echo $tq; ?></h3>
                            <p class="stat-label">Quantité totale</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Liste des collectes -->
        <?php if (empty($collectes)): ?>
            <div class="modern-card mb-5">
                <div class="card-body text-center py-5">
                    <i class="bi bi-inbox" style="font-size: 3rem; color: var(--text-muted);"></i>
                    <p class="text-muted mt-3 mb-4">Aucune collecte enregistrée pour le moment.</p>
                    <a href="/collecte/add" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-1"></i> Créer la première collecte
                    </a>
                </div>
            </div>
        <?php else: ?>
            <?php foreach ($collectes as $index => $collecte): ?>
                <div class="modern-card mb-3 animate-fade-in" style="animation-delay: <?= $index * 0.05 ?>s">
                    <div class="card-body p-0">
                        <div class="d-flex align-items-center justify-content-between flex-wrap p-3">
                            <div class="d-flex align-items-center gap-3">
                                <div class="d-flex align-items-center justify-content-center rounded-circle" style="width:45px;height:45px;background:var(--primary-subtle);color:var(--primary-color);font-weight:700;font-size:1.1rem;">
                                    <?= $index + 1 ?>
                                </div>
                                <div>
                                    <h6 class="mb-0 fw-bold">
                                        <i class="bi bi-calendar3 me-1 text-primary"></i>
                                        <?= date('d/m/Y', strtotime($collecte['c_date'])) ?>
                                    </h6>
                                    <small class="text-muted">Collecte #<?= $collecte['c_id'] ?></small>
                                </div>
                            </div>
                            <div class="d-flex align-items-center gap-3 mt-2 mt-md-0">
                                <div class="text-center">
                                    <span class="badge bg-info-subtle text-info px-3 py-2">
                                        <i class="bi bi-box-seam me-1"></i><?= (int) $collecte['nb_details'] ?> produit(s)
                                    </span>
                                </div>
                                <div class="text-center">
                                    <span class="badge bg-success-subtle text-success px-3 py-2">
                                        <i class="bi bi-123 me-1"></i>Qté: <?= (int) $collecte['total_quantite'] ?>
                                    </span>
                                </div>
                                <button type="button" class="btn btn-outline-primary btn-sm btn-toggle-details" data-collecte-id="<?= $collecte['c_id'] ?>">
                                    <i class="bi bi-chevron-down me-1"></i>Détails
                                </button>
                            </div>
                        </div>
                        <!-- Zone détails dépliable -->
                        <div class="details-zone" id="details-<?= $collecte['c_id'] ?>" style="display:none;">
                            <div class="border-top px-3 py-3" style="background:var(--gray-50, #f8f9fa);">
                                <div class="text-center text-muted">
                                    <i class="bi bi-hourglass-split me-1"></i>Chargement...
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</main>

<script nonce="<?= htmlspecialchars($csp_nonce ?? '') ?>">
    document.querySelectorAll('.btn-toggle-details').forEach(function(btn) {
        btn.addEventListener('click', function() {
            const collecteId = this.getAttribute('data-collecte-id');
            const detailsZone = document.getElementById('details-' + collecteId);

            if (detailsZone.style.display === 'none') {
                detailsZone.style.display = 'block';
                this.innerHTML = '<i class="bi bi-chevron-up me-1"></i>Masquer';

                fetch('/collecte/details/' + collecteId)
                    .then(function(response) { return response.json(); })
                    .then(function(data) {
                        let html = '';
                        if (data.length === 0) {
                            html = '<div class="text-center text-muted py-2"><i class="bi bi-inbox me-1"></i>Aucun détail</div>';
                        } else {
                            html = '<div class="row g-2">';
                            data.forEach(function(d) {
                                html += '<div class="col-md-4 col-sm-6">';
                                html += '<div class="d-flex align-items-center gap-2 p-2 rounded" style="background:white;border:1px solid var(--gray-200, #e5e7eb);">';
                                html += '<div class="d-flex align-items-center justify-content-center rounded" style="width:36px;height:36px;background:var(--primary-subtle);color:var(--primary-color);flex-shrink:0;"><i class="bi bi-box-seam"></i></div>';
                                html += '<div class="flex-grow-1"><div class="fw-semibold small">' + d.b_libelle + '</div><small class="text-muted">' + d.unite + '</small></div>';
                                html += '<span class="badge bg-primary px-2 py-1">' + d.cd_quantite + '</span>';
                                html += '</div></div>';
                            });
                            html += '</div>';
                        }
                        detailsZone.querySelector('.border-top').innerHTML = html;
                    })
                    .catch(function() {
                        detailsZone.querySelector('.border-top').innerHTML = '<div class="text-danger py-2"><i class="bi bi-exclamation-triangle me-1"></i>Erreur de chargement</div>';
                    });
            } else {
                detailsZone.style.display = 'none';
                this.innerHTML = '<i class="bi bi-chevron-down me-1"></i>Détails';
            }
        });
    });
</script>

<?php include ("inc/footer.php"); ?>
