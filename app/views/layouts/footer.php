        </main>

        <?php if (isAuthenticated()): ?>
        <footer class="main-footer">
            <p>&copy; <?= date('Y') ?> SENA - Servicio Nacional de Aprendizaje. Todos los derechos reservados.</p>
        </footer>
        <?php endif; ?>
    </div>

    <script src="<?= asset('js/main.js') ?>"></script>
</body>
</html>
