<?php
session_start();

// Validar que el usuario esté autenticado
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

require_once __DIR__ . "/models/conexion.php";

// Determinar qué sección mostrar (default: dashboard)
$seccion = $_GET['seccion'] ?? 'dashboard';
$titulo_seccion = 'Dashboard';

// Mapear secciones a títulos
$titulos = [
    'dashboard' => 'Dashboard',
    'usuarios' => 'Gestión de Usuarios',
    'control-ingreso' => 'Control de Ingreso',
    'prestamo-devolucion' => 'Préstamo de Llaves',
    'permisos-salida' => 'Permisos de Salida',
    'reportes' => 'Reportes de Acceso',
    'personal-externo' => 'Personal Externo'
];

$titulo_seccion = $titulos[$seccion] ?? 'Dashboard';

?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <title>Sistema Ingreso SENA</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            overflow-x: hidden;
        }
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: 230px;
            height: 100vh;
            background: linear-gradient(180deg, #6b4db8 0%, #5a3d9e 100%);
            padding: 0;
            box-shadow: 2px 0 5px rgba(0,0,0,0.1);
        }
        .sidebar-header {
            background-color: rgba(0,0,0,0.1);
            padding: 20px 15px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .sidebar-header i {
            font-size: 35px;
            color: #4ade80;
        }
        .sidebar-header h5 {
            color: white;
            margin: 0;
            font-size: 18px;
            font-weight: 600;
        }
        .sidebar-menu {
            list-style: none;
            padding: 0;
            margin: 20px 0;
        }
        .menu-item {
            margin: 5px 0;
        }
        .menu-item a {
            display: flex;
            align-items: center;
            padding: 15px 20px;
            color: rgba(255,255,255,0.9);
            text-decoration: none;
            transition: all 0.3s;
            gap: 12px;
            font-size: 15px;
        }
        .menu-item a:hover {
            background-color: rgba(255,255,255,0.1);
        }
        .menu-item a.active {
            background-color: #4ade80;
            color: white;
            font-weight: 600;
        }
        .menu-item i {
            font-size: 20px;
            width: 25px;
        }
        .main-content {
            margin-left: 230px;
            padding: 0;
            min-height: 100vh;
            background-color: #f5f5f5;
        }
        .top-bar {
            background-color: white;
            padding: 15px 30px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .top-bar h4 {
            margin: 0;
            color: #333;
        }
        .admin-badge {
            display: flex;
            align-items: center;
            gap: 10px;
            background-color: #4ade80;
            padding: 8px 15px;
            border-radius: 25px;
            color: white;
            font-size: 14px;
            cursor: pointer;
            border: none;
            padding: 0;
        }
        .admin-badge a {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 8px 15px;
            color: white;
            text-decoration: none;
            background-color: #4ade80;
            border-radius: 25px;
            transition: background-color 0.3s;
        }
        .admin-badge a:hover {
            background-color: #2d8a4d;
        }
        .admin-badge i {
            font-size: 18px;
        }
        .content-area {
            padding: 30px;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-header">
            <i class="bi bi-flower2"></i>
            <h5>Sistema Ingreso</h5>
        </div>
        <ul class="sidebar-menu">
            <li class="menu-item">
                <a href="?seccion=dashboard" <?php echo ($seccion === 'dashboard') ? 'class="active"' : ''; ?>>
                    <i class="bi bi-house-door"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li class="menu-item">
                <a href="?seccion=usuarios" <?php echo ($seccion === 'usuarios') ? 'class="active"' : ''; ?>>
                    <i class="bi bi-people"></i>
                    <span>Usuarios</span>
                </a>
            </li>
            <li class="menu-item">
                <a href="?seccion=control-ingreso" <?php echo ($seccion === 'control-ingreso') ? 'class="active"' : ''; ?>>
                    <i class="bi bi-grid"></i>
                    <span>Control de Ingreso</span>
                </a>
            </li>
            <li class="menu-item">
                <a href="?seccion=prestamo-devolucion" <?php echo ($seccion === 'prestamo-devolucion') ? 'class="active"' : ''; ?>>
                    <i class="bi bi-key"></i>
                    <span>Préstamo de Llaves</span>
                </a>
            </li>
            <li class="menu-item">
                <a href="?seccion=permisos-salida" <?php echo ($seccion === 'permisos-salida') ? 'class="active"' : ''; ?>>
                    <i class="bi bi-door-open"></i>
                    <span>Permisos de Salida</span>
                </a>
            </li>
            <li class="menu-item">
                <a href="?seccion=reportes" <?php echo ($seccion === 'reportes') ? 'class="active"' : ''; ?>>
                    <i class="bi bi-bar-chart"></i>
                    <span>Reportes</span>
                </a>
            </li>
            <li class="menu-item">
                <a href="?seccion=personal-externo" <?php echo ($seccion === 'personal-externo') ? 'class="active"' : ''; ?>>
                    <i class="bi bi-person-badge"></i>
                    <span>Personal Externo</span>
                </a>
            </li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Top Bar -->
        <div class="top-bar">
            <h4><?php echo htmlspecialchars($titulo_seccion); ?></h4>
            <div class="admin-badge">
                <a href="logout.php" title="Cerrar sesión">
                    <span><?php echo htmlspecialchars($_SESSION['usuario']['nombre'] ?? 'Usuario'); ?></span>
                    <i class="bi bi-box-arrow-right"></i>
                </a>
            </div>
        </div>

        <!-- Content Area - Cargar componentes dinámicamente -->
        <div class="content-area">
            <?php
                // Lógica especial para préstamo-devolucion con tabs
                if ($seccion === 'prestamo-devolucion' && isset($_GET['tab'])) {
                    $archivo_vista = __DIR__ . '/views/keyviews/prestamo_devolucion.php';
                } else {
                    // Mapear secciones a archivos de vistas
                    $vistas = [
                        'dashboard' => 'views/controldeIngreso/dashboardview.php',
                        'usuarios' => 'views/userViews/usuariosview.php',
                        'control-ingreso' => 'views/controldeIngreso/controldeIngresoview.php',
                        'prestamo-devolucion' => 'views/keyviews/keyviews.php',
                        'permisos-salida' => 'views/Permisos/permisosview.php',
                        'reportes' => 'views/reportes/reportesview.php',
                        'personal-externo' => 'views/personalExterno/personalExternoview.php'
                    ];

                    $archivo_vista = isset($vistas[$seccion]) ? __DIR__ . '/' . $vistas[$seccion] : null;
                }

                if ($archivo_vista && file_exists($archivo_vista)) {
                    include $archivo_vista;
                } else {
                    echo '<div class="alert alert-danger">Error: No se pudo cargar la vista.</div>';
                }
            ?>
        </div>
    </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
<script>
    // Evento para logout
    document.addEventListener('DOMContentLoaded', function() {
        // Aquí puedes agregar más funcionalidades si es necesario
    });
</script>
</body>
</html>