<?php
session_start();
require_once '../config/db.php';

$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM appointments WHERE id = ?");
$stmt->execute([$id]);
$row = $stmt->fetch();
?>

<div class="page-wrapper">
<div class="container">
<div class="card-custom mx-auto" style="max-width:500px">

<h3 class="text-center mb-4">Időpont módosítása</h3>

<form method="post" action="update_appointment.php">
    <input type="hidden" name="id" value="<?= $id ?>">

    <div class="form-group">
        <label>Új időpont</label>
        <input type="datetime-local" name="datetime" required>
    </div>

    <button class="btn-main w-100 mt-3">Mentés</button>
</form>

</div>
</div>
</div>
