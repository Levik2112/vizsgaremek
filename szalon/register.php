<?php
session_start();
require_once 'config/db.php';

$error = "";
$success = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name  = trim($_POST['name']);
    $email = trim($_POST['email']);
    $pass  = $_POST['password'];
    $role  = $_POST['role'];

    if (!$name || !$email || !$pass || !$role) {
        $error = "Minden mező kötelező!";
    } else {

        // email ellenőrzés
        $check = $pdo->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
        $check->execute([$email]);

        if ($check->fetchColumn() > 0) {
            $error = "Ez az email már létezik!";
        } else {

            try {
                $pdo->beginTransaction();

                // USER
                $stmt = $pdo->prepare("
                    INSERT INTO users (name, email, password, role)
                    VALUES (?, ?, ?, ?)
                ");
                $stmt->execute([
                    $name,
                    $email,
                    password_hash($pass, PASSWORD_DEFAULT),
                    $role
                ]);

                $userId = $pdo->lastInsertId();

                // WORKER SPECIÁLIS ADAT
                if ($role === 'worker') {

                    if (empty($_POST['profession'])) {
                        throw new Exception("Dolgozó szakma kötelező!");
                    }

                    $stmt = $pdo->prepare("
                        INSERT INTO workers (user_id, profession)
                        VALUES (?, ?)
                    ");
                    $stmt->execute([
                        $userId,
                        $_POST['profession']
                    ]);
                }

                $pdo->commit();

                // siker → loginra
                header("Location: login.php?registered=1");
                exit;

            } catch (Exception $e) {
                $pdo->rollBack();
                $error = "Hiba történt: " . $e->getMessage();
            }
        }
    }
}

include 'views/header.php';
?>

<div class="auth-container">
<div class="card-custom">
<script src="assets/js/main.js"></script>
<h2 class="text-center mb-4">Regisztráció</h2>

<?php if ($error): ?>
<div class="alert alert-danger"><?= $error ?></div>
<?php endif; ?>

<form method="post">

    <div class="form-group">
        <input type="text" name="name" placeholder="Név" required>
    </div>

    <div class="form-group">
        <input type="email" name="email" placeholder="E-mail" required>
    </div>

     <div class="password-wrapper mb-4">
    <input
        class="form-control"
        type="password"
        name="password"
        id="login-password"
        placeholder="Jelszó"
        required
    >
    <span class="toggle-password" onclick="toggleLoginPassword()">👁</span>
</div>


    <div class="form-group">
        <select name="role" id="role" required onchange="toggleWorker()">
            <option value="">Szerepkör</option>
            <option value="client">Ügyfél</option>
            <option value="worker">Dolgozó</option>
        </select>
    </div>

    <!-- WORKER EXTRA -->
    <div class="form-group" id="workerFields" style="display:none;">
        <select name="profession">
            <option value="">Szakma</option>
            <option value="Fodrász">Fodrász</option>
            <option value="Kozmetikus">Kozmetikus</option>
        </select>
    </div>

    <button class="btn-primary-custom w-100">
        Regisztráció
    </button>

</form>

<div class="auth-link">
    <a href="login.php">Van már fiókod? Bejelentkezés</a>
</div>

</div>
</div>

<script>
function toggleWorker() {
    const role = document.getElementById('role').value;
    document.getElementById('workerFields').style.display =
        role === 'worker' ? 'block' : 'none';
}
</script>

<?php include 'views/footer.php'; ?>
