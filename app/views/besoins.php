<?php $pageTitle = "Besoins"; ?>
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
                            <li class="breadcrumb-item active">Besoins</li>
                        </ol>
                    </nav>
                    <h1 class="hero-title mb-3">
                        <i class="bi bi-list-check me-2"></i>Gestion des Besoins
                    </h1>
                    <p class="hero-subtitle mb-0">
                        Liste des besoins des villes sinistrées avec prix unitaire et quantité
                    </p>
                </div>
                <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                    <button class="btn btn-light" data-bs-toggle="modal" data-bs-target="#addBesoinModal">
                        <i class="bi bi-plus-lg me-1"></i>Nouveau besoin
                    </button>
                </div>
            </div>
        </div>
    </section>

    <div class="container">
        <!-- Filtres -->
        <div class="card modern-card mb-4">
            <div class="card-body">
                <div class="row g-3 align-items-end">
                    <div class="col-md-3">
                        <label class="form-label">Rechercher</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                            <input type="text" class="form-control" placeholder="Nom du besoin...">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Ville</label>
                        <select class="form-select">
                            <option value="">Toutes les villes</option>
                            <option>Antananarivo</option>
                            <option>Toamasina</option>
                            <option>Mahajanga</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Catégorie</label>
                        <select class="form-select">
                            <option value="">Toutes</option>
                            <option>Alimentation</option>
                            <option>Médicaments</option>
                            <option>Abris</option>
                            <option>Vêtements</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Statut</label>
                        <select class="form-select">
                            <option value="">Tous</option>
                            <option value="urgent">Urgent</option>
                            <option value="partiel">Partiel</option>
                            <option value="satisfait">Satisfait</option>
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

        <!-- Liste des besoins -->
        <div class="card modern-card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="card-title mb-1">Liste des Besoins</h5>
                    <small class="text-muted">16 besoins enregistrés</small>
                </div>
                <div class="btn-group btn-group-sm">
                    <button class="btn btn-outline-secondary active">
                        <i class="bi bi-list"></i>
                    </button>
                    <button class="btn btn-outline-secondary">
                        <i class="bi bi-grid"></i>
                    </button>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover modern-table mb-0">
                        <thead>
                            <tr>
                                <th>Besoin</th>
                                <th>Ville</th>
                                <th>Prix Unitaire</th>
                                <th>Quantité Requise</th>
                                <th>Montant Total</th>
                                <th>Quantité Reçue</th>
                                <th>Progression</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="besoin-icon bg-warning-subtle text-warning me-2">
                                            <i class="bi bi-basket"></i>
                                        </div>
                                        <div>
                                            <strong>Riz</strong>
                                            <small class="d-block text-muted">Alimentation</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-primary-subtle text-primary">Antananarivo</span>
                                </td>
                                <td><strong>10,000 Ar</strong></td>
                                <td>100 sacs</td>
                                <td class="fw-semibold">1,000,000 Ar</td>
                                <td>
                                    <span class="text-success">72 sacs</span>
                                </td>
                                <td style="min-width: 130px;">
                                    <div class="progress-wrapper">
                                        <div class="progress" style="height: 6px;">
                                            <div class="progress-bar bg-success" style="width: 72%;"></div>
                                        </div>
                                        <small class="text-muted">72%</small>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm">
                                        <button class="btn btn-outline-primary" title="Modifier">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <button class="btn btn-outline-danger" title="Supprimer">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr class="table-danger">
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="besoin-icon bg-danger-subtle text-danger me-2">
                                            <i class="bi bi-capsule"></i>
                                        </div>
                                        <div>
                                            <strong>Médicaments de base</strong>
                                            <small class="d-block text-muted">Santé</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-danger-subtle text-danger">Toamasina</span>
                                </td>
                                <td><strong>50,000 Ar</strong></td>
                                <td>50 kits</td>
                                <td class="fw-semibold">2,500,000 Ar</td>
                                <td>
                                    <span class="text-danger">5 kits</span>
                                </td>
                                <td style="min-width: 130px;">
                                    <div class="progress-wrapper">
                                        <div class="progress" style="height: 6px;">
                                            <div class="progress-bar bg-danger" style="width: 10%;"></div>
                                        </div>
                                        <small class="text-danger fw-semibold">10%</small>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm">
                                        <button class="btn btn-outline-primary" title="Modifier">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <button class="btn btn-outline-danger" title="Supprimer">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="besoin-icon bg-info-subtle text-info me-2">
                                            <i class="bi bi-droplet"></i>
                                        </div>
                                        <div>
                                            <strong>Eau potable</strong>
                                            <small class="d-block text-muted">Essentiel</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-primary-subtle text-primary">Antananarivo</span>
                                </td>
                                <td><strong>500 Ar</strong></td>
                                <td>500 litres</td>
                                <td class="fw-semibold">250,000 Ar</td>
                                <td>
                                    <span class="text-success">400 litres</span>
                                </td>
                                <td style="min-width: 130px;">
                                    <div class="progress-wrapper">
                                        <div class="progress" style="height: 6px;">
                                            <div class="progress-bar bg-success" style="width: 80%;"></div>
                                        </div>
                                        <small class="text-muted">80%</small>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm">
                                        <button class="btn btn-outline-primary" title="Modifier">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <button class="btn btn-outline-danger" title="Supprimer">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr class="table-success">
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="besoin-icon bg-success-subtle text-success me-2">
                                            <i class="bi bi-house"></i>
                                        </div>
                                        <div>
                                            <strong>Tentes</strong>
                                            <small class="d-block text-muted">Abris</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-success-subtle text-success">Mahajanga</span>
                                </td>
                                <td><strong>150,000 Ar</strong></td>
                                <td>20 unités</td>
                                <td class="fw-semibold">3,000,000 Ar</td>
                                <td>
                                    <span class="text-success">20 unités</span>
                                </td>
                                <td style="min-width: 130px;">
                                    <div class="progress-wrapper">
                                        <div class="progress" style="height: 6px;">
                                            <div class="progress-bar bg-success" style="width: 100%;"></div>
                                        </div>
                                        <small class="text-success fw-semibold">100% ✓</small>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm">
                                        <button class="btn btn-outline-primary" title="Modifier">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <button class="btn btn-outline-danger" title="Supprimer">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="besoin-icon bg-secondary-subtle text-secondary me-2">
                                            <i class="bi bi-bandaid"></i>
                                        </div>
                                        <div>
                                            <strong>Couvertures</strong>
                                            <small class="d-block text-muted">Vêtements</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-warning-subtle text-warning">Fianarantsoa</span>
                                </td>
                                <td><strong>25,000 Ar</strong></td>
                                <td>80 unités</td>
                                <td class="fw-semibold">2,000,000 Ar</td>
                                <td>
                                    <span class="text-warning">44 unités</span>
                                </td>
                                <td style="min-width: 130px;">
                                    <div class="progress-wrapper">
                                        <div class="progress" style="height: 6px;">
                                            <div class="progress-bar bg-warning" style="width: 55%;"></div>
                                        </div>
                                        <small class="text-muted">55%</small>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm">
                                        <button class="btn btn-outline-primary" title="Modifier">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <button class="btn btn-outline-danger" title="Supprimer">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</main>

<!-- Modal Ajouter Besoin -->
<div class="modal fade" id="addBesoinModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-plus-circle me-2"></i>Ajouter un besoin
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="/besoins/store" method="POST">
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Nom du besoin <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="nom" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Ville concernée <span class="text-danger">*</span></label>
                            <select class="form-select" name="ville_id" required>
                                <option value="">Sélectionner une ville</option>
                                <option value="1">Antananarivo</option>
                                <option value="2">Toamasina</option>
                                <option value="3">Mahajanga</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Catégorie</label>
                            <select class="form-select" name="categorie">
                                <option value="alimentation">Alimentation</option>
                                <option value="medicaments">Médicaments</option>
                                <option value="abris">Abris</option>
                                <option value="vetements">Vêtements</option>
                                <option value="autre">Autre</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Unité de mesure</label>
                            <input type="text" class="form-control" name="unite" placeholder="ex: sacs, litres, kits...">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Prix unitaire (Ar) <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" name="prix_unitaire" required min="0">
                            <small class="text-muted">Ce prix ne changera pas</small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Quantité requise <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" name="quantite" required min="1">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Description</label>
                            <textarea class="form-control" name="description" rows="2"></textarea>
                        </div>
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
</style>

<?php include ("inc/footer.php"); ?>
