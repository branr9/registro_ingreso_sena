<?php require_once APP_PATH . '/views/layouts/header.php'; ?>

<style>
    @keyframes pulse-soft {
        0%, 100% { transform: scale(1); opacity: 1; }
        50% { transform: scale(1.08); opacity: 0.85; }
    }
    .scanner-icon-pulse { animation: pulse-soft 2s infinite; }

    @keyframes slideIn {
        from { transform: translateY(-20px); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
    }
    .result-animate { animation: slideIn 0.4s ease; }

    @keyframes spin {
        to { transform: rotate(360deg); }
    }
    .spinner-ring {
        border-radius: 9999px;
        border: 8px solid rgba(255,255,255,0.25);
        border-top-color: #ffffff;
        animation: spin 0.9s linear infinite;
    }
</style>

<!-- Page Header -->
<div class="mb-6">
    <h1 class="text-2xl font-extrabold text-primary-700 flex items-center gap-2">
        <i class="fas fa-qrcode"></i> Control de Ingreso
    </h1>
    <p class="text-sm text-gray-500 mt-1">Sistema de Código de Barras - Escanee para registrar entrada/salida</p>
</div>

<!-- Main Content -->
<div class="flex flex-col lg:flex-row gap-6 items-stretch" style="min-height: calc(100vh - 260px);">

    <!-- Scanner Area -->
    <div class="flex-[2] rounded-3xl shadow-xl p-8 md:p-10 flex flex-col items-center justify-center text-center text-white bg-gradient-to-br from-primary-700 via-primary-600 to-accent-500">

        <div id="scanner-idle" class="w-full flex flex-col items-center">
            <div class="scanner-icon-pulse w-28 h-28 rounded-full bg-white/15 flex items-center justify-center text-6xl mb-6">
                <i class="fas fa-barcode"></i>
            </div>
            <div class="text-2xl md:text-3xl font-bold mb-6">
                Escanee su código de barras
            </div>
            <div class="w-full flex flex-col items-center">
                <input type="text"
                       id="barcode-input"
                       placeholder="Escanee o ingrese el código de barras"
                       class="w-full max-w-md rounded-2xl border-2 border-white/30 bg-white/10 placeholder-white/70 text-white text-lg text-center px-4 py-3 outline-none focus:border-white/70 focus:bg-white/20 transition"
                       autofocus autocomplete="off">
                <small class="opacity-70 mt-3 block">
                    El lector escaneará automáticamente cuando pase el código
                </small>
            </div>
        </div>

        <div id="scanner-processing" class="w-full flex-col items-center hidden">
            <div class="w-28 h-28 spinner-ring mb-8"></div>
            <div class="text-2xl md:text-3xl font-bold">
                Verificando código de barras...
            </div>
        </div>

        <div id="result-display" class="w-full rounded-2xl p-8 hidden result-animate"></div>
    </div>

    <!-- Recent Activity -->
    <div class="flex-1 bg-white/85 backdrop-blur-md border border-primary-100 rounded-3xl shadow-xl p-6 overflow-y-auto">
        <h3 class="text-lg font-bold text-primary-700 mb-4 pb-3 border-b border-primary-100 flex items-center gap-2">
            <i class="fas fa-history"></i> Actividad Reciente
        </h3>
        <div id="recent-list" class="space-y-3">
            <?php if (!empty($recent)): ?>
                <?php foreach ($recent as $item): ?>
                    <div class="bg-white rounded-2xl shadow-sm border-l-4 <?= $item['exitoso'] ? 'border-green-500' : 'border-red-500' ?> px-4 py-3">
                        <div class="flex items-center justify-between gap-2">
                            <strong class="text-sm text-gray-800 truncate"><?= htmlspecialchars($item['nombres'] . ' ' . ($item['apellidos'] ?? '')) ?></strong>
                            <span class="shrink-0 px-2 py-0.5 rounded-xl text-xs font-semibold <?= $item['exitoso'] ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' ?>">
                                <?= $item['exitoso'] ? $item['tipo_evento'] : 'DENEGADO' ?>
                            </span>
                        </div>
                        <div class="text-xs text-gray-400 mt-1">
                            <?= date('H:i:s', strtotime($item['fecha_hora'])) ?>
                            <?php if ($item['exitoso']): ?>
                                - <?= $item['tipo_evento'] === 'ENTRADA' ? '🟢 Ingresó' : '🔴 Salió' ?>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-center text-gray-400 mt-10">
                    No hay actividad registrada hoy
                </p>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Simulador de Código de Barras (Solo para desarrollo) -->
<div class="fixed bottom-5 left-5 z-50 w-56 rounded-2xl border-2 border-amber-400 bg-gray-900/95 p-4 shadow-2xl">
    <h4 class="text-amber-400 text-sm font-bold mb-3 flex items-center gap-2">
        <i class="fas fa-vial"></i> SIMULADOR
    </h4>
    <select id="test-persona" class="w-full mb-2 rounded-lg border border-white/20 bg-white/10 text-white text-xs px-2 py-2 outline-none focus:border-amber-400">
        <option value="" class="text-gray-800">Seleccione...</option>
        <?php foreach ($personas_test as $p): ?>
            <option value="<?= $p['documento'] ?>" data-estado="<?= $p['estado'] ?>" class="text-gray-800">
                <?= htmlspecialchars(substr($p['nombres'], 0, 15)) ?> (<?= $p['estado'] ?>)
            </option>
        <?php endforeach; ?>
    </select>
    <button id="btn-simulate" disabled
            class="w-full mb-2 rounded-lg bg-amber-400 hover:bg-amber-500 disabled:opacity-40 disabled:cursor-not-allowed transition text-gray-900 text-xs font-bold px-2 py-2">
        <i class="fas fa-camera"></i> Simular
    </button>
    <button id="btn-simulate-unknown"
            class="w-full mb-2 rounded-lg bg-gray-600 hover:bg-gray-500 transition text-white text-xs font-bold px-2 py-2">
        <i class="fas fa-question"></i> Desconocido
    </button>
    <hr class="border-white/20 my-2">
    <a href="<?= baseUrl('/') ?>" class="block text-center rounded-lg bg-red-600 hover:bg-red-700 transition text-white text-xs font-bold px-2 py-2">
        Salir
    </a>
</div>

<script>
// ============================================================
// CONTROL DE INGRESO - CÓDIGO DE BARRAS
// ============================================================

const BASE_URL = window.location.origin;
let processingTimeout = null;
let lastBarcode = '';
let lastBarcodeTime = 0;

// Elementos DOM
const scannerIdle = document.getElementById('scanner-idle');
const scannerProcessing = document.getElementById('scanner-processing');
const resultDisplay = document.getElementById('result-display');
const barcodeInput = document.getElementById('barcode-input');
const testPersonaSelect = document.getElementById('test-persona');
const btnSimulate = document.getElementById('btn-simulate');
const btnSimulateUnknown = document.getElementById('btn-simulate-unknown');

let barcodeTimeoutId = null;
const BARCODE_TIMEOUT = 200; // Esperar 200ms después de dejar de escribir

// Detectar entrada de lector de código de barras - SIN necesidad de Enter
// Se procesa automáticamente cuando deja de escribir (detecta fin de lectura)
barcodeInput.addEventListener('input', function(e) {
    const barcode = this.value.trim();

    // Cancelar timeout anterior
    clearTimeout(barcodeTimeoutId);

    // Si está vacío, no hacer nada
    if (!barcode) {
        return;
    }

    // Crear nuevo timeout - después de 200ms sin escribir, procesar
    barcodeTimeoutId = setTimeout(() => {
        // Prevenir doble lectura (si el mismo código se escanea en menos de 2 segundos)
        const now = Date.now();
        if (barcode === lastBarcode && (now - lastBarcodeTime) < 2000) {
            barcodeInput.value = '';
            return;
        }

        lastBarcode = barcode;
        lastBarcodeTime = now;

        processAccess({ barcode: barcode });
        barcodeInput.value = '';
    }, BARCODE_TIMEOUT);
});

// También detectar Enter para los casos manuales
barcodeInput.addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        e.preventDefault();
        clearTimeout(barcodeTimeoutId);

        const barcode = barcodeInput.value.trim();

        if (!barcode) {
            return;
        }

        // Prevenir doble lectura
        const now = Date.now();
        if (barcode === lastBarcode && (now - lastBarcodeTime) < 2000) {
            barcodeInput.value = '';
            return;
        }

        lastBarcode = barcode;
        lastBarcodeTime = now;

        processAccess({ barcode: barcode });
        barcodeInput.value = '';
    }
});

