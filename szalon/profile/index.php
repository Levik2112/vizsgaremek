<?php
session_start();
require_once '../config/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

$stmt = $pdo->prepare("SELECT name, email, phone FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();
include '../views/header.php';
?>
<?php if (isset($_GET['saved'])): ?>
<div class="alert alert-success text-center mb-3">
    ✔ Alap adatok sikeresen mentve
</div>
<?php endif; ?>
<?php


if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

require_once '../views/header.php';
?>
<div class="page-wrapper">
<div class="container">
<div class="card-custom mx-auto" style="max-width:520px">

<h3 class="text-center mb-4">Profil beállítások</h3>

<!-- ALAP ADATOK -->
<form method="post" action="update_profile.php">
    <div class="form-group mb-3">
        <label>Név</label>
        <input class="form-control" name="name" value="<?= htmlspecialchars($user['name']) ?>" required>
    </div>

    <div class="form-group mb-3">
        <label>Telefonszám <span class="text-muted">(opcionális)</span></label>

        <input class="form-control" name="phone" value="<?= htmlspecialchars($user['phone']) ?>">
    </div>

    <button class="btn-main w-100">Alap adatok mentése</button>
</form>

<hr class="my-4">
<p class="text-muted mb-2" style="font-size:0.9rem">
    Jelenlegi email: <strong><?= htmlspecialchars($user['email']) ?></strong>
</p>

<!-- EMAIL MÓDOSÍTÁS -->
<h5>Email cím módosítása</h5>
<form method="post" action="request_email.php">
    <input class="form-control mb-3" name="new_email" placeholder="Új email cím" required>
    <button class="btn-main w-100">Megerősítő kód küldése</button>
</form>

<hr class="my-4">

<!-- JELSZÓ -->
<h5>Jelszó módosítása</h5>
<a href="request_password.php" class="btn-main w-75 profile-password-btn">

    🔐 Jelszó módosítása emailben
</a>


</div>
</div>
</div>

<?php include '../views/footer.php'; ?>
