<?php
session_start();
if ($_SESSION['role'] !== 'client') {
    header("Location: ../login.php");
    exit;
}
include '../views/header.php';
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

</div>

<script>
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
