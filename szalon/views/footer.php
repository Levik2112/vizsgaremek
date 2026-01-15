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

<!-- TOAST LOGIKA -->
<div id="toastContainer"></div>

<script>
function showToast(message, type = 'success') {
    const container = document.getElementById('toastContainer');

    const toast = document.createElement('div');
    toast.className = 'toast ' + type;
    toast.textContent = message;

    container.appendChild(toast);

    setTimeout(() => {
        toast.remove();
    }, 3000);
}
</script>

<script>
console.log('FOOTER BETÖLTÖTT');
</script>
<script>
console.log('SHOWTOAST TESZT ELŐTT:', typeof showToast);

/* TESZT KÓD
function showToast(msg) {
    alert('TESZT: ' + msg);
} */
console.log('SHOWTOAST TESZT UTÁN:', typeof showToast);
</script>

</body>
</html>
