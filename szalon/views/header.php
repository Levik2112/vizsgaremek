<?php
require_once __DIR__ . '/../config/db.php';

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
if (!$pdo instanceof PDO) {
    die("PDO nincs betöltve");
}

$home = "/szalon/index.php";
if (isset($_SESSION['role'])) {
    if ($_SESSION['role'] === 'client') $home = "/szalon/client/dashboard.php";
    if ($_SESSION['role'] === 'worker') $home = "/szalon/worker/dashboard.php";
    if ($_SESSION['role'] === 'admin')  $home = "/szalon/admin/dashboard.php";
}

$pdo->query("
    UPDATE appointments
    SET status = 'done'
    WHERE status = 'booked'
      AND appointment_time < NOW()
");
?>

<nav class="navbar-custom">
    <div class="navbar-inner">

        <!-- BAL OLDAL: ikonok -->
        <div class="nav-left">
            <button id="themeToggle" class="theme-toggle" aria-label="Dark mode váltás">
                🌙
            </button>

            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="/szalon/profile/index.php" class="nav-profile" title="Profil">
                    👤
                </a>
            <?php endif; ?>
        </div>

        <!-- KÖZÉP: LOGÓ -->
        <div class="nav-center">
            <a href="<?= $home ?>" class="navbar-logo">
                <span class="logo-icon">✦</span>
                <span class="logo-text">Szalon</span>
            </a>
        </div>

        <!-- JOBB OLDAL: kijelentkezés -->
        <div class="nav-right">
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="/szalon/logout.php" class="nav-btn logout-btn">
                    Kijelentkezés
                </a>
            <?php endif; ?>
        </div>

    </div>
</nav>
