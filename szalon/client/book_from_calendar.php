<?php
session_start();
require_once '../config/db.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'client') {
    exit;
}

$datetime = $_POST['date'].' '.$_POST['time'].':00';

$stmt = $pdo->prepare("
    INSERT INTO appointments
    (client_id, worker_id, service_id, appointment_time, status)
    VALUES (?, ?, ?, ?, 'booked')
");

$stmt->execute([
    $_SESSION['user_id'],
    $_POST['worker'],
    $_POST['service'],
    $datetime
]);

header("Location: dashboard.php");
exit;
