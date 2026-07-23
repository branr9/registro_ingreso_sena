<?php require_once APP_PATH . '/views/layouts/header.php'; ?>

<div class="page-header">
    <h1><i class="fas fa-chart-bar"></i> Reportes de Acceso</h1>
    <p>Consulta y exporta los registros de entrada y salida</p>
</div>

<div class="card">
    <div class="card-header">
        <h3><i class="fas fa-filter"></i> Filtros de Búsqueda</h3>
    </div>
    <div class="card-body">
        <form id="reportForm" method="GET">
            <div class="form-row">
                <div class="form-group">
                    <label for="fecha_inicio">
                        <i class="fas fa-calendar-alt"></i> Fecha Inicio
                    </label>
                    <input type="date" 
                           id="fecha_inicio" 
                           name="fecha_inicio" 
                           class="form-control"
                           value="<?= $_GET['fecha_inicio'] ?? date('Y-m-d', strtotime('-30 days')) ?>"
                           required>
                </div>

                <div class="form-group">
                    <label for="fecha_fin">
                        <i class="fas fa-calendar-alt"></i> Fecha Fin
                    </label>
                    <input type="date" 
                           id="fecha_fin" 
                           name="fecha_fin" 
                           class="form-control"
                           value="<?= $_GET['fecha_fin'] ?? date('Y-m-d') ?>"
                           required>
                </div>

                <div class="form-group">
                    <label for="documento">
                        <i class="fas fa-id-card"></i> Documento (Opcional)
                    </label>
                    <input type="text" 
                           id="documento" 
                           name="documento" 
                           class="form-control"
                           placeholder="Buscar por documento"
                           value="<?= $_GET['documento'] ?? '' ?>">
                </div>

                <div class="form-group">
                    <label>&nbsp;</label>
                    <button type="button" onclick="loadReport()" class="btn btn-primary">
                        <i class="fas fa-search"></i> Consultar
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3><i class="fas fa-list"></i> Resultados</h3>
        <div class="card-actions">
            <button onclick="exportExcel()" class="btn btn-success">
                <i class="fas fa-file-excel"></i> Exportar Excel
            </button>
            <button onclick="exportPdf()" class="btn btn-danger">
                <i class="fas fa-file-pdf"></i> Exportar PDF
            </button>
        </div>
    </div>
    <div class="card-body">
        <div class="tabs">
            <button class="tab-button active" onclick="showTab('accesos')">
                <i class="fas fa-door-open"></i> Accesos
            </button>
            <button class="tab-button" onclick="showTab('llaves')">
                <i class="fas fa-key"></i> Préstamos de Llaves
            </button>
        </div>
        
        <div id="tab-accesos" class="tab-content active">
            <div id="reportResults">
                <div class="text-center" style="padding: 40px;">
                    <i class="fas fa-chart-line" style="font-size: 48px; color: #ccc;"></i>
                    <p style="color: #666; margin-top: 20px;">
                        Seleccione un rango de fechas y haga clic en Consultar
                    </p>
                </div>
            </div>
        </div>
        
        <div id="tab-llaves" class="tab-content">
            <div id="prestamosResults">
                <div class="text-center" style="padding: 40px;">
                    <i class="fas fa-chart-line" style="font-size: 48px; color: #ccc;"></i>
                    <p style="color: #666; margin-top: 20px;">
                        Seleccione un rango de fechas y haga clic en Consultar
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.page-header {
    margin-bottom: 2rem;
}

.page-header h1 {
    color: var(--text-primary);
    font-size: 2rem;
    font-weight: 600;
    margin-bottom: 0.5rem;
}

.page-header p {
    color: var(--text-secondary);
    font-size: 1rem;
}

.form-row {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
    align-items: end;
}

.card-actions {
    display: flex;
    gap: 0.5rem;
}

.table-responsive {
    overflow-x: auto;
}

.report-table {
    width: 100%;
    border-collapse: collapse;
}

.report-table th,
.report-table td {
    padding: 12px;
    text-align: left;
    border-bottom: 1px solid var(--border-color);
}

.report-table th {
    background-color: #f8f9fa;
    font-weight: 600;
    color: var(--text-primary);
}

.report-table tbody tr:hover {
    background-color: #f8f9fa;
}

