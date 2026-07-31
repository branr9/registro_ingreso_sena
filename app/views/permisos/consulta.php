<?php require_once APP_PATH . '/views/layouts/header.php'; ?>

<style>
/* Anular el padding del <main> contenedor cuando se está en modo kiosk */
main.main-content:has(.kiosk-mode) {
    padding: 0 !important;
}
</style>

<div class="main-content kiosk-mode">
    <div class="content-header-kiosk">
        <div class="kiosk-title">
            <img src="<?= asset('images/logo.png') ?>" alt="Logo SENA" style="height: 60px; margin-right: 15px;">
            <h1><i class="fas fa-door-open"></i> Consulta de Permisos de Salida</h1>
        </div>
        <div class="kiosk-actions">
            <div class="kiosk-time" id="currentTime"></div>
            <a href="<?= baseUrl('/dashboard') ?>" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
        </div>
    </div>

    <div class="kiosk-container">
        <!-- Panel de búsqueda -->
        <div class="search-panel">
            <div class="search-icon">
                <i class="fas fa-search"></i>
            </div>
            <h2>Escanee el Código de Barras</h2>
            <p>o ingrese el documento manualmente</p>
            
            <form id="searchForm" class="search-form">
                <input type="text" 
                       id="documentoInput" 
                       class="barcode-input" 
                       placeholder="Número de documento..."
                       autofocus
                       autocomplete="off">
                <button type="submit" class="btn btn-primary btn-lg">
                    <i class="fas fa-search"></i> Buscar
                </button>
            </form>

            <div class="search-instructions">
                <i class="fas fa-info-circle"></i>
                El lector de código de barras debe estar en modo teclado (Keyboard Wedge)
            </div>
        </div>

        <!-- Resultado de la búsqueda -->
        <div id="resultPanel" class="result-panel" style="display: none;">
            <div id="resultContent"></div>
            <button id="btnNuevaBusqueda" class="btn btn-secondary btn-lg">
                <i class="fas fa-redo"></i> Nueva Búsqueda
            </button>
        </div>
    </div>
</div>

<style>
/* ── Base del kiosk ─────────────────────────────────────────── */
.kiosk-mode {
    min-height: calc(100vh - 70px);
    background: transparent;
    padding: 0;
    position: relative;
    overflow: hidden;
}

/* Decoradores de fondo — solo visibles en dark mode (se activan via CSS) */
.kiosk-mode::before,
.kiosk-mode::after {
    content: '';
    position: absolute;
    border-radius: 50%;
    pointer-events: none;
    opacity: 0;  /* ocultos en claro */
    transition: opacity .3s;
}
.kiosk-mode::before {
    top: -40%; right: -20%;
    width: 700px; height: 700px;
    background: radial-gradient(circle, rgba(107,70,193,0.22) 0%, transparent 70%);
}
.kiosk-mode::after {
    bottom: -30%; left: -10%;
    width: 500px; height: 500px;
    background: radial-gradient(circle, rgba(0,188,212,0.15) 0%, transparent 70%);
}

/* ── Cabecera del kiosk ──────────────────────────────────────── */
.content-header-kiosk {
    background: #ffffff;
    border-bottom: 1px solid #ede9f8;
    padding: 18px 40px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    position: relative;
    z-index: 1;
    box-shadow: 0 1px 4px rgba(107,70,193,0.08);
}

.kiosk-title {
    display: flex;
    align-items: center;
    gap: 12px;
    font-size: 1.6rem;
    font-weight: 700;
    color: #4a3189;
    letter-spacing: -0.02em;
}

