<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>
<?php
session_start();
require_once '../config/db.php';

$newEmail = filter_var($_POST['new_email'], FILTER_VALIDATE_EMAIL);
if (!$newEmail) {
    die("Érvénytelen email");
}

$code = random_int(100000, 999999);

$stmt = $pdo->prepare("
    UPDATE users
    SET email_change_code = ?, code_expires = DATE_ADD(NOW(), INTERVAL 10 MINUTE)
    WHERE id = ?
");
$stmt->execute([$code, $_SESSION['user_id']]);

// EMAIL (egyszerű mail – később PHPMailer)
mail(
    $newEmail,
    "Email módosítás megerősítés",
    "A megerősítő kódod: $code\n10 percig érvényes."
);

$_SESSION['pending_email'] = $newEmail;

header("Location: confirm_email.php");
exit;
