<?php
session_start();
require_once '../config/db.php';
require_once '../views/header.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'worker') {
    header("Location: ../login.php");
    exit;
}

/* worker_id */
$stmt = $pdo->prepare("SELECT id FROM workers WHERE user_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$workerId = $stmt->fetchColumn();

/* LEZÁRT IDŐPONTOK */
$stmt = $pdo->prepare("
    SELECT a.appointment_time, a.status, s.name AS service
    FROM appointments a
    JOIN services s ON s.id = a.service_id
    WHERE a.worker_id = ?
      AND a.status IN ('done','cancelled')
    ORDER BY a.appointment_time DESC
");
$stmt->execute([$workerId]);
$rows = $stmt->fetchAll();
?>

<div class="page-wrapper">
<div class="container">
<div class="card-custom">

<h3 class="mb-4">Kész időpontok</h3>

<?php if (!$rows): ?>
    <p class="text-muted">Még nincs lezárt időpont.</p>
<?php endif; ?>

<?php foreach ($rows as $r): ?>
    <div class="appointment-row closed">
        <strong><?= date('Y-m-d H:i', strtotime($r['appointment_time'])) ?></strong>
        – <?= htmlspecialchars($r['service']) ?>

        <span class="status-badge status-<?= $r['status'] ?>">
            <?= strtoupper($r['status']) ?>
        </span>
    </div>
<?php endforeach; ?>

</div>
</div>
</div>

<?php include '../views/footer.php'; ?>
