<?php
session_start();
require_once '../config/db.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

include '../views/header.php';
?>

<div class="page-wrapper">
    <div class="container">

        <div class="text-center mb-5">
            <h1>Admin felület</h1>
            <p class="text-muted">Rendszeráttekintés és kezelés</p>
        </div>

        <!-- STAT KÁRTYÁK -->
        <div class="row g-4 mb-5">
            <div class="col-md-4">
                <div class="card-custom text-center">
                    <h2 id="usersCount">–</h2>
                    <p class="text-muted">Felhasználók</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card-custom text-center">
                    <h2 id="appointmentsCount">–</h2>
                    <p class="text-muted">Havi foglalások</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card-custom text-center">
                    <h2 id="revenueSum">– Ft</h2>
                    <p class="text-muted">Havi bevétel</p>
                </div>
            </div>
        </div>

        <!-- MENÜ -->
        <div class="row g-4 justify-content-center mb-5">
            <div class="col-md-4">
                <div class="card-custom text-center h-100">
                    <h5>Foglalások</h4>
                    <p class="text-muted">Összes időpont kezelése</p>
                    <a href="appointments.php" class="btn-outline-main mt-3">Megnyitás</a>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card-custom text-center h-100">
                    <h5>Dolgozók/Felhasználók listázása</h4>
                    <p class="text-muted">Összes user kezelése</p>
                    <a href="workers.php" class="btn-outline-main mt-3">Dolgozók</a>
                    <a href="users.php" class="btn-outline-main mt-3">Felhasználók</a>

                </div>
            </div>
        </div>


        <!-- DIAGRAM -->
        <div class="card-custom">
            <h4 class="text-center mb-3">Bevétel szolgáltatásonként</h4>
            <div class="chart-container">
    <canvas id="revenueChart"></canvas>
</div>

        </div>

    </div>
</div>

<?php include '../views/footer.php'; ?>


<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener("DOMContentLoaded", () => {

    fetch("stats.php")
        .then(res => res.json())
        .then(data => {

            document.getElementById("usersCount").innerText = data.users;
            document.getElementById("appointmentsCount").innerText = data.appointments;
            document.getElementById("revenueSum").innerText = data.revenue + " Ft";

            if (!data.services || data.services.length === 0) {
                document.querySelector(".chart-container").innerHTML =
                    "<p class='text-center text-muted mt-4'>Nincs megjeleníthető adat</p>";
                return;
            }

            const ctx = document.getElementById("revenueChart").getContext("2d");

            new Chart(ctx, {
                type: "doughnut",
                data: {
                    labels: data.services.map(s => s.name),
                    datasets: [{
                        data: data.services.map(s => s.total)
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false
                }
            });
        });

});
</script>

