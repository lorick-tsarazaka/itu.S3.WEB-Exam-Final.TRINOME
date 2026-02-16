<?php $pageTitle = "Villes"; ?>
<?php include ("inc/header.php"); ?>

<main class="main-content">
    <!-- Hero Section -->
    <section class="hero-section mb-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-light mb-2">
                            <li class="breadcrumb-item"><a href="/">Tableau de bord</a></li>
                            <li class="breadcrumb-item active">Villes</li>
                        </ol>
                    </nav>
                    <h1 class="hero-title mb-3">
                        <i class="bi bi-geo-alt me-2"></i>Gestion des Villes
                    </h1>
                    <p class="hero-subtitle mb-0">
                        Liste des villes sinistrées et leurs besoins associés
                    </p>
                </div>
                <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                    <button class="btn btn-light" data-bs-toggle="modal" data-bs-target="#addVilleModal">
                        <i class="bi bi-plus-lg me-1"></i>Nouvelle ville
                    </button>
                </div>
            </div>
        </div>
    </section>

    <div class="container">
        <!-- Filtres et recherche -->
        <div class="card modern-card mb-4">
            <div class="card-body">
                <div class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label class="form-label">Rechercher</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white">
                                <i class="bi bi-search"></i>
                            </span>
                            <input type="text" class="form-control" placeholder="Nom de la ville..." id="searchVille">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Région</label>
                        <select class="form-select" id="filterRegion">
                            <option value="">Toutes les régions</option>
                            <option value="analamanga">Analamanga</option>
                            <option value="atsinanana">Atsinanana</option>
                            <option value="boeny">Boeny</option>
                            <option value="vakinankaratra">Vakinankaratra</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Statut</label>
                        <select class="form-select" id="filterStatus">
                            <option value="">Tous les statuts</option>
                            <option value="urgent">Urgent</option>
                            <option value="en-cours">En cours</option>
                            <option value="complet">Besoins satisfaits</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-outline-secondary w-100">
                            <i class="bi bi-funnel me-1"></i>Filtrer
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Liste des villes en cards -->
        <div class="row g-4">
            <!-- Card Ville 1 -->
            <div class="col-md-6 col-lg-4">
                <div class="card modern-card h-100 ville-card">
                    <div class="card-header d-flex justify-content-between align-items-start">
                        <div class="d-flex align-items-center">
                            <div class="city-icon me-3" style="width: 50px; height: 50px; font-size: 1.25rem;">
                                <i class="bi bi-geo-alt-fill"></i>
                            </div>
                            <div>
                                <h5 class="card-title mb-1">Antananarivo</h5>
                                <small class="text-muted">Région Analamanga</small>
                            </div>
                        </div>
                        <span class="badge bg-warning-subtle text-warning">En cours</span>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-1">
                                <span class="text-muted small">Progression des dons</span>
                                <span class="fw-semibold small">72%</span>
                            </div>
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar bg-success" style="width: 72%;"></div>
                            </div>
                        </div>
                        <div class="row text-center g-2">
                            <div class="col-4">
                                <div class="p-2 bg-light rounded">
                                    <div class="fw-bold text-primary">5</div>
                                    <small class="text-muted">Besoins</small>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="p-2 bg-light rounded">
                                    <div class="fw-bold text-success">12</div>
                                    <small class="text-muted">Dons</small>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="p-2 bg-light rounded">
                                    <div class="fw-bold text-info">2.5M</div>
                                    <small class="text-muted">Ariary</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer d-flex gap-2">
                        <a href="/villes/1" class="btn btn-outline-primary btn-sm flex-fill">
                            <i class="bi bi-eye me-1"></i>Détails
                        </a>
                        <a href="/villes/1/dons/nouveau" class="btn btn-success btn-sm flex-fill">
                            <i class="bi bi-plus-circle me-1"></i>Don
                        </a>
                    </div>
                </div>
            </div>

            <!-- Card Ville 2 -->
            <div class="col-md-6 col-lg-4">
                <div class="card modern-card h-100 ville-card border-danger">
                    <div class="card-header d-flex justify-content-between align-items-start">
                        <div class="d-flex align-items-center">
                            <div class="city-icon me-3" style="width: 50px; height: 50px; font-size: 1.25rem; background: var(--danger-subtle); color: var(--danger-color);">
                                <i class="bi bi-geo-alt-fill"></i>
                            </div>
                            <div>
                                <h5 class="card-title mb-1">Toamasina</h5>
                                <small class="text-muted">Région Atsinanana</small>
                            </div>
                        </div>
                        <span class="badge bg-danger">Urgent</span>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-1">
                                <span class="text-muted small">Progression des dons</span>
                                <span class="fw-semibold small text-danger">29%</span>
                            </div>
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar bg-danger" style="width: 29%;"></div>
                            </div>
                        </div>
                        <div class="row text-center g-2">
                            <div class="col-4">
                                <div class="p-2 bg-light rounded">
                                    <div class="fw-bold text-primary">8</div>
                                    <small class="text-muted">Besoins</small>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="p-2 bg-light rounded">
                                    <div class="fw-bold text-success">5</div>
                                    <small class="text-muted">Dons</small>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="p-2 bg-light rounded">
                                    <div class="fw-bold text-info">4.2M</div>
                                    <small class="text-muted">Ariary</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer d-flex gap-2">
                        <a href="/villes/2" class="btn btn-outline-primary btn-sm flex-fill">
                            <i class="bi bi-eye me-1"></i>Détails
                        </a>
                        <a href="/villes/2/dons/nouveau" class="btn btn-success btn-sm flex-fill">
                            <i class="bi bi-plus-circle me-1"></i>Don
                        </a>
                    </div>
                </div>
            </div>

            <!-- Card Ville 3 -->
            <div class="col-md-6 col-lg-4">
                <div class="card modern-card h-100 ville-card border-success">
                    <div class="card-header d-flex justify-content-between align-items-start">
                        <div class="d-flex align-items-center">
                            <div class="city-icon me-3" style="width: 50px; height: 50px; font-size: 1.25rem; background: var(--success-subtle); color: var(--success-color);">
                                <i class="bi bi-geo-alt-fill"></i>
                            </div>
                            <div>
                                <h5 class="card-title mb-1">Mahajanga</h5>
                                <small class="text-muted">Région Boeny</small>
                            </div>
                        </div>
                        <span class="badge bg-success">Complet</span>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-1">
                                <span class="text-muted small">Progression des dons</span>
                                <span class="fw-semibold small text-success">100%</span>
                            </div>
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar bg-success" style="width: 100%;"></div>
                            </div>
                        </div>
                        <div class="row text-center g-2">
                            <div class="col-4">
                                <div class="p-2 bg-light rounded">
                                    <div class="fw-bold text-primary">3</div>
                                    <small class="text-muted">Besoins</small>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="p-2 bg-light rounded">
                                    <div class="fw-bold text-success">8</div>
                                    <small class="text-muted">Dons</small>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="p-2 bg-light rounded">
                                    <div class="fw-bold text-info">1.8M</div>
                                    <small class="text-muted">Ariary</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer d-flex gap-2">
                        <a href="/villes/3" class="btn btn-outline-primary btn-sm flex-fill">
                            <i class="bi bi-eye me-1"></i>Détails
                        </a>
                        <a href="/villes/3/dons/nouveau" class="btn btn-success btn-sm flex-fill">
                            <i class="bi bi-plus-circle me-1"></i>Don
                        </a>
                    </div>
                </div>
            </div>

            <!-- Card Ville 4 -->
            <div class="col-md-6 col-lg-4">
                <div class="card modern-card h-100 ville-card">
                    <div class="card-header d-flex justify-content-between align-items-start">
                        <div class="d-flex align-items-center">
                            <div class="city-icon me-3" style="width: 50px; height: 50px; font-size: 1.25rem;">
                                <i class="bi bi-geo-alt-fill"></i>
                            </div>
                            <div>
                                <h5 class="card-title mb-1">Fianarantsoa</h5>
                                <small class="text-muted">Région Haute Matsiatra</small>
                            </div>
                        </div>
                        <span class="badge bg-warning-subtle text-warning">En cours</span>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-1">
                                <span class="text-muted small">Progression des dons</span>
                                <span class="fw-semibold small">55%</span>
                            </div>
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar bg-warning" style="width: 55%;"></div>
                            </div>
                        </div>
                        <div class="row text-center g-2">
                            <div class="col-4">
                                <div class="p-2 bg-light rounded">
                                    <div class="fw-bold text-primary">6</div>
                                    <small class="text-muted">Besoins</small>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="p-2 bg-light rounded">
                                    <div class="fw-bold text-success">7</div>
                                    <small class="text-muted">Dons</small>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="p-2 bg-light rounded">
                                    <div class="fw-bold text-info">1.2M</div>
                                    <small class="text-muted">Ariary</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer d-flex gap-2">
                        <a href="/villes/4" class="btn btn-outline-primary btn-sm flex-fill">
                            <i class="bi bi-eye me-1"></i>Détails
                        </a>
                        <a href="/villes/4/dons/nouveau" class="btn btn-success btn-sm flex-fill">
                            <i class="bi bi-plus-circle me-1"></i>Don
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<!-- Modal Ajouter Ville -->
<div class="modal fade" id="addVilleModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-plus-circle me-2"></i>Ajouter une ville
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="/villes/store" method="POST">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nom de la ville <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="nom" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Région <span class="text-danger">*</span></label>
                        <select class="form-select" name="region" required>
                            <option value="">Sélectionner une région</option>
                            <option value="Analamanga">Analamanga</option>
                            <option value="Atsinanana">Atsinanana</option>
                            <option value="Boeny">Boeny</option>
                            <option value="Haute Matsiatra">Haute Matsiatra</option>
                            <option value="Vakinankaratra">Vakinankaratra</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea class="form-control" name="description" rows="3" placeholder="Description de la situation..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-lg me-1"></i>Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include ("inc/footer.php"); ?>
