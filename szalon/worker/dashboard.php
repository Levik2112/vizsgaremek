<?php
session_start();
require_once '../views/header.php';
require_once '../config/db.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'worker') {
    header("Location: ../login.php");
    exit;
}

/* worker_id lekérés */
$stmt = $pdo->prepare("SELECT id FROM workers WHERE user_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$workerId = $stmt->fetchColumn();
?>

<!-- FullCalendar -->
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>

<div class="page-wrapper">
<div class="container">

    <!-- FEJLÉC -->
    <div class="text-center mb-4">
        <h1>Dolgozó felület</h1>
        <p class="text-muted">
            Saját időpontjaid és elérhetőséged kezelése
        </p>
    </div>

    <!-- GOMBOK -->
     
    <div class="d-flex justify-content-center gap-3 mb-4">
        <a href="availability.php" class="btn-outline-custom">
            ⏰ Elérhetőségem
        </a>

        <a href="my_appointments.php" class="btn-outline-custom">
            📋 Időpontjaim
        </a>
        <a href="history.php" class="btn-outline-custom">
    ✔️ Kész időpontok
</a>

    </div>

    <!-- NAPTÁR -->
    <div class="card-custom">
        <div id="calendar"></div>
    </div>

</div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const calendar = new FullCalendar.Calendar(
        document.getElementById('calendar'), {
        initialView: 'timeGridWeek',
        locale: 'hu',
        height: 'auto',

        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'timeGridDay,timeGridWeek,dayGridMonth'
        },

        events: 'appointments_feed.php'
    });

    calendar.render();
});
</script>

<?php include '../views/footer.php'; ?>
