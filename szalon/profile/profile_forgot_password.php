<?php
session_start();
require_once __DIR__ . '/../config/db.php';

// PHPMailer kézzel
require_once __DIR__ . '/../lib/PHPMailer.php';
require_once __DIR__ . '/../lib/SMTP.php';
require_once __DIR__ . '/../lib/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;


if (!isset($_SESSION['user_id'])) {
    die("Nincs bejelentkezve");
}

// email lekérés PROFILBÓL
$stmt = $pdo->prepare("SELECT email FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();

if (!$user) {
    die("Felhasználó nem található");
}

// kód
$code = bin2hex(random_bytes(32));
$expires = date('Y-m-d H:i:s', strtotime('+30 minutes'));

$stmt = $pdo->prepare("
    UPDATE users
    SET password_change_code = ?, code_expires = ?
    WHERE id = ?
");
$stmt->execute([$code, $expires, $_SESSION['user_id']]);

$link = "http://localhost/szalon/profile/reset_password.php?code=$code";


// EMAIL
$mail = new PHPMailer(true);
$mail->isSMTP();
$mail->Host = 'smtp.gmail.com';
$mail->SMTPAuth = true;

// 🔴 IDE A SAJÁT GMAIL ADATAID
$mail->Username = 'dobi.levente.domonkos.21@ady-nagyatad.hu';
$mail->Password = 'zbrv mosq ltxi jrqw';

$mail->SMTPSecure = 'tls';
$mail->Port = 587;

$mail->setFrom($mail->Username, 'Szalon');
$mail->addAddress($user['email']);

$mail->isHTML(true);
$mail->Subject = 'Jelszó visszaállítás';
$mail->Body = "
    <p>Kattints a jelszó módosításához:</p>
    <p><a href='$link'>$link</a></p>
    <p>30 percig érvényes.</p>
";

$mail->send();

echo "✅ Email elküldve a profilodhoz tartozó címre.";
