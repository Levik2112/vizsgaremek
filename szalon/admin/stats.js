fetch('stats.php')
    .then(res => res.json())
    .then(data => {

        // Számok
        document.getElementById('usersCount').innerText = data.users;
        document.getElementById('appointmentsCount').innerText = data.appointments;
        document.getElementById('revenueSum').innerText = data.revenue + ' Ft';

        // Diagram adatok
        const labels = data.chart.map(item => item.name);
        const values = data.chart.map(item => item.total);

        new Chart(document.getElementById('revenueChart'), {
            type: 'pie',
            data: {
                labels: labels,
                datasets: [{
                    data: values
                }]
            }
        });
    });
