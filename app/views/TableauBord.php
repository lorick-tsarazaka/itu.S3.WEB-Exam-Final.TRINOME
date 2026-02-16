<?php $pageTitle = "Tableau de bord"; ?>
<?php
    $breadcrumbs = [
        ['label' => 'Accueil', 'url' => '/'],
        ['label' => 'Tableau de bord']
    ];
?>
<?php include ("inc/header.php"); ?>

<section class="hero-section mb-4">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <?php if (file_exists(__DIR__ . '/inc/breadcrumb.php')) include('inc/breadcrumb.php'); ?>
                <h1 class="hero-title mb-3">Tableau de Bord</h1>
                <p class="hero-subtitle mb-0">Vue synthétique des villes, besoins et distributions</p>
            </div>
        </div>
    </div>
</section>

<main class="main-content">
    <h1>Tableau de Bord</h1>

    <?php foreach ($data as $item): ?>
        <div style="margin-bottom:40px; border:1px solid #ccc; padding:15px;">
            
            <h2>Ville : <?= htmlspecialchars($item['ville']['v_nom']) ?></h2>

            <h3>Besoins</h3>
            <ul>
                <?php foreach ($item['besoins'] as $besoin): ?>
                    <li>
                        <?= htmlspecialchars($besoin['besoin']) ?>
                        (<?= $besoin['quantite'] ?> <?= $besoin['unite'] ?>)
                    </li>
                <?php endforeach; ?>
            </ul>

            <h3>Distributions</h3>
            <ul>
                <?php foreach ($item['distributions'] as $dist): ?>
                    <li>
                        <?= htmlspecialchars($dist['besoin']) ?>
                        - <?= $dist['quantite'] ?> <?= $dist['unite'] ?>
                        (<?= $dist['date_distribution'] ?>)
                    </li>
                <?php endforeach; ?>
            </ul>

        </div>
    <?php endforeach; ?>
</main>