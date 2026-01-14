<?php
session_start();
require_once '../config/db.php';

if ($_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

$workers = $pdo->query("
    SELECT 
        u.name,
        u.email,
        w.profession,
        w.qualification,
        COUNT(a.id) AS active
    FROM workers w
    JOIN users u ON u.id = w.user_id
    LEFT JOIN appointments a 
        ON a.worker_id = w.id 
        AND a.status = 'booked'
    GROUP BY w.id
")->fetchAll();

include '../views/header.php';
?>

<div class="page-wrapper">
<div class="container">
<div class="card-custom">

<h3 class="mb-4">Dolgozók</h3>

<table class="table table-striped">
<thead>
<tr>
<th>Név</th><th>Email</th><th>Szakma</th><th>Végzettség</th><th>Aktív időpont</th>
</tr>
</thead>
<tbody>
<?php foreach ($workers as $w): ?>
<tr>
<td><?= $w['name'] ?></td>
<td><?= $w['email'] ?></td>
<td><?= $w['profession'] ?></td>
<td><?= $w['qualification'] ?></td>
<td class="text-center">
<span class="badge bg-info"><?= $w['active'] ?></span>
</td>
</tr>
<?php endforeach; ?>
</tbody>
</table>

</div>
</div>
</div>

<?php include '../views/footer.php'; ?>
