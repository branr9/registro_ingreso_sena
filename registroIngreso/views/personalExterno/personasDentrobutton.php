<?php
// ==========================================
// 1. LLAMAR A LA CONEXIÓN (conexion.php)
// ==========================================
// Al estar incluido desde index.php, la ruta parte desde la raíz
include_once "models/conexion.php"; 

// ==========================================
// 2. CONSULTAR SÓLO LOS QUE ESTÁN 'DENTRO' (Con MySQLi)
// ==========================================
$sql = "SELECT * FROM personal_externo WHERE estado = 'Dentro' ORDER BY fecha DESC, hora_ingreso DESC";
$resultado = mysqli_query($conexion, $sql);

$personas_dentro = [];

if ($resultado) {
    // Extraer todos los registros en un arreglo asociativo
    $personas_dentro = mysqli_fetch_all($resultado, MYSQLI_ASSOC);
} else {
    // Si hay un error en la consulta, lo mostramos
    die("<div class='alert alert-danger m-3'>Error en la consulta SQL: " . mysqli_error($conexion) . "</div>");
}

// Contar cuántas personas hay dentro
$cantidad_dentro = count($personas_dentro);
?>

<style>
    /* ==========================================
       ESTILOS DE LA VISTA "PERSONAS DENTRO"
       ========================================== */
    .header-dentro {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-bottom: 5px;
    }

    .btn-volver-gray {
        background-color: #6b7280; 
        color: white;
        padding: 6px 14px;
        border-radius: 4px;
        text-decoration: none;
        font-weight: 600;
        font-size: 14px;
        display: flex;
        align-items: center;
        gap: 6px;
        transition: 0.2s ease;
        border: none;
    }
    .btn-volver-gray:hover { background-color: #4b5563; color: white; }

    .title-main-dentro {
        margin: 0;
        font-size: 24px;
        color: #1e293b;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    /* Cuadro verde de contador de personas */
    .badge-count {
        background-color: #22c55e; 
        color: white;
        padding: 4px 14px;
        border-radius: 20px;
        font-size: 15px;
        font-weight: 600;
        margin-left: 5px;
        display: inline-block;
    }

    .subtitle-dentro {
        margin: 5px 0 25px 95px; 
        color: #64748b;
        font-size: 14px;
    }

    /* Tarjeta Blanca Principal */
    .card-dentro {
        background-color: #ffffff;
        border-radius: 8px;
        padding: 25px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        border: 1px solid #e2e8f0;
    }

    /* Tabla */
    .table-wrapper { overflow-x: auto; }
    .table-dentro { width: 100%; border-collapse: collapse; font-size: 14px; }
    .table-dentro th { 
        text-align: left; 
        padding: 15px 10px; 
        color: #1e293b; 
        font-weight: 700; 
        border-bottom: 1px solid #e2e8f0; 
        white-space: nowrap; 
    }
    .table-dentro td { 
        padding: 15px 10px; 
        vertical-align: middle; 
        border-bottom: 1px solid #f8fafc; 
        color: #475569; 
    }

    .text-bold { font-weight: 600; color: #334155; }
    
    /* Icono de Acción (Ojo Azul) */
    .action-icon { color: #1e40af; cursor: pointer; font-size: 18px; font-weight:bold; transition: 0.2s; }
    .action-icon:hover { color: #1e3a8a; transform: scale(1.1); }

    /* ==========================================
       ESTADO VACÍO (0 PERSONAS DENTRO)
       ========================================== */
    .empty-state {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 50px 20px;
    }
    .empty-icon-circle {
        background-color: #22c55e;
        color: white;
        width: 48px;
        height: 48px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 28px;
        margin-bottom: 15px;
    }
    .empty-state p {
        color: #64748b;
        font-size: 15px;
        font-weight: 600;
        margin: 0;
    }

    /* ==========================================
       ESTILOS DEL MODAL (VENTANA EMERGENTE)
       ========================================== */
    .modal-header-custom { background-color: #f8fafc; border-bottom: 1px solid #e2e8f0; border-radius: 8px 8px 0 0; }
    .modal-title-custom { color: #1e293b; font-weight: 700; font-size: 18px; display: flex; align-items: center; gap: 8px; }
    .info-group { margin-bottom: 15px; }
    .info-label { font-size: 12px; color: #64748b; font-weight: 600; text-transform: uppercase; margin-bottom: 4px; display: block; }
    .info-value { font-size: 15px; color: #1e293b; font-weight: 500; background-color: #f8fafc; padding: 8px 12px; border-radius: 6px; border: 1px solid #e2e8f0; }
    .status-badge-dentro { background-color: #dcfce7; color: #166534; padding: 4px 10px; border-radius: 20px; font-weight: 600; font-size: 12px; }
    .status-badge-salio { background-color: #f1f5f9; color: #475569; padding: 4px 10px; border-radius: 20px; font-weight: 600; font-size: 12px; }
</style>

<div class="header-dentro">
    <a href="index.php?vista=personalExterno" class="btn-volver-gray">
        <i class="bi bi-arrow-left"></i> Volver
    </a>
    <h2 class="title-main-dentro">
        <i class="bi bi-door-open-fill"></i> Personal Externo Dentro 
        <span class="badge-count"><?php echo $cantidad_dentro; ?> Personas</span>
    </h2>
</div>
<p class="subtitle-dentro">Lista de personal externo actualmente dentro de las instalaciones</p>

<div class="card-dentro">
    <div class="table-wrapper">
        <table class="table-dentro">
            <thead>
                <tr>
                    <th>Hora Entrada</th>
                    <th>Documento</th>
                    <th>Nombre Completo</th>
                    <th>Empresa</th>
                    <th>Motivo</th>
                    <th>Persona Visitada</th>
                    <th>Tiempo</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($cantidad_dentro > 0): ?>
                    <?php foreach ($personas_dentro as $reg): ?>
                        <tr>
                            <td class="text-bold"><?php echo date('h:i A', strtotime($reg['hora_ingreso'])); ?></td>
                            <td>
                                <span class="text-bold d-block"><?php echo htmlspecialchars($reg['documento']); ?></span>
                                <span style="font-size: 11px; color:#94a3b8;"><?php echo htmlspecialchars($reg['tipo_documento']); ?></span>
                            </td>
                            <td class="text-bold"><?php echo ucwords(htmlspecialchars($reg['nombre'])); ?></td>
                            <td><?php echo htmlspecialchars($reg['empresa'] ?? '-'); ?></td>
                            <td><?php echo htmlspecialchars($reg['motivo'] ?? '-'); ?></td>
                            <td><?php echo htmlspecialchars($reg['responsable'] ?? '-'); ?></td>
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
                                   data-salida="Aún dentro de las instalaciones"
                                   data-estado="<?php echo htmlspecialchars($reg['estado']); ?>"
                                   data-tiempo="<?php echo htmlspecialchars($reg['tiempo_estancia'] ?? '-'); ?>"
                                   onclick="abrirModalDetalles(this)" title="Ver detalles completos"></i>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8" style="border: none;">
                            <div class="empty-state">
                                <div class="empty-icon-circle">
                                    <i class="bi bi-check-lg"></i>
                                </div>
                                <p>No hay personal externo dentro en este momento</p>
                            </div>
                        </td>
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

        // Poner el estilo correcto al estado (en esta vista siempre será 'Dentro')
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