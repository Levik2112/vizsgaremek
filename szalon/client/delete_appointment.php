<?php
session_start();
require_once '../config/db.php';

$stmt = $pdo->prepare("
    UPDATE appointments
    SET status = 'cancelled'
    WHERE id = ? AND client_id = ?
");
$stmt->execute([$_POST['id'], $_SESSION['user_id']]);

header("Location: my_appointments.php");
exit;
