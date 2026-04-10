<?php
// ==========================================
// 1. LLAMAR A LA CONEXIÓN (conexion.php)
// ==========================================
include_once "models/conexion.php"; 

// ==========================================
// 2. RECIBIR FILTROS DEL FORMULARIO
// ==========================================
$busqueda = isset($_GET['buscar']) ? trim($_GET['buscar']) : '';
$estado_filtro = isset($_GET['estado']) ? $_GET['estado'] : 'Todos';
$fecha_desde = isset($_GET['desde']) ? $_GET['desde'] : '';
$fecha_hasta = isset($_GET['hasta']) ? $_GET['hasta'] : '';

// ==========================================
// 3. CONSTRUIR LA CONSULTA SQL DINÁMICA
// ==========================================
$sql = "SELECT * FROM personal_externo WHERE 1=1";
$params = [];
$tipos = ""; 

if ($busqueda !== '') {
    $sql .= " AND (nombre LIKE ? OR documento LIKE ? OR empresa LIKE ?)";
    $like_term = "%$busqueda%";
    $params[] = $like_term;
    $params[] = $like_term;
    $params[] = $like_term;
    $tipos .= "sss"; 
}

if ($estado_filtro !== 'Todos') {
    $sql .= " AND estado = ?";
    $params[] = $estado_filtro;
    $tipos .= "s"; 
}

if ($fecha_desde !== '') {
    $sql .= " AND fecha >= ?";
    $params[] = $fecha_desde;
    $tipos .= "s";
}

if ($fecha_hasta !== '') {
    $sql .= " AND fecha <= ?";
    $params[] = $fecha_hasta;
    $tipos .= "s";
}

$sql .= " ORDER BY fecha DESC, hora_ingreso DESC";

// ==========================================
// 4. EJECUTAR LA CONSULTA
// ==========================================
$stmt = mysqli_prepare($conexion, $sql);

if ($stmt) {
    if (!empty($params)) {
        mysqli_stmt_bind_param($stmt, $tipos, ...$params);
    }
    mysqli_stmt_execute($stmt);
    $resultado = mysqli_stmt_get_result($stmt);
    $registros_filtrados = mysqli_fetch_all($resultado, MYSQLI_ASSOC);
    mysqli_stmt_close($stmt);
} else {
    die("<div class='alert alert-danger'>Error en la consulta SQL: " . mysqli_error($conexion) . "</div>");
}
?>