// Mantener el foco en el campo de entrada para que el lector siempre funcione
setInterval(function() {
    if (document.activeElement !== barcodeInput && !scannerIdle.classList.contains('hidden')) {
        barcodeInput.focus();
    }
}, 500);

// Habilitar botón cuando se seleccione persona
if (testPersonaSelect) {
    testPersonaSelect.addEventListener('change', function() {
        btnSimulate.disabled = !this.value;
    });
}

// Simular escaneo de código de barras conocido
if (btnSimulate) {
    btnSimulate.addEventListener('click', function() {
        const documento = testPersonaSelect.value;
        if (!documento) return;

        processAccess({ barcode: documento });
    });
}

// Simular código de barras desconocido
if (btnSimulateUnknown) {
    btnSimulateUnknown.addEventListener('click', function() {
        const unknownBarcode = 'UNKNOWN_' + Date.now();
        processAccess({ barcode: unknownBarcode });
    });
}

/**
 * Procesar acceso por código de barras
 */
async function processAccess(data) {
    showProcessing();

    try {
        const response = await fetch(`${BASE_URL}/control-ingreso/process`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify(data)
        });

        // Verificar si la respuesta es válida
        if (!response.ok) {
            if (response.status === 401) {
                showError('Sesión expirada. Por favor, inicie sesión nuevamente.');
                setTimeout(() => {
                    window.location.href = `${BASE_URL}/login`;
                }, 2000);
                return;
            }
            throw new Error(`HTTP ${response.status}`);
        }

        // Parsear JSON
        const text = await response.text();
        let result;
        try {
            result = JSON.parse(text);
        } catch (e) {
            console.error('Invalid JSON response:', text);
            showError('Error en la respuesta del servidor');
            return;
        }

        if (!result.success && result.type !== 'denied' && result.type !== 'allowed') {
            showError(result.message || 'Error al procesar');
            return;
        }

        showResult(result);
        updateStats();
        updateRecentActivity();

        clearTimeout(processingTimeout);
        processingTimeout = setTimeout(() => {
            resetScanner();
        }, 4000);

    } catch (error) {
        console.error('Error:', error);
        showError('Error de conexión. Por favor, intente nuevamente.');
    }
}

