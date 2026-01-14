<?php
session_start();
require_once '../config/db.php';

if ($_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

if (isset($_POST['cancel'])) {
    $stmt = $pdo->prepare("
        UPDATE appointments
        SET status = 'cancelled'
        WHERE id = ?
    ");
    $stmt->execute([$_POST['id']]);
}

header("Location: appointments.php");
exit;
