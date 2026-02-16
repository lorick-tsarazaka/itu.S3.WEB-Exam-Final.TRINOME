<?php $pageTitle = "Tableau de bord"; ?>
<?php
    $breadcrumbs = [
        ['label' => 'Accueil', 'url' => '/'],
        ['label' => 'Tableau de bord']
    ];
?>
<?php include ("inc/header.php"); ?>

<?php
    // Calculs des statistiques
    $totalVilles = count($data ?? []);
    $totalBesoins = 0;
    $totalDistributions = 0;
    foreach ($data ?? [] as $item) {
        $totalBesoins += count($item['besoins'] ?? []);
        $totalDistributions += count($item['distributions'] ?? []);
    }
    $tauxCouverture = $totalBesoins > 0 ? round(($totalDistributions / $totalBesoins) * 100) : 0;
?>

<main class="main-content">
    <!-- Hero Section -->
    <section class="hero-section mb-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <?php if (file_exists(__DIR__ . '/inc/breadcrumb.php')) include('inc/breadcrumb.php'); ?>
                    <h1 class="hero-title mb-3">
                        <i class="bi bi-speedometer2 me-2"></i>Tableau de Bord
                    </h1>
                    <p class="hero-subtitle mb-0">
                        Suivi en temps réel des besoins et distributions de dons pour les sinistrés
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
        <!-- Stats Cards -->
        <div class="row g-4 mb-5">
            <div class="col-md-6 col-xl-3">
                <div class="stat-card stat-card-primary">
                    <div class="stat-card-body">
                        <div class="stat-icon">
                            <i class="bi bi-geo-alt-fill"></i>
                        </div>
                        <div class="stat-info">
                            <h3 class="stat-number"><?= $totalVilles ?></h3>
                            <p class="stat-label">Villes sinistrées</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xl-3">
                <div class="stat-card stat-card-warning">
                    <div class="stat-card-body">
                        <div class="stat-icon">
                            <i class="bi bi-exclamation-triangle-fill"></i>
                        </div>
                        <div class="stat-info">
                            <h3 class="stat-number"><?= $totalBesoins ?></h3>
                            <p class="stat-label">Besoins enregistrés</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xl-3">
                <div class="stat-card stat-card-success">
                    <div class="stat-card-body">
                        <div class="stat-icon">
                            <i class="bi bi-truck"></i>
                        </div>
                        <div class="stat-info">
                            <h3 class="stat-number"><?= $totalDistributions ?></h3>
                            <p class="stat-label">Distributions effectuées</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xl-3">
                <div class="stat-card stat-card-info">
                    <div class="stat-card-body">
                        <div class="stat-icon">
                            <i class="bi bi-pie-chart-fill"></i>
                        </div>
                        <div class="stat-info">
                            <h3 class="stat-number"><?= $tauxCouverture ?>%</h3>
                            <p class="stat-label">Taux de couverture</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Détails par ville -->
        <?php if (!empty($data)): ?>
            <?php foreach ($data as $index => $item): ?>
                <div class="modern-card mb-4 animate-fade-in" style="animation-delay: <?= $index * 0.1 ?>s">
                    <div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div class="d-flex align-items-center">
                            <div class="city-icon me-3">
                                <i class="bi bi-building"></i>
                            </div>
                            <div>
                                <h5 class="card-title mb-0"><?= htmlspecialchars($item['ville']['v_nom']) ?></h5>
                                <small class="text-muted"><?= count($item['besoins']) ?> besoin(s) · <?= count($item['distributions']) ?> distribution(s)</small>
                            </div>
                        </div>
                        <div class="d-flex gap-2">
                            <?php
                                $nbB = count($item['besoins']);
                                $nbD = count($item['distributions']);
                                $pct = $nbB > 0 ? round(($nbD / $nbB) * 100) : 0;
                            ?>
                            <span class="badge <?= $pct >= 75 ? 'bg-success-subtle text-success' : ($pct >= 40 ? 'bg-warning-subtle text-warning' : 'bg-danger-subtle text-danger') ?> px-3 py-2">
                                <i class="bi bi-<?= $pct >= 75 ? 'check-circle' : ($pct >= 40 ? 'exclamation-circle' : 'x-circle') ?> me-1"></i>
                                <?= $pct ?>% couvert
                            </span>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="row g-0">
                            <!-- Colonne Besoins -->
                            <div class="col-md-6" style="border-right: 1px solid var(--gray-100);">
                                <div class="p-3 pb-2">
                                    <h6 class="text-uppercase small fw-bold mb-3" style="color:var(--gray-500);letter-spacing:0.05em;">
                                        <i class="bi bi-exclamation-triangle me-1 text-warning"></i>Besoins
                                    </h6>
                                </div>
                                <?php if (!empty($item['besoins'])): ?>
                                    <div class="px-3 pb-3">
                                        <?php foreach ($item['besoins'] as $b): ?>
                                            <div class="donation-item">
                                                <div class="donation-icon" style="background:var(--warning-subtle);color:var(--warning-color);">
                                                    <i class="bi bi-box-seam"></i>
                                                </div>
                                                <div class="donation-info">
                                                    <strong><?= htmlspecialchars($b['besoin']) ?></strong>
                                                    <small class="text-muted d-block"><?= htmlspecialchars($b['unite']) ?></small>
                                                </div>
                                                <div class="donation-amount">
                                                    <span class="badge bg-warning-subtle text-warning px-3 py-2" style="font-size:0.85rem;">
                                                        <?= $b['quantite'] ?>
                                                    </span>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php else: ?>
                                    <div class="text-center text-muted py-4 px-3">
                                        <i class="bi bi-inbox" style="font-size:1.5rem;"></i>
                                        <p class="mb-0 mt-1 small">Aucun besoin enregistré</p>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <!-- Colonne Distributions -->
                            <div class="col-md-6">
                                <div class="p-3 pb-2">
                                    <h6 class="text-uppercase small fw-bold mb-3" style="color:var(--gray-500);letter-spacing:0.05em;">
                                        <i class="bi bi-truck me-1 text-success"></i>Distributions
                                    </h6>
                                </div>
                                <?php if (!empty($item['distributions'])): ?>
                                    <div class="px-3 pb-3">
                                        <?php foreach ($item['distributions'] as $d): ?>
                                            <div class="donation-item">
                                                <div class="donation-icon" style="background:var(--success-subtle);color:var(--success-color);">
                                                    <i class="bi bi-gift"></i>
                                                </div>
                                                <div class="donation-info">
                                                    <strong><?= htmlspecialchars($d['besoin']) ?></strong>
                                                    <small class="text-muted d-block">
                                                        <?= htmlspecialchars($d['unite']) ?> · 
                                                        <i class="bi bi-calendar2 me-1"></i><?= htmlspecialchars($d['date_distribution']) ?>
                                                    </small>
                                                </div>
                                                <div class="donation-amount">
                                                    <span class="badge bg-success-subtle text-success px-3 py-2" style="font-size:0.85rem;">
                                                        <?= $d['quantite'] ?>
                                                    </span>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php else: ?>
                                    <div class="text-center text-muted py-4 px-3">
                                        <i class="bi bi-inbox" style="font-size:1.5rem;"></i>
                                        <p class="mb-0 mt-1 small">Aucune distribution</p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <!-- Barre de progression -->
                    <div class="card-footer" style="background:var(--gray-50);border-top:1px solid var(--gray-100);padding:0.75rem 1.5rem;">
                        <div class="progress-wrapper">
                            <small class="text-muted fw-semibold" style="min-width:100px;">Couverture</small>
                            <div class="progress" style="height:8px;flex:1;">
                                <div class="progress-bar <?= $pct >= 75 ? 'bg-success' : ($pct >= 40 ? 'bg-warning' : 'bg-danger') ?>" 
                                     role="progressbar" 
                                     style="width: <?= $pct ?>%; border-radius: var(--radius-full);"
                                     aria-valuenow="<?= $pct ?>" aria-valuemin="0" aria-valuemax="100">
                                </div>
                            </div>
                            <small class="fw-bold <?= $pct >= 75 ? 'text-success' : ($pct >= 40 ? 'text-warning' : 'text-danger') ?>"><?= $pct ?>%</small>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <!-- État vide -->
            <div class="modern-card">
                <div class="card-body text-center py-5">
                    <div style="width:80px;height:80px;background:var(--gray-100);color:var(--gray-400);border-radius:var(--radius-lg);display:inline-flex;align-items:center;justify-content:center;font-size:2.5rem;" class="mb-3">
                        <i class="bi bi-inbox"></i>
                    </div>
                    <h5 class="text-muted mb-2">Aucune donnée disponible</h5>
                    <p class="text-muted small mb-4">Les données apparaîtront ici lorsque des besoins et distributions seront enregistrés.</p>
                    <a href="/distribution/saisie" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-1"></i> Saisir une distribution
                    </a>
                </div>
            </div>
        <?php endif; ?>
    </div>
</main>

<?php include ("inc/footer.php"); ?>