<style>
    /* Estilos Generales */
    .page-header-custom { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 25px; }
    .title-section h2 { margin: 0; font-size: 22px; display: flex; align-items: center; gap: 10px; color: #2c3e50; font-weight: 700; }
    .title-section p { margin: 5px 0 0 38px; color: #6c757d; font-size: 13px; }
    .action-buttons { display: flex; gap: 10px; }
    .btn-custom { padding: 8px 16px; border: none; border-radius: 4px; cursor: pointer; font-weight: 600; font-size: 14px; display: flex; align-items: center; gap: 8px; color: white; transition: opacity 0.2s; text-decoration: none; }
    .btn-custom:hover { opacity: 0.9; color: white;}
    .btn-cyan { background-color: #00bcd4; }
    .btn-green { background-color: #3aa822; }
    .btn-gray { background-color: #718096; }
    .custom-card { background-color: #ffffff; border-radius: 8px; padding: 25px; box-shadow: 0 2px 8px rgba(0,0,0,0.04); margin-bottom: 25px; border: 1px solid #eaeaea; }
    
    /* Filtros y Tabla */
    .filters-form { display: flex; flex-wrap: wrap; gap: 20px; align-items: flex-end; }
    .form-group-custom { display: flex; flex-direction: column; gap: 6px; }
    .form-group-custom label { font-size: 13px; font-weight: 600; color: #4a5568; }
    .form-control-custom { padding: 8px 12px; border: 1px solid #cbd5e0; border-radius: 5px; font-size: 14px; color: #2d3748; outline: none; min-width: 200px; background-color: #fff; }
    .form-control-custom:focus { border-color: #3aa822; }
    .custom-table-wrapper { overflow-x: auto; }
    .custom-table { width: 100%; border-collapse: collapse; font-size: 13px; }
    .custom-table th { text-align: left; padding: 12px 10px; color: #4a5568; font-weight: 700; border-bottom: 2px solid #e2e8f0; white-space: nowrap; }
    .custom-table td { padding: 12px 10px; vertical-align: top; border-bottom: 1px solid #edf2f7; color: #4a5568; }
    .data-primary { font-weight: 600; color: #2d3748; display: block; margin-bottom: 3px;}
    .data-secondary { font-size: 11px; color: #718096; display: flex; align-items: center; gap: 4px; }
    .action-icon { color: #1e40af; cursor: pointer; font-size: 18px; font-weight:bold; transition: 0.2s; }
    .action-icon:hover { color: #1e3a8a; transform: scale(1.1); }
    .empty-state { text-align: center; padding: 20px; color: #a0aec0; }

    /* Estilos del Modal (Ventana Emergente) */
    .modal-header-custom { background-color: #f8fafc; border-bottom: 1px solid #e2e8f0; border-radius: 8px 8px 0 0; }
    .modal-title-custom { color: #1e293b; font-weight: 700; font-size: 18px; display: flex; align-items: center; gap: 8px; }
    .info-group { margin-bottom: 15px; }
    .info-label { font-size: 12px; color: #64748b; font-weight: 600; text-transform: uppercase; margin-bottom: 4px; display: block; }
    .info-value { font-size: 15px; color: #1e293b; font-weight: 500; background-color: #f8fafc; padding: 8px 12px; border-radius: 6px; border: 1px solid #e2e8f0; }
    .status-badge-dentro { background-color: #dcfce7; color: #166534; padding: 4px 10px; border-radius: 20px; font-weight: 600; font-size: 12px; }
    .status-badge-salio { background-color: #f1f5f9; color: #475569; padding: 4px 10px; border-radius: 20px; font-weight: 600; font-size: 12px; }
</style>

<div class="page-header-custom">
    <div class="title-section">
        <h2><i class="bi bi-people-fill"></i> Registro de Personal Externo</h2>
        <p>Control de entrada y salida de personal sin carnet (visitantes, contratistas, proveedores)</p>
    </div>
    <div class="action-buttons">
        <a href="index.php?vista=personasDentrobutton" class="btn-custom btn-cyan">
            <i class="bi bi-door-open"></i> Personas Dentro
        </a>
        <a href="index.php?vista=registrarEntradabutton" class="btn-custom btn-green">
            <i class="bi bi-person-plus-fill"></i> Registrar Entrada
        </a>
    </div>
</div>

<div class="custom-card">
    <form class="filters-form" method="GET" action="index.php">
        <input type="hidden" name="vista" value="personalExterno">
        
        <div class="form-group-custom">
            <label>Buscar</label>
            <input type="text" name="buscar" class="form-control-custom" placeholder="Documento, nombre, empresa..." value="<?php echo htmlspecialchars($busqueda); ?>">
        </div>
        <div class="form-group-custom">
            <label>Estado</label>
            <select name="estado" class="form-control-custom" style="min-width: 150px;">
                <option value="Todos" <?php echo $estado_filtro == 'Todos' ? 'selected' : ''; ?>>Todos</option>
                <option value="Dentro" <?php echo $estado_filtro == 'Dentro' ? 'selected' : ''; ?>>Dentro</option>
                <option value="Salió" <?php echo $estado_filtro == 'Salió' ? 'selected' : ''; ?>>Salió</option>
            </select>
        </div>
        <div class="form-group-custom">
            <label>Desde</label>
            <input type="date" name="desde" class="form-control-custom" style="min-width: 150px;" value="<?php echo htmlspecialchars($fecha_desde); ?>">
        </div>
        <div class="form-group-custom">
            <label>Hasta</label>
            <input type="date" name="hasta" class="form-control-custom" style="min-width: 150px;" value="<?php echo htmlspecialchars($fecha_hasta); ?>">
        </div>
        <div class="action-buttons" style="margin-bottom: 2px;">
            <button type="submit" class="btn-custom btn-green"><i class="bi bi-search"></i> Buscar</button>
            <button type="button" class="btn-custom btn-gray" onclick="limpiarFiltros()" title="Restablecer"><i class="bi bi-arrow-clockwise"></i></button>
        </div>
    </form>
</div>

<div class="custom-card">
    <div class="custom-table-wrapper">
        <table class="custom-table">
            <thead>
                <tr>
                    <th>Fecha/Hora Entrada</th>
                    <th>Documento</th>
                    <th>Nombre Completo</th>
                    <th>Empresa</th>
                    <th>Motivo</th>
                    <th>Estado</th>
                    <th>Tiempo</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($registros_filtrados) > 0): ?>
                    <?php foreach ($registros_filtrados as $reg): ?>
                        <tr>
                            <td>
                                <span class="data-primary"><?php echo date('d/m/Y', strtotime($reg['fecha'])); ?></span>
                                <span class="data-secondary"><?php echo date('h:i A', strtotime($reg['hora_ingreso'])); ?></span>
                            </td>
                            <td>
                                <span class="data-primary"><?php echo htmlspecialchars($reg['documento']); ?></span>
                                <span class="data-secondary"><?php echo htmlspecialchars($reg['tipo_documento']); ?></span>
                            </td>
                            <td>
                                <span class="data-primary"><?php echo ucwords(htmlspecialchars($reg['nombre'])); ?></span>
                                <span class="data-secondary">
                                    <i class="bi bi-telephone"></i> <?php echo htmlspecialchars($reg['telefono'] ?? 'No registra'); ?>
                                </span>
                            </td>
                            <td><?php echo htmlspecialchars($reg['empresa'] ?? '-'); ?></td>
                            <td><?php echo htmlspecialchars($reg['motivo'] ?? '-'); ?></td>
                            <td><strong><?php echo htmlspecialchars($reg['estado']); ?></strong></td>
                            <td><?php echo htmlspecialchars($reg['tiempo_estancia'] ?? '-'); ?></td>
                            <td>
                                <i class="bi bi-eye-fill action-icon" 
                                   data-nombre="<?php echo ucwords(htmlspecialchars($reg['nombre'])); ?>"
                                   data-doc="<?php echo htmlspecialchars($reg['tipo_documento'] . ' ' . $reg['documento']); ?>"
                                   data-tel="<?php echo htmlspecialchars($reg['telefono'] ?? 'No registra'); ?>"
                                   data-emp="<?php echo htmlspecialchars($reg['empresa'] ?? 'No registra'); ?>"
                                   data-motivo="<?php echo htmlspecialchars($reg['motivo'] ?? 'No especificado'); ?>"
                                   data-visita="<?php echo htmlspecialchars($reg['responsable'] ?? 'No especificado'); ?>"
                                   data-fecha="<?php echo date('d/m/Y', strtotime($reg['fecha'])); ?>"
                                   data-ingreso="<?php echo date('h:i A', strtotime($reg['hora_ingreso'])); ?>"
                                   data-salida="<?php echo !empty($reg['hora_salida']) ? date('h:i A', strtotime($reg['hora_salida'])) : 'Aún dentro de las instalaciones'; ?>"
                                   data-estado="<?php echo htmlspecialchars($reg['estado']); ?>"
                                   data-tiempo="<?php echo htmlspecialchars($reg['tiempo_estancia'] ?? '-'); ?>"
                                   onclick="abrirModalDetalles(this)" title="Ver detalles completos"></i>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8" class="empty-state">No se encontraron registros.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="modalDetalles" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content" style="border: none; border-radius: 8px; box-shadow: 0 10px 25px rgba(0,0,0,0.1);">
      <div class="modal-header modal-header-custom">
        <h5 class="modal-title modal-title-custom" id="modalLabel">
            <i class="bi bi-person-vcard text-primary"></i> Detalles del Visitante
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body p-4">
          
          <div class="row">
              <div class="col-md-6 border-end">
                  <h6 style="color: #3aa822; font-weight: 700; margin-bottom: 15px;"><i class="bi bi-person"></i> Información Personal</h6>
                  
                  <div class="info-group">
                      <span class="info-label">Nombre Completo</span>
                      <div class="info-value" id="det-nombre"></div>
                  </div>
                  
                  <div class="row">
                      <div class="col-6 info-group">
                          <span class="info-label">Documento</span>
                          <div class="info-value" id="det-doc"></div>
                      </div>
                      <div class="col-6 info-group">
                          <span class="info-label">Teléfono</span>
                          <div class="info-value" id="det-tel"></div>
                      </div>
                  </div>

                  <div class="info-group">
                      <span class="info-label">Empresa / Institución</span>
                      <div class="info-value" id="det-emp"></div>
                  </div>
              </div>

              <div class="col-md-6 ps-md-4">
                  <h6 style="color: #3aa822; font-weight: 700; margin-bottom: 15px;"><i class="bi bi-building"></i> Detalles de la Visita</h6>
                  
                  <div class="info-group">
                      <span class="info-label">Estado Actual</span>
                      <div id="det-estado-container"></div>
                  </div>

                  <div class="info-group">
                      <span class="info-label">Motivo de Visita</span>
                      <div class="info-value" id="det-motivo"></div>
                  </div>

                  <div class="info-group">
                      <span class="info-label">Persona que Visita</span>
                      <div class="info-value" id="det-visita"></div>
                  </div>
              </div>
          </div>

          <hr style="border-color: #e2e8f0; margin: 20px 0;">

          <div class="row">
              <div class="col-md-3 col-6 info-group">
                  <span class="info-label"><i class="bi bi-calendar-event"></i> Fecha</span>
                  <div class="info-value" id="det-fecha" style="background-color: transparent; border: none; padding-left:0;"></div>
              </div>
              <div class="col-md-3 col-6 info-group">
                  <span class="info-label text-success"><i class="bi bi-box-arrow-in-right"></i> Ingreso</span>
                  <div class="info-value" id="det-ingreso" style="background-color: transparent; border: none; padding-left:0;"></div>
              </div>
              <div class="col-md-3 col-6 info-group">
                  <span class="info-label text-danger"><i class="bi bi-box-arrow-right"></i> Salida</span>
                  <div class="info-value" id="det-salida" style="background-color: transparent; border: none; padding-left:0;"></div>
              </div>
              <div class="col-md-3 col-6 info-group">
                  <span class="info-label text-primary"><i class="bi bi-clock-history"></i> Estancia</span>
                  <div class="info-value" id="det-tiempo" style="background-color: transparent; border: none; padding-left:0;"></div>
              </div>
          </div>

      </div>
      <div class="modal-footer" style="background-color: #f8fafc; border-top: 1px solid #e2e8f0;">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="font-weight: 600;">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<script>
    function limpiarFiltros() { window.location.href = "index.php?vista=personalExterno"; }
    
    // Función para abrir la ventana modal y llenarla de datos
    function abrirModalDetalles(elemento) {
        // Llenar los textos
        document.getElementById('det-nombre').innerText = elemento.getAttribute('data-nombre');
        document.getElementById('det-doc').innerText = elemento.getAttribute('data-doc');
        document.getElementById('det-tel').innerText = elemento.getAttribute('data-tel');
        document.getElementById('det-emp').innerText = elemento.getAttribute('data-emp');
        document.getElementById('det-motivo').innerText = elemento.getAttribute('data-motivo');
        document.getElementById('det-visita').innerText = elemento.getAttribute('data-visita');
        document.getElementById('det-fecha').innerText = elemento.getAttribute('data-fecha');
        document.getElementById('det-ingreso').innerText = elemento.getAttribute('data-ingreso');
        document.getElementById('det-salida').innerText = elemento.getAttribute('data-salida');
        document.getElementById('det-tiempo').innerText = elemento.getAttribute('data-tiempo');

        // Poner el estilo correcto al estado (verde si está dentro, gris si ya salió)
        let estado = elemento.getAttribute('data-estado');
        let badgeContainer = document.getElementById('det-estado-container');
        if(estado === 'Dentro') {
            badgeContainer.innerHTML = '<span class="status-badge-dentro"><i class="bi bi-circle-fill" style="font-size: 8px; margin-right: 4px;"></i>Dentro</span>';
        } else {
            badgeContainer.innerHTML = '<span class="status-badge-salio"><i class="bi bi-door-closed"></i> Salió</span>';
        }

        // Mostrar el modal usando Javascript de Bootstrap
        var modal = new bootstrap.Modal(document.getElementById('modalDetalles'));
        modal.show();
    }
</script>