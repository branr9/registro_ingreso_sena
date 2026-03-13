<?php
// Simulación de base de datos de personal externo
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

// Lógica de filtrado (Funcionalidad PHP)
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
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Personal Externo</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        /* Estilos Base */
        :root {
            --bg-color: #f4f6f9;
            --primary-green: #3aa822;
            --primary-cyan: #00bcd4;
            --text-main: #333;
            --text-muted: #777;
            --border-color: #e0e0e0;
            --card-bg: #ffffff;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--bg-color);
            margin: 0;
            padding: 0;
            color: var(--text-main);
        }

        /* Top Header */
        .top-header {
            background-color: var(--card-bg);
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid var(--border-color);
        }

        .top-header h2 {
            margin: 0;
            font-size: 18px;
            color: var(--text-main);
            font-weight: 600;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 14px;
        }

        .user-icon {
            background-color: var(--primary-green);
            color: white;
            border-radius: 50%;
            width: 30px;
            height: 30px;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 16px;
        }

        /* Main Container */
        .container {
            max-width: 1200px;
            margin: 30px auto;
            padding: 0 20px;
        }

        /* Page Title Area */
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 25px;
        }

        .title-section h1 {
            margin: 0;
            font-size: 24px;
            display: flex;
            align-items: center;
            gap: 10px;
            color: #2c3e50;
        }

        .title-section p {
            margin: 5px 0 0 45px;
            color: var(--text-muted);
            font-size: 14px;
        }

        .action-buttons {
            display: flex;
            gap: 10px;
        }

        .btn {
            padding: 8px 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: 600;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 8px;
            color: white;
            transition: opacity 0.2s;
        }

        .btn:hover { opacity: 0.9; }
        .btn-cyan { background-color: var(--primary-cyan); }
        .btn-green { background-color: var(--primary-green); }
        .btn-gray { background-color: #718096; }

        /* Cards */
        .card {
            background-color: var(--card-bg);
            border-radius: 8px;
            padding: 25px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.03);
            margin-bottom: 25px;
            border: 1px solid rgba(0,0,0,0.05);
        }

        /* Filters Form */
        .filters-form {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            align-items: flex-end;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .form-group label {
            font-size: 13px;
            font-weight: 600;
            color: #4a5568;
        }

        .form-control {
            padding: 10px 12px;
            border: 1px solid var(--border-color);
            border-radius: 5px;
            font-size: 14px;
            color: var(--text-main);
            outline: none;
            min-width: 200px;
        }

        .form-control:focus {
            border-color: var(--primary-green);
        }

        .filter-actions {
            display: flex;
            gap: 10px;
        }

        /* Table Styles */
        .table-responsive {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
        }

        th {
            text-align: left;
            padding: 12px 10px;
            color: #4a5568;
            font-weight: 700;
            border-bottom: 2px solid var(--border-color);
            white-space: nowrap;
        }

        td {
            padding: 15px 10px;
            vertical-align: top;
            border-bottom: 1px solid var(--border-color);
        }

        .data-primary {
            font-weight: 600;
            color: #2d3748;
            margin-bottom: 4px;
            display: block;
        }

        .data-secondary {
            font-size: 12px;
            color: var(--text-muted);
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .action-icon {
            color: #4c1d95; /* Morado para el icono del ojo */
            cursor: pointer;
            font-size: 18px;
        }

        .empty-state {
            text-align: center;
            padding: 30px;
            color: var(--text-muted);
        }
    </style>
</head>
<body>

    <header class="top-header">
        <h2>Registro de Personal Externo</h2>
        <div class="user-info">
            Administrador Sistema
            <div class="user-icon">
                <i class="bi bi-person-fill"></i>
            </div>
        </div>
    </header>

    <div class="container">
        <div class="page-header">
            <div class="title-section">
                <h1><i class="bi bi-people-fill"></i> Registro de Personal Externo</h1>
                <p>Control de entrada y salida de personal sin carnet (visitantes, contratistas, proveedores)</p>
            </div>
            <div class="action-buttons">
                <button class="btn btn-cyan" onclick="verPersonasDentro()">
                    <i class="bi bi-door-open"></i> Personas Dentro
                </button>
                <button class="btn btn-green" onclick="registrarEntrada()">
                    <i class="bi bi-person-plus-fill"></i> Registrar Entrada
                </button>
            </div>
        </div>

        <div class="card">
            <form class="filters-form" method="GET" action="">
                <div class="form-group">
                    <label>Buscar</label>
                    <input type="text" name="buscar" class="form-control" placeholder="Documento, nombre, empres" value="<?php echo htmlspecialchars($busqueda); ?>">
                </div>
                <div class="form-group">
                    <label>Estado</label>
                    <select name="estado" class="form-control" style="min-width: 150px;">
                        <option value="Todos" <?php echo $estado_filtro == 'Todos' ? 'selected' : ''; ?>>Todos</option>
                        <option value="Dentro" <?php echo $estado_filtro == 'Dentro' ? 'selected' : ''; ?>>Dentro</option>
                        <option value="Salió" <?php echo $estado_filtro == 'Salió' ? 'selected' : ''; ?>>Salió</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Desde</label>
                    <input type="date" name="desde" class="form-control" style="min-width: 150px;">
                </div>
                <div class="form-group">
                    <label>Hasta</label>
                    <input type="date" name="hasta" class="form-control" style="min-width: 150px;">
                </div>
                <div class="filter-actions">
                    <button type="submit" class="btn btn-green">
                        <i class="bi bi-search"></i> Buscar
                    </button>
                    <button type="button" class="btn btn-gray" onclick="limpiarFiltros()" title="Restablecer">
                        <i class="bi bi-arrow-clockwise"></i>
                    </button>
                </div>
            </form>
        </div>

        <div class="card">
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Fecha/Hora Entrada</th>
                            <th>Documento</th>
                            <th>Nombre Completo</th>
                            <th>Empresa</th>
                            <th>Motivo</th>
                            <th>Estado</th>
                            <th>Tiempo Permanencia</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($registros_filtrados) > 0): ?>
                            <?php foreach ($registros_filtrados as $reg): ?>
                                <tr>
                                    <td>
                                        <span class="data-primary"><?php echo $reg['fecha']; ?></span>
                                        <span class="data-secondary"><?php echo $reg['hora']; ?></span>
                                    </td>
                                    <td>
                                        <span class="data-primary"><?php echo $reg['documento']; ?></span>
                                        <span class="data-secondary"><?php echo $reg['tipo_doc']; ?></span>
                                    </td>
                                    <td>
                                        <span class="data-primary"><?php echo ucwords($reg['nombre']); ?></span>
                                        <span class="data-secondary"><i class="bi bi-telephone"></i> <?php echo $reg['telefono']; ?></span>
                                    </td>
                                    <td><?php echo $reg['empresa']; ?></td>
                                    <td><?php echo $reg['motivo']; ?></td>
                                    <td><strong><?php echo $reg['estado']; ?></strong></td>
                                    <td><?php echo $reg['tiempo']; ?></td>
                                    <td>
                                        <i class="bi bi-eye-fill action-icon" onclick="verDetalles('<?php echo $reg['documento']; ?>')" title="Ver detalles"></i>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="8" class="empty-state">No se encontraron registros con los filtros aplicados.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        function limpiarFiltros() {
            // Redirige a la misma página sin parámetros GET para limpiar
            window.location.href = window.location.pathname;
        }

        function registrarEntrada() {
            alert("Abriendo formulario para Registrar Nueva Entrada...");
            // Aquí iría la lógica para abrir un modal o redirigir
        }

        function verPersonasDentro() {
            // Filtrar automáticamente por estado "Dentro"
            window.location.href = "?buscar=&estado=Dentro";
        }

        function verDetalles(documento) {
            alert("Viendo detalles del documento: " + documento);
            // Aquí iría la lógica para mostrar la vista detallada
        }
    </script>
</body>
</html>