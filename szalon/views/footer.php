<footer class="footer">
    © <?php echo date("Y"); ?> Szalon Időpontfoglaló
</footer>


<!-- Bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<!-- DARK MODE -->
<script>
document.addEventListener("DOMContentLoaded", () => {
    const toggle = document.getElementById('themeToggle');
    if (!toggle) return;

    const body = document.body;

    if (localStorage.getItem('theme') === 'dark') {
        body.classList.add('dark-mode');
        toggle.textContent = '☀️';
    }

    toggle.addEventListener('click', () => {
        body.classList.toggle('dark-mode');

        const dark = body.classList.contains('dark-mode');
        toggle.textContent = dark ? '☀️' : '🌙';

        localStorage.setItem('theme', dark ? 'dark' : 'light');
    });
});
</script>
<script>
console.log('FOOTER BETÖLTÖTT');
</script>


</body>
</html>
