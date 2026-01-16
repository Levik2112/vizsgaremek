<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'worker') {
    header("Location: ../login.php");
    exit;
}

require_once '../config/db.php';

/* ==== WORKER ID ==== */
$stmt = $pdo->prepare("SELECT id FROM workers WHERE user_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$workerId = $stmt->fetchColumn();

/* ==== KÉSZRE JELÖLÉS ==== */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['done_id'])) {
    $stmt = $pdo->prepare("
        UPDATE appointments 
        SET status = 'done' 
        WHERE id = ? AND worker_id = ?
    ");
    $stmt->execute([
        $_POST['done_id'],
        $workerId
    ]);
    header("Location: my_appointments.php");
    exit;
}

/* ==== TÖRLÉS ==== */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cancel_id'])) {
    $stmt = $pdo->prepare("
        UPDATE appointments 
        SET status = 'cancelled' 
        WHERE id = ? AND worker_id = ?
    ");
    $stmt->execute([
        $_POST['cancel_id'],
        $workerId
    ]);
    header("Location: my_appointments.php");
    exit;
}

/* ==== FOGLALÁSOK ==== */
$stmt = $pdo->prepare("
    SELECT 
        a.id,
        a.appointment_time,
        a.status,
        s.name AS service
    FROM appointments a
    JOIN services s ON s.id = a.service_id
    WHERE a.worker_id = ?
  AND a.status = 'booked'
ORDER BY a.appointment_time DESC

");
$stmt->execute([$workerId]);
$appointments = $stmt->fetchAll();

require_once '../views/header.php';
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
        <strong><?= date('Y-m-d H:i', strtotime($a['appointment_time'])) ?></strong>
        – <?= htmlspecialchars($a['service']) ?>

        <span class="badge badge-<?= $a['status'] ?> ms-2">
            <?= $a['status'] ?>
        </span>
    </div>

    <div class="d-flex gap-2">

    <!-- MÓDOSÍTÁS – GET FORM (BIZTOS) -->
    <form method="get" action="edit_appointment.php" class="m-0">
        <input type="hidden" name="id" value="<?= $a['id'] ?>">
        <button type="submit" class="btn btn-outline-primary btn-sm">
            Módosítás
        </button>
    </form>

    <?php if ($a['status'] === 'booked'): ?>

        <!-- KÉSZ -->
        <form method="post" class="m-0">
            <input type="hidden" name="done_id" value="<?= $a['id'] ?>">
            <button type="submit" class="btn btn-success btn-sm">
                Kész
            </button>
        </form>

        <!-- TÖRLÉS -->
        <form method="post" class="m-0">
            <input type="hidden" name="cancel_id" value="<?= $a['id'] ?>">
            <button type="submit" class="btn btn-outline-danger btn-sm">
                Törlés
            </button>
        </form>

    <?php else: ?>

        <button class="btn btn-secondary btn-sm" disabled>
            Lezárt
        </button>

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
