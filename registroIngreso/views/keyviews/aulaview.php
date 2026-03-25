<!-- Vista de Gestión de Aulas -->
<style>
    .aula-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 20px;
        background: #fff;
    }

    .aula-header {
        margin-bottom: 30px;
        border-bottom: 3px solid #2c3e50;
        padding-bottom: 15px;
    }

    .aula-header h1 {
        color: #2c3e50;
        font-size: 28px;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .aula-header p {
        color: #7f8c8d;
        margin: 5px 0 0 0;
    }

    .aula-tabs {
        display: flex;
        gap: 10px;
        margin-bottom: 30px;
        border-bottom: 2px solid #ecf0f1;
    }

    .aula-tab-btn {
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

    .aula-tab-btn.active {
        color: #2980b9;
        border-bottom-color: #2980b9;
    }

    .aula-tab-btn:hover {
        color: #2c3e50;
    }

    .aula-tab-content {
        display: none;
    }

    .aula-tab-content.active {
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

    .aula-form-group {
        margin-bottom: 20px;
    }

    .aula-form-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        color: #2c3e50;
        font-size: 14px;
    }

    .aula-form-group input,
    .aula-form-group select,
    .aula-form-group textarea {
        width: 100%;
        padding: 10px 12px;
        border: 1px solid #bdc3c7;
        border-radius: 5px;
        font-size: 14px;
        font-family: inherit;
        transition: border-color 0.3s ease;
    }

    .aula-form-group input:focus,
    .aula-form-group select:focus,
    .aula-form-group textarea:focus {
        outline: none;
        border-color: #2980b9;
        box-shadow: 0 0 0 3px rgba(41, 128, 185, 0.1);
    }

    .aula-form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }

    .aula-form-row.full {
        grid-template-columns: 1fr;
    }

    .aula-btn {
        padding: 11px 24px;
        border: none;
        border-radius: 5px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .aula-btn-primary {
        background: #2980b9;
        color: white;
    }

    .aula-btn-primary:hover {
        background: #1f5fa0;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(41, 128, 185, 0.3);
    }

    .aula-btn-secondary {
        background: #95a5a6;
        color: white;
    }

    .aula-btn-secondary:hover {
        background: #7f8c8d;
    }

    .aula-btn-danger {
        background: #e74c3c;
        color: white;
        padding: 5px 10px;
        font-size: 12px;
    }

    .aula-btn-danger:hover {
        background: #c0392b;
    }

    .aula-btn-group {
        display: flex;
        gap: 10px;
        margin-top: 25px;
    }

    .aula-alert {
        padding: 15px 20px;
        border-radius: 5px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .aula-alert-success {
        background: #d5f4e6;
        border-left: 4px solid #27ae60;
        color: #27ae60;
    }

    .aula-alert-error {
        background: #fadbd8;
        border-left: 4px solid #e74c3c;
        color: #e74c3c;
    }

    .aula-alert-info {
        background: #d6eaf8;
        border-left: 4px solid #2980b9;
        color: #2980b9;
    }

    .aula-card {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 20px;
        border: 1px solid #ecf0f1;
    }

    .aula-card h3 {
        color: #2c3e50;
        margin-top: 0;
        margin-bottom: 15px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .aula-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 15px;
        margin-bottom: 25px;
    }

    .aula-stat-item {
        background: white;
        padding: 15px;
        border-radius: 8px;
        border-left: 4px solid #2980b9;
        text-align: center;
    }

    .aula-stat-number {
        font-size: 28px;
        font-weight: bold;
        color: #2980b9;
    }

    .aula-stat-label {
        font-size: 13px;
        color: #7f8c8d;
        margin-top: 5px;
    }

    .aula-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 15px;
    }

    .aula-table thead {
        background: #2c3e50;
        color: white;
    }

    .aula-table th,
    .aula-table td {
        padding: 12px;
        text-align: left;
        border-bottom: 1px solid #ecf0f1;
    }

    .aula-table tbody tr:hover {
        background: #f8f9fa;
    }

    .aula-badge {
        display: inline-block;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }

    .aula-badge-active {
        background: #d5f4e6;
        color: #27ae60;
    }

    .aula-badge-inactive {
        background: #fadbd8;
        color: #e74c3c;
    }

    .aula-badge-good {
        background: #d5f4e6;
        color: #27ae60;
    }

    .aula-badge-low {
        background: #fef5e7;
        color: #f39c12;
    }

    .aula-empty-state {
        text-align: center;
        padding: 40px 20px;
        color: #7f8c8d;
    }

    .aula-empty-state i {
        font-size: 48px;
        margin-bottom: 15px;
        opacity: 0.5;
    }

    .aula-icon {
        font-size: 20px;
    }

    .aula-modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0,0,0,0.5);
    }

    .aula-modal-content {
        background-color: white;
        margin: 10% auto;
        padding: 25px;
        border-radius: 8px;
        width: 80%;
        max-width: 600px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.2);
    }

    .aula-modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        border-bottom: 2px solid #ecf0f1;
        padding-bottom: 15px;
    }

    .aula-modal-header h2 {
        margin: 0;
        color: #2c3e50;
    }

    .aula-close {
        font-size: 28px;
        font-weight: bold;
        cursor: pointer;
        color: #7f8c8d;
    }

    .aula-close:hover {
        color: #e74c3c;
    }

    @media (max-width: 768px) {
        .aula-form-row {
            grid-template-columns: 1fr;
        }

        .aula-header h1 {
            font-size: 22px;
        }

        .aula-tab-btn {
            padding: 10px 15px;
            font-size: 14px;
        }

        .aula-table {
            font-size: 13px;
        }

        .aula-table th,
        .aula-table td {
            padding: 8px;
        }
    }
