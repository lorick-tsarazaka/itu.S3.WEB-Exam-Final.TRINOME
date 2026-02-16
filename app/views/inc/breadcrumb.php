<?php
    // Fil d'Ariane réutilisable (ne pas ajouter de <div.container> pour pouvoir l'insérer dans le hero)
    $base = defined('BASE_URL') ? BASE_URL : '';

    // Attendu: $breadcrumbs = [ ['label'=>'Accueil','url'=>'/'], ['label'=>'Page courante'] ];
    $crumbs = $breadcrumbs ?? [];

    if (empty($crumbs)) {
        $label = isset($pageTitle) ? $pageTitle : (isset($page_title) ? $page_title : 'Accueil');
        $crumbs = [
            ['label' => 'Accueil', 'url' => '/'],
            ['label' => $label]
        ];
    }
?>

<nav aria-label="breadcrumb">
    <ol class="breadcrumb breadcrumb-light mb-2">
        <?php $last = count($crumbs) - 1; foreach ($crumbs as $i => $c): ?>
            <?php $label = htmlspecialchars($c['label'] ?? ''); ?>
            <?php if (!empty($c['url']) && $i !== $last): ?>
                <li class="breadcrumb-item"><a href="<?= htmlspecialchars($c['url']) ?>"><?= $label ?></a></li>
            <?php else: ?>
                <li class="breadcrumb-item active" aria-current="page"><?= $label ?></li>
            <?php endif; ?>
        <?php endforeach; ?>
    </ol>
</nav>
