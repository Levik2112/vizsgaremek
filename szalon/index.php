<?php
session_start();
require_once 'views/header.php';
?>

<div class="page-wrapper landing-page">

<script src="assets/js/main.js"></script>


    <!-- HERO -->
    <section class="container mb-5">
        <div class="card-custom text-center">

            <h1 class="mb-3">Szalon Időpontfoglaló</h1>
            <p class="text-muted mb-4">
                Modern online időpontfoglaló rendszer szalonok számára.
                Gyors, átlátható és könnyen kezelhető.
            </p>

            <?php if (!isset($_SESSION['user_id'])): ?>
                <div class="d-flex justify-content-center gap-3">
                    <a href="login.php" class="btn-main">Bejelentkezés</a>
                    <a href="register.php" class="btn-outline-main">Regisztráció</a>
                </div>
            <?php else: ?>
                <div class="mt-3">
                    <?php if ($_SESSION['role'] === 'client'): ?>
                        <a href="client/dashboard.php" class="btn-main">Ügyfél felület</a>
                    <?php elseif ($_SESSION['role'] === 'worker'): ?>
                        <a href="worker/dashboard.php" class="btn-main">Dolgozó felület</a>
                    <?php else: ?>
                        <a href="admin/dashboard.php" class="btn-main">Admin dashboard</a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

        </div>
    </section>

    <!-- MIÉRT JÓ -->
    <section class="container mb-5">
        <div class="row g-4 text-center">

            <div class="col-md-4">
                <div class="card-custom h-100">
                    <div class="feature-icon">⚡</div>
                    <h5 class="mt-3">Gyors</h5>
                    <p class="text-muted">
                        Időpontfoglalás néhány kattintással,
                        bármilyen eszközről.
                    </p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card-custom h-100">
                    <div class="feature-icon">🔒</div>
                    <h5 class="mt-3">Biztonságos</h5>
                    <p class="text-muted">
                        Jelszavas védelem, jogosultságkezelés,
                        admin felügyelet.
                    </p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card-custom h-100">
                    <div class="feature-icon">📊</div>
                    <h5 class="mt-3">Átlátható</h5>
                    <p class="text-muted">
                        Statisztikák, bevételek és időpontok
                        egy helyen.
                    </p>
                </div>
            </div>

        </div>
    </section>

    <!-- CTA -->
    <?php if (!isset($_SESSION['user_id'])): ?>
    <section class="container">
        <div class="card-custom text-center">
            <h3 class="mb-3">Készen állsz az indulásra?</h3>
            <p class="text-muted mb-4">
                Regisztrálj most és kezdd el használni a rendszert!
            </p>
            <a href="register.php" class="btn-main">Regisztráció</a>
        </div>
    </section>
    <?php endif; ?>

</div>


<?php include 'views/footer.php'; ?>
