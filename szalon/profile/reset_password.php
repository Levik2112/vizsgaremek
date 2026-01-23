<?php
require_once __DIR__ . '../config/db.php';

$code = $_GET['code'] ?? null;
if (!$code) die("Érvénytelen link");

$stmt = $pdo->prepare("
    SELECT id FROM users
    WHERE password_change_code = ?
      AND code_expires > NOW()
");
$stmt->execute([$code]);
$user = $stmt->fetch();

if (!$user) die("A link lejárt vagy érvénytelen");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (strlen($_POST['password']) < 6) {
        die("Jelszó túl rövid");
    }

    $hash = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("
        UPDATE users
        SET password = ?, password_change_code = NULL, code_expires = NULL
        WHERE id = ?
    ");
    $stmt->execute([$hash, $user['id']]);

    echo "✅ Jelszó módosítva. <a href='login.php'>Bejelentkezés</a>";
    exit;
}
?>

<form method="post">
    <input type="password" name="password" placeholder="Új jelszó" required>
    <button>Mentés</button>
</form>
