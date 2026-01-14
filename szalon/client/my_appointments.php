<?php
require_once '../views/header.php';
require_once '../config/db.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'client') {
    header("Location: ../login.php");
    exit;
}

/* === TÖRLÉS (CANCEL) === */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cancel_id'])) {
    $stmt = $pdo->prepare("
        UPDATE appointments 
        SET status = 'cancelled' 
        WHERE id = ? AND client_id = ?
    ");
    $stmt->execute([$_POST['cancel_id'], $_SESSION['user_id']]);

    header("Location: my_appointments.php");
    exit;
}

/* FOGLALÁSOK */
$stmt = $pdo->prepare("
    SELECT a.id, a.appointment_time, a.status, s.name AS service
    FROM appointments a
    JOIN services s ON s.id = a.service_id
    WHERE a.client_id = ?
    ORDER BY a.appointment_time DESC
");
$stmt->execute([$_SESSION['user_id']]);
$appointments = $stmt->fetchAll();
?>

<div class="page-wrapper">
<div class="container">
<div class="card-custom">

<h3 class="mb-4">Foglalásaim</h3>

<?php if (empty($appointments)): ?>
    <p class="text-muted">Még nincs foglalásod.</p>
<?php else: ?>

<ul class="list-group">
<?php foreach ($appointments as $a): ?>
<li class="list-group-item d-flex justify-content-between align-items-center">

    <div>
        <strong><?= $a['appointment_time'] ?></strong> – <?= $a['service'] ?>
        <span class="badge badge-<?= $a['status'] ?> ms-2">
            <?= $a['status'] ?>
        </span>
    </div>

    <?php if ($a['status'] === 'booked'): ?>
        <form method="post">
            <input type="hidden" name="cancel_id" value="<?= $a['id'] ?>">
            <button class="btn btn-outline-danger btn-sm">
                Törlés
            </button>
        </form>
    <?php endif; ?>

</li>
<?php endforeach; ?>
</ul>

<?php endif; ?>

</div>
</div>
</div>

<?php include '../views/footer.php'; ?>
