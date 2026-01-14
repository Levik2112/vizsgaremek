<?php
session_start();
require_once '../config/db.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'client') {
    header("Location: ../login.php");
    exit;
}

$services = $pdo->query("SELECT id, name, price FROM services")->fetchAll();

/* FONTOS: workers.id kell, NEM users.id */
$workers = $pdo->query("
    SELECT w.id, u.name
    FROM workers w
    JOIN users u ON u.id = w.user_id
")->fetchAll();

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $clientId  = $_SESSION['user_id'];
    $workerId  = $_POST['worker'];
    $serviceId = $_POST['service'];
    $time      = $_POST['datetime'];

    // ütközés ellenőrzés
    $check = $pdo->prepare("
        SELECT COUNT(*) FROM appointments
        WHERE worker_id = ?
        AND appointment_time = ?
        AND status = 'booked'
    ");
    $check->execute([$workerId, $time]);

    if ($check->fetchColumn() > 0) {
        $message = "❌ Ez az időpont már foglalt!";
    } else {
        $stmt = $pdo->prepare("
            INSERT INTO appointments (client_id, worker_id, service_id, appointment_time, status)
            VALUES (?, ?, ?, ?, 'booked')
        ");
        $stmt->execute([$clientId, $workerId, $serviceId, $time]);
        $message = "✅ Sikeres foglalás!";
    }
}

include '../views/header.php';
?>

<div class="page-wrapper">
<div class="container">
<div class="card-custom mx-auto" style="max-width:600px">

<h3 class="text-center mb-4">Időpont foglalás</h3>

<?php if ($message): ?>
<div class="alert alert-info text-center"><?= $message ?></div>
<?php endif; ?>

<form method="post">
    <div class="mb-3">
        <select name="service" class="form-control" required>
            <option value="">Szolgáltatás</option>
            <?php foreach ($services as $s): ?>
                <option value="<?= $s['id'] ?>">
                    <?= $s['name'] ?> (<?= $s['price'] ?> Ft)
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="mb-3">
        <select name="worker" class="form-control" required>
            <option value="">Dolgozó</option>
            <?php foreach ($workers as $w): ?>
                <option value="<?= $w['id'] ?>">
                    <?= $w['name'] ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="mb-4">
        <input type="datetime-local" name="datetime" class="form-control" required>
    </div>

    <button class="btn-main w-100">Foglalás</button>
</form>

</div>
</div>
</div>

<?php include '../views/footer.php'; ?>
