<?php
session_start();
require_once '../config/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

$id = $_POST['id'] ?? null;
$user = $_SESSION['user_id'];

$stmt = $pdo->prepare("
    UPDATE appointments
    SET status = 'cancelled'
    WHERE id = ? AND client_id = ?
");
$stmt->execute([$id, $user]);

header("Location: my_appointments.php?status=cancelled");
exit;