/**
 * Mostrar estado de procesamiento
 */
function showProcessing() {
    scannerIdle.classList.add('hidden');
    scannerProcessing.classList.remove('hidden');
    scannerProcessing.classList.add('flex');
    resultDisplay.classList.add('hidden');
}

/**
 * Mostrar resultado del acceso
 */
function showResult(data) {
    scannerIdle.classList.add('hidden');
    scannerProcessing.classList.add('hidden');
    scannerProcessing.classList.remove('flex');
    resultDisplay.classList.remove('hidden');

    const isAllowed = data.type === 'allowed';
    resultDisplay.className = 'w-full rounded-2xl p-8 result-animate ' +
        (isAllowed ? 'bg-white/15 border-2 border-green-400' : 'bg-white/15 border-2 border-red-400');

    let html = `
        <div class="text-6xl mb-4">${data.icon}</div>
        <div class="text-3xl md:text-4xl font-extrabold mb-3">${data.message}</div>
    `;

    if (data.persona) {
        html += `
            <div class="text-xl font-semibold mb-1">
                ${data.persona.nombre}
            </div>
            <div class="text-base opacity-80">
                ${data.persona.documento}
            </div>
        `;
    }

    if (data.evento) {
        const horaEvento = new Date().toLocaleTimeString('es-CO', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
        const textoEvento = data.evento === 'ENTRADA' ? `🟢 INGRESÓ a las ${horaEvento}` : `🔴 SALIÓ a las ${horaEvento}`;
        html += `
            <div class="mt-5">
                <span class="inline-block bg-white text-gray-800 text-lg font-bold rounded-2xl px-6 py-2">
                    ${textoEvento}
                </span>
            </div>
        `;
    }

    html += `
        <div class="mt-5 text-sm opacity-70">
            ${data.reason}
        </div>
    `;

    resultDisplay.innerHTML = html;
    playSound(data.sound);
}

/**
 * Mostrar error
 */
function showError(message) {
    scannerIdle.classList.add('hidden');
    scannerProcessing.classList.add('hidden');
    scannerProcessing.classList.remove('flex');
    resultDisplay.classList.remove('hidden');
    resultDisplay.className = 'w-full rounded-2xl p-8 result-animate bg-white/15 border-2 border-red-400';
    resultDisplay.innerHTML = `
        <div class="text-6xl mb-4">⚠️</div>
        <div class="text-3xl font-extrabold mb-3">ERROR</div>
        <div class="text-base opacity-80">${message}</div>
    `;

    clearTimeout(processingTimeout);
    processingTimeout = setTimeout(() => {
        resetScanner();
    }, 3000);
}

/**
 * Resetear escáner al estado inicial
 */
function resetScanner() {
    scannerIdle.classList.remove('hidden');
    scannerProcessing.classList.add('hidden');
    scannerProcessing.classList.remove('flex');
    resultDisplay.classList.add('hidden');
    barcodeInput.focus();
}

/**
 * Actualizar estadísticas
 */
async function updateStats() {
    try {
        const response = await fetch(`${BASE_URL}/control-ingreso/stats`);
        if (!response.ok) return;

        const text = await response.text();
        const data = JSON.parse(text);

        if (data.success && data.stats && typeof data.stats.total === 'number') {
            const statElement = document.getElementById('stat-total');
            if (statElement) {
                statElement.textContent = data.stats.total;
            }
        }
    } catch (error) {
        console.error('Error al actualizar stats:', error);
    }
}

/**
 * Actualizar actividad reciente
 */
async function updateRecentActivity() {
    try {
        const response = await fetch(`${BASE_URL}/control-ingreso/recent`);
        if (!response.ok) return;

        const text = await response.text();
        const data = JSON.parse(text);

        if (data.success && Array.isArray(data.recent)) {
            const recentList = document.getElementById('recent-list');
            if (recentList) {
                if (data.recent.length === 0) {
                    recentList.innerHTML = '<p class="text-center text-gray-400 mt-10">No hay actividad registrada hoy</p>';
                    return;
                }
                recentList.innerHTML = data.recent.map(item => {
                    const hora = new Date(item.fecha_hora).toLocaleTimeString('es-CO', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
                    const textoEvento = item.exitoso ? (item.tipo_evento === 'ENTRADA' ? '🟢 Ingresó' : '🔴 Salió') : '';
                    const borderClass = item.exitoso ? 'border-green-500' : 'border-red-500';
                    const badgeClass = item.exitoso ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700';
                    return `
                    <div class="bg-white rounded-2xl shadow-sm border-l-4 ${borderClass} px-4 py-3">
                        <div class="flex items-center justify-between gap-2">
                            <strong class="text-sm text-gray-800 truncate">${(item.nombres || 'Desconocido')} ${(item.apellidos || '').trim()}</strong>
                            <span class="shrink-0 px-2 py-0.5 rounded-xl text-xs font-semibold ${badgeClass}">
                                ${item.exitoso ? (item.tipo_evento || 'EVENTO') : 'DENEGADO'}
                            </span>
                        </div>
                        <div class="text-xs text-gray-400 mt-1">
                            ${hora} ${item.exitoso ? `- ${textoEvento}` : ''}
                        </div>
                    </div>
                `;
                }).join('');
            }
        }
    } catch (error) {
        console.error('Error al actualizar actividad:', error);
    }
}

/**
 * Reproducir sonido (opcional)
 */
function playSound(type) {
    // Punto de extensión: aquí se puede reproducir un sonido según el tipo
    // de evento (ej. 'success', 'error'). Pendiente de implementar audio real.
}

/**
 * Actualizar reloj
 */
function updateClock() {
    const now = new Date();
    const timeString = now.toLocaleString('es-CO', {
        weekday: 'long',
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
        second: '2-digit'
    });

    // Actualizar elemento de tiempo si existe
    const currentTimeElement = document.getElementById('current-time');
    if (currentTimeElement) {
        currentTimeElement.textContent = timeString;
    }
}

// Actualizar reloj cada segundo
setInterval(updateClock, 1000);
updateClock();

// Actualizar estadísticas cada 30 segundos
setInterval(updateStats, 30000);

// Actualizar actividad reciente cada 10 segundos
setInterval(updateRecentActivity, 10000);

// Focus al campo de código de barras al cargar
barcodeInput.focus();
</script>

<?php require_once APP_PATH . '/views/layouts/footer.php'; ?>
