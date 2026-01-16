<?php
session_start();
require_once '../config/db.php';

$stmt = $pdo->prepare("SELECT id FROM workers WHERE user_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$workerId = $stmt->fetchColumn();

$stmt = $pdo->prepare("
    SELECT 
        a.appointment_time AS start,
        s.name AS title
    FROM appointments a
    JOIN services s ON s.id = a.service_id
    WHERE a.worker_id = ?
      AND a.status = 'booked'
");
$stmt->execute([$workerId]);

echo json_encode($stmt->fetchAll());
