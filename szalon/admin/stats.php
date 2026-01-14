<?php
session_start();
require_once '../config/db.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    http_response_code(403);
    exit;
}

/* ===== FELHASZNÁLÓK SZÁMA ===== */
$users = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();

/* ===== HAVI FOGLALÁSOK ===== */
$appointments = $pdo->query("
    SELECT COUNT(*) 
    FROM appointments
    WHERE MONTH(appointment_time) = MONTH(CURRENT_DATE())
      AND YEAR(appointment_time) = YEAR(CURRENT_DATE())
")->fetchColumn();

/* ===== HAVI BEVÉTEL (CSAK DONE) ===== */
$revenue = $pdo->query("
    SELECT COALESCE(SUM(s.price), 0)
    FROM appointments a
    JOIN services s ON s.id = a.service_id
    WHERE a.status = 'done'
      AND MONTH(a.appointment_time) = MONTH(CURRENT_DATE())
      AND YEAR(a.appointment_time) = YEAR(CURRENT_DATE())
")->fetchColumn();

/* ===== BEVÉTEL SZOLGÁLTATÁSONKÉNT (CSAK DONE) ===== */
$stmt = $pdo->query("
    SELECT 
        s.name,
        SUM(s.price) AS total
    FROM appointments a
    JOIN services s ON s.id = a.service_id
    WHERE a.status = 'done'
    GROUP BY s.id
");

$services = $stmt->fetchAll(PDO::FETCH_ASSOC);

/* ===== JSON VÁLASZ ===== */
echo json_encode([
    'users'        => (int)$users,
    'appointments' => (int)$appointments,
    'revenue'      => (int)$revenue,
    'services'     => $services
]);
