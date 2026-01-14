<?php
session_start();
require_once '../config/db.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

$stmt = $pdo->query("
    SELECT 
        u.id,
        u.name,
        u.email,
        u.role,
        w.profession
    FROM users u
    LEFT JOIN workers w ON w.user_id = u.id
    ORDER BY u.role, u.name
");

$users = $stmt->fetchAll();

require_once '../views/header.php';
?>

<div class="page-wrapper">
<div class="container">
<div class="card-custom">

<h3 class="mb-4">Felhasználók</h3>

<table class="table table-striped">
<thead>
<tr>
    <th>ID</th>
    <th>Név</th>
    <th>Email</th>
    <th>Szerepkör</th>
    <th>Szakma</th>
</tr>
</thead>
<tbody>

<?php foreach ($users as $u): ?>
<tr>
    <td><?= $u['id'] ?></td>
    <td><?= htmlspecialchars($u['name']) ?></td>
    <td><?= htmlspecialchars($u['email']) ?></td>
    <td><?= $u['role'] ?></td>
    <td><?= $u['profession'] ?? '-' ?></td>
</tr>
<?php endforeach; ?>

</tbody>
</table>

</div>
</div>
</div>

<?php include '../views/footer.php'; ?>
