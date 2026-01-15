<?php
session_start();
require_once '../config/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

$name  = trim($_POST['name'] ?? '');
$phone = trim($_POST['phone'] ?? '');

if (!$name) {
    die("Név kötelező");
}

$stmt = $pdo->prepare("UPDATE users SET name = ?, phone = ? WHERE id = ?");
$stmt->execute([$name, $phone, $_SESSION['user_id']]);
header("Location: index.php?saved=1");
header("Location: index.php");
exit;

