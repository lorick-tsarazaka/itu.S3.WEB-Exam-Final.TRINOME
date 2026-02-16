<?php $pageTitle = "Récapitulation"; ?>
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
                            <li class="breadcrumb-item active">Récapitulation</li>
                        </ol>
                    </nav>
                    <h1 class="hero-title mb-3">
                        <i class="bi bi-graph-up me-2"></i>Récapitulation
                    </h1>
                    <p class="hero-subtitle mb-0">
                        Vue d'ensemble des besoins, distributions et montants
                    </p>
                </div>
                <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                    <button type="button" class="btn btn-light btn-lg" id="btnActualiser">
                        <i class="bi bi-arrow-clockwise me-1"></i>Actualiser
                    </button>
                </div>
            </div>
        </div>
    </section>

    <div class="container">
        <!-- Filtres -->
        <div class="card modern-card mb-4">
            <div class="card-body">
                <form id="formFiltres" method="GET" class="row g-3 align-items-end">
                    <div class="col-md-6 col-lg-4">
                        <label for="filterVille" class="form-label fw-semibold">
                            <i class="bi bi-geo-alt me-1 text-primary"></i>Filtrer par ville
                        </label>
                        <select class="form-select" id="filterVille" name="ville_id">
                            <option value="">Tous les villes</option>
                            <?php if (isset($villes) && !empty($villes)): ?>
                                <?php foreach ($villes as $ville): ?>
                                    <option value="<?= $ville['v_id'] ?>" <?= (isset($_GET['ville_id']) && $_GET['ville_id'] == $ville['v_id']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($ville['v_nom']) ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div class="col-md-6 col-lg-4">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-funnel me-1"></i>Filtrer
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Tableau de récapitulation -->
        <div id="recapTableContainer">
        <?php if (isset($recapitulations) && !empty($recapitulations)): ?>
        <div class="card modern-card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-table me-2"></i>Détails des récapitulations
                </h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover modern-table mb-0">
                        <thead>
                            <tr>
                                <th>Ville</th>
                                <th>Besoin</th>
                                <th class="text-center">Total Demandé</th>
                                <th class="text-center">Total Distribué</th>
                                <th class="text-center">Reste</th>
                                <th class="text-end">Montant Demandé</th>
                                <th class="text-end">Montant Distribué</th>
                                <th class="text-end">Montant Reste</th>
                            </tr>
                        </thead>
                        <tbody id="recapTableBody">
                            <?php foreach ($recapitulations as $recap): ?>
                            <?php $rowClass = $recap['reste'] == 0 ? 'table-success' : ''; ?>
                            <tr class="<?= $rowClass ?>">
                                <td><strong><?= htmlspecialchars($recap['ville']) ?></strong></td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="besoin-icon-small bg-primary-subtle text-primary me-2">
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
.besoin-icon-small {
    width: 35px;
    height: 35px;
    border-radius: var(--radius);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.9rem;
}
</style>

<script>
    function formatNumber(num) {
        return new Intl.NumberFormat('fr-FR').format(num);
    }
    function formatMontant(num) {
        return new Intl.NumberFormat('fr-FR', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(num);
    }

    function renderTable(data) {
        var container = document.getElementById('recapTableContainer');
        if (!data || data.length === 0) {
            container.innerHTML = '<div class="card modern-card"><div class="card-body text-center py-5">' +
                '<i class="bi bi-inbox text-muted" style="font-size: 4rem;"></i>' +
                '<h4 class="mt-3 text-muted">Aucune récapitulation disponible</h4>' +
                '<p class="text-muted mb-4">Aucune donnée à afficher pour les critères sélectionnés.</p>' +
                '</div></div>';
            return;
        }
        var rows = '';
        data.forEach(function(r) {
            var rowClass = r.reste == 0 ? 'table-success' : '';
            var resteCell = r.reste > 0
                ? '<span class="badge bg-danger">' + formatNumber(r.reste) + '</span>'
                : '<span class="badge bg-success"><i class="bi bi-check"></i> Complet</span>';
            var montantResteCell = r.montant_reste > 0
                ? '<span class="text-danger fw-semibold">' + formatMontant(r.montant_reste) + ' Ar</span>'
                : '<span class="text-success fw-semibold">0.00 Ar</span>';

            rows += '<tr class="' + rowClass + '">' +
                '<td><strong>' + r.ville + '</strong></td>' +
                '<td><div class="d-flex align-items-center">' +
                    '<div class="besoin-icon-small bg-primary-subtle text-primary me-2"><i class="bi bi-box"></i></div>' +
                    '<div><strong>' + r.besoin + '</strong><small class="d-block text-muted">' + r.unite + '</small></div>' +
                '</div></td>' +
                '<td class="text-center"><span class="fw-semibold">' + formatNumber(r.total_demande) + '</span></td>' +
                '<td class="text-center"><span class="text-success fw-semibold">' + formatNumber(r.total_distribue) + '</span></td>' +
                '<td class="text-center">' + resteCell + '</td>' +
                '<td class="text-end"><span class="fw-semibold">' + formatMontant(r.montant_demande) + ' Ar</span></td>' +
                '<td class="text-end"><span class="text-success fw-semibold">' + formatMontant(r.montant_distribue) + ' Ar</span></td>' +
                '<td class="text-end">' + montantResteCell + '</td>' +
                '</tr>';
        });

        container.innerHTML = '<div class="card modern-card">' +
            '<div class="card-header"><h5 class="card-title mb-0"><i class="bi bi-table me-2"></i>Détails des récapitulations</h5></div>' +
            '<div class="card-body p-0"><div class="table-responsive">' +
            '<table class="table table-hover modern-table mb-0"><thead><tr>' +
                '<th>Ville</th><th>Besoin</th>' +
                '<th class="text-center">Total Demandé</th><th class="text-center">Total Distribué</th><th class="text-center">Reste</th>' +
                '<th class="text-end">Montant Demandé</th><th class="text-end">Montant Distribué</th><th class="text-end">Montant Reste</th>' +
            '</tr></thead><tbody>' + rows + '</tbody></table></div></div></div>';
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
                renderTable(data);
                btn.disabled = false;
                btn.innerHTML = '<i class="bi bi-arrow-clockwise me-1"></i>Actualiser';
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