</style>

<div class="aula-container">
    <!-- Header -->
    <div class="aula-header">
        <h1><i class="fas fa-building aula-icon"></i> Gestión de Aulas</h1>
        <p>Administra las aulas y sus llaves</p>
    </div>

    <!-- Tabs -->
    <div class="aula-tabs">
        <button class="aula-tab-btn active" onclick="cambiarTabAula(event, 'nueva-aula')">
            <i class="fas fa-plus-circle"></i> Nueva Aula
        </button>
        <button class="aula-tab-btn" onclick="cambiarTabAula(event, 'listar-aulas')">
            <i class="fas fa-list"></i> Listar Aulas
        </button>
    </div>

    <!-- Alertas -->
    <div id="aulaAlert" style="display: none;"></div>

    <!-- TAB 1: NUEVA AULA -->
    <div id="nueva-aula" class="aula-tab-content active">
        <div class="aula-card">
            <h3><i class="fas fa-plus-circle aula-icon"></i> Registrar Nueva Aula</h3>

            <form id="formNuevaAula" onsubmit="procesarNuevaAulaSubmit(event)">
                <div class="aula-form-row">
                    <div class="aula-form-group">
                        <label for="nombre_aula">Nombre del Aula <span style="color: #e74c3c;">*</span></label>
                        <input type="text" id="nombre_aula" name="nombre" placeholder="Ej: Aula 106 - Ciberseguridad" required>
                    </div>
                    <div class="aula-form-group">
                        <label for="ubicacion_aula">Ubicación <span style="color: #e74c3c;">*</span></label>
                        <input type="text" id="ubicacion_aula" name="ubicacion" placeholder="Ej: Segundo piso, ala norte" required>
                    </div>
                </div>

                <div class="aula-form-row">
                    <div class="aula-form-group">
                        <label for="capacidad_aula">Capacidad (estudiantes) <span style="color: #e74c3c;">*</span></label>
                        <input type="number" id="capacidad_aula" name="capacidad" min="1" placeholder="Ej: 30" required>
                    </div>
                    <div class="aula-form-group">
                        <label for="total_llaves_aula">Total de Llaves <span style="color: #e74c3c;">*</span></label>
                        <input type="number" id="total_llaves_aula" name="total_llaves" min="1" placeholder="Ej: 2" required>
                    </div>
                </div>

                <div class="aula-form-group">
                    <label for="responsable_aula">Responsable del Aula</label>
                    <input type="text" id="responsable_aula" name="responsable" placeholder="Nombre del encargado">
                </div>

                <div class="aula-form-group">
                    <label for="descripcion_aula">Descripción del Aula</label>
                    <textarea id="descripcion_aula" name="descripcion" rows="4" placeholder="Describe el aula, equipamiento, especialidad, etc..."></textarea>
                </div>

                <div class="aula-btn-group">
                    <button type="submit" class="aula-btn aula-btn-primary">
                        <i class="fas fa-save"></i> Crear Aula
                    </button>
                    <button type="reset" class="aula-btn aula-btn-secondary">
                        <i class="fas fa-redo"></i> Limpiar Formulario
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- TAB 2: LISTAR AULAS -->
    <div id="listar-aulas" class="aula-tab-content">
        <div class="aula-card">
            <h3><i class="fas fa-list aula-icon"></i> Aulas Registradas</h3>

            <div class="aula-stats" id="aulaStats">
                <div class="aula-stat-item">
                    <div class="aula-stat-number" id="totalAulas">0</div>
                    <div class="aula-stat-label">Total de Aulas</div>
                </div>
                <div class="aula-stat-item">
                    <div class="aula-stat-number" id="totalLlaves">0</div>
                    <div class="aula-stat-label">Total de Llaves</div>
                </div>
                <div class="aula-stat-item">
                    <div class="aula-stat-number" id="llavesDisponibles">0</div>
                    <div class="aula-stat-label">Llaves Disponibles</div>
                </div>
                <div class="aula-stat-item">
                    <div class="aula-stat-number" id="prestamosActivos">0</div>
                    <div class="aula-stat-label">Préstamos Activos</div>
                </div>
            </div>

            <button class="aula-btn aula-btn-primary" onclick="cargarAulas()" style="margin-bottom: 20px;">
                <i class="fas fa-refresh"></i> Actualizar Lista
            </button>

            <table class="aula-table" id="aulaTabla">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Ubicación</th>
                        <th>Capacidad</th>
                        <th>Total Llaves</th>
                        <th>Disponibles</th>
                        <th>Responsable</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="aulaBody">
                    <tr>
                        <td colspan="8" style="text-align: center; color: #7f8c8d;">Cargando aulas...</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal para Ver Detalles -->
    <div id="aulaModal" class="aula-modal">
        <div class="aula-modal-content">
            <div class="aula-modal-header">
                <h2 id="modalTitulo">Detalles del Aula</h2>
                <span class="aula-close" onclick="cerrarModal()">&times;</span>
            </div>
            <div id="modalContenido"></div>
        </div>
    </div>
