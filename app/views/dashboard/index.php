<?php require_once APP_PATH . '/views/layouts/header.php'; ?>

<div>
    <!-- Bienvenida -->
    <div class="mb-8 rounded-3xl bg-gradient-to-r from-primary-700 to-accent-500 p-6 text-white shadow-xl">
        <div class="flex items-center gap-4">
            <div class="w-16 h-16 rounded-2xl bg-white/20 flex items-center justify-center text-3xl flex-shrink-0">
                <i class="fas fa-user"></i>
            </div>
            <div>
                <h2 class="text-2xl font-extrabold">Bienvenido, <?= e(currentUser()['nombre']) ?></h2>
                <p class="text-primary-100 mt-1">Panel de control del sistema de ingreso SENA</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
        <div class="bg-white rounded-3xl shadow-md border border-primary-100 p-5 flex items-center gap-4 hover:shadow-lg transition">
            <div class="w-14 h-14 rounded-2xl bg-primary-100 text-primary-700 flex items-center justify-center text-2xl flex-shrink-0">
                <i class="fas fa-user"></i>
            </div>
            <div class="min-w-0">
                <h3 class="text-lg font-extrabold text-gray-800 truncate"><?= e(currentUser()['nombre']) ?></h3>
                <p class="text-sm text-gray-500">Usuario Actual</p>
            </div>
        </div>

        <div class="bg-white rounded-3xl shadow-md border border-primary-100 p-5 flex items-center gap-4 hover:shadow-lg transition">
            <div class="w-14 h-14 rounded-2xl bg-accent-100 text-accent-700 flex items-center justify-center text-2xl flex-shrink-0">
                <i class="fas fa-shield-alt"></i>
            </div>
            <div>
                <h3 class="text-lg font-extrabold text-gray-800"><?= ucfirst(e(currentUser()['rol'])) ?></h3>
                <p class="text-sm text-gray-500">Rol del Sistema</p>
            </div>
        </div>

        <div class="bg-white rounded-3xl shadow-md border border-primary-100 p-5 flex items-center gap-4 hover:shadow-lg transition">
            <div class="w-14 h-14 rounded-2xl bg-blue-100 text-blue-700 flex items-center justify-center text-2xl flex-shrink-0">
                <i class="fas fa-clock"></i>
            </div>
            <div>
                <h3 class="text-lg font-extrabold text-gray-800"><?= formatSessionTime(getSessionElapsedTime()) ?></h3>
                <p class="text-sm text-gray-500">Tiempo de Sesión</p>
            </div>
        </div>

        <div class="bg-white rounded-3xl shadow-md border border-primary-100 p-5 flex items-center gap-4 hover:shadow-lg transition">
            <div class="w-14 h-14 rounded-2xl bg-amber-100 text-amber-700 flex items-center justify-center text-2xl flex-shrink-0">
                <i class="fas fa-network-wired"></i>
            </div>
            <div class="min-w-0">
                <h3 class="text-lg font-extrabold text-gray-800 truncate"><?= e($_SERVER['REMOTE_ADDR'] ?? 'N/A') ?></h3>
                <p class="text-sm text-gray-500">IP de Conexión</p>
            </div>
        </div>
    </div>

    <div class="mb-8">
        <h3 class="text-lg font-bold text-primary-700 mb-4"><i class="fas fa-th-large"></i> Módulos del Sistema</h3>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
            <?php if (Auth::hasRole('admin')): ?>
            <a href="<?= baseUrl('/usuarios') ?>" class="block bg-white rounded-3xl shadow-md border border-primary-100 p-6 hover:shadow-xl hover:-translate-y-0.5 transition">
                <div class="w-12 h-12 rounded-2xl bg-primary-100 text-primary-700 flex items-center justify-center text-xl mb-4">
                    <i class="fas fa-users"></i>
                </div>
                <h4 class="font-bold text-gray-800 mb-1">Gestión de Usuarios</h4>
                <p class="text-sm text-gray-500 mb-3">Administrar usuarios del sistema</p>
                <span class="bg-green-100 text-green-700 px-2 py-0.5 rounded-xl text-xs font-semibold">Activo</span>
            </a>
            <?php endif; ?>

            <?php if (Auth::hasRole('admin') || Auth::hasRole('vigilante')): ?>
            <a href="<?= baseUrl('/control-ingreso/kiosk') ?>" class="block bg-white rounded-3xl shadow-md border border-primary-100 p-6 hover:shadow-xl hover:-translate-y-0.5 transition">
                <div class="w-12 h-12 rounded-2xl bg-blue-100 text-blue-700 flex items-center justify-center text-xl mb-4">
                    <i class="fas fa-qrcode"></i>
                </div>
                <h4 class="font-bold text-gray-800 mb-1">Control de Ingreso</h4>
                <p class="text-sm text-gray-500 mb-3">Registro de entrada y salida con código de barras</p>
                <span class="bg-green-100 text-green-700 px-2 py-0.5 rounded-xl text-xs font-semibold">Activo</span>
            </a>

            <?php if (Auth::hasRole('vigilante') && !Auth::hasRole('admin')): ?>
            <a href="<?= baseUrl('/permisos/consulta') ?>" class="block bg-white rounded-3xl shadow-md border border-primary-100 p-6 hover:shadow-xl hover:-translate-y-0.5 transition">
                <div class="w-12 h-12 rounded-2xl bg-primary-100 text-primary-700 flex items-center justify-center text-xl mb-4">
                    <i class="fas fa-search"></i>
                </div>
                <h4 class="font-bold text-gray-800 mb-1">Consultar Permiso</h4>
                <p class="text-sm text-gray-500 mb-3">Verificar permisos de salida por código de barras</p>
                <span class="bg-green-100 text-green-700 px-2 py-0.5 rounded-xl text-xs font-semibold">Activo</span>
            </a>
            <?php endif; ?>

            <a href="<?= baseUrl('/acceso-externo') ?>" class="block bg-white rounded-3xl shadow-md border border-primary-100 p-6 hover:shadow-xl hover:-translate-y-0.5 transition">
                <div class="w-12 h-12 rounded-2xl bg-purple-100 text-purple-700 flex items-center justify-center text-xl mb-4">
                    <i class="fas fa-users"></i>
                </div>
                <h4 class="font-bold text-gray-800 mb-1">Personal Externo</h4>
                <p class="text-sm text-gray-500 mb-3">Registro de visitantes y personal sin carnet</p>
                <span class="bg-green-100 text-green-700 px-2 py-0.5 rounded-xl text-xs font-semibold">Activo</span>
            </a>
            <?php endif; ?>


            <?php if (Auth::hasRole('admin') || Auth::hasRole('instructor')): ?>
            <a href="<?= baseUrl('/control-llaves') ?>" class="block bg-white rounded-3xl shadow-md border border-primary-100 p-6 hover:shadow-xl hover:-translate-y-0.5 transition">
                <div class="w-12 h-12 rounded-2xl bg-amber-100 text-amber-700 flex items-center justify-center text-xl mb-4">
                    <i class="fas fa-key"></i>
                </div>
                <h4 class="font-bold text-gray-800 mb-1">Control de Llaves</h4>
                <p class="text-sm text-gray-500 mb-3">Préstamo y devolución de llaves</p>
                <span class="bg-green-100 text-green-700 px-2 py-0.5 rounded-xl text-xs font-semibold">Activo</span>
            </a>

            <a href="<?= baseUrl('/permisos') ?>" class="block bg-white rounded-3xl shadow-md border border-primary-100 p-6 hover:shadow-xl hover:-translate-y-0.5 transition">
                <div class="w-12 h-12 rounded-2xl bg-primary-100 text-primary-700 flex items-center justify-center text-xl mb-4">
                    <i class="fas fa-file-signature"></i>
                </div>
                <h4 class="font-bold text-gray-800 mb-1">Permisos de Salida</h4>
                <p class="text-sm text-gray-500 mb-3">Gestión de permisos de salida para aprendices</p>
                <span class="bg-green-100 text-green-700 px-2 py-0.5 rounded-xl text-xs font-semibold">Activo</span>
            </a>
            <?php endif; ?>


            <?php if (Auth::hasRole('admin')): ?>
            <a href="<?= baseUrl('/reportes') ?>" class="block bg-white rounded-3xl shadow-md border border-primary-100 p-6 hover:shadow-xl hover:-translate-y-0.5 transition">
                <div class="w-12 h-12 rounded-2xl bg-rose-100 text-rose-700 flex items-center justify-center text-xl mb-4">
                    <i class="fas fa-chart-bar"></i>
                </div>
                <h4 class="font-bold text-gray-800 mb-1">Reportes</h4>
                <p class="text-sm text-gray-500 mb-3">Informes y estadísticas</p>
                <span class="bg-green-100 text-green-700 px-2 py-0.5 rounded-xl text-xs font-semibold">Activo</span>
            </a>
            <?php endif; ?>
        </div>
    </div>

    <div class="bg-white/85 backdrop-blur-md border border-primary-100 rounded-3xl shadow-xl p-6">
        <h3 class="text-lg font-bold text-primary-700 mb-4"><i class="fas fa-shield-alt"></i> Información de Seguridad</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
            <div class="flex items-start gap-3">
                <i class="fas fa-clock text-primary-600 mt-1"></i>
                <div>
                    <strong class="block text-sm text-gray-800">Tiempo de sesión</strong>
                    <p class="text-sm text-gray-500">Su sesión expirará después de <?= round(SESSION_LIFETIME / 60) ?> minutos de inactividad</p>
                </div>
            </div>
            <div class="flex items-start gap-3">
                <i class="fas fa-hourglass-half text-primary-600 mt-1"></i>
                <div>
                    <strong class="block text-sm text-gray-800">Tiempo restante</strong>
                    <p class="text-sm text-gray-500" id="session-timer">Calculando...</p>
                </div>
            </div>
            <div class="flex items-start gap-3">
                <i class="fas fa-sign-out-alt text-primary-600 mt-1"></i>
                <div>
                    <strong class="block text-sm text-gray-800">Cierre de sesión</strong>
                    <p class="text-sm text-gray-500">Por seguridad, cierre sesión al terminar sus actividades</p>
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
