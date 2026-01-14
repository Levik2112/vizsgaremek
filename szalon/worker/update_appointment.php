<?php
require_once '../views/header.php';
require_once '../config/db.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'worker') {
    header("Location: ../login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $appointmentId = $_POST['appointment_id'] ?? null;
    $action = $_POST['action'] ?? null;

    if ($appointmentId && $action === 'done') {

        $stmt = $pdo->prepare("
            UPDATE appointments
            SET status = 'done'
            WHERE id = ?
        ");
        $stmt->execute([$appointmentId]);
    }
}

header("Location: my_appointments.php");
exit;
