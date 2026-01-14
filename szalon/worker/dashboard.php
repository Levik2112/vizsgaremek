<?php
require_once '../views/header.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'worker') {
    header("Location: ../login.php");
    exit;
}
?>

<div class="page-wrapper">
    <div class="container">

        <div class="card-custom text-center">

            <h1 class="mb-3">Dolgozó felület</h1>

            <p class="text-muted mb-4">
                Itt tudod kezelni a saját időpontjaidat.
            </p>

            <a href="my_appointments.php" class="btn-main">
                Saját időpontjaim
            </a>

        </div>

    </div>
</div>

<?php include '../views/footer.php'; ?>
