<?php $pageTitle = "Dons"; ?>
<?php include ("inc/header.php"); ?>

<main class="main-content">
    <!-- Hero Section -->
    <section class="hero-section hero-section-success mb-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-light mb-2">
                            <li class="breadcrumb-item"><a href="/">Tableau de bord</a></li>
                            <li class="breadcrumb-item active">Dons</li>
                        </ol>
                    </nav>
                    <h1 class="hero-title mb-3">
                        <i class="bi bi-gift me-2"></i>Gestion des Dons
                    </h1>
                    <p class="hero-subtitle mb-0">
                        Suivi des dons reçus et distribués aux villes sinistrées
                    </p>
                </div>
                <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                    <button class="btn btn-light" data-bs-toggle="modal" data-bs-target="#addDonModal">
                        <i class="bi bi-plus-lg me-1"></i>Nouveau don
                    </button>
                </div>
            </div>
        </div>
    </section>

    <div class="container">
        <!-- Statistiques des dons -->
        <div class="row g-4 mb-5">
            <div class="col-md-4">
                <div class="stat-card stat-card-success">
                    <div class="stat-card-body">
                        <div class="stat-icon">
                            <i class="bi bi-gift-fill"></i>
                        </div>
                        <div class="stat-info">
                            <h3 class="stat-number">32</h3>
                            <p class="stat-label">Dons totaux reçus</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card stat-card-info">
                    <div class="stat-card-body">
                        <div class="stat-icon">
                            <i class="bi bi-currency-exchange"></i>
                        </div>
                        <div class="stat-info">
                            <h3 class="stat-number">8,500,000 Ar</h3>
                            <p class="stat-label">Valeur totale</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card stat-card-primary">
                    <div class="stat-card-body">
                        <div class="stat-icon">
                            <i class="bi bi-people-fill"></i>
                        </div>
                        <div class="stat-info">
                            <h3 class="stat-number">18</h3>
                            <p class="stat-label">Donateurs</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filtres -->
        <div class="card modern-card mb-4">
            <div class="card-body">
                <div class="row g-3 align-items-end">
                    <div class="col-md-3">
                        <label class="form-label">Rechercher</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                            <input type="text" class="form-control" placeholder="Donateur ou besoin...">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Ville destinataire</label>
                        <select class="form-select">
                            <option value="">Toutes les villes</option>
                            <option>Antananarivo</option>
                            <option>Toamasina</option>
                            <option>Mahajanga</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Période</label>
                        <select class="form-select">
                            <option value="">Toutes</option>
                            <option>Aujourd'hui</option>
                            <option>Cette semaine</option>
                            <option>Ce mois</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Type de don</label>
                        <select class="form-select">
                            <option value="">Tous</option>
                            <option>Nature</option>
                            <option>Financier</option>
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

        <!-- Liste des dons -->
        <div class="card modern-card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="card-title mb-1">Historique des Dons</h5>
                    <small class="text-muted">32 dons enregistrés</small>
                </div>
                <button class="btn btn-outline-success btn-sm">
                    <i class="bi bi-download me-1"></i>Exporter
                </button>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover modern-table mb-0">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Donateur</th>
                                <th>Besoin</th>
                                <th>Ville</th>
                                <th>Quantité</th>
                                <th>Valeur</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <span class="text-muted">15/02/2026</span>
                                    <small class="d-block text-muted">10:30</small>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm bg-primary-subtle text-primary me-2">
                                            <i class="bi bi-person"></i>
                                        </div>
                                        <div>
                                            <strong>Association Solidarité</strong>
                                            <small class="d-block text-muted">ONG</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-warning-subtle text-warning">Riz</span>
                                </td>
                                <td>Antananarivo</td>
                                <td><strong>50 sacs</strong></td>
                                <td class="text-success fw-semibold">500,000 Ar</td>
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm">
                                        <button class="btn btn-outline-primary" title="Voir">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                        <button class="btn btn-outline-danger" title="Supprimer">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <span class="text-muted">14/02/2026</span>
                                    <small class="d-block text-muted">14:15</small>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm bg-success-subtle text-success me-2">
                                            <i class="bi bi-building"></i>
                                        </div>
                                        <div>
                                            <strong>Pharmacie Centrale</strong>
                                            <small class="d-block text-muted">Entreprise</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-danger-subtle text-danger">Médicaments</span>
                                </td>
                                <td>Toamasina</td>
                                <td><strong>5 kits</strong></td>
                                <td class="text-success fw-semibold">250,000 Ar</td>
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm">
                                        <button class="btn btn-outline-primary" title="Voir">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                        <button class="btn btn-outline-danger" title="Supprimer">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <span class="text-muted">14/02/2026</span>
                                    <small class="d-block text-muted">09:00</small>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm bg-info-subtle text-info me-2">
                                            <i class="bi bi-person"></i>
                                        </div>
                                        <div>
                                            <strong>Jean Rakoto</strong>
                                            <small class="d-block text-muted">Particulier</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-info-subtle text-info">Eau potable</span>
                                </td>
                                <td>Antananarivo</td>
                                <td><strong>200 litres</strong></td>
                                <td class="text-success fw-semibold">100,000 Ar</td>
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm">
                                        <button class="btn btn-outline-primary" title="Voir">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                        <button class="btn btn-outline-danger" title="Supprimer">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <span class="text-muted">13/02/2026</span>
                                    <small class="d-block text-muted">16:45</small>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm bg-warning-subtle text-warning me-2">
                                            <i class="bi bi-building"></i>
                                        </div>
                                        <div>
                                            <strong>JIRAMA</strong>
                                            <small class="d-block text-muted">Entreprise</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-success-subtle text-success">Tentes</span>
                                </td>
                                <td>Mahajanga</td>
                                <td><strong>10 unités</strong></td>
                                <td class="text-success fw-semibold">1,500,000 Ar</td>
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm">
                                        <button class="btn btn-outline-primary" title="Voir">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                        <button class="btn btn-outline-danger" title="Supprimer">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <span class="text-muted">12/02/2026</span>
                                    <small class="d-block text-muted">11:20</small>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm bg-primary-subtle text-primary me-2">
                                            <i class="bi bi-person"></i>
                                        </div>
                                        <div>
                                            <strong>Marie Ratsimba</strong>
                                            <small class="d-block text-muted">Particulier</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-secondary-subtle text-secondary">Couvertures</span>
                                </td>
                                <td>Fianarantsoa</td>
                                <td><strong>20 unités</strong></td>
                                <td class="text-success fw-semibold">500,000 Ar</td>
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm">
                                        <button class="btn btn-outline-primary" title="Voir">
                                            <i class="bi bi-eye"></i>
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
            <div class="card-footer">
                <nav>
                    <ul class="pagination pagination-sm justify-content-center mb-0">
                        <li class="page-item disabled">
                            <a class="page-link" href="#">Précédent</a>
                        </li>
                        <li class="page-item active"><a class="page-link" href="#">1</a></li>
                        <li class="page-item"><a class="page-link" href="#">2</a></li>
                        <li class="page-item"><a class="page-link" href="#">3</a></li>
                        <li class="page-item">
                            <a class="page-link" href="#">Suivant</a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</main>

