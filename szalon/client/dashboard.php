<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'client') {
    header("Location: ../login.php");
    exit;
}

require_once '../config/db.php';
include '../views/header.php';

/* SZOLGÁLTATÁSOK */
$services = $pdo->query("
    SELECT id, name, price, duration 
    FROM services
")->fetchAll();

/* DOLGOZÓK */
$workers = $pdo->query("
    SELECT w.id, u.name
    FROM workers w
    JOIN users u ON u.id = w.user_id
")->fetchAll();
?>

<!-- FullCalendar + Interaction -->
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@fullcalendar/interaction@6.1.11/index.global.min.js"></script>



<div class="container-fluid">
    <div class="container text-center mt-4 mb-4">
    <div class="d-flex justify-content-center gap-3">
        <a href="book.php" class="btn-primary-custom">
            Időpont foglalás
        </a>

        <a href="my_appointments.php" class="btn-outline-custom">
            Foglalásaim
        </a>
        <a href="history.php" class="btn-outline-custom">
    Korábbi foglalások
</a>

    </div>
</div>

<div class="row">

<!-- ===== BAL OLDAL: SZOLGÁLTATÁS KÁRTYÁK ===== -->
<div class="col-md-3">
    <h5 class="mb-3">Szolgáltatások</h5>

    <div id="service-list">
        <?php foreach ($services as $s): ?>
            <div class="service-card"
                 data-id="<?= $s['id'] ?>"
                 data-title="<?= htmlspecialchars($s['name']) ?>"
                 data-duration="<?= $s['duration'] ?>">
                <strong><?= htmlspecialchars($s['name']) ?></strong>
                <div><?= number_format($s['price'], 0, '', ' ') ?> Ft</div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- ===== JOBB OLDAL: NAPTÁR ===== -->
<div class="col-md-9">
    <div id="calendar"></div>
</div>

</div>
</div>
</div>

<!-- ===== BOOKING MODAL ===== -->
<div id="bookingModal" class="modal-overlay">
<div class="modal-box">

<h5>Időpont foglalás</h5>

<form method="post" action="book_from_calendar.php">
    <input type="hidden" name="date" id="modalDate">
    <input type="hidden" name="service" id="modalService">

    <label>Időpont</label>
    <input type="time" name="time" required>

    <label>Dolgozó</label>
    <select name="worker" required>
        <option value="">Válassz dolgozót</option>
        <?php foreach ($workers as $w): ?>
            <option value="<?= $w['id'] ?>">
                <?= htmlspecialchars($w['name']) ?>
            </option>
        <?php endforeach; ?>
    </select>

    <button class="btn-main w-100 mt-3">Foglalás</button>
</form>

<button class="modal-close" onclick="closeModal()">✕</button>
</div>
</div>

<script>
/* ===== MODAL ===== */
function openModal(date, serviceId) {
    document.getElementById('modalDate').value = date;
    document.getElementById('modalService').value = serviceId;
    document.getElementById('bookingModal').classList.add('active');
}

function closeModal() {
    document.getElementById('bookingModal').classList.remove('active');
}

/* ===== FULLCALENDAR + DRAG ===== */
document.addEventListener('DOMContentLoaded', function () {

    new FullCalendar.Draggable(document.getElementById('service-list'), {
        itemSelector: '.service-card',
        eventData: function(el) {
            return {
                title: el.dataset.title,
                extendedProps: {
                    service_id: el.dataset.id
                }
            };
        }
    });

    const calendar = new FullCalendar.Calendar(
        document.getElementById('calendar'), {
        initialView: 'dayGridMonth',
        locale: 'hu',
        height: 'auto',
        droppable: true,

        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },

        drop: function(info) {
            openModal(
                info.dateStr,
                info.draggedEl.dataset.id
            );
        },

        events: 'appointments_feed.php'
    });

    calendar.render();
});
</script>

<?php include '../views/footer.php'; ?>
