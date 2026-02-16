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
        <div class="card shadow-sm">
            <div class="card-header d-flex align-items-center">
                <i class="bi bi-layout-text-window-reverse me-2"></i>
                <h5 class="mb-0">Villes — Besoins et Distributions</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Ville</th>
                                <th>Besoins</th>
                                <th>Distributions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($data)): ?>
                                <?php foreach ($data as $item): ?>
                                    <tr>
                                        <td style="min-width:160px; vertical-align:top;">
                                            <?= htmlspecialchars($item['ville']['v_nom'] ?? ($item['ville']->v_nom ?? '-')) ?>
                                        </td>
                                        <td>
                                            <?php if (!empty($item['besoins'])): ?>
                                                <ul class="mb-0">
                                                    <?php foreach ($item['besoins'] as $b): ?>
                                                        <li><?= htmlspecialchars(($b['besoin'] ?? $b->besoin) . ' — ' . ($b['quantite'] ?? $b->quantite) . ' ' . ($b['unite'] ?? $b->unite)) ?></li>
                                                    <?php endforeach; ?>
                                                </ul>
                                            <?php else: ?>
                                                <span class="text-muted">Aucun besoin</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if (!empty($item['distributions'])): ?>
                                                <ul class="mb-0">
                                                    <?php foreach ($item['distributions'] as $d): ?>
                                                        <li><?= htmlspecialchars(($d['besoin'] ?? $d->besoin) . ' — ' . ($d['quantite'] ?? $d->quantite) . ' ' . ($d['unite'] ?? $d->unite) . ' (' . ($d['date_distribution'] ?? $d->date_distribution) . ')') ?></li>
                                                    <?php endforeach; ?>
                                                </ul>
                                            <?php else: ?>
                                                <span class="text-muted">Aucune distribution</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan="3" class="text-center text-muted">Aucune donnée disponible</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include ("inc/footer.php"); ?>