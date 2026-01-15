<?php
session_start();
require_once '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $code = $_POST['code'] ?? '';

    $stmt = $pdo->prepare("
        SELECT email_change_code, code_expires
        FROM users WHERE id = ?
    ");
    $stmt->execute([$_SESSION['user_id']]);
    $row = $stmt->fetch();

    if (
        $row &&
        $row['email_change_code'] === $code &&
        strtotime($row['code_expires']) > time()
    ) {
        $stmt = $pdo->prepare("
            UPDATE users
            SET email = ?, email_change_code = NULL, code_expires = NULL
            WHERE id = ?
        ");
        $stmt->execute([$_SESSION['pending_email'], $_SESSION['user_id']]);

        unset($_SESSION['pending_email']);
        header("Location: index.php");
        exit;
    }

    $error = "Hibás vagy lejárt kód!";
}

include '../views/header.php';
?>

<div class="page-wrapper">
<div class="container">
<div class="card-custom mx-auto" style="max-width:400px">

<h4 class="text-center mb-3">Email megerősítése</h4>

<?php if (!empty($error)): ?>
<div class="alert alert-danger"><?= $error ?></div>
<?php endif; ?>

<form method="post">
    <input class="form-control mb-3" name="code" placeholder="6 jegyű kód" required>
    <button class="btn-main w-100">Megerősítés</button>
</form>

</div>
</div>
</div>

<?php include '../views/footer.php'; ?>
