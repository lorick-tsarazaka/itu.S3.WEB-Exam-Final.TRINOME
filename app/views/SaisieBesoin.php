<?php
    $page_title = "Saisie Besoins";
    include('inc/doctype.php');
?>
<body>
<?php
    $breadcrumbs = [
        ['label' => 'Accueil', 'url' => '/'],
        ['label' => 'Saisie Besoins']
    ];
?>
<?php include('inc/header.php'); ?>

<?php $base = defined('BASE_URL') ? BASE_URL : ''; ?>

<main class="main-content">
    <section class="hero-section mb-4">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <?php if (file_exists(__DIR__ . '/inc/breadcrumb.php')) include('inc/breadcrumb.php'); ?>
                    <h1 class="hero-title mb-3">
                        <i class="bi bi-clipboard-plus me-2"></i><?= htmlspecialchars($page_title ?? 'Saisie des besoins') ?>
                    </h1>
                    <p class="hero-subtitle mb-0">Enregistrer les besoins par ville</p>
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

    <div class="container py-4">
        <div class="card modern-card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="card-title mb-1">
                        <i class="bi bi-clipboard-plus me-2"></i>Saisie des besoins
                    </h5>
                    <small class="text-muted">Enregistrer les besoins par ville</small>
                </div>
            </div>
            <div class="card-body">

        <?php
            if (session_status() === PHP_SESSION_NONE) session_start();
            // affiche les messages flash puis les supprime
            if (!empty($_SESSION['flash_success'])): ?>
                <div class="alert alert-success"><?= htmlspecialchars($_SESSION['flash_success']) ?></div>
            <?php unset($_SESSION['flash_success']); endif; ?>

            <?php if (!empty($_SESSION['flash_error'])): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($_SESSION['flash_error']) ?></div>
            <?php unset($_SESSION['flash_error']); endif; ?>

        <form method="post" action="<?= $base ?>/besoins/save">

            <div id="rows-wrapper">
                <div class="row besoin-row g-2 align-items-end mb-3" data-index="0">
                    <div class="col-md-5">
                        <label class="form-label">Besoin</label>
                        <select name="besoins[0][besoin]" class="form-control">
                            <?php if (!empty($besoins)): ?>
                                <?php foreach ($besoins as $b): ?>
                                    <?php $id = is_array($b) ? ($b['b_id'] ?? '') : ($b->b_id ?? ''); ?>
                                    <?php $label = is_array($b) ? ($b['b_libelle'] ?? '') : ($b->b_libelle ?? ''); ?>
                                    <option value="<?= htmlspecialchars($id) ?>"><?= htmlspecialchars($label) ?></option>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <option value="">-- Aucun besoin disponible --</option>
                            <?php endif; ?>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Ville</label>
                        <select name="besoins[0][ville]" class="form-control">
                            <?php if (!empty($villes)): ?>
                                <?php foreach ($villes as $v): ?>
                                    <?php $id = is_array($v) ? ($v['v_id'] ?? '') : ($v->v_id ?? ''); ?>
                                    <?php $label = is_array($v) ? ($v['v_nom'] ?? '') : ($v->v_nom ?? ''); ?>
                                    <option value="<?= htmlspecialchars($id) ?>"><?= htmlspecialchars($label) ?></option>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <option value="">-- Aucune ville disponible --</option>
                            <?php endif; ?>
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label class="form-label">Quantité</label>
                        <input type="number" name="besoins[0][quantite]" class="form-control" min="0" value="1" required>
                    </div>

                    <div class="col-md-1">
                        <button type="button" class="btn btn-outline-danger btn-sm remove-row" title="Retirer">
                            &times;
                        </button>
                    </div>
                </div>
            </div>

                <div class="d-flex gap-2">
                    <button type="button" id="add-row" class="btn btn-outline-primary">Ajouter un autre</button>
                    <button type="submit" class="btn btn-primary">Valider</button>
                </div>
            </form>
            </div>
        </div>
    </div>
</main>

<template id="row-template">
    <div class="row besoin-row g-2 align-items-end mb-3" data-index="__INDEX__">
        <div class="col-md-5">
            <label class="form-label">Besoin</label>
            <select name="besoins[__INDEX__][besoin]" class="form-control">
                <?php if (!empty($besoins)): ?>
                    <?php foreach ($besoins as $b): ?>
                        <?php $id = is_array($b) ? ($b['b_id'] ?? '') : ($b->b_id ?? ''); ?>
                        <?php $label = is_array($b) ? ($b['b_libelle'] ?? '') : ($b->b_libelle ?? ''); ?>
                        <option value="<?= htmlspecialchars($id) ?>"><?= htmlspecialchars($label) ?></option>
                    <?php endforeach; ?>
                <?php else: ?>
                    <option value="">-- Aucun besoin disponible --</option>
                <?php endif; ?>
            </select>
        </div>

        <div class="col-md-4">
            <label class="form-label">Ville</label>
            <select name="besoins[__INDEX__][ville]" class="form-control">
                <?php if (!empty($villes)): ?>
                    <?php foreach ($villes as $v): ?>
                        <?php $id = is_array($v) ? ($v['v_id'] ?? '') : ($v->v_id ?? ''); ?>
                        <?php $label = is_array($v) ? ($v['v_nom'] ?? '') : ($v->v_nom ?? ''); ?>
                        <option value="<?= htmlspecialchars($id) ?>"><?= htmlspecialchars($label) ?></option>
                    <?php endforeach; ?>
                <?php else: ?>
                    <option value="">-- Aucune ville disponible --</option>
                <?php endif; ?>
            </select>
        </div>

        <div class="col-md-2">
            <label class="form-label">Quantité</label>
            <input type="number" name="besoins[__INDEX__][quantite]" class="form-control" min="0" value="1" required>
        </div>

        <div class="col-md-1">
            <button type="button" class="btn btn-outline-danger btn-sm remove-row" title="Retirer">&times;</button>
        </div>
    </div>
</template>

<script nonce="<?= $csp_nonce ?>">
    (function(){
        const addBtn = document.getElementById('add-row');
        const wrapper = document.getElementById('rows-wrapper');
        const tpl = document.getElementById('row-template');

        function bindRemove(btn){
            btn.addEventListener('click', function(){
                const row = btn.closest('.besoin-row');
                if (!row) return;
                // if only one row left, clear inputs instead of removing
                const rows = wrapper.querySelectorAll('.besoin-row');
                if (rows.length === 1) {
                    row.querySelectorAll('select, input').forEach(i => i.value = '');
                    return;
                }
                row.remove();
            });
        }

        // initial remove bind
        wrapper.querySelectorAll('.remove-row').forEach(bindRemove);

        addBtn.addEventListener('click', function(){
            // determine next index based on existing rows
            const nextIndex = wrapper.querySelectorAll('.besoin-row').length;
            // use template HTML and replace placeholder
            const html = tpl.innerHTML.replace(/__INDEX__/g, String(nextIndex));
            wrapper.insertAdjacentHTML('beforeend', html);
            // bind remove on the newly added button
            const newRemove = wrapper.querySelectorAll('.remove-row');
            bindRemove(newRemove[newRemove.length - 1]);
        });
    })();
</script>

<?php include('inc/footer.php'); ?>
