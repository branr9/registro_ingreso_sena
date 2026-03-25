<?php
// Simulación de base de datos para la tabla
?>
<!-- Personal Externo Content -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- Bootstrap Icons -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
<!-- Google Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<div style="font-family: 'Nunito', sans-serif;">
<?php
$registros_db = [
    [
        'fecha' => '27/02/2026', 'hora' => '06:12 PM',
        'documento' => '1236486', 'tipo_doc' => 'CE',
        'nombre' => 'yirlene perez', 'telefono' => '21562',
        'empresa' => 'centro aguas', 'motivo' => 'revisar acueducto',
        'estado' => 'Salió', 'tiempo' => '0h 1m'
    ],
    [
        'fecha' => '20/02/2026', 'hora' => '08:18 PM',
        'documento' => '1234569', 'tipo_doc' => 'CC',
        'nombre' => 'merian lopez', 'telefono' => '12364',
        'empresa' => 'celcia', 'motivo' => 'revision',
        'estado' => 'Salió', 'tiempo' => '-'
    ]
];

$busqueda = isset($_GET['buscar']) ? strtolower($_GET['buscar']) : '';
$estado_filtro = isset($_GET['estado']) ? $_GET['estado'] : 'Todos';

$registros_filtrados = [];
foreach ($registros_db as $reg) {
    $coincide_busqueda = empty($busqueda) || 
                         strpos(strtolower($reg['nombre']), $busqueda) !== false || 
                         strpos(strtolower($reg['documento']), $busqueda) !== false ||
                         strpos(strtolower($reg['empresa']), $busqueda) !== false;
                         
    $coincide_estado = ($estado_filtro === 'Todos') || ($reg['estado'] === $estado_filtro);

    if ($coincide_busqueda && $coincide_estado) {
        $registros_filtrados[] = $reg;
    }
}
?>

<style>
    /* Estilos exclusivos de esta vista */
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
    .action-icon { color: #1e40af; cursor: pointer; font-size: 16px; font-weight:bold; }
    .empty-state { text-align: center; padding: 20px; color: #a0aec0; }
</style>

<div class="page-header-custom">
    <div class="title-section">
        <h2><i class="bi bi-people-fill"></i> Registro de Personal Externo</h2>
        <p>Control de entrada y salida de personal sin carnet (visitantes, contratistas, proveedores)</p>
    </div>
    <div class="action-buttons">
        <button class="btn-custom btn-cyan" onclick="verPersonasDentro()"><i class="bi bi-door-open"></i> Personas Dentro</button>
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
            <input type="text" name="buscar" class="form-control-custom" placeholder="Documento, nombre..." value="<?php echo htmlspecialchars($busqueda); ?>">
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
            <input type="date" name="desde" class="form-control-custom" style="min-width: 150px;">
        </div>
        <div class="form-group-custom">
            <label>Hasta</label>
            <input type="date" name="hasta" class="form-control-custom" style="min-width: 150px;">
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
                    <th>Fecha/Hora Entrada</th><th>Documento</th><th>Nombre Completo</th><th>Empresa</th><th>Motivo</th><th>Estado</th><th>Tiempo</th><th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($registros_filtrados) > 0): ?>
                    <?php foreach ($registros_filtrados as $reg): ?>
                        <tr>
                            <td><span class="data-primary"><?php echo $reg['fecha']; ?></span><span class="data-secondary"><?php echo $reg['hora']; ?></span></td>
                            <td><span class="data-primary"><?php echo $reg['documento']; ?></span><span class="data-secondary"><?php echo $reg['tipo_doc']; ?></span></td>
                            <td><span class="data-primary"><?php echo ucwords($reg['nombre']); ?></span><span class="data-secondary"><i class="bi bi-telephone"></i> <?php echo $reg['telefono']; ?></span></td>
                            <td><?php echo $reg['empresa']; ?></td>
                            <td><?php echo $reg['motivo']; ?></td>
                            <td><strong><?php echo $reg['estado']; ?></strong></td>
                            <td><?php echo $reg['tiempo']; ?></td>
                            <td><i class="bi bi-eye-fill action-icon" onclick="verDetalles('<?php echo $reg['documento']; ?>')"></i></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="8" class="empty-state">No se encontraron registros.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
    function limpiarFiltros() { window.location.href = "index.php?vista=personalExterno"; }
    function registrarEntrada() { alert("Abriendo formulario para Registrar Nueva Entrada..."); }
    function verPersonasDentro() { window.location.href = "index.php?vista=personalExterno&buscar=&estado=Dentro"; }
    function verDetalles(documento) { alert("Viendo detalles del documento: " + documento); }
</script>
</div>