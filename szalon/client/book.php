<?php
session_start();
require_once '../config/db.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'client') {
    header("Location: ../login.php");
    exit;
}

$selectedDate = $_GET['date'] ?? '';

$services = $pdo->query("SELECT id, name FROM services")->fetchAll();

$workers = $pdo->query("
    SELECT w.id, u.name
    FROM workers w
    JOIN users u ON u.id = w.user_id
")->fetchAll();

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $clientId  = $_SESSION['user_id'];
    $workerId  = $_POST['worker']   ?? null;
    $serviceId = $_POST['service']  ?? null;
    $datetime  = $_POST['datetime'] ?? null;

    if (!$workerId || !$serviceId || !$datetime) {
        $message = "❌ Hiányzó adatok!";
    } else {

        $check = $pdo->prepare("
            SELECT COUNT(*) FROM appointments
            WHERE worker_id = ?
            AND appointment_time = ?
            AND status = 'booked'
        ");
        $check->execute([$workerId, $datetime]);

        if ($check->fetchColumn() > 0) {
            $message = "❌ Ez az időpont már foglalt!";
        } else {

            $stmt = $pdo->prepare("
                INSERT INTO appointments (client_id, worker_id, service_id, appointment_time, status)
                VALUES (?, ?, ?, ?, 'booked')
            ");
            $stmt->execute([$clientId, $workerId, $serviceId, $datetime]);

            $message = "✅ Sikeres foglalás!";
        }
    }
}

include '../views/header.php';
?>

<div class="page-wrapper">
<div class="container">
<div class="card-custom mx-auto" style="max-width:500px">

<h3 class="text-center mb-4">Időpont foglalás</h3>

<?php if ($message): ?>
<div class="alert alert-info text-center"><?= $message ?></div>
<?php endif; ?>

<form method="post">

    <div class="form-group">
        <label>Dátum és idő</label>
        <input type="datetime-local" name="datetime" class="form-control"
               value="<?= $selectedDate ? $selectedDate . 'T09:00' : '' ?>" required>
    </div>

    <div class="form-group mt-3">
        <label>Dolgozó</label>
        <select name="worker" class="form-control" required>
            <option value="">Válassz dolgozót</option>
            <?php foreach ($workers as $w): ?>
                <option value="<?= $w['id'] ?>"><?= htmlspecialchars($w['name']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="form-group mt-3">
        <label>Szolgáltatás</label>
        <select name="service" class="form-control" required>
            <option value="">Válassz szolgáltatást</option>
            <?php foreach ($services as $s): ?>
                <option value="<?= $s['id'] ?>"><?= htmlspecialchars($s['name']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <button class="btn-main w-100 mt-4">Foglalás</button>

</form>

</div>
</div>
</div>

<?php include '../views/footer.php'; ?>