.kiosk-title i { color: #6B46C1; }

.kiosk-time {
    font-size: 1.1rem;
    font-weight: 600;
    color: #6B46C1;
    letter-spacing: 0.01em;
}

.kiosk-actions {
    display: flex;
    align-items: center;
    gap: 15px;
    flex-wrap: wrap;
    justify-content: flex-end;
}

/* ── Botones del kiosk (anula el verde de SENA) ─────────────── */
.kiosk-mode .btn-primary {
    background: linear-gradient(135deg, #6B46C1, #8257c9) !important;
    border-color: #6B46C1 !important;
    color: #fff !important;
    box-shadow: 0 4px 15px rgba(107,70,193,0.4) !important;
    transition: all .25s ease !important;
}
.kiosk-mode .btn-primary:hover {
    background: linear-gradient(135deg, #5a3ba8, #6B46C1) !important;
    box-shadow: 0 6px 20px rgba(107,70,193,0.55) !important;
    transform: translateY(-1px) !important;
}

.kiosk-mode .btn-secondary {
    background: #f5f3fb !important;
    border: 1px solid #d9cef1 !important;
    color: #4a3189 !important;
    transition: all .25s ease !important;
}
.kiosk-mode .btn-secondary:hover {
    background: #ede7f8 !important;
    border-color: #beabe6 !important;
    transform: translateY(-1px) !important;
}

/* ── Contenedor principal ────────────────────────────────────── */
.kiosk-container {
    max-width: 860px;
    margin: 50px auto;
    padding: 20px;
    position: relative;
    z-index: 1;
}

/* ── Panel de búsqueda ───────────────────────────────────────── */
.search-panel {
    background: #ffffff;
    border: 1px solid #ede9f8;
    border-radius: 24px;
    padding: 60px 40px;
    text-align: center;
    box-shadow: 0 4px 24px rgba(107,70,193,0.1), 0 1px 4px rgba(107,70,193,0.06);
}

.search-icon {
    font-size: 5rem;
    background: linear-gradient(135deg, #6B46C1, #00BCD4);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    margin-bottom: 20px;
    display: block;
    filter: drop-shadow(0 4px 12px rgba(107,70,193,0.4));
}

.search-panel h2 {
    font-size: 2rem;
    font-weight: 700;
    color: #3d2a70;
    margin-bottom: 10px;
    letter-spacing: -0.03em;
}

.search-panel p {
    color: #6b7280;
    font-size: 1.1rem;
    margin-bottom: 32px;
}

.search-panel p a { color: #6B46C1; text-decoration: none; }

/* ── Formulario de búsqueda ─────────────────────────────────── */
.search-form {
    display: flex;
    gap: 12px;
    max-width: 600px;
    margin: 0 auto 28px;
}

.barcode-input {
    flex: 1;
    padding: 18px 22px;
    font-size: 1.3rem;
    font-weight: 600;
    background: #faf9fe !important;
    border: 2px solid #beabe6 !important;
    border-radius: 14px !important;
    text-align: center;
    color: #2d1b69 !important;
    transition: all .2s ease;
}

.barcode-input::placeholder { color: rgba(180,165,215,0.6) !important; }

.barcode-input:focus {
    outline: none;
    border-color: #8257c9 !important;
    background: rgba(107,70,193,0.12) !important;
    box-shadow: 0 0 0 4px rgba(107,70,193,0.2), 0 0 20px rgba(107,70,193,0.15) !important;
}

/* ── Instrucciones ───────────────────────────────────────────── */
.search-instructions {
    background: rgba(0,188,212,0.08);
    border: 1px solid rgba(0,188,212,0.2);
    padding: 14px 20px;
    border-radius: 12px;
    color: rgba(121,226,239,0.85);
    font-size: 0.9rem;
}

/* ── Panel de resultado ──────────────────────────────────────── */
.result-panel {
    background: rgba(255,255,255,0.07);
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
    border: 1px solid rgba(255,255,255,0.14);
    border-radius: 24px;
    padding: 60px 40px;
    text-align: center;
    box-shadow: 0 24px 48px rgba(0,0,0,0.35), inset 0 1px 0 rgba(255,255,255,0.1);
    animation: slideIn 0.35s cubic-bezier(.16,1,.3,1);
}

@keyframes slideIn {
    from { opacity: 0; transform: translateY(-24px) scale(0.98); }
    to   { opacity: 1; transform: translateY(0) scale(1); }
}

.result-success { color: #6ee7b7; }
.result-success .result-icon { font-size: 6rem; margin-bottom: 20px; }

.result-danger { color: #fca5a5; }
.result-danger .result-icon { font-size: 6rem; margin-bottom: 20px; }

.result-title {
    font-size: 2.3rem;
    font-weight: 800;
    margin-bottom: 30px;
    letter-spacing: -0.03em;
}

/* ── Detalles del permiso ────────────────────────────────────── */
.permission-details {
    background: rgba(255,255,255,0.05);
    border: 1px solid rgba(255,255,255,0.1);
    border-radius: 16px;
    padding: 30px;
    margin: 30px 0;
    text-align: left;
}

.detail-row {
    display: flex;
    justify-content: space-between;
    padding: 14px 0;
    border-bottom: 1px solid rgba(255,255,255,0.07);
}

.detail-row:last-child { border-bottom: none; }

.detail-label {
    font-weight: 700;
    color: #b79aeb;
    display: flex;
    align-items: center;
    gap: 8px;
}

.detail-value {
    color: #e8e2f8;
    font-size: 1.1rem;
}
</style>

<script>
// Reloj en tiempo real
function updateClock() {
    const now = new Date();
    const timeString = now.toLocaleTimeString('es-CO', { 
        hour: '2-digit', 
        minute: '2-digit', 
        second: '2-digit' 
    });
    const dateString = now.toLocaleDateString('es-CO', { 
        weekday: 'long', 
        year: 'numeric', 
        month: 'long', 
        day: 'numeric' 
    });
    document.getElementById('currentTime').textContent = `${dateString} - ${timeString}`;
}
setInterval(updateClock, 1000);
updateClock();

// Formulario de búsqueda
document.getElementById('searchForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const documento = document.getElementById('documentoInput').value.trim();
    
    if (!documento) {
        alert('Por favor ingrese un documento');
        return;
    }
    
    buscarPermiso(documento);
});

// Buscar permiso via AJAX
async function buscarPermiso(documento) {
    try {
        const response = await fetch('<?= baseUrl('/permisos/validar-salida') ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ documento: documento })
        });
        
        const data = await response.json();
        mostrarResultado(data);
    } catch (error) {
        console.error('Error:', error);
        mostrarError('Error al consultar el permiso');
    }
}

// Mostrar resultado
function mostrarResultado(data) {
    const resultPanel = document.getElementById('resultPanel');
    const resultContent = document.getElementById('resultContent');
    const searchPanel = document.querySelector('.search-panel');
    
    if (data.permitido) {
        // PERMISO ENCONTRADO
        const permiso = data.permiso;
        resultContent.innerHTML = `
            <div class="result-success">
                <div class="result-icon">${data.icon}</div>
                <div class="result-title">${data.message}</div>
                <div class="permission-details">
                    <div class="detail-row">
                        <span class="detail-label"><i class="fas fa-user"></i> Aprendiz:</span>
                        <span class="detail-value">${permiso.nombre}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label"><i class="fas fa-id-card"></i> Documento:</span>
                        <span class="detail-value">${permiso.documento}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label"><i class="fas fa-calendar"></i> Fecha:</span>
                        <span class="detail-value">${permiso.fecha}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label"><i class="fas fa-clock"></i> Hora Salida:</span>
                        <span class="detail-value">${permiso.hora_salida}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label"><i class="fas fa-clock"></i> Hora Regreso:</span>
                        <span class="detail-value">${permiso.hora_regreso}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label"><i class="fas fa-clipboard"></i> Motivo:</span>
                        <span class="detail-value">${permiso.motivo}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label"><i class="fas fa-user-tie"></i> Autorizado por:</span>
                        <span class="detail-value">${permiso.instructor}</span>
                    </div>
                </div>
            </div>
        `;
    } else {
        // NO HAY PERMISO
        resultContent.innerHTML = `
            <div class="result-danger">
                <div class="result-icon">${data.icon}</div>
                <div class="result-title">${data.message}</div>
                <p style="font-size: 1.3rem; margin-top: 20px;">${data.detalle}</p>
            </div>
        `;
    }
    
    searchPanel.style.display = 'none';
    resultPanel.style.display = 'block';
    
    // Auto-reset después de 10 segundos
    setTimeout(resetBusqueda, 10000);
}

// Mostrar error
function mostrarError(mensaje) {
    alert(mensaje);
}

// Reset búsqueda
function resetBusqueda() {
    document.querySelector('.search-panel').style.display = 'block';
    document.getElementById('resultPanel').style.display = 'none';
    document.getElementById('documentoInput').value = '';
    document.getElementById('documentoInput').focus();
}

document.getElementById('btnNuevaBusqueda').addEventListener('click', resetBusqueda);

// Mantener foco en el input
setInterval(function() {
    if (document.getElementById('resultPanel').style.display === 'none') {
        document.getElementById('documentoInput').focus();
    }
}, 500);
</script>

<?php require_once APP_PATH . '/views/layouts/footer.php'; ?>
