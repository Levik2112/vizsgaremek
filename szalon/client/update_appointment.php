<?php
session_start();
require_once '../config/db.php';

if ($_SESSION['role'] !== 'client') {
    exit;
}

$id   = $_POST['appointment_id'] ?? null;
$time = $_POST['new_time'] ?? null;

if (!$id || !$time) {
    header("Location: my_appointments.php?error=Hibás adatok");
    exit;
}

$stmt = $pdo->prepare("
    UPDATE appointments
    SET appointment_time = ?
    WHERE id = ? AND client_id = ?
");
$stmt->execute([$time, $id, $_SESSION['user_id']]);

header("Location: my_appointments.php?success=Sikeres módosítás");
exit;
