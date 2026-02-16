<?php $pageTitle = "Récapitulation"; ?>
<?php
    $breadcrumbs = [
        ['label' => 'Accueil', 'url' => '/'],
        ['label' => 'Récapitulation']
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
                        <i class="bi bi-graph-up me-2"></i>Récapitulation
                    </h1>
                    <p class="hero-subtitle mb-0">
                        Vue d'ensemble des besoins, distributions et montants
                    </p>
                </div>
            </div>
        </div>
    </section>

    <div class="container">
        <!-- Filtres -->
        <div class="card modern-card mb-4">
            <div class="card-body">
                <form id="formFiltres" method="GET" class="row g-3 align-items-end">
                    <div class="col-md-5 col-lg-4 flex-grow-1">
                        <label for="filterVille" class="form-label fw-semibold">
                            <i class="bi bi-geo-alt me-1 text-primary"></i>Filtrer par ville
                        </label>
                        <select class="form-select" id="filterVille" name="ville_id">
                            <option value="">Toutes les villes</option>
                            <?php if (isset($villes) && !empty($villes)): ?>
                                <?php foreach ($villes as $ville): ?>
                                    <option value="<?= $ville['v_id'] ?>" <?= (isset($_GET['ville_id']) && $_GET['ville_id'] == $ville['v_id']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($ville['v_nom']) ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div class="col-md-3 col-lg-2">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-funnel me-1"></i>Filtrer
                        </button>
                    </div>
                    <div class="col-md-3 col-lg-2">
                        <a href="/recapitulation" class="btn btn-outline-secondary w-100">
                            <i class="bi bi-x-lg me-1"></i>Réinitialiser
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Récapitulation groupée par ville -->
        <div id="recapTableContainer">
        <?php if (isset($recapitulations) && !empty($recapitulations)): ?>
        <?php
            // Grouper par ville
            $parVille = [];
            foreach ($recapitulations as $recap) {
                $vid = $recap['ville_id'];
                if (!isset($parVille[$vid])) {
                    $parVille[$vid] = [
                        'ville' => $recap['ville'],
                        'besoins' => [],
                        'total_demande' => 0,
                        'total_distribue' => 0,
                        'total_reste' => 0,
                        'montant_demande' => 0,
                        'montant_distribue' => 0,
                        'montant_reste' => 0
                    ];
                }
                $parVille[$vid]['besoins'][] = $recap;
                $parVille[$vid]['total_demande'] += $recap['total_demande'];
                $parVille[$vid]['total_distribue'] += $recap['total_distribue'];
                $parVille[$vid]['total_reste'] += $recap['reste'];
                $parVille[$vid]['montant_demande'] += $recap['montant_demande'];
                $parVille[$vid]['montant_distribue'] += $recap['montant_distribue'];
                $parVille[$vid]['montant_reste'] += $recap['montant_reste'];
            }
        ?>
        <div class="row g-4">
            <?php foreach ($parVille as $villeId => $ville): ?>
            <?php
                $pourcentage = $ville['total_demande'] > 0 
                    ? round(($ville['total_distribue'] / $ville['total_demande']) * 100) 
                    : 0;
                $badgeClass = $pourcentage >= 100 ? 'bg-success' : ($pourcentage >= 50 ? 'bg-warning text-dark' : 'bg-danger');
            ?>
            <div class="col-12">
                <div class="card modern-card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <div class="city-icon me-3" style="width: 50px; height: 50px; font-size: 1.25rem;">
                                <i class="bi bi-geo-alt-fill"></i>
                            </div>
                            <div>
                                <h5 class="card-title mb-1"><?= htmlspecialchars($ville['ville']) ?></h5>
                                <small class="text-muted">
                                    <?= count($ville['besoins']) ?> besoin(s) | 
                                    Montant total: <?= number_format($ville['montant_demande'], 2) ?> Ar
                                </small>
                            </div>
                        </div>
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
                                        <th class="text-end">Montant Demandé</th>
                                        <th class="text-end">Montant Distribué</th>
                                        <th class="text-end">Montant Reste</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($ville['besoins'] as $recap): ?>
                                    <tr class="<?= $recap['reste'] == 0 ? 'table-success' : '' ?>">
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="besoin-icon bg-primary-subtle text-primary me-2">
                                                    <i class="bi bi-box"></i>
                                                </div>
                                                <div>
                                                    <strong><?= htmlspecialchars($recap['besoin']) ?></strong>
                                                    <small class="d-block text-muted"><?= htmlspecialchars($recap['unite']) ?></small>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center"><span class="fw-semibold"><?= number_format($recap['total_demande']) ?></span></td>
                                        <td class="text-center"><span class="text-success fw-semibold"><?= number_format($recap['total_distribue']) ?></span></td>
                                        <td class="text-center">
                                            <?php if ($recap['reste'] > 0): ?>
                                                <span class="badge bg-danger"><?= number_format($recap['reste']) ?></span>
                                            <?php else: ?>
                                                <span class="badge bg-success"><i class="bi bi-check"></i> Complet</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-end"><span class="fw-semibold"><?= number_format($recap['montant_demande'], 2) ?> Ar</span></td>
                                        <td class="text-end"><span class="text-success fw-semibold"><?= number_format($recap['montant_distribue'], 2) ?> Ar</span></td>
                                        <td class="text-end">
                                            <?php if ($recap['montant_reste'] > 0): ?>
                                                <span class="text-danger fw-semibold"><?= number_format($recap['montant_reste'], 2) ?> Ar</span>
                                            <?php else: ?>
                                                <span class="text-success fw-semibold">0.00 Ar</span>
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
                                    <div class="fw-bold text-primary fs-5"><?= number_format($ville['montant_demande'], 2) ?> Ar</div>
                                    <small class="text-muted">Montant demandé</small>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="p-2">
                                    <div class="fw-bold text-success fs-5"><?= number_format($ville['montant_distribue'], 2) ?> Ar</div>
                                    <small class="text-muted">Montant distribué</small>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="p-2">
                                    <div class="fw-bold text-danger fs-5"><?= number_format($ville['montant_reste'], 2) ?> Ar</div>
                                    <small class="text-muted">Montant restant</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <div class="card modern-card">
            <div class="card-body text-center py-5">
                <i class="bi bi-inbox text-muted" style="font-size: 4rem;"></i>
                <h4 class="mt-3 text-muted">Aucune récapitulation disponible</h4>
                <p class="text-muted mb-4">Aucune donnée à afficher pour les critères sélectionnés.</p>
            </div>
        </div>
        <?php endif; ?>
        </div>
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
</style>

<script>
    function formatNumber(num) {
        return new Intl.NumberFormat('fr-FR').format(num);
    }
    function formatMontant(num) {
        return new Intl.NumberFormat('fr-FR', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(num);
    }

    function actualiserRecap() {
        var villeId = document.getElementById('filterVille').value;
        var url = '/recapitulation/data';
        if (villeId) url += '?ville_id=' + villeId;

        var btn = document.getElementById('btnActualiser');
        btn.disabled = true;
        btn.innerHTML = '<i class="bi bi-arrow-clockwise me-1 spin"></i>Chargement...';

        fetch(url)
            .then(function(response) { return response.json(); })
            .then(function(data) {
                location.reload();
            })
            .catch(function() {
                btn.disabled = false;
                btn.innerHTML = '<i class="bi bi-arrow-clockwise me-1"></i>Actualiser';
            });
    }

    document.getElementById('btnActualiser').addEventListener('click', actualiserRecap);

    document.getElementById('filterVille').addEventListener('change', function() {
        document.getElementById('formFiltres').submit();
    });
</script>

<?php include ("inc/footer.php"); ?>