</div>

<script>
    // Cambiar entre tabs
    function cambiarTabAula(event, tabName) {
        event.preventDefault();
        
        // Ocultar todos los tabs
        const contents = document.querySelectorAll('.aula-tab-content');
        contents.forEach(content => content.classList.remove('active'));
        
        // Remover clase active de todos los botones
        const buttons = document.querySelectorAll('.aula-tab-btn');
        buttons.forEach(btn => btn.classList.remove('active'));
        
        // Mostrar el tab seleccionado
        document.getElementById(tabName).classList.add('active');
        event.target.closest('.aula-tab-btn').classList.add('active');
        
        // Cargar aulas si es el tab de listar
        if (tabName === 'listar-aulas') {
            cargarAulas();
            cargarEstadisticas();
        }
    }

    // Mostrar alerta
    function mostrarAlerta(mensaje, tipo = 'success') {
        const alertDiv = document.getElementById('aulaAlert');
        alertDiv.className = `aula-alert aula-alert-${tipo}`;
        alertDiv.innerHTML = `<i class="fas fa-${tipo === 'success' ? 'check-circle' : tipo === 'error' ? 'exclamation-circle' : 'info-circle'}"></i> <span>${mensaje}</span>`;
        alertDiv.style.display = 'flex';
        
        setTimeout(() => {
            alertDiv.style.display = 'none';
        }, 5000);
    }

    // Procesar nueva aula
    function procesarNuevaAulaSubmit(event) {
        event.preventDefault();
        
        const formData = new FormData(document.getElementById('formNuevaAula'));
        formData.append('action', 'crear_aula');

        fetch('../../../api/keyAPI.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                mostrarAlerta('✅ ' + data.message, 'success');
                document.getElementById('formNuevaAula').reset();
                setTimeout(() => cargarAulas(), 1500);
            } else {
                mostrarAlerta('❌ ' + data.message, 'error');
            }
        })
        .catch(error => {
            mostrarAlerta('Error de conexión: ' + error, 'error');
        });
    }

    // Cargar aulas
    function cargarAulas() {
        fetch('../../../api/keyAPI.php', {
            method: 'POST',
            body: 'action=obtener_aulas'
        })
        .then(response => response.json())
        .then(data => {
            const tbody = document.getElementById('aulaBody');
            tbody.innerHTML = '';

            if (data.success && data.data.length > 0) {
                data.data.forEach(aula => {
                    const porcentaje = (aula.disponibles / aula.total_llaves * 100).toFixed(0);
                    const badge = aula.disponibles > 0 ? 'aula-badge-good' : 'aula-badge-low';
                    
                    tbody.innerHTML += `
                        <tr>
                            <td><strong>${aula.nombre}</strong></td>
                            <td>${aula.ubicacion || 'N/A'}</td>
                            <td>${aula.capacidad}</td>
                            <td>${aula.total_llaves}</td>
                            <td><span class="aula-badge ${badge}">${aula.disponibles}/${aula.total_llaves}</span></td>
                            <td>${aula.responsable || 'Sin asignar'}</td>
                            <td><span class="aula-badge aula-badge-active">Activa</span></td>
                            <td>
                                <button class="aula-btn aula-btn-primary" onclick="verDetalles(${aula.id_aula})" style="font-size: 12px; padding: 5px 10px;">Ver</button>
                            </td>
                        </tr>
                    `;
                });
            } else {
                tbody.innerHTML = '<tr><td colspan="8" style="text-align: center; color: #7f8c8d;">No hay aulas registradas</td></tr>';
            }
        })
        .catch(error => console.error('Error:', error));
    }

    // Ver detalles de aula
    function verDetalles(id) {
        fetch('../../../api/keyAPI.php', {
            method: 'POST',
            body: `action=obtener_llaves_aula&id_aula=${id}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                let html = `
                    <h4>Llaves disponibles:</h4>
                    <table class="aula-table">
                        <thead>
                            <tr>
                                <th>Número</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                `;
                
                data.data.forEach(llave => {
                    const estado = llave.disponible ? '<span class="aula-badge aula-badge-good">Disponible</span>' : '<span class="aula-badge aula-badge-low">En uso</span>';
                    html += `<tr><td>${llave.numero_llave}</td><td>${estado}</td></tr>`;
                });
                
                html += `</tbody></table>`;
                document.getElementById('modalContenido').innerHTML = html;
                document.getElementById('aulaModal').style.display = 'block';
            }
        });
    }

    // Cerrar modal
    function cerrarModal() {
        document.getElementById('aulaModal').style.display = 'none';
    }

    // Cargar estadísticas
    function cargarEstadisticas() {
        fetch('../../../api/keyAPI.php', {
            method: 'POST',
            body: 'action=obtener_estadisticas'
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('totalAulas').textContent = data.data.total_aulas;
                document.getElementById('totalLlaves').textContent = data.data.total_llaves;
                document.getElementById('llavesDisponibles').textContent = data.data.llaves_disponibles;
                document.getElementById('prestamosActivos').textContent = data.data.prestamos_activos;
            }
        });
    }

    // Cargar al abrir
    window.addEventListener('load', function() {
        cargarAulas();
    });

    // Cerrar modal al hacer clic fuera
    window.onclick = function(event) {
        const modal = document.getElementById('aulaModal');
        if (event.target == modal) {
            modal.style.display = 'none';
        }
    }
</script>
