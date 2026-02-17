    <!-- Footer moderne -->
    <footer class="footer-section mt-auto">
        <div class="footer-wave">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 100" preserveAspectRatio="none">
                <path fill="currentColor" d="M0,50 C150,100 350,0 500,50 C650,100 800,20 1000,50 C1200,80 1350,30 1440,50 L1440,100 L0,100 Z"></path>
            </svg>
        </div>
        <div class="footer-content">
            <div class="container">
                <div class="row py-4">
                    <div class="col-lg-4 mb-4 mb-lg-0">
                        <div class="footer-brand mb-3">
                            <i class="bi bi-heart-pulse-fill me-2"></i>
                            <span class="fw-bold">BNGRC</span>
                        </div>
                        <p class="small mb-0" style="color: rgba(255,255,255,0.85);">
                            Bureau National de Gestion des Risques et des Catastrophes<br>
                            Application de suivi des collectes et distributions de dons pour les sinistrés.
                        </p>
                    </div>
                    <div class="col-lg-4 mb-4 mb-lg-0">
                        <h6 class="text-white mb-3">Liens rapides</h6>
                        <ul class="list-unstyled footer-links">
                            <?php $base = defined('BASE_URL') ? BASE_URL : ''; ?>
                            <li><a href="<?= $base ?>/"><i class="bi bi-chevron-right me-1"></i>Tableau de bord</a></li>
                            <li><a href="<?= $base ?>/collecte"><i class="bi bi-chevron-right me-1"></i>Dons</a></li>
                            <li><a href="<?= $base ?>/achats"><i class="bi bi-chevron-right me-1"></i>Achats</a></li>
                            <li><a href="<?= $base ?>/besoins"><i class="bi bi-chevron-right me-1"></i>Besoins</a></li>
                        </ul>
                    </div>
                    <div class="col-lg-4">
                        <h6 class="text-white mb-3">Équipe de développement</h6>
                        <ul class="list-unstyled team-list" style="color: rgba(255,255,255,0.85);">
                            <li><i class="bi bi-person-badge me-2"></i>ETU003892 - TSARAZAKA Tsilavina Lorick</li>
                            <li><i class="bi bi-person-badge me-2"></i>ETU003933 - AKO Ny Antso Rivaldo</li>
                            <li><i class="bi bi-person-badge me-2"></i>ETU003938 - CHARLES Amel Soanasy</li>
                        </ul>
                    </div>
                </div>
                <hr class="border-secondary my-0">
                <div class="py-3 text-center">
                    <small style="color: rgba(255,255,255,0.75);">
                        &copy; <?= date('Y') ?> BNGRC - Tous droits réservés | Projet d'examen Web S3
                    </small>
                </div>
            </div>
        </div>
    </footer>

    <script src="<?= $base ?>/assets/js/main.js" nonce="<?= htmlspecialchars($csp_nonce ?? '') ?>"></script>
</body>
</html>