<?php
    // Ensure $base is available even if BASE_URL is not defined
    $base = defined('BASE_URL') ? BASE_URL : '';
    include ("doctype.php");
?>
<body>
    <!-- Navbar moderne -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary-gradient fixed-top shadow-sm">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="<?= $base ?>/">
                <div class="brand-icon me-2">
                    <i class="bi bi-heart-pulse-fill"></i>
                </div>
                <div class="brand-text">
                    <span class="fw-bold">BNGRC</span>
                    <small class="d-none d-md-block" style="font-size: 0.6rem; line-height: 1; color: rgba(255,255,255,0.85);">Gestion des Risques et Catastrophes</small>
                </div>
            </a>
            
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarMain">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link px-3 <?= ($page_title ?? '') === 'Tableau de bord' ? 'active' : '' ?>" href="<?= $base ?>/">
                            <i class="bi bi-speedometer2 me-1"></i> Tableau de bord
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link px-3 <?= ($pageTitle ?? '') === 'Liste des Collectes' || ($pageTitle ?? '') === 'Nouvelle Collecte' ? 'active' : '' ?>" href="<?= $base ?>/collecte">
                            <i class="bi bi-collection me-1"></i> Dons
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link px-3 <?= ($pageTitle ?? '') === 'Achats' ? 'active' : '' ?>" href="<?= $base ?>/achats">
                            <i class="bi bi-cart3 me-1"></i> Achats
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link px-3 <?= ($page_title ?? '') === 'Saisie Besoins' ? 'active' : '' ?>" href="<?= $base ?>/besoins">
                            <i class="bi bi-list-check me-1"></i> Besoins
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link px-3 <?= ($pageTitle ?? '') === 'Simulation de Dons' ? 'active' : '' ?>" href="<?= $base ?>/simulation">
                            <i class="bi bi-arrow-repeat me-1"></i> Simulation
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link px-3 <?= ($pageTitle ?? '') === 'Récapitulation' ? 'active' : '' ?>" href="<?= $base ?>/recapitulation">
                            <i class="bi bi-graph-up me-1"></i> Récapitulation
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Spacer pour le fixed navbar -->
    <div class="navbar-spacer"></div>

    <!-- Breadcrumb moved into each page's hero section to keep it inside the hero -->