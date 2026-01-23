<?php
session_start();
require_once '../views/header.php';
require_once '../config/db.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'client') {
    header("Location: ../login.php");
    exit;
}

/* === TÖRLÉS === */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cancel_id'])) {
    $stmt = $pdo->prepare("
        UPDATE appointments 
        SET status = 'cancelled' 
        WHERE id = ? AND client_id = ?
    ");
    $stmt->execute([$_POST['cancel_id'], $_SESSION['user_id']]);

    header("Location: my_appointments.php?success=Sikeres lemondás");
    exit;
}

$stmt = $pdo->prepare("
    SELECT a.id, a.appointment_time, a.status, s.name AS service
    FROM appointments a
    JOIN services s ON s.id = a.service_id
    WHERE a.client_id = ?
      AND a.status = 'booked'
    ORDER BY a.appointment_time DESC
");

$stmt->execute([$_SESSION['user_id']]);
$appointments = $stmt->fetchAll();
?>

<div class="page-wrapper">
<div class="container">
<div class="card-custom">

<h3 class="mb-4">Foglalásaim</h3>

<?php if (isset($_GET['success'])): ?>
    <div class="status-message status-success">
        <?= htmlspecialchars($_GET['success']) ?>
    </div>
<?php endif; ?>

<?php if (empty($appointments)): ?>
    <p class="text-muted">Még nincs foglalásod.</p>
<?php else: ?>

<ul class="appointment-list">
<?php foreach ($appointments as $a): ?>
<li class="appointment-item">

    <div class="appointment-info">
        <strong><?= date('Y-m-d H:i', strtotime($a['appointment_time'])) ?></strong>
        <span><?= htmlspecialchars($a['service']) ?></span>
        <span class="status-badge status-<?= $a['status'] ?>">
            <?= $a['status'] ?>
        </span>
    </div>

    <div class="appointment-actions">
        <?php if ($a['status'] === 'booked'): ?>

            <button
    class="btn-action btn-edit"
    onclick="openEditModal(<?= $a['id'] ?>, '<?= $a['appointment_time'] ?>')">
    Módosítás
</button>


            <form method="post" onsubmit="return confirm('Biztosan lemondod?')">
                <input type="hidden" name="cancel_id" value="<?= $a['id'] ?>">
                <button class="btn-action btn-delete">
                    Lemondás
                </button>
            </form>

        <?php else: ?>
            <span class="text-muted">Lezárt</span>
        <?php endif; ?>
    </div>

</li>
<?php endforeach; ?>
</ul>


<?php endif; ?>

</div>
</div>
</div>
<script>
function openEditModal(id, time) {
    document.getElementById('editId').value = id;

    // datetime-local formátum
    const formatted = time.replace(' ', 'T').slice(0,16);
    document.getElementById('editTime').value = formatted;

    document.getElementById('editModal').classList.add('active');
}

function closeEditModal() {
    document.getElementById('editModal').classList.remove('active');
}
</script>

<div id="editModal" class="modal-overlay">
    <div class="modal-box">
        <h5>Időpont módosítása</h5>

        <form method="post" action="update_appointment.php">
            <input type="hidden" name="appointment_id" id="editId">

            <label>Új időpont</label>
            <input type="datetime-local" name="new_time" id="editTime" required>

            <button class="btn-main w-100 mt-3">Mentés</button>
        </form>

        <button class="modal-close" onclick="closeEditModal()">✕</button>
    </div>
</div>

<?php include '../views/footer.php'; ?>
