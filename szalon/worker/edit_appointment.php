<?php
require_once '../views/header.php';
require_once '../config/db.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'worker') {
    header("Location: ../login.php");
    exit;
}

/* === KÉSZ === */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['done_id'])) {
    $stmt = $pdo->prepare("
        UPDATE appointments 
        SET status = 'done' 
        WHERE id = ?
    ");
    $stmt->execute([$_POST['done_id']]);

    header("Location: my_appointments.php");
    exit;
}

/* === TÖRLÉS === */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cancel_id'])) {
    $stmt = $pdo->prepare("
        UPDATE appointments 
        SET status = 'cancelled' 
        WHERE id = ?
    ");
    $stmt->execute([$_POST['cancel_id']]);

    header("Location: my_appointments.php");
    exit;
}

/* WORKER ID */
$stmt = $pdo->prepare("SELECT id FROM workers WHERE user_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$workerId = $stmt->fetchColumn();

/* FOGLALÁSOK */
$stmt = $pdo->prepare("
    SELECT a.id, a.appointment_time, a.status, s.name AS service
    FROM appointments a
    JOIN services s ON s.id = a.service_id
    WHERE a.worker_id = ?
    ORDER BY a.appointment_time
");
$stmt->execute([$workerId]);
$appointments = $stmt->fetchAll();
?>

<div class="page-wrapper">
<div class="container">
<div class="card-custom">

<h3 class="mb-4">Időpontjaim</h3>

<?php if (empty($appointments)): ?>
    <p class="text-muted">Nincs időpontod.</p>
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

    <div class="d-flex gap-2">
        <?php if ($a['status'] === 'booked'): ?>
            <form method="post">
                <input type="hidden" name="done_id" value="<?= $a['id'] ?>">
                <button class="btn btn-success btn-sm">Kész</button>
            </form>

            <form method="post">
                <input type="hidden" name="cancel_id" value="<?= $a['id'] ?>">
                <button class="btn btn-outline-danger btn-sm">Törlés</button>
            </form>
        <?php endif; ?>
    </div>

</li>
<?php endforeach; ?>
</ul>

<?php endif; ?>

</div>
</div>
</div>

<?php include '../views/footer.php'; ?>