.badge-entrada {
    background-color: #28a745;
    color: white;
    padding: 0.25rem 0.5rem;
    border-radius: 0.25rem;
    font-size: 0.875rem;
}

.badge-salida {
    background-color: #dc3545;
    color: white;
    padding: 0.25rem 0.5rem;
    border-radius: 0.25rem;
    font-size: 0.875rem;
}

.badge-warning {
    background-color: #ffc107;
    color: #000;
    padding: 0.25rem 0.5rem;
    border-radius: 0.25rem;
    font-size: 0.875rem;
}

.text-center {
    text-align: center;
}

.tabs {
    display: flex;
    border-bottom: 2px solid var(--border-color);
    margin-bottom: 1.5rem;
    gap: 0.5rem;
}

.tab-button {
    padding: 0.75rem 1.5rem;
    background: none;
    border: none;
    border-bottom: 3px solid transparent;
    cursor: pointer;
    font-weight: 500;
    color: var(--text-secondary);
    transition: all 0.3s;
}

.tab-button:hover {
    color: var(--primary-color);
}

.tab-button.active {
    color: var(--primary-color);
    border-bottom-color: var(--primary-color);
}

.tab-content {
    display: none;
}

.tab-content.active {
    display: block;
}
</style>

<script>
let currentData = null;

function showTab(tabName) {
    // Ocultar todas las pestañas
    document.querySelectorAll('.tab-content').forEach(tab => {
        tab.classList.remove('active');
    });
    document.querySelectorAll('.tab-button').forEach(btn => {
        btn.classList.remove('active');
    });
    
    // Mostrar la pestaña seleccionada
    document.getElementById('tab-' + tabName).classList.add('active');
    event.target.classList.add('active');
}
function loadReport() {
    const fechaInicio = document.getElementById('fecha_inicio').value;
    const fechaFin = document.getElementById('fecha_fin').value;
    const documento = document.getElementById('documento').value;

    if (!fechaInicio || !fechaFin) {
        alert('Por favor seleccione ambas fechas');
        return;
    }

    // Mostrar loading
    document.getElementById('reportResults').innerHTML = `
        <div class="text-center" style="padding: 40px;">
            <i class="fas fa-spinner fa-spin" style="font-size: 48px; color: var(--primary-color);"></i>
            <p style="color: #666; margin-top: 20px;">Cargando reporte...</p>
        </div>
    `;

    // Construir URL con parámetros
    let url = `<?= baseUrl('/reportes/data') ?>?fecha_inicio=${fechaInicio}&fecha_fin=${fechaFin}`;
    if (documento) {
        url += `&documento=${documento}`;
    }

    // Hacer petición AJAX
    fetch(url)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                currentData = data; // Guardar datos globalmente
                displayReport(data.marcaciones, data.stats);
                displayPrestamos(data.prestamos);
            } else {
                document.getElementById('reportResults').innerHTML = `
                    <div class="text-center" style="padding: 40px;">
                        <i class="fas fa-exclamation-triangle" style="font-size: 48px; color: #ffc107;"></i>
                        <p style="color: #666; margin-top: 20px;">${data.message}</p>
                    </div>
                `;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('reportResults').innerHTML = `
                <div class="text-center" style="padding: 40px;">
                    <i class="fas fa-times-circle" style="font-size: 48px; color: #dc3545;"></i>
                    <p style="color: #666; margin-top: 20px;">Error al cargar el reporte</p>
                </div>
            `;
        });
}

