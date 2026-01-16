<?php
session_start();
require_once '../config/db.php';
require_once '../views/header.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'worker') {
    header("Location: ../login.php");
    exit;
}

/* worker_id */
$stmt = $pdo->prepare("SELECT id FROM workers WHERE user_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$workerId = $stmt->fetchColumn();

/* mentés */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pdo->prepare("DELETE FROM worker_availability WHERE worker_id = ?")
        ->execute([$workerId]);

    foreach ($_POST['day'] as $i => $day) {
        if (!empty($_POST['start'][$i]) && !empty($_POST['end'][$i])) {
            $pdo->prepare("
                INSERT INTO worker_availability
                (worker_id, day_of_week, start_time, end_time)
                VALUES (?, ?, ?, ?)
            ")->execute([
                $workerId,
                $day,
                $_POST['start'][$i],
                $_POST['end'][$i]
            ]);
        }
    }

    header("Location: availability.php?saved=1");
    exit;
}

/* meglévő adatok */
$stmt = $pdo->prepare("
    SELECT * FROM worker_availability
    WHERE worker_id = ?
");
$stmt->execute([$workerId]);
$availability = $stmt->fetchAll(PDO::FETCH_GROUP | PDO::FETCH_UNIQUE);
?>

<div class="page-wrapper">
<div class="container">
<div class="card-custom">

<h3 class="mb-4">Elérhetőségem</h3>

<?php if (isset($_GET['saved'])): ?>
    <div class="status-message status-success">
        Elérhetőség sikeresen mentve
    </div>
<?php endif; ?>

<form method="post">

<?php
$days = [
    1 => 'Hétfő',
    2 => 'Kedd',
    3 => 'Szerda',
    4 => 'Csütörtök',
    5 => 'Péntek',
    6 => 'Szombat',
    7 => 'Vasárnap'
];

foreach ($days as $d => $name):
    $start = $availability[$d]['start_time'] ?? '';
    $end   = $availability[$d]['end_time'] ?? '';
?>
<div class="availability-row">
    <strong><?= $name ?></strong>

    <input type="hidden" name="day[]" value="<?= $d ?>">

    <input type="time" name="start[]" value="<?= $start ?>">
    <input type="time" name="end[]" value="<?= $end ?>">
</div>
<?php endforeach; ?>

<button class="btn-main mt-3">Mentés</button>
</form>

</div>
</div>
</div>

<?php include '../views/footer.php'; ?>