<!-- Modal Ajouter Don -->
<div class="modal fade" id="addDonModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-success-subtle">
                <h5 class="modal-title text-success">
                    <i class="bi bi-gift me-2"></i>Enregistrer un don
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="/dons/store" method="POST">
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Nom du donateur <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="donateur" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Type de donateur</label>
                            <select class="form-select" name="type_donateur">
                                <option value="particulier">Particulier</option>
                                <option value="entreprise">Entreprise</option>
                                <option value="ong">ONG / Association</option>
                                <option value="gouvernement">Gouvernement</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Ville destinataire <span class="text-danger">*</span></label>
                            <select class="form-select" name="ville_id" required id="selectVille">
                                <option value="">Sélectionner une ville</option>
                                <option value="1">Antananarivo</option>
                                <option value="2">Toamasina</option>
                                <option value="3">Mahajanga</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Besoin concerné <span class="text-danger">*</span></label>
                            <select class="form-select" name="besoin_id" required id="selectBesoin">
                                <option value="">Sélectionner d'abord une ville</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Quantité <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" name="quantite" required min="1">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Date du don</label>
                            <input type="date" class="form-control" name="date_don" value="<?= date('Y-m-d') ?>">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Remarques</label>
                            <textarea class="form-control" name="remarques" rows="2" placeholder="Notes additionnelles..."></textarea>
                        </div>
                    </div>
                    
                    <div class="alert alert-info mt-3 mb-0">
                        <i class="bi bi-info-circle me-2"></i>
                        <strong>Note :</strong> La valeur du don sera calculée automatiquement selon le prix unitaire du besoin.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-check-lg me-1"></i>Enregistrer le don
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.avatar-sm {
    width: 36px;
    height: 36px;
    border-radius: var(--radius);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.9rem;
}

.hero-section-success {
    background: linear-gradient(135deg, #059669 0%, #047857 50%, #065f46 100%) !important;
}

.breadcrumb-light {
    margin-bottom: 0;
}

.breadcrumb-light .breadcrumb-item a {
    color: rgba(255, 255, 255, 0.8);
    text-decoration: none;
}

.breadcrumb-light .breadcrumb-item.active {
    color: rgba(255, 255, 255, 0.6);
}

.breadcrumb-light .breadcrumb-item + .breadcrumb-item::before {
    color: rgba(255, 255, 255, 0.5);
}
</style>

<?php include ("inc/footer.php"); ?>
