<?php
// reportesdeacceso.php
// Verificar sesión de administrador (ajusta según tu sistema de autenticación)
// session_start();
// if (!isset($_SESSION['usuario'])) { header('Location: index.php'); exit; }

$fechaInicio = isset($_GET['fecha_inicio']) ? $_GET['fecha_inicio'] : date('Y-m-d', strtotime('-30 days'));
$fechaFin    = isset($_GET['fecha_fin'])    ? $_GET['fecha_fin']    : date('Y-m-d');
$documento   = isset($_GET['documento'])    ? trim($_GET['documento']) : '';
$tabActiva   = isset($_GET['tab'])          ? $_GET['tab'] : 'accesos';
$consultado  = isset($_GET['consultar']);

// ── Base de datos ────────────────────────────────────────────────────────────
// require_once '../models/conexion.php';   // Activa tu conexión real

$registrosAccesos  = [];
$registrosLlaves   = [];

if ($consultado) {
    /*
    // Ejemplo de consulta real para Accesos:
    $sql = "SELECT u.nombre, u.apellido, u.documento, ci.fecha_entrada, ci.fecha_salida, ci.estado
            FROM control_ingreso ci
            INNER JOIN usuarios u ON ci.id_usuario = u.id
            WHERE ci.fecha_entrada BETWEEN ? AND ?";
    if ($documento !== '') $sql .= " AND u.documento = ?";
    $sql .= " ORDER BY ci.fecha_entrada DESC";
    // ... preparar y ejecutar ...

    // Ejemplo de consulta real para Llaves:
    $sql2 = "SELECT u.nombre, u.apellido, u.documento, pl.llave, pl.fecha_prestamo, pl.fecha_devolucion, pl.estado
             FROM prestamos_llaves pl
             INNER JOIN usuarios u ON pl.id_usuario = u.id
             WHERE pl.fecha_prestamo BETWEEN ? AND ?";
    // ...
    */

    // ── Datos de demostración (elimina cuando conectes la BD) ────────────────
    $registrosAccesos = [
        ['nombre'=>'Ana Gómez',     'documento'=>'1020304050', 'entrada'=>'2026-02-10 07:45', 'salida'=>'2026-02-10 17:30', 'estado'=>'Completado'],
        ['nombre'=>'Carlos Ruiz',   'documento'=>'1030405060', 'entrada'=>'2026-02-11 08:00', 'salida'=>'',                 'estado'=>'En instalaciones'],
        ['nombre'=>'María Torres',  'documento'=>'1040506070', 'entrada'=>'2026-02-12 09:15', 'salida'=>'2026-02-12 16:00', 'estado'=>'Completado'],
        ['nombre'=>'Luis Herrera',  'documento'=>'1050607080', 'entrada'=>'2026-02-13 07:30', 'salida'=>'2026-02-13 18:00', 'estado'=>'Completado'],
        ['nombre'=>'Sandra Mora',   'documento'=>'1060708090', 'entrada'=>'2026-02-14 08:45', 'salida'=>'',                 'estado'=>'En instalaciones'],
    ];
    $registrosLlaves = [
        ['nombre'=>'Carlos Ruiz',   'documento'=>'1030405060', 'llave'=>'Sala 201',  'prestamo'=>'2026-02-11 08:05', 'devolucion'=>'2026-02-11 12:00', 'estado'=>'Devuelta'],
        ['nombre'=>'Sandra Mora',   'documento'=>'1060708090', 'llave'=>'Bodega 3',  'prestamo'=>'2026-02-14 09:00', 'devolucion'=>'',                  'estado'=>'Pendiente'],
        ['nombre'=>'Ana Gómez',     'documento'=>'1020304050', 'llave'=>'Oficina A', 'prestamo'=>'2026-02-10 08:00', 'devolucion'=>'2026-02-10 17:00', 'estado'=>'Devuelta'],
    ];

    // Filtrar por documento si se ingresó
    if ($documento !== '') {
        $registrosAccesos = array_filter($registrosAccesos, fn($r) => $r['documento'] === $documento);
        $registrosLlaves  = array_filter($registrosLlaves,  fn($r) => $r['documento'] === $documento);
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reportes de Acceso – Sistema Ingreso</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        /* ── Reset & Variables ─────────────────────────────────────────────── */
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --sidebar-bg   : #6a3fac;
            --sidebar-hover: #7b4fc0;
            --sidebar-act  : #4caf50;
            --accent       : #4caf50;
            --accent-dark  : #388e3c;
            --accent-light : #e8f5e9;
            --white        : #ffffff;
            --bg           : #f4f6f9;
            --border       : #dee2e6;
            --text         : #333333;
            --text-light   : #6c757d;
            --danger       : #e53935;
            --warning      : #f57c00;
            --sidebar-w    : 220px;
            --header-h     : 56px;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: var(--bg);
            color: var(--text);
            display: flex;
            min-height: 100vh;
        }

        /* ── Sidebar ───────────────────────────────────────────────────────── */
        .sidebar {
            width: var(--sidebar-w);
            background: var(--sidebar-bg);
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0; left: 0;
            height: 100vh;
            z-index: 100;
            box-shadow: 2px 0 8px rgba(0,0,0,.25);
        }

        .sidebar-brand {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 18px 20px;
            color: var(--white);
            font-size: 1.05rem;
            font-weight: 700;
            border-bottom: 1px solid rgba(255,255,255,.15);
        }

        .sidebar-brand .brand-icon {
            width: 38px; height: 38px;
            background: rgba(255,255,255,.15);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.3rem;
            color: var(--accent);
        }

        .sidebar-nav { flex: 1; padding: 10px 0; overflow-y: auto; }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 13px 22px;
            color: rgba(255,255,255,.85);
            text-decoration: none;
            font-size: .92rem;
            transition: background .2s, color .2s;
            cursor: pointer;
        }
        .nav-item:hover { background: var(--sidebar-hover); color: var(--white); }
        .nav-item.active {
            background: var(--accent);
            color: var(--white);
            font-weight: 600;
        }
        .nav-item i { width: 20px; text-align: center; font-size: 1rem; }

        .sidebar-footer {
            border-top: 1px solid rgba(255,255,255,.15);
            padding: 14px 20px;
        }
        .user-info {
            display: flex; align-items: center; gap: 10px;
            color: var(--white);
            margin-bottom: 12px;
        }
        .user-avatar {
            width: 36px; height: 36px; border-radius: 50%;
            background: var(--accent);
            display: flex; align-items: center; justify-content: center;
            font-size: 1rem; flex-shrink: 0;
        }
        .user-name  { font-size: .88rem; font-weight: 600; line-height: 1.2; }
        .user-role  { font-size: .74rem; opacity: .7; }
        .btn-logout {
            display: flex; align-items: center; gap: 8px;
            color: #ff7043;
            font-size: .88rem;
            text-decoration: none;
            transition: opacity .2s;
        }
        .btn-logout:hover { opacity: .75; }

        /* ── Top Bar ───────────────────────────────────────────────────────── */
        .topbar {
            position: fixed;
            top: 0; left: var(--sidebar-w);
            right: 0; height: var(--header-h);
            background: var(--white);
            border-bottom: 1px solid var(--border);
            display: flex; align-items: center; justify-content: space-between;
            padding: 0 28px;
            z-index: 90;
            box-shadow: 0 1px 4px rgba(0,0,0,.08);
        }
        .topbar-title { font-size: 1rem; font-weight: 600; color: var(--text); }
        .topbar-user  {
            display: flex; align-items: center; gap: 10px;
            font-size: .9rem; color: var(--text);
        }
        .topbar-user .avatar {
            width: 34px; height: 34px; border-radius: 50%;
            background: var(--accent);
            display: flex; align-items: center; justify-content: center;
            color: var(--white); font-size: 1rem;
        }

        /* ── Main Content ──────────────────────────────────────────────────── */
        .main {
            margin-left: var(--sidebar-w);
            padding-top: calc(var(--header-h) + 28px);
            padding-right: 28px;
            padding-left: 28px;
            padding-bottom: 40px;
            flex: 1;
            min-height: 100vh;
        }

        .page-header { margin-bottom: 24px; }
        .page-header h1 {
            font-size: 1.6rem;
            font-weight: 700;
            color: var(--text);
            display: flex; align-items: center; gap: 10px;
        }
        .page-header h1 i { color: var(--accent); font-size: 1.4rem; }
        .page-header p  { font-size: .88rem; color: var(--text-light); margin-top: 4px; }

        /* ── Card ──────────────────────────────────────────────────────────── */
        .card {
            background: var(--white);
            border-radius: 10px;
            box-shadow: 0 1px 6px rgba(0,0,0,.07);
            padding: 24px;
            margin-bottom: 22px;
        }

        .section-title {
            font-size: .95rem; font-weight: 700;
            color: var(--text);
            display: flex; align-items: center; gap: 8px;
            margin-bottom: 18px;
        }
        .section-title i { color: var(--accent); }

        /* ── Filtros ───────────────────────────────────────────────────────── */
        .filters-grid {
            display: grid;
            grid-template-columns: 1fr 1fr 1.4fr auto;
            gap: 16px;
            align-items: end;
        }
        .form-group label {
            display: flex; align-items: center; gap: 6px;
            font-size: .82rem; font-weight: 600;
            color: var(--text); margin-bottom: 6px;
        }
        .form-group label i { color: var(--text-light); }
        .form-control {
            width: 100%;
            padding: 9px 12px;
            border: 1px solid var(--border);
            border-radius: 6px;
            font-size: .88rem;
            color: var(--text);
            background: var(--white);
            transition: border-color .2s, box-shadow .2s;
            height: 40px;
        }
        .form-control:focus {
            outline: none;
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(76,175,80,.15);
        }

        .btn-consultar {
            display: inline-flex; align-items: center; gap: 7px;
            background: var(--accent);
            color: var(--white);
            border: none; border-radius: 6px;
            padding: 0 20px; height: 40px;
            font-size: .9rem; font-weight: 600;
            cursor: pointer;
            transition: background .2s, transform .1s, box-shadow .2s;
            white-space: nowrap;
        }
        .btn-consultar:hover {
            background: var(--accent-dark);
            box-shadow: 0 3px 10px rgba(76,175,80,.35);
        }
        .btn-consultar:active { transform: scale(.97); }

        /* ── Resultados ────────────────────────────────────────────────────── */
        .export-bar {
            display: flex; align-items: center; gap: 10px;
            margin-bottom: 16px; flex-wrap: wrap;
        }
        .btn-export {
            display: inline-flex; align-items: center; gap: 7px;
            padding: 7px 16px;
            border: 1px solid var(--border);
            border-radius: 6px;
            background: var(--white);
            font-size: .84rem; font-weight: 600;
            color: var(--text);
            cursor: pointer;
            transition: background .2s, border-color .2s;
            text-decoration: none;
        }
        .btn-export:hover { background: var(--bg); border-color: #aaa; }
        .btn-export .ico-excel { color: #1d6f42; }
        .btn-export .ico-pdf   { color: #c62828; }

        /* ── Tabs ──────────────────────────────────────────────────────────── */
        .tabs { display: flex; border-bottom: 2px solid var(--border); margin-bottom: 20px; }
        .tab-link {
            display: inline-flex; align-items: center; gap: 7px;
            padding: 10px 18px;
            font-size: .88rem; font-weight: 600;
            color: var(--text-light);
            text-decoration: none;
            border-bottom: 3px solid transparent;
            margin-bottom: -2px;
            transition: color .2s, border-color .2s;
        }
        .tab-link:hover  { color: var(--accent); }
        .tab-link.active { color: var(--accent); border-bottom-color: var(--accent); }

        /* ── Tabla ─────────────────────────────────────────────────────────── */
        .table-wrapper { overflow-x: auto; }
        table {
            width: 100%; border-collapse: collapse;
            font-size: .86rem;
        }
        thead th {
            background: var(--bg);
            padding: 10px 14px;
            text-align: left;
            font-size: .8rem; font-weight: 700;
            color: var(--text-light);
            text-transform: uppercase;
            letter-spacing: .04em;
            border-bottom: 2px solid var(--border);
        }
        tbody tr { transition: background .15s; }
        tbody tr:hover { background: #f9fbf9; }
        tbody td {
            padding: 11px 14px;
            border-bottom: 1px solid #f0f0f0;
            color: var(--text);
        }

        .badge {
            display: inline-block;
            padding: 3px 10px; border-radius: 20px;
            font-size: .75rem; font-weight: 600;
        }
        .badge-success { background: #e8f5e9; color: #2e7d32; }
        .badge-warning { background: #fff3e0; color: #e65100; }

        /* ── Estado vacío ──────────────────────────────────────────────────── */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: var(--text-light);
        }
        .empty-state i {
            font-size: 3.5rem; opacity: .25;
            display: block; margin-bottom: 14px;
        }
        .empty-state p { font-size: .92rem; }

        /* ── Responsive ────────────────────────────────────────────────────── */
        @media (max-width: 900px) {
            .filters-grid { grid-template-columns: 1fr 1fr; }
        }
        @media (max-width: 600px) {
            .filters-grid { grid-template-columns: 1fr; }
            .sidebar { display: none; }
            .topbar  { left: 0; }
            .main    { margin-left: 0; padding: 80px 14px 30px; }
        }
    </style>
</head>
<body>

<!-- ══════════════════════════════════════════════════════════════════════════
     SIDEBAR
════════════════════════════════════════════════════════════════════════════ -->
<aside class="sidebar">
    <div class="sidebar-brand">
        <div class="brand-icon"><i class="fa-solid fa-seedling"></i></div>
        Sistema Ingreso
    </div>

    <nav class="sidebar-nav">
        <a class="nav-item" href="dashboard.php">
            <i class="fa-solid fa-house"></i> Dashboard
        </a>
        <a class="nav-item" href="userview.php">
            <i class="fa-solid fa-users"></i> Usuarios
        </a>
        <a class="nav-item" href="controlingreso.php">
            <i class="fa-solid fa-table-cells-large"></i> Control de Ingreso
        </a>
        <a class="nav-item" href="controllaves.php">
            <i class="fa-solid fa-key"></i> Control de Llaves
        </a>
        <a class="nav-item" href="permisossalida.php">
            <i class="fa-solid fa-file-signature"></i> Permisos de Salida
        </a>
        <a class="nav-item active" href="reportesdeacceso.php">
            <i class="fa-solid fa-chart-bar"></i> Reportes
        </a>
        <a class="nav-item" href="personalexterno.php">
            <i class="fa-solid fa-id-card"></i> Personal Externo
        </a>
    </nav>

    <div class="sidebar-footer">
        <div class="user-info">
            <div class="user-avatar"><i class="fa-solid fa-user"></i></div>
            <div>
                <div class="user-name">Administrador Sistema</div>
                <div class="user-role">ADMIN</div>
            </div>
        </div>
        <a class="btn-logout" href="logout.php">
            <i class="fa-solid fa-right-from-bracket"></i> Salir
        </a>
    </div>
</aside>

<!-- ══════════════════════════════════════════════════════════════════════════
     TOP BAR
════════════════════════════════════════════════════════════════════════════ -->
<header class="topbar">
    <span class="topbar-title">Reportes de Acceso</span>
    <div class="topbar-user">
        <span>Administrador Sistema</span>
        <div class="avatar"><i class="fa-solid fa-user"></i></div>
    </div>
</header>

<!-- ══════════════════════════════════════════════════════════════════════════
     MAIN CONTENT
════════════════════════════════════════════════════════════════════════════ -->
<main class="main">

    <!-- Encabezado de página -->
    <div class="page-header">
        <h1><i class="fa-solid fa-chart-bar"></i> Reportes de Acceso</h1>
        <p>Consulta y exporta los registros de entrada y salida</p>
    </div>

    <!-- ── Filtros de Búsqueda ─────────────────────────────────────────── -->
    <div class="card">
        <div class="section-title">
            <i class="fa-solid fa-filter"></i> Filtros de Búsqueda
        </div>

        <form method="GET" action="">
            <div class="filters-grid">

                <div class="form-group">
                    <label><i class="fa-regular fa-calendar"></i> Fecha Inicio</label>
                    <input
                        class="form-control"
                        type="date"
                        name="fecha_inicio"
                        value="<?= htmlspecialchars($fechaInicio) ?>"
                        required>
                </div>

                <div class="form-group">
                    <label><i class="fa-regular fa-calendar"></i> Fecha Fin</label>
                    <input
                        class="form-control"
                        type="date"
                        name="fecha_fin"
                        value="<?= htmlspecialchars($fechaFin) ?>"
                        required>
                </div>

                <div class="form-group">
                    <label><i class="fa-regular fa-id-card"></i> Documento (Opcional)</label>
                    <input
                        class="form-control"
                        type="text"
                        name="documento"
                        placeholder="Buscar por documento"
                        value="<?= htmlspecialchars($documento) ?>">
                </div>

                <div class="form-group">
                    <label style="visibility:hidden">Acción</label>
                    <button type="submit" name="consultar" class="btn-consultar">
                        <i class="fa-solid fa-magnifying-glass"></i> Consultar
                    </button>
                </div>

            </div>
            <!-- Mantener tab activa al reenviar -->
            <input type="hidden" name="tab" value="<?= htmlspecialchars($tabActiva) ?>">
        </form>
    </div>

    <!-- ── Resultados ─────────────────────────────────────────────────── -->
    <div class="card">
        <div class="section-title">
            <i class="fa-solid fa-list"></i> Resultados
        </div>

        <!-- Botones de exportación -->
        <div class="export-bar">
            <a class="btn-export"
               href="?<?= http_build_query(array_merge($_GET, ['exportar'=>'excel'])) ?>"
               title="Exportar a Excel">
                <i class="fa-regular fa-file-excel ico-excel"></i> Exportar Excel
            </a>
            <a class="btn-export"
               href="?<?= http_build_query(array_merge($_GET, ['exportar'=>'pdf'])) ?>"
               title="Exportar a PDF">
                <i class="fa-regular fa-file-pdf ico-pdf"></i> Exportar PDF
            </a>
        </div>

        <!-- Tabs -->
        <div class="tabs">
            <a class="tab-link <?= $tabActiva === 'accesos' ? 'active' : '' ?>"
               href="?<?= http_build_query(array_merge($_GET, ['tab'=>'accesos'])) ?>">
                <i class="fa-solid fa-right-to-bracket"></i> Accesos
            </a>
            <a class="tab-link <?= $tabActiva === 'llaves' ? 'active' : '' ?>"
               href="?<?= http_build_query(array_merge($_GET, ['tab'=>'llaves'])) ?>">
                <i class="fa-solid fa-key"></i> Préstamos de Llaves
            </a>
        </div>

        <!-- ── TAB: Accesos ─────────────────────────────────────────── -->
        <?php if ($tabActiva === 'accesos'): ?>
            <?php if (!$consultado): ?>
                <div class="empty-state">
                    <i class="fa-solid fa-chart-line"></i>
                    <p>Seleccione un rango de fechas y haga clic en Consultar</p>
                </div>
            <?php elseif (empty($registrosAccesos)): ?>
                <div class="empty-state">
                    <i class="fa-solid fa-circle-xmark"></i>
                    <p>No se encontraron registros para los filtros seleccionados.</p>
                </div>
            <?php else: ?>
                <div class="table-wrapper">
                    <table>
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nombre</th>
                                <th>Documento</th>
                                <th>Fecha Entrada</th>
                                <th>Fecha Salida</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = 1; foreach ($registrosAccesos as $reg): ?>
                            <tr>
                                <td><?= $i++ ?></td>
                                <td><?= htmlspecialchars($reg['nombre']) ?></td>
                                <td><?= htmlspecialchars($reg['documento']) ?></td>
                                <td><?= htmlspecialchars($reg['entrada']) ?></td>
                                <td><?= $reg['salida'] ? htmlspecialchars($reg['salida']) : '<span style="color:#aaa">—</span>' ?></td>
                                <td>
                                    <?php if ($reg['estado'] === 'Completado'): ?>
                                        <span class="badge badge-success">Completado</span>
                                    <?php else: ?>
                                        <span class="badge badge-warning">En instalaciones</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>

        <!-- ── TAB: Préstamos de Llaves ────────────────────────────── -->
        <?php else: ?>
            <?php if (!$consultado): ?>
                <div class="empty-state">
                    <i class="fa-solid fa-key"></i>
                    <p>Seleccione un rango de fechas y haga clic en Consultar</p>
                </div>
            <?php elseif (empty($registrosLlaves)): ?>
                <div class="empty-state">
                    <i class="fa-solid fa-circle-xmark"></i>
                    <p>No se encontraron préstamos de llaves para los filtros seleccionados.</p>
                </div>
            <?php else: ?>
                <div class="table-wrapper">
                    <table>
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nombre</th>
                                <th>Documento</th>
                                <th>Llave / Área</th>
                                <th>Fecha Préstamo</th>
                                <th>Fecha Devolución</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = 1; foreach ($registrosLlaves as $reg): ?>
                            <tr>
                                <td><?= $i++ ?></td>
                                <td><?= htmlspecialchars($reg['nombre']) ?></td>
                                <td><?= htmlspecialchars($reg['documento']) ?></td>
                                <td><?= htmlspecialchars($reg['llave']) ?></td>
                                <td><?= htmlspecialchars($reg['prestamo']) ?></td>
                                <td><?= $reg['devolucion'] ? htmlspecialchars($reg['devolucion']) : '<span style="color:#aaa">—</span>' ?></td>
                                <td>
                                    <?php if ($reg['estado'] === 'Devuelta'): ?>
                                        <span class="badge badge-success">Devuelta</span>
                                    <?php else: ?>
                                        <span class="badge badge-warning">Pendiente</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        <?php endif; ?>

    </div><!-- /card resultados -->

</main>
</body>
</html>