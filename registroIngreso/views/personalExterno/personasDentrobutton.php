<?php
// ==========================================
// 1. CONEXIÓN A LA BASE DE DATOS NEXUS
// ==========================================
$host = '127.0.0.1';
$dbname = 'nexus';
$username = 'root'; 
$password = 'root'; // Pon 'root' aquí si tu base de datos tiene esa contraseña

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("<div class='alert alert-danger m-3'>Error de conexión a la BD: " . $e->getMessage() . "</div>");
}

// ==========================================
// 2. CONSULTAR SÓLO LOS QUE ESTÁN 'DENTRO'
// ==========================================
$sql = "SELECT * FROM personal_externo WHERE estado = 'Dentro' ORDER BY fecha DESC, hora_ingreso DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$personas_dentro = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Contar cuántas personas hay
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
        background-color: #6b7280; /* Gris azulado idéntico a la imagen */
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
        background-color: #22c55e; /* Verde brillante */
        color: white;
        padding: 4px 14px;
        border-radius: 20px;
        font-size: 15px;
        font-weight: 600;
        margin-left: 5px;
        display: inline-block;
    }

    .subtitle-dentro {
        margin: 5px 0 25px 95px; /* Alineado al texto del título, saltando el botón */
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
                                <i class="bi bi-eye-fill" style="color: #1e40af; cursor: pointer; font-size: 18px;" onclick="verDetalles('<?php echo htmlspecialchars($reg['documento']); ?>')"></i>
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

<script>
    function verDetalles(documento) {
        alert("Viendo detalles del documento: " + documento);
    }
</script>