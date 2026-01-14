<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <title>Szalon</title>

    <link rel="stylesheet" href="/szalon/assets/css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>
<body>

<?php
$home = "/szalon/index.php";
if (isset($_SESSION['role'])) {
    if ($_SESSION['role'] === 'client') $home = "/szalon/client/dashboard.php";
    if ($_SESSION['role'] === 'worker') $home = "/szalon/worker/dashboard.php";
    if ($_SESSION['role'] === 'admin')  $home = "/szalon/admin/dashboard.php";
}
?>
<button id="themeToggle" class="theme-toggle" aria-label="Dark mode váltás">
    🌙
</button>


<nav class="navbar-custom">
    <div class="navbar-inner">

        <div class="nav-side"></div>

       <a href="<?= $home ?>" class="navbar-logo">
    <span class="logo-icon">✦</span>
    <span class="logo-text">Szalon</span>
</a>

        <div class="nav-side nav-right">
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="/szalon/logout.php" class="nav-btn logout-btn">
                    Kijelentkezés
                </a>
            
            <?php endif; ?>
        </div>

    </div>
</nav>
