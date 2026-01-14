<?php
require_once 'views/header.php';
require_once 'config/db.php';

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$_POST['email']]);
    $user = $stmt->fetch();

    if ($user && password_verify($_POST['password'], $user['password'])) {

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role']    = $user['role'];

        if ($user['role'] === 'client') {
            header("Location: client/dashboard.php");
        } elseif ($user['role'] === 'worker') {
            header("Location: worker/dashboard.php");
        } else {
            header("Location: admin/dashboard.php");
        }
        exit;

    } else {
        $error = "Hibás adatok!";
    }
}
?>
<div class="page-wrapper">

    <!-- HERO / FEJLÉC -->
    <div class="container text-center mb-5">
        <h1 class="mb-3">Szalon Időpontfoglaló</h1>
        <p class="text-muted">
            Online időpontfoglalás ügyfeleknek és dolgozóknak – gyorsan, egyszerűen.
        </p>
    </div>
<script src="assets/js/main.js"></script>
    <!-- FUNKCIÓ KÁRTYÁK -->
    <div class="container mb-5">
        <div class="row g-4 justify-content-center">

            <div class="col-md-4">
                <div class="card-custom text-center h-100">
                    <h5>📅 Időpontfoglalás</h5>
                    <p class="text-muted mt-2">
                        Foglalj időpontot pár kattintással a választott szolgáltatásra.
                    </p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card-custom text-center h-100">
                    <h5>👤 Saját fiók</h5>
                    <p class="text-muted mt-2">
                        Kezeld a foglalásaidat, módosíts vagy törölj időpontokat.
                    </p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card-custom text-center h-100">
                    <h5>📊 Admin felület</h5>
                    <p class="text-muted mt-2">
                        Statisztikák, dolgozók és bevételek egy helyen.
                    </p>
                </div>
            </div>

        </div>
    </div>

    <!-- LOGIN KÁRTYA -->
    <div class="container d-flex justify-content-center">
        <div class="card-custom" style="max-width:400px;width:100%">

            <h3 class="text-center mb-4">Bejelentkezés</h3>

            <?php if ($error): ?>
                <div class="alert alert-danger"><?= $error ?></div>
            <?php endif; ?>

            <form method="post">
                <input class="form-control mb-3" name="email" placeholder="Email" required>
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

                <button class="btn-main w-100">Belépés</button>
            </form>

            <p class="text-center mt-4 text-muted" style="font-size:0.9rem">
                Még nincs fiókod? <a href="register.php">Regisztráció</a>
            </p>

        </div>
    </div>

</div>


<?php include 'views/footer.php'; ?>
