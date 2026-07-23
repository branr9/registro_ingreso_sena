<?php require_once APP_PATH . '/views/layouts/header.php'; ?>

<div class="main-content kiosk-mode">
    <div class="content-header-kiosk">
        <div class="kiosk-title">
            <img src="<?= asset('images/logo.png') ?>" alt="Logo SENA" style="height: 60px; margin-right: 15px;">
            <h1><i class="fas fa-door-open"></i> Consulta de Permisos de Salida</h1>
        </div>
        <div class="kiosk-time" id="currentTime"></div>
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
.kiosk-mode {
    min-height: calc(100vh - 70px);
    background: linear-gradient(135deg, #00324D 0%, #004060 100%);
    padding: 0;
}

.content-header-kiosk {
    background: white;
    padding: 20px 40px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.kiosk-title {
    display: flex;
    align-items: center;
    font-size: 1.8rem;
    color: var(--secondary-color);
}

.kiosk-time {
    font-size: 1.5rem;
    font-weight: bold;
    color: var(--primary-color);
}

.kiosk-container {
    max-width: 900px;
    margin: 50px auto;
    padding: 20px;
}

.search-panel {
    background: white;
    border-radius: 20px;
    padding: 60px 40px;
    text-align: center;
    box-shadow: 0 10px 30px rgba(0,0,0,0.2);
}

.search-icon {
    font-size: 5rem;
    color: var(--primary-color);
    margin-bottom: 20px;
}

.search-panel h2 {
    font-size: 2rem;
    color: var(--secondary-color);
    margin-bottom: 10px;
}

.search-panel p {
    color: #666;
    font-size: 1.2rem;
    margin-bottom: 30px;
}

.search-form {
    display: flex;
    gap: 15px;
    max-width: 600px;
    margin: 0 auto 30px;
}

.barcode-input {
    flex: 1;
    padding: 20px;
    font-size: 1.5rem;
    border: 3px solid var(--primary-color);
    border-radius: 10px;
    text-align: center;
    font-weight: bold;
}

.barcode-input:focus {
    outline: none;
    border-color: var(--primary-dark);
    box-shadow: 0 0 0 3px rgba(57, 169, 0, 0.2);
}

.search-instructions {
    background: #f8f9fa;
    padding: 15px;
    border-radius: 10px;
    color: #666;
    font-size: 0.95rem;
}

.result-panel {
    background: white;
    border-radius: 20px;
    padding: 60px 40px;
    text-align: center;
    box-shadow: 0 10px 30px rgba(0,0,0,0.2);
    animation: slideIn 0.3s ease;
}

@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateY(-20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.result-success {
    color: var(--success-color);
}

.result-success .result-icon {
    font-size: 6rem;
    margin-bottom: 20px;
}

.result-danger {
    color: var(--danger-color);
}

.result-danger .result-icon {
    font-size: 6rem;
    margin-bottom: 20px;
}

.result-title {
    font-size: 2.5rem;
    font-weight: bold;
    margin-bottom: 30px;
}

.permission-details {
    background: #f8f9fa;
    border-radius: 15px;
    padding: 30px;
    margin: 30px 0;
    text-align: left;
}

.detail-row {
    display: flex;
    justify-content: space-between;
    padding: 15px 0;
    border-bottom: 1px solid #dee2e6;
}

.detail-row:last-child {
    border-bottom: none;
}

.detail-label {
    font-weight: bold;
    color: var(--secondary-color);
}

.detail-value {
    color: #333;
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
