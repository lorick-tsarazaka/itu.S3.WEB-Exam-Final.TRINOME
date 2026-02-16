<?php $pageTitle = "Tableau de bord"; ?>
<?php include ("inc/header.php"); ?>

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