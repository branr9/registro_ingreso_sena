<?php require_once APP_PATH . '/views/layouts/header.php'; ?>

<style>
    /* Estilos del Kiosco adaptados al layout principal */
    .kiosk-main {
        display: flex;
        gap: 30px;
        height: calc(100vh - 200px);
    }

    /* Scanner Area */
    .scanner-area {
        flex: 2;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        background: linear-gradient(135deg, #1e4d2b 0%, #39a635 100%);
        border-radius: 15px;
        padding: 40px;
        color: white;
    }

    .scanner-icon {
        font-size: 120px;
        margin-bottom: 20px;
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0%, 100% { transform: scale(1); opacity: 1; }
        50% { transform: scale(1.1); opacity: 0.8; }
    }

    .scanner-instruction {
        font-size: 1.8rem;
        text-align: center;
        margin-bottom: 20px;
        font-weight: 500;
        color: white;
    }

    .scanner-status {
        font-size: 1rem;
        opacity: 0.9;
        text-align: center;
    }

    .scanner-status input {
        font-size: 1.2rem !important;
        text-align: center;
        max-width: 100% !important;
    }

    /* Result Display */
    .result-display {
        display: none;
        width: 100%;
        text-align: center;
        padding: 30px;
        border-radius: 10px;
        animation: slideIn 0.5s ease;
        color: white;
    }

    @keyframes slideIn {
        from { transform: translateY(-30px); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
    }

    .result-display.allowed {
        background: rgba(40, 167, 69, 0.3);
        border: 2px solid #28a745;
    }

    .result-display.denied {
        background: rgba(220, 53, 69, 0.3);
        border: 2px solid #dc3545;
    }

    .result-icon {
        font-size: 80px;
        margin-bottom: 15px;
    }

    .result-message {
        font-size: 2.5rem;
        font-weight: bold;
        margin-bottom: 15px;
    }

    .result-person {
        font-size: 1.5rem;
        margin-bottom: 10px;
    }

    .result-details {
        font-size: 1.1rem;
        opacity: 0.9;
    }

    /* Recent Activity */
    .recent-activity {
        flex: 1;
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border-radius: 15px;
        padding: 25px;
        overflow-y: auto;
    }

    .recent-activity h3 {
        margin-bottom: 15px;
        font-size: 1.3rem;
        border-bottom: 2px solid #39a635;
        padding-bottom: 10px;
        color: #333;
    }

    .activity-item {
        background: white;
        padding: 12px;
        margin-bottom: 12px;
        border-radius: 8px;
        border-left: 4px solid;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .activity-item.success {
        border-left-color: #28a745;
    }

    .activity-item.failed {
        border-left-color: #dc3545;
    }

    .activity-time {
        font-size: 0.8rem;
        opacity: 0.7;
        color: #666;
    }

    /* Simulator */
    .simulator-panel {
        position: fixed;
        bottom: 20px;
        left: 20px;
        background: rgba(0, 0, 0, 0.95);
        padding: 15px;
        border-radius: 8px;
        border: 2px solid #ffc800;
        z-index: 1000;
        max-width: 200px;
    }

    .simulator-panel h4 {
        margin-bottom: 10px;
        color: #ffc800;
        font-size: 0.9rem;
    }

    .simulator-panel select,
    .simulator-panel button {
        width: 100%;
        margin-bottom: 8px;
        font-size: 0.85rem;
    }

    .page-header {
        margin-bottom: 20px;
    }

    .page-header h1 {
        color: #333;
        font-size: 2rem;
        margin-bottom: 5px;
    }

    .page-header p {
        color: #666;
        font-size: 1rem;
    }
</style>

<!-- Page Header -->
<div class="page-header">
    <h1><i class="fas fa-qrcode"></i> Control de Ingreso</h1>
    <p>Sistema de Código de Barras - Escanee para registrar entrada/salida</p>
</div>

<!-- Main Content -->
<div class="kiosk-main">
        <!-- Scanner Area -->
        <div class="scanner-area">
            <div id="scanner-idle" class="scanner-content">
                <div class="scanner-icon">�</div>
                <div class="scanner-instruction">
                    Escanee su código de barras
                </div>
                <div class="scanner-status">
                    <input type="text" 
                           id="barcode-input" 
                           class="form-control form-control-lg" 
                           placeholder="Escanee o ingrese el código de barras"
                           style="max-width: 500px; margin: 20px auto; font-size: 1.5rem; text-align: center;"
                           autofocus>
                    <small style="opacity: 0.7; display: block; margin-top: 10px;">
                        El lector escaneará automáticamente cuando pase el código
                    </small>
                </div>
            </div>

            <div id="scanner-processing" class="scanner-content" style="display: none;">
                <div class="spinner-border" role="status" style="width: 100px; height: 100px; border-width: 8px;">
                    <span class="visually-hidden">Procesando...</span>
                </div>
                <div class="scanner-instruction" style="margin-top: 30px;">
                    Verificando código de barras...
                </div>
            </div>

            <div id="result-display" class="result-display">
                <!-- Resultado dinámico vía JS -->
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="recent-activity">
            <h3>Actividad Reciente</h3>
            <div id="recent-list">
                <?php if (!empty($recent)): ?>
                    <?php foreach ($recent as $item): ?>
                        <div class="activity-item <?= $item['exitoso'] ? 'success' : 'failed' ?>">
                            <div>
                                <strong><?= htmlspecialchars($item['nombres'] . ' ' . ($item['apellidos'] ?? '')) ?></strong>
                                <span class="badge bg-<?= $item['exitoso'] ? 'success' : 'danger' ?> ms-2">
                                    <?= $item['exitoso'] ? $item['tipo_evento'] : 'DENEGADO' ?>
                                </span>
                            </div>
                            <div class="activity-time">
                                <?= date('H:i:s', strtotime($item['fecha_hora'])) ?>
                                <?php if ($item['exitoso']): ?>
                                    - <?= $item['tipo_evento'] === 'ENTRADA' ? '🟢 Ingresó' : '🔴 Salió' ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p style="opacity: 0.6; text-align: center; margin-top: 40px;">
                        No hay actividad registrada hoy
                    </p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Simulador de Código de Barras (Solo para desarrollo) -->
<div class="simulator-panel">
    <h4>🧪 SIMULADOR</h4>
    <select id="test-persona" class="form-select form-select-sm">
        <option value="">Seleccione...</option>
        <?php foreach ($personas_test as $p): ?>
            <option value="<?= $p['documento'] ?>" data-estado="<?= $p['estado'] ?>">
                <?= htmlspecialchars(substr($p['nombres'], 0, 15)) ?> (<?= $p['estado'] ?>)
            </option>
        <?php endforeach; ?>
    </select>
    <button id="btn-simulate" class="btn btn-warning btn-sm" disabled>
        📷 Simular
    </button>
    <button id="btn-simulate-unknown" class="btn btn-secondary btn-sm">
        ❓ Desconocido
    </button>
    <hr style="border-color: rgba(255, 255, 255, 0.3); margin: 8px 0;">
    <a href="<?= baseUrl('/') ?>" class="btn btn-danger btn-sm btn-block" style="width: 100%;">Salir</a>
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
    if (document.activeElement !== barcodeInput && scannerIdle.style.display !== 'none') {
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
    scannerIdle.style.display = 'none';
    scannerProcessing.style.display = 'block';
    resultDisplay.style.display = 'none';
}

/**
 * Mostrar resultado del acceso
 */
function showResult(data) {
    scannerIdle.style.display = 'none';
    scannerProcessing.style.display = 'none';
    resultDisplay.style.display = 'block';

    resultDisplay.className = 'result-display';
    resultDisplay.classList.add(data.type === 'allowed' ? 'allowed' : 'denied');

    let html = `
        <div class="result-icon">${data.icon}</div>
        <div class="result-message">${data.message}</div>
    `;

    if (data.persona) {
        html += `
            <div class="result-person">
                ${data.persona.nombre}
            </div>
            <div class="result-details">
                ${data.persona.documento}
            </div>
        `;
    }

    if (data.evento) {
        const horaEvento = new Date().toLocaleTimeString('es-CO', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
        const textoEvento = data.evento === 'ENTRADA' ? `🟢 INGRESÓ a las ${horaEvento}` : `🔴 SALIÓ a las ${horaEvento}`;
        html += `
            <div class="result-details" style="margin-top: 20px;">
                <span class="badge bg-light text-dark" style="font-size: 1.5rem; padding: 10px 30px;">
                    ${textoEvento}
                </span>
            </div>
        `;
    }

    html += `
        <div class="result-details" style="margin-top: 20px; opacity: 0.7;">
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
    scannerIdle.style.display = 'none';
    scannerProcessing.style.display = 'none';
    resultDisplay.style.display = 'block';
    resultDisplay.className = 'result-display denied';
    resultDisplay.innerHTML = `
        <div class="result-icon">⚠️</div>
        <div class="result-message">ERROR</div>
        <div class="result-details">${message}</div>
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
    scannerIdle.style.display = 'block';
    scannerProcessing.style.display = 'none';
    resultDisplay.style.display = 'none';
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
                recentList.innerHTML = data.recent.map(item => {
                    const hora = new Date(item.fecha_hora).toLocaleTimeString('es-CO', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
                    const textoEvento = item.exitoso ? (item.tipo_evento === 'ENTRADA' ? '🟢 Ingresó' : '🔴 Salió') : '';
                    return `
                    <div class="activity-item ${item.exitoso ? 'success' : 'failed'}">
                        <div>
                            <strong>${(item.nombres || 'Desconocido')} ${(item.apellidos || '').trim()}</strong>
                            <span class="badge bg-${item.exitoso ? 'success' : 'danger'} ms-2">
                                ${item.exitoso ? (item.tipo_evento || 'EVENTO') : 'DENEGADO'}
                            </span>
                        </div>
                        <div class="activity-time">
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
    console.log('Sound:', type);
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
