<?php
session_start();
require_once '../config/db.php';

if ($_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

$appointments = $pdo->query("
    SELECT 
        a.id,
        a.appointment_time,
        a.status,
        u1.name AS client,
        u2.name AS worker,
        s.name AS service
    FROM appointments a
    JOIN users u1 ON u1.id = a.client_id
    JOIN workers w ON w.id = a.worker_id
    JOIN users u2 ON u2.id = w.user_id
    JOIN services s ON s.id = a.service_id
    ORDER BY a.appointment_time DESC
")->fetchAll();

include '../views/header.php';
?>

<div class="page-wrapper">
<div class="container">
<div class="card-custom">

<h3 class="mb-4">Foglalások</h3>

<table class="table table-hover">
<thead>
<tr>
<th>Dátum</th><th>Ügyfél</th><th>Dolgozó</th><th>Szolgáltatás</th><th>Státusz</th><th></th>
</tr>
</thead>
<tbody>
<?php foreach ($appointments as $a): ?>
<tr>
<td><?= $a['appointment_time'] ?></td>
<td><?= $a['client'] ?></td>
<td><?= $a['worker'] ?></td>
<td><?= $a['service'] ?></td>
<td>
<span class="badge badge-<?= $a['status'] ?>"><?= $a['status'] ?></span>
</td>
<td>
<?php if ($a['status'] === 'booked'): ?>
<form method="post" action="update_appointment.php">
<input type="hidden" name="id" value="<?= $a['id'] ?>">
<button name="cancel" class="btn btn-sm btn-danger">Törlés</button>
</form>
<?php endif; ?>
</td>
</tr>
<?php endforeach; ?>
</tbody>
</table>

</div>
</div>
</div>

<?php include '../views/footer.php'; ?>