function displayReport(marcaciones, stats) {
    let html = `
        <div style="margin-bottom: 20px;">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 1rem;">
                <div class="stat-card">
                    <div class="stat-icon"><i class="fas fa-list"></i></div>
                    <div class="stat-content">
                        <h3>${stats.total}</h3>
                        <p>Total Registros</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon" style="background: #28a745;"><i class="fas fa-sign-in-alt"></i></div>
                    <div class="stat-content">
                        <h3>${stats.entradas}</h3>
                        <p>Entradas</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon" style="background: #dc3545;"><i class="fas fa-sign-out-alt"></i></div>
                    <div class="stat-content">
                        <h3>${stats.salidas}</h3>
                        <p>Salidas</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="table-responsive">
            <table class="report-table">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Hora</th>
                        <th>Documento</th>
                        <th>Nombre</th>
                        <th>Tipo Persona</th>
                        <th>Tipo Acceso</th>
                        <th>Método</th>
                    </tr>
                </thead>
                <tbody>
    `;

    if (marcaciones.length === 0) {
        html += `
            <tr>
                <td colspan="7" class="text-center" style="padding: 40px;">
                    No se encontraron registros para el período seleccionado
                </td>
            </tr>
        `;
    } else {
        marcaciones.forEach(m => {
            const fecha = new Date(m.fecha_hora);
            const badgeClass = m.tipo_acceso === 'ENTRADA' ? 'badge-entrada' : 'badge-salida';
            
            html += `
                <tr>
                    <td>${fecha.toLocaleDateString('es-CO')}</td>
                    <td>${fecha.toLocaleTimeString('es-CO')}</td>
                    <td>${m.documento}</td>
                    <td>${m.nombre_completo}</td>
                    <td>${m.tipo_persona}</td>
                    <td><span class="${badgeClass}">${m.tipo_acceso}</span></td>
                    <td>${m.metodo}</td>
                </tr>
            `;
        });
    }

    html += `
                </tbody>
            </table>
        </div>
    `;

    document.getElementById('reportResults').innerHTML = html;
}

function displayPrestamos(prestamos) {
    let html = `
        <div class="table-responsive">
            <table class="report-table">
                <thead>
                    <tr>
                        <th>Fecha Préstamo</th>
                        <th>Aula</th>
                        <th>Documento</th>
                        <th>Nombre</th>
                        <th>Tipo Persona</th>
                        <th>Fecha Devolución</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
    `;

    if (prestamos.length === 0) {
        html += `
            <tr>
                <td colspan="7" class="text-center" style="padding: 40px;">
                    No se encontraron préstamos para el período seleccionado
                </td>
            </tr>
        `;
    } else {
        prestamos.forEach(p => {
            const fechaPrestamo = new Date(p.fecha_prestamo);
            const fechaDevolucion = p.fecha_devolucion ? new Date(p.fecha_devolucion) : null;
            const badgeClass = p.estado === 'PRESTADO' ? 'badge-warning' : p.estado === 'DEVUELTO' ? 'badge-entrada' : 'badge-salida';
            
            html += `
                <tr>
                    <td>${fechaPrestamo.toLocaleDateString('es-CO')} ${fechaPrestamo.toLocaleTimeString('es-CO')}</td>
                    <td><strong>${p.aula_nombre}</strong></td>
                    <td>${p.documento}</td>
                    <td>${p.nombre_completo}</td>
                    <td>${p.tipo_persona}</td>
                    <td>${fechaDevolucion ? fechaDevolucion.toLocaleDateString('es-CO') + ' ' + fechaDevolucion.toLocaleTimeString('es-CO') : '<span class="text-muted">Pendiente</span>'}</td>
                    <td><span class="${badgeClass}">${p.estado}</span></td>
                </tr>
            `;
        });
    }

    html += `
                </tbody>
            </table>
        </div>
    `;

    document.getElementById('prestamosResults').innerHTML = html;
}

function exportExcel() {
    const fechaInicio = document.getElementById('fecha_inicio').value;
    const fechaFin = document.getElementById('fecha_fin').value;
    const documento = document.getElementById('documento').value;

    if (!fechaInicio || !fechaFin) {
        alert('Por favor seleccione ambas fechas');
        return;
    }

    let url = `<?= baseUrl('/reportes/export-excel') ?>?fecha_inicio=${fechaInicio}&fecha_fin=${fechaFin}`;
    if (documento) {
        url += `&documento=${documento}`;
    }
    window.location.href = url;
}

function exportPdf() {
    const fechaInicio = document.getElementById('fecha_inicio').value;
    const fechaFin = document.getElementById('fecha_fin').value;
    const documento = document.getElementById('documento').value;

    if (!fechaInicio || !fechaFin) {
        alert('Por favor seleccione ambas fechas');
        return;
    }

    let url = `<?= baseUrl('/reportes/export-pdf') ?>?fecha_inicio=${fechaInicio}&fecha_fin=${fechaFin}`;
    if (documento) {
        url += `&documento=${documento}`;
    }
    window.open(url, '_blank');
}
</script>

<?php require_once APP_PATH . '/views/layouts/footer.php'; ?>
