<?php include ("doctype.php"); ?>
<body>
    <!-- Navbar moderne -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary-gradient fixed-top shadow-sm">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="/">
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
                        <a class="nav-link px-3 <?= ($pageTitle ?? '') === 'Tableau de bord' ? 'active' : '' ?>" href="/">
                            <i class="bi bi-speedometer2 me-1"></i> Tableau de bord
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link px-3 <?= ($pageTitle ?? '') === 'Villes' ? 'active' : '' ?>" href="/villes">
                            <i class="bi bi-geo-alt me-1"></i> Villes
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link px-3 <?= ($pageTitle ?? '') === 'Besoins' ? 'active' : '' ?>" href="/besoins">
                            <i class="bi bi-list-check me-1"></i> Besoins
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link px-3 <?= ($pageTitle ?? '') === 'Dons' ? 'active' : '' ?>" href="/dons">
                            <i class="bi bi-gift me-1"></i> Dons
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Spacer pour le fixed navbar -->
    <div class="navbar-spacer"></div>