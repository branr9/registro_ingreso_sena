<!-- Vista de Préstamo de Llaves -->
<?php
    // Obtener el tab a mostrar por defecto
    $tab_activo = $_GET['tab'] ?? 'tomar';
    $tabs_validos = ['tomar', 'devolver', 'nueva-aula', 'historial'];
    if (!in_array($tab_activo, $tabs_validos)) {
        $tab_activo = 'tomar';
    }
?>
<style>
    .llaves-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 20px;
        background: #fff;
    }

    .llaves-header {
        margin-bottom: 30px;
        border-bottom: 3px solid #2c3e50;
        padding-bottom: 15px;
    }

    .llaves-header h1 {
        color: #2c3e50;
        font-size: 28px;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .llaves-header p {
        color: #7f8c8d;
        margin: 5px 0 0 0;
    }

    .llaves-tabs {
        display: flex;
        gap: 10px;
        margin-bottom: 30px;
        border-bottom: 2px solid #ecf0f1;
    }

    .llaves-tab-btn {
        background: none;
        border: none;
        padding: 12px 20px;
        cursor: pointer;
        font-size: 16px;
        font-weight: 500;
        color: #7f8c8d;
        border-bottom: 3px solid transparent;
        transition: all 0.3s ease;
    }

    .llaves-tab-btn.active {
        color: #2980b9;
        border-bottom-color: #2980b9;
    }

    .llaves-tab-btn:hover {
        color: #2c3e50;
    }

    .llaves-tab-content {
        display: none;
    }

    .llaves-tab-content.active {
        display: block;
        animation: fadeIn 0.3s ease;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .llaves-form-group {
        margin-bottom: 20px;
    }

    .llaves-form-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        color: #2c3e50;
        font-size: 14px;
    }

    .llaves-form-group input,
    .llaves-form-group select,
    .llaves-form-group textarea {
        width: 100%;
        padding: 10px 12px;
        border: 1px solid #bdc3c7;
        border-radius: 5px;
        font-size: 14px;
        font-family: inherit;
        transition: border-color 0.3s ease;
    }

    .llaves-form-group input:focus,
    .llaves-form-group select:focus,
    .llaves-form-group textarea:focus {
        outline: none;
        border-color: #2980b9;
        box-shadow: 0 0 0 3px rgba(41, 128, 185, 0.1);
    }

    .llaves-form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }

    .llaves-form-row.full {
        grid-template-columns: 1fr;
    }

    .llaves-btn {
        padding: 11px 24px;
        border: none;
        border-radius: 5px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .llaves-btn-primary {
        background: #2980b9;
        color: white;
    }

    .llaves-btn-primary:hover {
        background: #1f5fa0;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(41, 128, 185, 0.3);
    }

    .llaves-btn-success {
        background: #27ae60;
        color: white;
        width: 100%;
        padding: 14px;
        font-size: 16px;
        font-weight: bold;
    }

    .llaves-btn-success:hover {
        background: #1e8449;
    }

    .llaves-btn-secondary {
        background: #95a5a6;
        color: white;
    }

    .llaves-btn-secondary:hover {
        background: #7f8c8d;
    }

    .llaves-btn-group {
        display: flex;
        gap: 10px;
        margin-top: 25px;
    }

    .llaves-alert {
        padding: 15px 20px;
        border-radius: 5px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .llaves-alert-success {
        background: #d5f4e6;
        border-left: 4px solid #27ae60;
        color: #27ae60;
    }

    .llaves-alert-error {
        background: #fadbd8;
        border-left: 4px solid #e74c3c;
        color: #e74c3c;
    }

    .llaves-card {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 20px;
        border: 1px solid #ecf0f1;
    }

    .llaves-card h3 {
        color: #2c3e50;
        margin-top: 0;
        margin-bottom: 15px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    /* Grid de Aulas */
    .aulas-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 20px;
        margin-bottom: 20px;
    }

    .aula-card-item {
        background: white;
        border: 2px solid #ecf0f1;
        border-radius: 10px;
        padding: 20px;
        transition: all 0.3s ease;
        display: flex;
        flex-direction: column;
    }

    .aula-card-item:hover {
        border-color: #2980b9;
        box-shadow: 0 8px 20px rgba(41, 128, 185, 0.15);
    }

    .aula-card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
        border-bottom: 2px solid #ecf0f1;
        padding-bottom: 10px;
    }

    .aula-card-title {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 18px;
        font-weight: bold;
        color: #2c3e50;
    }

    .aula-badge-status {
        background: #27ae60;
        color: white;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: bold;
    }

    .aula-card-stat {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 0;
        font-size: 15px;
        color: #2c3e50;
    }

    .aula-stat-label {
        color: #7f8c8d;
        font-weight: 500;
    }

    .aula-stat-value {
        font-size: 20px;
        font-weight: bold;
        color: #2980b9;
    }

    .aula-stat-value.good {
        color: #27ae60;
    }

    .aula-stat-value.warning {
        color: #f39c12;
    }

    .aula-stat-value.danger {
        color: #e74c3c;
    }

    .llaves-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 15px;
    }

    .llaves-table thead {
        background: #2c3e50;
        color: white;
    }

    .llaves-table th,
    .llaves-table td {
        padding: 12px;
        text-align: left;
        border-bottom: 1px solid #ecf0f1;
    }

    .llaves-table tbody tr:hover {
        background: #f8f9fa;
    }

    .llaves-badge {
        display: inline-block;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }

    .llaves-badge-active {
        background: #d5f4e6;
        color: #27ae60;
    }

    .llaves-badge-returned {
        background: #d6eaf8;
        color: #2980b9;
    }

    .llaves-icon {
        font-size: 20px;
    }

    .llaves-modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0,0,0,0.6);
    }

    .llaves-modal-content {
        background-color: white;
        margin: 5% auto;
        padding: 30px;
        border-radius: 10px;
        width: 90%;
        max-width: 600px;
        box-shadow: 0 8px 30px rgba(0,0,0,0.3);
        max-height: 85vh;
        overflow-y: auto;
    }

    .llaves-modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        border-bottom: 2px solid #ecf0f1;
        padding-bottom: 15px;
    }

    .llaves-modal-header h2 {
        margin: 0;
        color: #2c3e50;
    }

    .llaves-close {
        font-size: 28px;
        font-weight: bold;
        cursor: pointer;
        color: #7f8c8d;
    }

    .llaves-close:hover {
        color: #e74c3c;
    }

    @media (max-width: 768px) {
        .aulas-grid {
            grid-template-columns: 1fr;
        }

        .llaves-form-row {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="llaves-container" data-base-url="<?php echo isset($_GET['tab']) ? '../../' : './'; ?>">
    <!-- Header -->
    <div class="llaves-header">
        <h1><i class="fas fa-key llaves-icon"></i> Préstamo de Llaves</h1>
        <p>Seleccione el aula para tomar o devolver la llave</p>
    </div>

    <!-- Alertas -->
    <div id="llaveAlert" style="display: none;"></div>

    <!-- Tabs -->
    <div class="llaves-tabs">
        <button class="llaves-tab-btn active" onclick="cambiarTab(event, 'tomar')">
            <i class="fas fa-arrow-right-from-bracket"></i> Tomar Llave
        </button>
        <button class="llaves-tab-btn" onclick="cambiarTab(event, 'devolver')">
            <i class="fas fa-arrow-left-to-bracket"></i> Devolver Llave
        </button>
        <button class="llaves-tab-btn" onclick="cambiarTab(event, 'nueva-aula')">
            <i class="fas fa-plus-circle"></i> Nueva Aula
        </button>
        <button class="llaves-tab-btn" onclick="cambiarTab(event, 'historial')">
            <i class="fas fa-history"></i> Historial
        </button>
    </div>

    <!-- TAB 1: TOMAR LLAVE (Grid de Aulas) -->
    <div id="tomar" class="llaves-tab-content active">
        <div class="llaves-card">
            <h3><i class="fas fa-arrow-right-from-bracket llaves-icon"></i> Seleccione Aula</h3>
            
            <button class="llaves-btn llaves-btn-primary" onclick="cargarAulasGrid()" style="margin-bottom: 20px;">
                <i class="fas fa-refresh"></i> Actualizar
            </button>

            <div class="aulas-grid" id="aulasGrid">
                <div style="grid-column: 1/-1; text-align: center; padding: 40px; color: #7f8c8d;">
                    <i class="fas fa-hourglass-start" style="font-size: 40px; margin-bottom: 10px;"></i>
                    <p>Cargando aulas...</p>
                </div>
            </div>
        </div>
    </div>

    <!-- TAB 2: DEVOLVER LLAVE -->
    <div id="devolver" class="llaves-tab-content">
        <div class="llaves-card">
            <h3><i class="fas fa-arrow-left-to-bracket llaves-icon"></i> Devolver Llave</h3>

            <div class="llaves-alert llaves-alert-success" style="display: flex;">
                <i class="fas fa-info-circle"></i>
                <span>Selecciona un préstamo activo para registrar su devolución</span>
            </div>

            <form id="formDevolucion" onsubmit="procesarDevolucion(event)">
                <div class="llaves-form-group">
                    <label for="prestamo_activo">Préstamo Activo <span style="color: #e74c3c;">*</span></label>
                    <select id="prestamo_activo" name="prestamo_id" required>
                        <option value="">-- Cargar Préstamos --</option>
                    </select>
                </div>

                <div class="llaves-form-row">
                    <div class="llaves-form-group">
                        <label for="hora_devolucion">Hora de Devolución <span style="color: #e74c3c;">*</span></label>
                        <input type="time" id="hora_devolucion" name="hora_devolucion" required>
                    </div>
                    <div class="llaves-form-group">
                        <label for="estado_llave">Estado de la Llave <span style="color: #e74c3c;">*</span></label>
                        <select id="estado_llave" name="estado_llave" required>
                            <option value="">-- Seleccionar Estado --</option>
                            <option value="buena">Buena Condición</option>
                            <option value="dañada">Dañada</option>
                            <option value="perdida">Perdida</option>
                        </select>
                    </div>
                </div>

                <div class="llaves-form-group">
                    <label for="notas_devolucion">Notas / Observaciones</label>
                    <textarea id="notas_devolucion" name="notas" rows="3" placeholder="Describe el estado de la llave o cualquier incidente..."></textarea>
                </div>

                <div class="llaves-btn-group">
                    <button type="submit" class="llaves-btn llaves-btn-primary">
                        <i class="fas fa-check"></i> Confirmar Devolución
                    </button>
                    <button type="reset" class="llaves-btn llaves-btn-secondary">
                        <i class="fas fa-redo"></i> Limpiar
                    </button>
                </div>
            </form>

            <!-- Préstamos Activos -->
            <div style="margin-top: 40px;">
                <h3><i class="fas fa-list llaves-icon"></i> Préstamos Activos</h3>
                
                <button class="llaves-btn llaves-btn-primary" onclick="cargarPrestamosActivos()" style="margin-bottom: 15px;">
                    <i class="fas fa-refresh"></i> Actualizar
                </button>

                <table class="llaves-table">
                    <thead>
                        <tr>
                            <th>Aula</th>
                            <th>Llave</th>
                            <th>Usuario</th>
                            <th>Hora Préstamo</th>
                            <th>Tiempo Transcurrido</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody id="prestamosActivosTbody">
                        <tr>
                            <td colspan="6" style="text-align: center; color: #7f8c8d;">Cargando préstamos activos...</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- TAB 3: NUEVA AULA -->
    <div id="nueva-aula" class="llaves-tab-content">
        <div class="llaves-card">
            <h3><i class="fas fa-plus-circle llaves-icon"></i> Crear Nueva Aula</h3>

            <button class="llaves-btn llaves-btn-primary" onclick="abrirModalNuevaAula()">
                <i class="fas fa-plus"></i> Crear Aula
            </button>

            <div style="margin-top: 30px;">
                <h4>Aulas Registradas</h4>
                <button class="llaves-btn llaves-btn-primary" onclick="cargarAulasTabla()" style="margin-bottom: 15px;">
                    <i class="fas fa-refresh"></i> Actualizar
                </button>

                <table class="llaves-table">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Ubicación</th>
                            <th>Capacidad</th>
                            <th>Total Llaves</th>
                            <th>Disponibles</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody id="aulasTabla">
                        <tr>
                            <td colspan="6" style="text-align: center; color: #7f8c8d;">Cargando aulas...</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- TAB 4: HISTORIAL -->
    <div id="historial" class="llaves-tab-content">
        <div class="llaves-card">
            <h3><i class="fas fa-history llaves-icon"></i> Historial de Transacciones</h3>

            <div class="llaves-form-row" style="margin-bottom: 20px;">
                <div class="llaves-form-group">
                    <label for="fecha_inicio">Fecha Inicio</label>
                    <input type="date" id="fecha_inicio">
                </div>
                <div class="llaves-form-group">
                    <label for="fecha_fin">Fecha Fin</label>
                    <input type="date" id="fecha_fin">
                </div>
            </div>

            <button class="llaves-btn llaves-btn-primary" onclick="cargarHistorial()">
                <i class="fas fa-search"></i> Filtrar
            </button>

            <table class="llaves-table" style="margin-top: 25px;">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Hora</th>
                        <th>Tipo</th>
                        <th>Aula</th>
                        <th>Llave</th>
                        <th>Usuario</th>
                        <th>Documento</th>
                        <th>Duración</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody id="historialTbody">
                    <tr>
                        <td colspan="9" style="text-align: center; color: #7f8c8d;">Cargando historial...</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal para Nueva Aula -->
<div id="modalNuevaAula" class="llaves-modal">
    <div class="llaves-modal-content">
        <div class="llaves-modal-header">
            <h2><i class="fas fa-plus-circle" style="margin-right: 10px;"></i>Crear Nueva Aula</h2>
            <span class="llaves-close" onclick="cerrarModalNuevaAula()">&times;</span>
        </div>

        <form id="formNuevaAula" onsubmit="procesarNuevaAula(event)">
            <div class="llaves-form-group">
                <label for="nombre_aula">Nombre del Aula <span style="color: #e74c3c;">*</span></label>
                <input type="text" id="nombre_aula" name="nombre" placeholder="Ej: Aula 101" required>
            </div>

            <div class="llaves-form-row">
                <div class="llaves-form-group">
                    <label for="ubicacion_aula">Ubicación <span style="color: #e74c3c;">*</span></label>
                    <input type="text" id="ubicacion_aula" name="ubicacion" placeholder="Ej: Segundo piso" required>
                </div>
                <div class="llaves-form-group">
                    <label for="responsable_aula">Responsable</label>
                    <input type="text" id="responsable_aula" name="responsable" placeholder="Nombre del encargado">
                </div>
            </div>

            <div class="llaves-form-row">
                <div class="llaves-form-group">
                    <label for="capacidad_aula">Capacidad (estudiantes) <span style="color: #e74c3c;">*</span></label>
                    <input type="number" id="capacidad_aula" name="capacidad" min="1" placeholder="Ej: 30" required>
                </div>
                <div class="llaves-form-group">
                    <label for="total_llaves_aula">Total de Llaves <span style="color: #e74c3c;">*</span></label>
                    <input type="number" id="total_llaves_aula" name="total_llaves" min="1" placeholder="Ej: 2" required>
                </div>
            </div>

            <div class="llaves-form-group">
                <label for="descripcion_aula">Descripción</label>
                <textarea id="descripcion_aula" name="descripcion" rows="3" placeholder="Descripción del aula..."></textarea>
            </div>

            <div class="llaves-btn-group">
                <button type="submit" class="llaves-btn llaves-btn-primary">
                    <i class="fas fa-save"></i> Crear Aula
                </button>
                <button type="button" class="llaves-btn llaves-btn-secondary" onclick="cerrarModalNuevaAula()">
                    <i class="fas fa-times"></i> Cancelar
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal para Tomar Llave desde tarjeta -->
<div id="modalTomarLlave" class="llaves-modal">
    <div class="llaves-modal-content">
        <div class="llaves-modal-header">
            <h2><i class="fas fa-arrow-right-from-bracket" style="margin-right: 10px;"></i>Tomar Llave</h2>
            <span class="llaves-close" onclick="cerrarModalTomarLlave()">&times;</span>
        </div>

        <form id="formTomarLlave" onsubmit="procesarPrestamo(event)">
            <input type="hidden" id="aula_id_form" name="id_aula">

            <div class="llaves-form-group">
                <label for="llave_select">Llave Disponible <span style="color: #e74c3c;">*</span></label>
                <select id="llave_select" name="id_llave" required>
                    <option value="">-- Seleccionar Llave --</option>
                </select>
            </div>

            <div class="llaves-form-group">
                <label for="usuario_form">Usuario que Retira <span style="color: #e74c3c;">*</span></label>
                <input type="text" id="usuario_form" name="usuario" placeholder="Nombre completo" required>
            </div>

            <div class="llaves-form-group">
                <label for="documento_form">Documento de Identidad <span style="color: #e74c3c;">*</span></label>
                <input type="text" id="documento_form" name="documento" placeholder="1.234.567.890" required>
            </div>

            <div class="llaves-form-group">
                <label for="hora_form">Hora de Préstamo <span style="color: #e74c3c;">*</span></label>
                <input type="time" id="hora_form" name="hora" value="" required>
            </div>

            <div class="llaves-form-group">
                <label for="observaciones_form">Observaciones</label>
                <textarea id="observaciones_form" name="observaciones" rows="2" placeholder="Notas adicionales..."></textarea>
            </div>

            <div class="llaves-btn-group">
                <button type="submit" class="llaves-btn llaves-btn-primary">
                    <i class="fas fa-check"></i> Registrar Préstamo
                </button>
                <button type="button" class="llaves-btn llaves-btn-secondary" onclick="cerrarModalTomarLlave()">
                    <i class="fas fa-times"></i> Cancelar
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // Construir URL del API de forma robusta
    // El archivo está en: /views/keyviews/prestamo_devolucion.php
    // El API está en: /api/keyAPI.php
    // Necesitamos subir 2 niveles: ../../api/keyAPI.php
    
    const API_URL = '../../api/keyAPI.php';
    
    console.log('API URL:', API_URL);
    console.log('Ubicación:', window.location.href);

    // Mostrar alerta
    function mostrarAlerta(mensaje, tipo = 'success') {
        const alertDiv = document.getElementById('llaveAlert');
        alertDiv.className = `llaves-alert llaves-alert-${tipo}`;
        alertDiv.innerHTML = `<i class="fas fa-${tipo === 'success' ? 'check-circle' : 'exclamation-circle'}"></i> <span>${mensaje}</span>`;
        alertDiv.style.display = 'flex';
        
        setTimeout(() => {
            alertDiv.style.display = 'none';
        }, 5000);
    }

    // Cambiar entre tabs
    function cambiarTab(event, tabName) {
        event.preventDefault();
        
        const contents = document.querySelectorAll('.llaves-tab-content');
        contents.forEach(content => content.classList.remove('active'));
        
        const buttons = document.querySelectorAll('.llaves-tab-btn');
        buttons.forEach(btn => btn.classList.remove('active'));
        
        document.getElementById(tabName).classList.add('active');
        event.target.closest('.llaves-tab-btn').classList.add('active');

        if (tabName === 'devolver') {
            cargarPrestamosActivos();
        } else if (tabName === 'historial') {
            cargarHistorial();
        }
    }

    // Cargar aulas en grid
    function cargarAulasGrid() {
        console.log('Iniciando cargarAulasGrid...');
        
        const formData = new FormData();
        formData.append('action', 'obtener_aulas');
        
        fetch(API_URL, {
            method: 'POST',
            body: formData
        })
        .then(response => {
            console.log('Respuesta HTTP:', response.status);
            return response.json();
        })
        .then(data => {
            console.log('Datos recibidos del API:', data);
            const grid = document.getElementById('aulasGrid');
            grid.innerHTML = '';
            
            if (data.success && data.data && data.data.length > 0) {
                console.log('Total de aulas encontradas:', data.data.length);
                data.data.forEach(aula => {
                    const prestadas = aula.total_llaves - aula.disponibles;
                    let prestamosHTML = '';
                    
                    if (aula.prestamos_activos && aula.prestamos_activos.length > 0) {
                        prestamosHTML = `
                            <div class="prestamos-activos" style="margin-bottom: 15px; padding: 12px; background: #f8f9fa; border-radius: 6px;">
                                <h4 style="margin: 0 0 10px 0; font-size: 13px; color: #2c3e50;"><i class="fas fa-users" style="margin-right: 6px;"></i> Llaves Prestadas:</h4>
                        `;
                        aula.prestamos_activos.forEach(prestamo => {
                            prestamosHTML += `
                                <div class="prestamo-item" style="padding: 8px; background: white; margin-bottom: 8px; border-left: 3px solid #e74c3c; border-radius: 3px;">
                                    <div style="font-weight: 600; color: #2c3e50; font-size: 13px;">${prestamo.usuario_retira}</div>
                                    <small style="color: #7f8c8d;">Doc: ${prestamo.documento}</small><br>
                                    <small style="color: #7f8c8d;">Desde: ${prestamo.hora_prestamo}</small>
                                </div>
                            `;
                        });
                        prestamosHTML += `</div>`;
                    }
                    
                    const html = `
                        <div class="aula-card-item">
                            <div class="aula-card-header">
                                <div class="aula-card-title">
                                    <i class="fas fa-building" style="color: #2980b9;"></i>
                                    ${aula.nombre}
                                </div>
                                <div class="aula-badge-status">ACTIVO</div>
                            </div>
                            
                            <div class="aula-info">
                                <div class="aula-card-stat">
                                    <i class="fas fa-key" style="color: #2980b9; margin-right: 8px;"></i>
                                    <span>Llaves totales: <strong>${aula.total_llaves}</strong></span>
                                </div>
                                
                                <div class="aula-card-stat">
                                    <i class="fas fa-check-circle" style="color: #27ae60; margin-right: 8px;"></i>
                                    <span>Disponibles: <strong>${aula.disponibles}</strong></span>
                                </div>
                                
                                <div class="aula-card-stat">
                                    <i class="fas fa-hand-holding" style="color: #e74c3c; margin-right: 8px;"></i>
                                    <span>Prestadas: <strong>${prestadas}</strong></span>
                                </div>
                            </div>

                            ${prestamosHTML}

                            ${aula.disponibles > 0 ? `
                                <button class="llaves-btn llaves-btn-success" onclick="abrirModalTomarLlave(${aula.id_aula})" style="width: 100%;">
                                    <i class="fas fa-arrow-right-from-bracket"></i> TOMAR LLAVE
                                </button>
                            ` : `
                                <button class="llaves-btn llaves-btn-secondary" disabled style="width: 100%;">
                                    <i class="fas fa-ban"></i> NO DISPONIBLE
                                </button>
                            `}
                        </div>
                    `;
                    grid.innerHTML += html;
                });
            } else {
                grid.innerHTML = '<div style="grid-column: 1/-1; text-align: center; padding: 40px; color: #7f8c8d;"><i class="fas fa-inbox" style="font-size: 40px; margin-bottom: 10px;"></i><p>No hay aulas registradas</p></div>';
            }
        })
        .catch(error => console.error('Error:', error));
    }

    // Abrir modal para tomar llave
    function abrirModalTomarLlave(idAula) {
        document.getElementById('aula_id_form').value = idAula;
        document.getElementById('hora_form').value = new Date().toTimeString().slice(0, 5);
        
        // Cargar llaves disponibles del aula
        const formData = new FormData();
        formData.append('action', 'obtener_llaves_aula');
        formData.append('id_aula', idAula);
        
        fetch(API_URL, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            const select = document.getElementById('llave_select');
            select.innerHTML = '<option value="">-- Seleccionar Llave --</option>';
            
            if (data.success && data.data) {
                data.data.forEach(llave => {
                    if (llave.disponible) {
                        const option = document.createElement('option');
                        option.value = llave.id_llave;
                        option.textContent = llave.numero_llave;
                        select.appendChild(option);
                    }
                });
            }
        });
        
        document.getElementById('modalTomarLlave').style.display = 'block';
    }

    // Cerrar modal tomar llave
    function cerrarModalTomarLlave() {
        document.getElementById('modalTomarLlave').style.display = 'none';
        document.getElementById('formTomarLlave').reset();
    }

    // Abrir modal nueva aula
    function abrirModalNuevaAula() {
        document.getElementById('modalNuevaAula').style.display = 'block';
    }

    // Cerrar modal nueva aula
    function cerrarModalNuevaAula() {
        document.getElementById('modalNuevaAula').style.display = 'none';
        document.getElementById('formNuevaAula').reset();
    }

    // Procesar préstamo
    function procesarPrestamo(event) {
        event.preventDefault();
        
        const formData = new FormData(document.getElementById('formTomarLlave'));
        formData.append('action', 'registrar_prestamo');

        fetch(API_URL, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                mostrarAlerta('✅ ' + data.message, 'success');
                cerrarModalTomarLlave();
                cargarAulasGrid();
                cargarPrestamosActivos();
            } else {
                mostrarAlerta('❌ ' + data.message, 'error');
            }
        })
        .catch(error => mostrarAlerta('Error: ' + error, 'error'));
    }

    // Procesar devolución
    function procesarDevolucion(event) {
        event.preventDefault();
        
        const formData = new FormData(document.getElementById('formDevolucion'));
        formData.append('action', 'devolver_llave');

        fetch(API_URL, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                mostrarAlerta('✅ ' + data.message, 'success');
                document.getElementById('formDevolucion').reset();
                cargarPrestamosActivos();
                cargarAulasGrid();
                setTimeout(() => cargarHistorial(), 1000);
            } else {
                mostrarAlerta('❌ ' + data.message, 'error');
            }
        })
        .catch(error => mostrarAlerta('Error: ' + error, 'error'));
    }

    // Procesar nueva aula
    function procesarNuevaAula(event) {
        event.preventDefault();
        
        const formData = new FormData(document.getElementById('formNuevaAula'));
        formData.append('action', 'crear_aula');

        console.log('Enviando a:', API_URL);
        
        fetch(API_URL, {
            method: 'POST',
            body: formData
        })
        .then(response => {
            console.log('Status:', response.status);
            console.log('URL:', response.url);
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.text().then(text => {
                try {
                    return JSON.parse(text);
                } catch (e) {
                    console.error('Respuesta no JSON:', text);
                    throw new Error('Respuesta del servidor inválida: ' + text.substring(0, 100));
                }
            });
        })
        .then(data => {
            console.log('Respuesta:', data);
            if (data.success) {
                mostrarAlerta('✅ ' + data.message, 'success');
                cerrarModalNuevaAula();
                cargarAulasTabla();
                cargarAulasGrid();
            } else {
                mostrarAlerta('❌ ' + data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Error completo:', error);
            console.error('Error message:', error.message);
            mostrarAlerta('Error: ' + error.message, 'error');
        });
    }

    // Cargar préstamos activos
    function cargarPrestamosActivos() {
        const formData = new FormData();
        formData.append('action', 'obtener_prestamos_activos');
        
        fetch(API_URL, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            const select = document.getElementById('prestamo_activo');
            const tbody = document.getElementById('prestamosActivosTbody');
            
            select.innerHTML = '<option value="">-- Seleccionar Préstamo --</option>';
            tbody.innerHTML = '';
            
            if (data.success && data.data && data.data.length > 0) {
                data.data.forEach(prestamo => {
                    const option = document.createElement('option');
                    option.value = prestamo.id_prestamo;
                    option.textContent = `${prestamo.nombre} | ${prestamo.numero_llave} | ${prestamo.usuario_retira}`;
                    select.appendChild(option);

                    const fila = document.createElement('tr');
                    fila.innerHTML = `
                        <td>${prestamo.nombre}</td>
                        <td>${prestamo.numero_llave}</td>
                        <td>${prestamo.usuario_retira}</td>
                        <td>${prestamo.hora_prestamo}</td>
                        <td>${calcularTiempo(prestamo.hora_prestamo)}</td>
                        <td><span class="llaves-badge llaves-badge-active">Activo</span></td>
                    `;
                    tbody.appendChild(fila);
                });
            } else {
                tbody.innerHTML = '<tr><td colspan="6" style="text-align: center; color: #7f8c8d;">No hay préstamos activos</td></tr>';
            }
        })
        .catch(error => console.error('Error:', error));
    }

    // Cargar aulas en tabla
    function cargarAulasTabla() {
        const formData = new FormData();
        formData.append('action', 'obtener_aulas');
        
        fetch(API_URL, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            const tbody = document.getElementById('aulasTabla');
            tbody.innerHTML = '';
            
            if (data.success && data.data && data.data.length > 0) {
                data.data.forEach(aula => {
                    const fila = document.createElement('tr');
                    fila.innerHTML = `
                        <td><strong>${aula.nombre}</strong></td>
                        <td>${aula.ubicacion || 'N/A'}</td>
                        <td>${aula.capacidad}</td>
                        <td>${aula.total_llaves}</td>
                        <td><span class="llaves-badge llaves-badge-active">${aula.disponibles}/${aula.total_llaves}</span></td>
                        <td><span class="llaves-badge llaves-badge-active">Activa</span></td>
                    `;
                    tbody.appendChild(fila);
                });
            } else {
                tbody.innerHTML = '<tr><td colspan="6" style="text-align: center; color: #7f8c8d;">No hay aulas registradas</td></tr>';
            }
        })
        .catch(error => console.error('Error:', error));
    }

    // Cargar historial
    function cargarHistorial() {
        const inicio = document.getElementById('fecha_inicio').value || new Date(Date.now() - 30*24*60*60*1000).toISOString().split('T')[0];
        const fin = document.getElementById('fecha_fin').value || new Date().toISOString().split('T')[0];
        
        const formData = new FormData();
        formData.append('action', 'obtener_historial');
        formData.append('fecha_inicio', inicio);
        formData.append('fecha_fin', fin);

        fetch(API_URL, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            const tbody = document.getElementById('historialTbody');
            tbody.innerHTML = '';
            
            if (data.success && data.data && data.data.length > 0) {
                data.data.forEach(registro => {
                    const fila = document.createElement('tr');
                    const badge = registro.estado === 'Devuelta' ? 'llaves-badge-returned' : 'llaves-badge-active';
                    fila.innerHTML = `
                        <td>${registro.fecha_prestamo}</td>
                        <td>${registro.hora_prestamo}</td>
                        <td>${registro.estado === 'Prestada' ? 'Préstamo' : 'Devolución'}</td>
                        <td>${registro.nombre_aula}</td>
                        <td>${registro.numero_llave}</td>
                        <td>${registro.usuario_retira}</td>
                        <td>${registro.documento}</td>
                        <td>${calcularDuracion(registro.hora_prestamo, registro.hora_devolucion)}</td>
                        <td><span class="llaves-badge ${badge}">${registro.estado}</span></td>
                    `;
                    tbody.appendChild(fila);
                });
            } else {
                tbody.innerHTML = '<tr><td colspan="9" style="text-align: center; color: #7f8c8d;">No hay registros</td></tr>';
            }
        })
        .catch(error => console.error('Error:', error));
    }

    // Calcular tiempo transcurrido
    function calcularTiempo(horaInicio) {
        const [h, m] = horaInicio.split(':');
        const inicio = new Date();
        inicio.setHours(parseInt(h), parseInt(m), 0);
        
        const ahora = new Date();
        let diff = Math.floor((ahora - inicio) / 1000 / 60);
        
        if (diff < 0) diff = 0;
        
        const horas = Math.floor(diff / 60);
        const minutos = diff % 60;
        
        return `${horas}h ${minutos}m`;
    }

    // Calcular duración
    function calcularDuracion(inicio, fin) {
        if (!fin) return 'En progreso';
        const [h1, m1] = inicio.split(':');
        const [h2, m2] = fin.split(':');
        
        const t1 = parseInt(h1) * 60 + parseInt(m1);
        const t2 = parseInt(h2) * 60 + parseInt(m2);
        
        let diff = t2 - t1;
        if (diff < 0) diff = 1440 + diff;
        
        const horas = Math.floor(diff / 60);
        const minutos = diff % 60;
        
        return `${horas}h ${minutos}m`;
    }

    // Cerrar modales al hacer clic fuera
    window.onclick = function(event) {
        const modal1 = document.getElementById('modalNuevaAula');
        const modal2 = document.getElementById('modalTomarLlave');
        
        if (event.target == modal1) modal1.style.display = 'none';
        if (event.target == modal2) modal2.style.display = 'none';
    }

    // Función para activar un tab específico
    function activarTab(tabName) {
        // Ocultar todos los tabs
        const contents = document.querySelectorAll('.llaves-tab-content');
        contents.forEach(content => content.classList.remove('active'));
        
        // Desactivar todos los botones
        const buttons = document.querySelectorAll('.llaves-tab-btn');
        buttons.forEach(btn => btn.classList.remove('active'));
        
        // Activar tab y botón
        const tabElement = document.getElementById(tabName);
        if (tabElement) {
            tabElement.classList.add('active');
        }
        
        // Activar botón correspondiente
        const botonesMap = {
            'tomar': 0,
            'devolver': 1,
            'nueva-aula': 2,
            'historial': 3
        };
        
        if (botonesMap[tabName] !== undefined) {
            buttons[botonesMap[tabName]].classList.add('active');
        }
        
        // Cargar datos según el tab
        if (tabName === 'devolver') {
            cargarPrestamosActivos();
        } else if (tabName === 'historial') {
            cargarHistorial();
        } else if (tabName === 'nueva-aula') {
            cargarAulasTabla();
        } else if (tabName === 'tomar') {
            cargarAulasGrid();
        }
    }

    // Cargar al iniciar
    window.addEventListener('load', function() {
        // Obtener tab a activar
        const tabActivo = '<?php echo $tab_activo; ?>';
        
        console.log('Tab activo al cargar:', tabActivo);
        
        if (tabActivo === 'tomar' || tabActivo === '') {
            // Tab por defecto: cargar grid de aulas
            console.log('Cargando grid de aulas...');
            cargarAulasGrid();
        } else if (tabActivo === 'devolver') {
            // Cargar préstamos activos
            cargarPrestamosActivos();
        } else if (tabActivo === 'nueva-aula') {
            // Cargar tabla de aulas
            cargarAulasTabla();
        } else if (tabActivo === 'historial') {
            // Cargar historial
            cargarHistorial();
        }
        
        // Activar la tab visualmente
        activarTab(tabActivo || 'tomar');
    });
</script>
