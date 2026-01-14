<?php
session_start();
if ($_SESSION['role'] !== 'client') {
    header("Location: ../login.php");
    exit;
}
require_once '../config/db.php';
include '../views/header.php';
?>
<?php
$workers = $pdo->query("
    SELECT w.id, u.name
    FROM workers w
    JOIN users u ON u.id = w.user_id
")->fetchAll();


$reminder = null;

$stmt = $pdo->prepare("
    SELECT a.appointment_time, u.name AS worker_name
    FROM appointments a
    JOIN workers w ON w.id = a.worker_id
    JOIN users u ON u.id = w.user_id
    WHERE a.client_id = ?
      AND a.status = 'booked'
      AND a.appointment_time BETWEEN NOW() AND DATE_ADD(NOW(), INTERVAL 30 MINUTE)
    LIMIT 1
");
$stmt->execute([$_SESSION['user_id']]);
$reminder = $stmt->fetch();




?>


<!-- FullCalendar CDN -->
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>

<div class="page-wrapper">

    <div class="container mt-5 text-center">
        <h1>Ügyfél felület</h1>
        <p class="text-muted">Itt tudsz időpontot foglalni és kezelni.</p>

        <div class="d-flex justify-content-center gap-3 mt-4 mb-5">
            <a href="book.php" class="btn-primary-custom">
                Időpont foglalás
            </a>

            <a href="my_appointments.php" class="btn-outline-custom">
                Foglalásaim
            </a>
        </div>
    </div>

    <!-- NAPTÁR -->
    <div class="container mb-5">
        <div id="calendar"></div>
    </div>

    <!-- BOOKING MODAL -->
<div id="bookingModal" class="modal-overlay">
    <div class="modal-box">
        <h4>Időpont foglalás</h4>

        <form method="post" action="book.php">

            <!-- DÁTUM -->
            <input type="hidden" name="datetime" id="modalDate">

            <div class="form-group">
                <label>Dátum</label>
                <input type="text" id="modalDateText" disabled>
            </div>

            <!-- DOLGOZÓ -->
            <div class="form-group">
    <label>Dolgozó</label>
    <select name="worker" required>
        <option value="">Válassz dolgozót</option>

        <?php foreach ($workers as $w): ?>
            <option value="<?= $w['id'] ?>">
                <?= htmlspecialchars($w['name']) ?>
            </option>
        <?php endforeach; ?>

    </select>
</div>


            <!-- SZOLGÁLTATÁS -->
            <div class="form-group">
                <label>Szolgáltatás</label>
                <select name="service" required>
                    <option value="">Mit szeretnél?</option>
                    <option value="1">Hajvágás</option>
                    <option value="2">Festés</option>
                </select>
            </div>

            <button class="btn-main w-100 mt-3">Foglalás</button>
        </form>

        <button class="modal-close" onclick="closeModal()">✕</button>
    </div>
</div>




<script>
/* MODAL KEZELÉS */


function openModal(dateStr) {
    document.getElementById('modalDate').value = dateStr + ' 09:00:00';
    document.getElementById('modalDateText').value = dateStr;
    document.getElementById('bookingModal').classList.add('active');
}

function closeModal() {
    document.getElementById('bookingModal').classList.remove('active');
}

/* FULLCALENDAR */
document.addEventListener('DOMContentLoaded', function () {

    const calendarEl = document.getElementById('calendar');

    const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        locale: 'hu',
        height: 'auto',

        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },

        dateClick: function(info) {
            openModal(info.dateStr);
        },

        events: 'appointments_feed.php',

        eventClick: function(info) {
            alert(
                'Foglalás részletei:\n\n' +
                'Kezdés: ' + info.event.start.toLocaleString()
            );
        }
    });

    calendar.render();
});
</script>

<?php include '../views/footer.php'; ?>
