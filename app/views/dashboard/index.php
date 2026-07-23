<?php require_once APP_PATH . '/views/layouts/header.php'; ?>

<div class="dashboard-container">
    <div class="dashboard-stats">
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-user"></i>
            </div>
            <div class="stat-content">
                <h3><?= e(currentUser()['nombre']) ?></h3>
                <p>Usuario Actual</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-shield-alt"></i>
            </div>
            <div class="stat-content">
                <h3><?= ucfirst(e(currentUser()['rol'])) ?></h3>
                <p>Rol del Sistema</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-clock"></i>
            </div>
            <div class="stat-content">
                <h3><?= formatSessionTime(getSessionElapsedTime()) ?></h3>
                <p>Tiempo de Sesión</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-network-wired"></i>
            </div>
            <div class="stat-content">
                <h3><?= e($_SERVER['REMOTE_ADDR'] ?? 'N/A') ?></h3>
                <p>IP de Conexión</p>
            </div>
        </div>
    </div>

    <div class="dashboard-modules">
        <h3><i class="fas fa-th-large"></i> Módulos del Sistema</h3>
        
        <div class="modules-grid">
            <?php if (Auth::hasRole('admin')): ?>
            <a href="<?= baseUrl('/usuarios') ?>" class="module-card-link">
                <div class="module-card">
                    <div class="module-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <h4>Gestión de Usuarios</h4>
                    <p>Administrar usuarios del sistema</p>
                    <span class="badge badge-active">Activo</span>
                </div>
            </a>
            <?php endif; ?>

            <?php if (Auth::hasRole('admin') || Auth::hasRole('vigilante')): ?>
            <a href="<?= baseUrl('/control-ingreso/kiosk') ?>" class="module-card-link">
                <div class="module-card">
                    <div class="module-icon">
                        <i class="fas fa-qrcode"></i>
                    </div>
                    <h4>Control de Ingreso</h4>
                    <p>Registro de entrada y salida con código de barras</p>
                    <span class="badge badge-active">Activo</span>
                </div>
            </a>

            <a href="<?= baseUrl('/acceso-externo') ?>" class="module-card-link">
                <div class="module-card">
                    <div class="module-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <h4>Personal Externo</h4>
                    <p>Registro de visitantes y personal sin carnet</p>
                    <span class="badge badge-active">Activo</span>
                </div>
            </a>
            <?php endif; ?>

            <?php if (Auth::hasRole('admin') || Auth::hasRole('instructor')): ?>
            <a href="<?= baseUrl('/control-llaves') ?>" class="module-card-link">
                <div class="module-card">
                    <div class="module-icon">
                        <i class="fas fa-key"></i>
                    </div>
                    <h4>Control de Llaves</h4>
                    <p>Préstamo y devolución de llaves</p>
                    <span class="badge badge-active">Activo</span>
                </div>
            </a>
            <?php endif; ?>

            <?php if (Auth::hasRole('admin')): ?>
            <a href="<?= baseUrl('/reportes') ?>" class="module-card-link">
                <div class="module-card">
                    <div class="module-icon">
                        <i class="fas fa-chart-bar"></i>
                    </div>
                    <h4>Reportes</h4>
                    <p>Informes y estadísticas</p>
                    <span class="badge badge-active">Activo</span>
                </div>
            </a>
            <?php endif; ?>
        </div>
    </div>

    <div class="dashboard-info">
        <h3><i class="fas fa-shield-alt"></i> Información de Seguridad</h3>
        <div class="info-grid">
            <div class="info-item">
                <i class="fas fa-clock"></i>
                <div>
                    <strong>Tiempo de sesión</strong>
                    <p>Su sesión expirará después de <?= round(SESSION_LIFETIME / 60) ?> minutos de inactividad</p>
                </div>
            </div>
            <div class="info-item">
                <i class="fas fa-hourglass-half"></i>
                <div>
                    <strong>Tiempo restante</strong>
                    <p id="session-timer">Calculando...</p>
                </div>
            </div>
            <div class="info-item">
                <i class="fas fa-sign-out-alt"></i>
                <div>
                    <strong>Cierre de sesión</strong>
                    <p>Por seguridad, cierre sesión al terminar sus actividades</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Contador de tiempo de sesión restante
let sessionStartTime = <?= getSessionElapsedTime() ?>;
let lastUpdate = Date.now();

function updateSessionTimer() {
    const sessionLifetime = <?= SESSION_LIFETIME ?>;
    const now = Date.now();
    const elapsedSinceLoad = Math.floor((now - lastUpdate) / 1000);
    const totalElapsed = sessionStartTime + elapsedSinceLoad;
    const remaining = sessionLifetime - totalElapsed;
    
    if (remaining <= 0) {
        const timerElement = document.getElementById('session-timer');
        if (timerElement) timerElement.textContent = 'Sesión expirada';
        setTimeout(() => {
            window.location.href = '<?= baseUrl('/login') ?>';
        }, 1000);
        return;
    }
    
    const minutes = Math.floor(remaining / 60);
    const seconds = remaining % 60;
    const timerElement = document.getElementById('session-timer');
    if (timerElement) {
        timerElement.textContent = `${minutes}:${seconds.toString().padStart(2, '0')} minutos`;
    }
}

setInterval(updateSessionTimer, 1000);
updateSessionTimer();
</script>

<?php require_once APP_PATH . '/views/layouts/footer.php'; ?>
