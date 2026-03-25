<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

<style>
    .reportes-container { width: 100%; padding: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
    
    .reportes-header { background: white; border-bottom: 1px solid #e5e7eb; padding: 20px 32px; }
    .reportes-header h1 { margin: 0; font-size: 24px; font-weight: 600; color: #1f2937; }
    
    .reportes-content { padding: 32px; background: #f9fafb; }
    
    .reportes-title { font-size: 28px; font-weight: 600; color: #1f2937; margin: 0 0 8px 0; display: flex; align-items: center; gap: 12px; }
    .reportes-subtitle { font-size: 14px; color: #6b7280; margin: 0 0 32px 0; }
    
    .reportes-filters { background: white; border-radius: 8px; border: 1px solid #e5e7eb; padding: 24px; margin-bottom: 24px; }
    .reportes-filters-title { font-size: 15px; font-weight: 700; color: #1f2937; margin: 0 0 16px 0; display: flex; align-items: center; gap: 8px; }
    .reportes-filters-grid { display: grid; grid-template-columns: 1fr 1fr 1fr auto; gap: 16px; align-items: flex-end; }
    
    .reportes-filter-group { display: flex; flex-direction: column; }
    .reportes-filter-label { font-size: 13px; font-weight: 600; color: #374151; margin-bottom: 6px; }
    .reportes-filter-input { padding: 10px 12px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 14px; background: white; }
    .reportes-filter-input:focus { outline: none; border-color: #4ade80; box-shadow: 0 0 0 3px rgba(74, 222, 128, 0.1); }
    
    .reportes-btn { padding: 10px 20px; border-radius: 6px; border: none; cursor: pointer; font-weight: 600; font-size: 14px; display: flex; align-items: center; gap: 8px; transition: all 0.2s; }
    .reportes-btn-primary { background: #4ade80; color: white; }
    .reportes-btn-primary:hover { background: #22c55e; }
    
    .reportes-results { background: white; border-radius: 8px; border: 1px solid #e5e7eb; padding: 24px; }
    .reportes-results-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px; }
    .reportes-results-title { font-size: 15px; font-weight: 700; color: #1f2937; margin: 0; }
    .reportes-export-buttons { display: flex; gap: 12px; }
    .reportes-export-btn { padding: 8px 16px; border-radius: 6px; border: none; cursor: pointer; font-size: 13px; font-weight: 600; display: flex; align-items: center; gap: 6px; transition: all 0.2s; background: #f3f4f6; color: #374151; }
    .reportes-export-btn:hover { background: #e5e7eb; }
    
    .reportes-tabs { display: flex; gap: 24px; margin-bottom: 24px; border-bottom: 2px solid #e5e7eb; }
    .reportes-tab { padding: 12px 0; border-bottom: 3px solid transparent; cursor: pointer; font-size: 14px; font-weight: 600; color: #6b7280; transition: all 0.2s; }
    .reportes-tab.active { color: #28b463; border-bottom-color: #28b463; }
    
    .reportes-empty-state { text-align: center; padding: 60px 20px; }
    .reportes-empty-icon { font-size: 64px; color: #d1d5db; margin-bottom: 16px; }
    .reportes-empty-text { font-size: 15px; color: #9ca3af; }
    
    .reportes-table { width: 100%; border-collapse: collapse; display: none; }
    .reportes-table.active { display: table; }
    .reportes-table thead th { background: #f3f4f6; padding: 12px; text-align: left; font-size: 12px; font-weight: 700; color: #374151; border-bottom: 2px solid #e5e7eb; text-transform: uppercase; letter-spacing: 0.5px; }
    .reportes-table tbody td { padding: 12px; border-bottom: 1px solid #e5e7eb; font-size: 14px; }
    .reportes-table tbody tr:hover { background: #f9fafb; }
</style>

<!-- Reportes Content -->
<div class="reportes-container">
    <!-- Header -->
    <div class="reportes-header">
        <h1>Reportes de Acceso</h1>
    </div>

    <!-- Main Content -->
    <div class="reportes-content">
        <!-- Title & Description -->
        <div>
            <h2 class="reportes-title">
                <i class="bi bi-file-earmark-text" style="color: #6b7280;"></i>
                Reportes de Acceso
            </h2>
            <p class="reportes-subtitle">Consulta y exporta los registros de entrada y salida</p>
        </div>

        <!-- Filters Section -->
        <div class="reportes-filters">
            <h3 class="reportes-filters-title">
                <i class="bi bi-funnel"></i> Filtros de Búsqueda
            </h3>
            
            <div class="reportes-filters-grid">
                <div class="reportes-filter-group">
                    <label class="reportes-filter-label">📅 Fecha Inicio</label>
                    <input type="date" class="reportes-filter-input" id="fecha-inicio" value="31/01/2026">
                </div>
                
                <div class="reportes-filter-group">
                    <label class="reportes-filter-label">📅 Fecha Fin</label>
                    <input type="date" class="reportes-filter-input" id="fecha-fin" value="02/03/2026">
                </div>
                
                <div class="reportes-filter-group">
                    <label class="reportes-filter-label">👤 Documento (Opcional)</label>
                    <input type="text" class="reportes-filter-input" id="documento" placeholder="Buscar por documento">
                </div>
                
                <button class="reportes-btn reportes-btn-primary" onclick="consultarReportes()">
                    <i class="bi bi-search"></i> Consultar
                </button>
            </div>
        </div>

        <!-- Results Section -->
        <div class="reportes-results">
            <!-- Results Header -->
            <div class="reportes-results-header">
                <h3 class="reportes-results-title">
                    <i class="bi bi-list-check"></i> Resultados
                </h3>
                <div class="reportes-export-buttons">
                    <button class="reportes-export-btn" onclick="exportarExcel()">
                        <i class="bi bi-file-earmark-excel"></i> Exportar Excel
                    </button>
                    <button class="reportes-export-btn" onclick="exportarPDF()">
                        <i class="bi bi-file-earmark-pdf"></i> Exportar PDF
                    </button>
                </div>
            </div>

            <!-- Tabs -->
            <div class="reportes-tabs">
                <div class="reportes-tab active" onclick="cambiarTab('accesos', this)">
                    <i class="bi bi-box-arrow-in-right"></i> Accesos
                </div>
                <div class="reportes-tab" onclick="cambiarTab('prestamos', this)">
                    <i class="bi bi-key"></i> Préstamos de Llaves
                </div>
            </div>

            <!-- Accesos Tab -->
            <div id="accesos" class="reportes-table active" style="display: block;">
                <div class="reportes-empty-state">
                    <div class="reportes-empty-icon">
                        <i class="bi bi-graph-up"></i>
                    </div>
                    <p class="reportes-empty-text">Seleccione un rango de fechas y haga clic en Consultar</p>
                </div>
            </div>

            <!-- Prestamos Tab -->
            <div id="prestamos" class="reportes-table">
                <div class="reportes-empty-state">
                    <div class="reportes-empty-icon">
                        <i class="bi bi-graph-up"></i>
                    </div>
                    <p class="reportes-empty-text">Seleccione un rango de fechas y haga clic en Consultar</p>
                </div>
            </div>

            <!-- Table Example (Accesos) -->
            <table class="reportes-table active" id="tabla-accesos">
                <thead>
                    <tr>
                        <th>Documento</th>
                        <th>Nombre</th>
                        <th>Hora Entrada</th>
                        <th>Hora Salida</th>
                        <th>Fecha</th>
                    </tr>
                </thead>
                <tbody style="display: none;">
                    <tr>
                        <td>12345678</td>
                        <td>Juan Carlos</td>
                        <td>08:00 AM</td>
                        <td>05:00 PM</td>
                        <td>31/01/2026</td>
                    </tr>
                </tbody>
            </table>

            <!-- Table Example (Préstamos) -->
            <table class="reportes-table" id="tabla-prestamos">
                <thead>
                    <tr>
                        <th>Aula</th>
                        <th>Llaves Prestadas</th>
                        <th>Hora Préstamo</th>
                        <th>Hora Devolución</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody style="display: none;">
                    <tr>
                        <td>Aula 101</td>
                        <td>2</td>
                        <td>08:30 AM</td>
                        <td>04:30 PM</td>
                        <td>Devuelto</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    function cambiarTab(tabName, element) {
        // Remove active class from all tabs and tables
        document.querySelectorAll('.reportes-tab').forEach(tab => tab.classList.remove('active'));
        document.querySelectorAll('.reportes-empty-state').forEach(state => state.parentElement.style.display = 'none');

        // Add active class to clicked tab
        element.classList.add('active');

        // Show corresponding content
        if (tabName === 'accesos') {
            document.getElementById('accesos').style.display = 'block';
        } else if (tabName === 'prestamos') {
            document.getElementById('prestamos').style.display = 'block';
        }
    }

    function consultarReportes() {
        const fechaInicio = document.getElementById('fecha-inicio').value;
        const fechaFin = document.getElementById('fecha-fin').value;
        const documento = document.getElementById('documento').value;

        if (!fechaInicio || !fechaFin) {
            alert('Por favor selecciona un rango de fechas');
            return;
        }

        alert(`Consultando reportes:\nDesde: ${fechaInicio}\nHasta: ${fechaFin}\nDocumento: ${documento || 'Todos'}`);
    }

    function exportarExcel() {
        alert('Exportando a Excel...');
    }

    function exportarPDF() {
        alert('Exportando a PDF...');
    }
</script>
