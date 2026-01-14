<?php
session_start();
require_once '../config/db.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'client') {
    http_response_code(403);
    exit;
}

$clientId = $_SESSION['user_id'];

$stmt = $pdo->prepare("
    SELECT 
        appointment_time AS start,
        DATE_ADD(appointment_time, INTERVAL 1 HOUR) AS end,
        status
    FROM appointments
    WHERE client_id = ?
");
$stmt->execute([$clientId]);

$events = [];

while ($row = $stmt->fetch()) {
    $events[] = [
        'title' => 'Foglalás',
        'start' => $row['start'],
        'end'   => $row['end'],
        'color' => match ($row['status']) {
            'booked' => '#d4af37',
            'done' => '#198754',
            'cancelled' => '#6c757d',
            default => '#999'
        }
    ];
}

header('Content-Type: application/json');
echo json_encode($events);
