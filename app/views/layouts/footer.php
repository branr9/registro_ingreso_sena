        </main>

        <?php if (isAuthenticated()): ?>
        <footer class="text-center text-xs text-gray-400 py-6 px-4 md:px-8">
            <p>&copy; <?= date('Y') ?> SENA - Servicio Nacional de Aprendizaje. Todos los derechos reservados.</p>
        </footer>
        <?php endif; ?>
    </div>
    <?php if (isAuthenticated()): ?>
    </div>
    <?php endif; ?>

    <script src="<?= asset('js/main.js') ?>"></script>
    <script>
        (function() {
            var toggle = document.getElementById('themeToggle');
            if (!toggle) return;
            var icon = document.getElementById('themeToggleIcon');

            function updateIcon() {
                var isDark = document.documentElement.classList.contains('dark');
                icon.className = isDark ? 'fas fa-sun' : 'fas fa-moon';
            }
            updateIcon();

            toggle.addEventListener('click', function() {
                var isDark = document.documentElement.classList.toggle('dark');
                localStorage.setItem('sena-theme', isDark ? 'dark' : 'light');
                updateIcon();
            });
        })();
    </script>
</body>
</html>
