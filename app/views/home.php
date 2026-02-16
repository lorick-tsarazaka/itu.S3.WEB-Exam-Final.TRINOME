<?php $pageTitle = "Tableau de bord"; ?>
<?php include ("inc/header.php"); ?>

<main class="main-content">
    <!-- Hero Section -->
    <section class="hero-section mb-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <h1 class="hero-title mb-3">
                        <i class="bi bi-speedometer2 me-2"></i>Tableau de Bord
                    </h1>
                    <p class="hero-subtitle mb-0">
                        Suivi en temps réel des collectes et distributions de dons pour les sinistrés
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
                            <h3 class="stat-number" id="totalVilles">0</h3>
                            <p class="stat-label">Villes sinistrées</p>
                        </div>
                    </div>
                    <div class="stat-card-footer">
                        <a href="/villes" class="stat-link">
                            Voir détails <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xl-3">
                <div class="stat-card stat-card-success">
                    <div class="stat-card-body">
                        <div class="stat-icon">
                            <i class="bi bi-gift-fill"></i>
                        </div>
                        <div class="stat-info">
                            <h3 class="stat-number" id="totalDons">0</h3>
                            <p class="stat-label">Dons reçus</p>
                        </div>
                    </div>
                    <div class="stat-card-footer">
                        <a href="/dons" class="stat-link">
                            Voir détails <i class="bi bi-arrow-right"></i>
                        </a>
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
                            <h3 class="stat-number" id="totalBesoins">0</h3>
                            <p class="stat-label">Besoins en attente</p>
                        </div>
                    </div>
                    <div class="stat-card-footer">
                        <a href="/besoins" class="stat-link">
                            Voir détails <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xl-3">
                <div class="stat-card stat-card-info">
                    <div class="stat-card-body">
                        <div class="stat-icon">
                            <i class="bi bi-currency-exchange"></i>
                        </div>
                        <div class="stat-info">
                            <h3 class="stat-number" id="totalMontant">0 Ar</h3>
                            <p class="stat-label">Valeur totale</p>
                        </div>
                    </div>
                    <div class="stat-card-footer">
                        <span class="text-muted small">Mise à jour automatique</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content Grid -->
        <div class="row g-4">
            <!-- Liste des villes avec besoins -->
            <div class="col-lg-8">
                <div class="card modern-card h-100">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title mb-1">
                                <i class="bi bi-map me-2"></i>Villes et Besoins
                            </h5>
                            <small class="text-muted">Liste des villes sinistrées avec leurs besoins</small>
                        </div>
                        <a href="/villes/nouveau" class="btn btn-primary btn-sm">
                            <i class="bi bi-plus-lg me-1"></i>Ajouter
                        </a>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover modern-table mb-0">
                                <thead>
                                    <tr>
                                        <th>Ville</th>
                                        <th>Besoins</th>
                                        <th>Montant Total</th>
                                        <th>Dons Attribués</th>
                                        <th>Progression</th>
                                        <th class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="villesTableBody">
                                    <!-- Exemple de données (à remplacer par les données dynamiques) -->
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="city-icon me-2">
                                                    <i class="bi bi-geo-alt"></i>
                                                </div>
                                                <div>
                                                    <strong>Antananarivo</strong>
                                                    <small class="d-block text-muted">Région Analamanga</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-warning-subtle text-warning">5 besoins</span>
                                        </td>
                                        <td>
                                            <strong>2,500,000 Ar</strong>
                                        </td>
                                        <td>
                                            <span class="text-success fw-semibold">1,800,000 Ar</span>
                                        </td>
                                        <td style="min-width: 150px;">
                                            <div class="progress-wrapper">
                                                <div class="progress" style="height: 8px;">
                                                    <div class="progress-bar bg-success" style="width: 72%;"></div>
                                                </div>
                                                <small class="text-muted">72%</small>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group btn-group-sm">
                                                <a href="#" class="btn btn-outline-primary" title="Voir détails">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                <a href="#" class="btn btn-outline-success" title="Ajouter un don">
                                                    <i class="bi bi-plus-circle"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="city-icon me-2">
                                                    <i class="bi bi-geo-alt"></i>
                                                </div>
                                                <div>
                                                    <strong>Toamasina</strong>
                                                    <small class="d-block text-muted">Région Atsinanana</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-danger-subtle text-danger">8 besoins</span>
                                        </td>
                                        <td>
                                            <strong>4,200,000 Ar</strong>
                                        </td>
                                        <td>
                                            <span class="text-success fw-semibold">1,200,000 Ar</span>
                                        </td>
                                        <td style="min-width: 150px;">
                                            <div class="progress-wrapper">
                                                <div class="progress" style="height: 8px;">
                                                    <div class="progress-bar bg-warning" style="width: 29%;"></div>
                                                </div>
                                                <small class="text-muted">29%</small>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group btn-group-sm">
                                                <a href="#" class="btn btn-outline-primary" title="Voir détails">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                <a href="#" class="btn btn-outline-success" title="Ajouter un don">
                                                    <i class="bi bi-plus-circle"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="city-icon me-2">
                                                    <i class="bi bi-geo-alt"></i>
                                                </div>
                                                <div>
                                                    <strong>Mahajanga</strong>
                                                    <small class="d-block text-muted">Région Boeny</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-warning-subtle text-warning">3 besoins</span>
                                        </td>
                                        <td>
                                            <strong>1,800,000 Ar</strong>
                                        </td>
                                        <td>
                                            <span class="text-success fw-semibold">1,800,000 Ar</span>
                                        </td>
                                        <td style="min-width: 150px;">
                                            <div class="progress-wrapper">
                                                <div class="progress" style="height: 8px;">
                                                    <div class="progress-bar bg-success" style="width: 100%;"></div>
                                                </div>
                                                <small class="text-success">100% ✓</small>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group btn-group-sm">
                                                <a href="#" class="btn btn-outline-primary" title="Voir détails">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                <a href="#" class="btn btn-outline-success" title="Ajouter un don">
                                                    <i class="bi bi-plus-circle"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar - Derniers dons -->
            <div class="col-lg-4">
                <div class="card modern-card h-100">
                    <div class="card-header">
                        <h5 class="card-title mb-1">
                            <i class="bi bi-clock-history me-2"></i>Derniers Dons
                        </h5>
                        <small class="text-muted">Dons récemment reçus</small>
                    </div>
                    <div class="card-body">
                        <div class="donation-list">
                            <!-- Exemple de don -->
                            <div class="donation-item">
                                <div class="donation-icon bg-success-subtle">
                                    <i class="bi bi-gift text-success"></i>
                                </div>
                                <div class="donation-info">
                                    <strong>Riz - 50 sacs</strong>
                                    <small class="d-block text-muted">Antananarivo</small>
                                </div>
                                <div class="donation-amount">
                                    <span class="badge bg-success">500,000 Ar</span>
                                </div>
                            </div>
                            <div class="donation-item">
                                <div class="donation-icon bg-primary-subtle">
                                    <i class="bi bi-droplet text-primary"></i>
                                </div>
                                <div class="donation-info">
                                    <strong>Eau potable - 200L</strong>
                                    <small class="d-block text-muted">Toamasina</small>
                                </div>
                                <div class="donation-amount">
                                    <span class="badge bg-primary">100,000 Ar</span>
                                </div>
                            </div>
                            <div class="donation-item">
                                <div class="donation-icon bg-warning-subtle">
                                    <i class="bi bi-bandaid text-warning"></i>
                                </div>
                                <div class="donation-info">
                                    <strong>Médicaments</strong>
                                    <small class="d-block text-muted">Mahajanga</small>
                                </div>
                                <div class="donation-amount">
                                    <span class="badge bg-warning text-dark">300,000 Ar</span>
                                </div>
                            </div>
                            <div class="donation-item">
                                <div class="donation-icon bg-info-subtle">
                                    <i class="bi bi-house text-info"></i>
                                </div>
                                <div class="donation-info">
                                    <strong>Tentes - 10 unités</strong>
                                    <small class="d-block text-muted">Fianarantsoa</small>
                                </div>
                                <div class="donation-amount">
                                    <span class="badge bg-info">800,000 Ar</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-center">
                        <a href="/dons" class="btn btn-outline-primary btn-sm w-100">
                            <i class="bi bi-list me-1"></i>Voir tous les dons
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section des besoins urgents -->
        <div class="row mt-5">
            <div class="col-12">
                <div class="card modern-card border-danger">
                    <div class="card-header bg-danger-subtle">
                        <h5 class="card-title mb-1 text-danger">
                            <i class="bi bi-exclamation-octagon me-2"></i>Besoins Urgents
                        </h5>
                        <small class="text-muted">Besoins prioritaires nécessitant une attention immédiate</small>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6 col-lg-3">
                                <div class="urgent-need-card">
                                    <div class="urgent-badge">URGENT</div>
                                    <h6 class="mb-2">Nourriture</h6>
                                    <p class="text-muted small mb-2">Toamasina</p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="fw-bold">1,000,000 Ar</span>
                                        <span class="badge bg-danger">0%</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-3">
                                <div class="urgent-need-card">
                                    <div class="urgent-badge">URGENT</div>
                                    <h6 class="mb-2">Couvertures</h6>
                                    <p class="text-muted small mb-2">Antsirabe</p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="fw-bold">500,000 Ar</span>
                                        <span class="badge bg-warning text-dark">15%</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-3">
                                <div class="urgent-need-card">
                                    <div class="urgent-badge">URGENT</div>
                                    <h6 class="mb-2">Médicaments</h6>
                                    <p class="text-muted small mb-2">Morondava</p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="fw-bold">800,000 Ar</span>
                                        <span class="badge bg-danger">5%</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-3">
                                <div class="urgent-need-card">
                                    <div class="urgent-badge">URGENT</div>
                                    <h6 class="mb-2">Eau potable</h6>
                                    <p class="text-muted small mb-2">Manakara</p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="fw-bold">300,000 Ar</span>
                                        <span class="badge bg-warning text-dark">20%</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include ("inc/footer.php"); ?>