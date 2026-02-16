<?php $pageTitle = "Simulation de Dons"; ?>
<?php
    $breadcrumbs = [
        ['label' => 'Accueil', 'url' => '/'],
        ['label' => 'Simulation de Dons']
    ];
?>
<?php include ("inc/header.php"); ?>

<main class="main-content">
    <!-- Hero Section -->
    <section class="hero-section mb-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <?php if (file_exists(__DIR__ . '/inc/breadcrumb.php')) include('inc/breadcrumb.php'); ?>
                    <h1 class="hero-title mb-3">
                        <i class="bi bi-arrow-repeat me-2"></i>Simulation de Dispatch
                    </h1>
                    <p class="hero-subtitle mb-0">
                        Distribution automatique des collectes vers les villes selon leurs besoins
                    </p>
                </div>
                <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                    <form action="/simulation/executer" method="POST" class="d-inline">
                        <button type="submit" class="btn btn-light btn-lg ">
                            <i class="bi bi-play-fill me-1"></i>Lancer la simulation
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <div class="container">
        <!-- Statistiques globales -->
        <?php if (isset($statistiques)): ?>
        <div class="row g-4 mb-5">
            <div class="col-md-6 col-xl-4">
                <div class="stat-card stat-card-primary">
                    <div class="stat-card-body">
                        <div class="stat-icon">
                            <i class="bi bi-list-check"></i>
                        </div>
                        <div class="stat-info">
                            <h3 class="stat-number"><?= number_format($statistiques['total_besoins']) ?></h3>
                            <p class="stat-label">Besoins enregistrés</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xl-4">
                <div class="stat-card stat-card-success">
                    <div class="stat-card-body">
                        <div class="stat-icon">
                            <i class="bi bi-check-circle-fill"></i>
                        </div>
                        <div class="stat-info">
                            <h3 class="stat-number"><?= number_format($statistiques['besoins_satisfaits']) ?></h3>
                            <p class="stat-label">Besoins satisfaits</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xl-4">
                <div class="stat-card stat-card-warning">
                    <div class="stat-card-body">
                        <div class="stat-icon">
                            <i class="bi bi-hourglass-split"></i>
                        </div>
                        <div class="stat-info">
                            <h3 class="stat-number"><?= number_format($statistiques['besoins_partiels']) ?></h3>
                            <p class="stat-label">Partiellement couverts</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Message de succès après validation -->
        <?php if (isset($validation_result) && $validation_result['success']): ?>
        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
            <div class="d-flex align-items-center">
                <i class="bi bi-check-circle-fill me-2 fs-4"></i>
                <div>
                    <strong>Distribution validée et enregistrée avec succès !</strong><br>
                    <small>
                        <?= count($validation_result['distributions']) ?> distributions créées | 
                        <?= $validation_result['villes_traitees'] ?> villes traitées | 
                        <?= $validation_result['besoins_satisfaits'] ?> besoins satisfaits
                    </small>
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php elseif (isset($validation_result) && !$validation_result['success']): ?>
        <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            <strong>Erreur :</strong> <?= htmlspecialchars($validation_result['error'] ?? 'Erreur inconnue') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>

        <!-- Aperçu de la simulation (non enregistré) -->
        <?php if (isset($mode_apercu) && $mode_apercu && isset($simulationParVille) && !empty($simulationParVille)): ?>
        <div class="alert alert-warning mb-4">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i>
                    <strong>Aperçu de la simulation</strong> — Ces données ne sont pas encore enregistrées. Cliquez sur <strong>Valider</strong> pour enregistrer.
                </div>
                <form action="/simulation/valider" method="POST" class="d-inline ms-3">
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-check-lg me-1"></i>Valider et enregistrer
                    </button>
                </form>
            </div>
        </div>

        <div class="row g-4 mb-5">
            <?php foreach ($simulationParVille as $ville): ?>
            <div class="col-12">
                <div class="card modern-card border-warning">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <div class="city-icon me-3" style="width: 50px; height: 50px; font-size: 1.25rem;">
                                <i class="bi bi-geo-alt-fill"></i>
                            </div>
                            <div>
                                <h5 class="card-title mb-1"><?= htmlspecialchars($ville['ville_nom']) ?></h5>
                                <small class="text-muted">
                                    <?= count($ville['besoins']) ?> besoin(s) | 
                                    Distribué: <?= number_format($ville['total_distribue']) ?> / <?= number_format($ville['total_demande']) ?>
                                </small>
                            </div>
                        </div>
                        <span class="badge bg-warning text-dark px-3 py-2">
                            <i class="bi bi-eye me-1"></i>Aperçu
                        </span>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover modern-table mb-0">
                                <thead>
                                    <tr>
                                        <th>Besoin</th>
                                        <th class="text-center">Demandé</th>
                                        <th class="text-center">Distribué</th>
                                        <th class="text-center">Reste</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($ville['besoins'] as $besoin): ?>
                                    <tr class="<?= $besoin['reste'] == 0 ? 'table-success' : '' ?>">
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="besoin-icon bg-primary-subtle text-primary me-2">
                                                    <i class="bi bi-box"></i>
                                                </div>
                                                <div>
                                                    <strong><?= htmlspecialchars($besoin['besoin_libelle']) ?></strong>
                                                    <small class="d-block text-muted"><?= htmlspecialchars($besoin['unite']) ?></small>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center"><span class="fw-semibold"><?= number_format($besoin['quantite_demandee']) ?></span></td>
                                        <td class="text-center"><span class="text-success fw-semibold"><?= number_format($besoin['quantite_distribuee']) ?></span></td>
                                        <td class="text-center">
                                            <?php if ($besoin['reste'] > 0): ?>
                                                <span class="text-danger fw-semibold"><?= number_format($besoin['reste']) ?></span>
                                            <?php else: ?>
                                                <span class="badge bg-success"><i class="bi bi-check"></i> Complet</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="row text-center">
                            <div class="col-4">
                                <div class="p-2">
                                    <div class="fw-bold text-primary fs-5"><?= number_format($ville['total_demande']) ?></div>
                                    <small class="text-muted">Total demandé</small>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="p-2">
                                    <div class="fw-bold text-success fs-5"><?= number_format($ville['total_distribue']) ?></div>
                                    <small class="text-muted">Total distribué</small>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="p-2">
                                    <div class="fw-bold text-danger fs-5"><?= number_format($ville['total_reste']) ?></div>
                                    <small class="text-muted">Total restant</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php elseif (isset($mode_apercu) && $mode_apercu): ?>
        <div class="alert alert-info mb-4">
            <i class="bi bi-info-circle-fill me-2"></i>
            <strong>Aucune distribution à effectuer.</strong> Tous les besoins sont déjà couverts ou aucun stock disponible.
        </div>
        <?php endif; ?>

        <!-- Liste des distributions par ville -->
        <?php if (isset($distributionsParVille) && !empty($distributionsParVille)): ?>
        <div class="row g-4">
            <?php foreach ($distributionsParVille as $ville): ?>
            <div class="col-12">
                <div class="card modern-card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <div class="city-icon me-3" style="width: 50px; height: 50px; font-size: 1.25rem;">
                                <i class="bi bi-geo-alt-fill"></i>
                            </div>
                            <div>
                                <h5 class="card-title mb-1"><?= htmlspecialchars($ville['ville_nom']) ?></h5>
                                <small class="text-muted">
                                    <?= count($ville['besoins']) ?> besoin(s) | 
                                    Distribué: <?= number_format($ville['total_distribue']) ?> / <?= number_format($ville['total_demande']) ?>
                                </small>
                            </div>
                        </div>
                        <?php 
                            $pourcentage = $ville['total_demande'] > 0 
                                ? round(($ville['total_distribue'] / $ville['total_demande']) * 100) 
                                : 0;
                            $badgeClass = $pourcentage >= 100 ? 'bg-success' : ($pourcentage >= 50 ? 'bg-warning text-dark' : 'bg-danger');
                        ?>
                        <span class="badge <?= $badgeClass ?> px-3 py-2">
                            <?= $pourcentage ?>% couvert
                        </span>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover modern-table mb-0">
                                <thead>
                                    <tr>
                                        <th>Besoin</th>
                                        <th class="text-center">Demandé</th>
                                        <th class="text-center">Distribué</th>
                                        <th class="text-center">Reste</th>
                                        <th>Progression</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($ville['besoins'] as $besoin): ?>
                                    <?php 
                                        $progPourcentage = $besoin['quantite_demandee'] > 0 
                                            ? round(($besoin['quantite_distribuee'] / $besoin['quantite_demandee']) * 100) 
                                            : 0;
                                        $progClass = $progPourcentage >= 100 ? 'bg-success' : ($progPourcentage >= 50 ? 'bg-warning' : 'bg-danger');
                                    ?>
                                    <tr class="<?= $besoin['reste'] == 0 ? 'table-success' : ($besoin['quantite_distribuee'] == 0 ? 'table-danger' : '') ?>">
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="besoin-icon bg-primary-subtle text-primary me-2">
                                                    <i class="bi bi-box"></i>
                                                </div>
                                                <div>
                                                    <strong><?= htmlspecialchars($besoin['besoin_libelle']) ?></strong>
                                                    <small class="d-block text-muted"><?= htmlspecialchars($besoin['unite']) ?></small>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <span class="fw-semibold"><?= number_format($besoin['quantite_demandee']) ?></span>
                                        </td>
                                        <td class="text-center">
                                            <span class="text-success fw-semibold"><?= number_format($besoin['quantite_distribuee']) ?></span>
                                        </td>
                                        <td class="text-center">
                                            <?php if ($besoin['reste'] > 0): ?>
                                                <span class="text-danger fw-semibold"><?= number_format($besoin['reste']) ?></span>
                                            <?php else: ?>
                                                <span class="badge bg-success"><i class="bi bi-check"></i> Complet</span>
                                            <?php endif; ?>
                                        </td>
                                        <td style="min-width: 150px;">
                                            <div class="progress-wrapper">
                                                <div class="progress" style="height: 8px; flex: 1;">
                                                    <div class="progress-bar <?= $progClass ?>" style="width: <?= $progPourcentage ?>%;"></div>
                                                </div>
                                                <small class="text-muted ms-2"><?= $progPourcentage ?>%</small>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="row text-center">
                            <div class="col-4">
                                <div class="p-2">
                                    <div class="fw-bold text-primary fs-5"><?= number_format($ville['total_demande']) ?></div>
                                    <small class="text-muted">Total demandé</small>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="p-2">
                                    <div class="fw-bold text-success fs-5"><?= number_format($ville['total_distribue']) ?></div>
                                    <small class="text-muted">Total distribué</small>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="p-2">
                                    <div class="fw-bold text-danger fs-5"><?= number_format($ville['total_reste']) ?></div>
                                    <small class="text-muted">Total restant</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <!-- État vide -->
        <div class="card modern-card">
            <div class="card-body text-center py-5">
                <i class="bi bi-inbox text-muted" style="font-size: 4rem;"></i>
                <h4 class="mt-3 text-muted">Aucune distribution enregistrée</h4>
                <p class="text-muted mb-4">
                    Lancez une simulation pour distribuer automatiquement les collectes vers les villes.
                </p>
                <form action="/simulation/executer" method="POST" class="d-inline">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="bi bi-play-fill me-2"></i>Lancer la simulation
                    </button>
                </form>
            </div>
        </div>
        <?php endif; ?>
    </div>
</main>

<style>
.besoin-icon {
    width: 40px;
    height: 40px;
    border-radius: var(--radius);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1rem;
}

.progress-wrapper {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}
</style>

<?php include ("inc/footer.php"); ?>
