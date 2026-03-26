<?php
// Capturamos qué vista quiere ver el usuario
$vista = isset($_GET['vista']) ? $_GET['vista'] : 'dashboard';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <title>Sistema Ingreso SENA</title>
    <style>
        body { margin: 0; padding: 0; overflow-x: hidden; background-color: #f5f5f5; }
        .sidebar { position: fixed; top: 0; left: 0; width: 230px; height: 100vh; background: linear-gradient(180deg, #6b4db8 0%, #5a3d9e 100%); padding: 0; box-shadow: 2px 0 5px rgba(0,0,0,0.1); z-index: 1000; }
        .sidebar-header { background-color: rgba(0,0,0,0.1); padding: 20px 15px; display: flex; align-items: center; gap: 10px; }
        .sidebar-header i { font-size: 35px; color: #4ade80; }
        .sidebar-header h5 { color: white; margin: 0; font-size: 18px; font-weight: 600; }
        .sidebar-menu { list-style: none; padding: 0; margin: 20px 0; }
        .menu-item { margin: 5px 0; }
        .menu-item a { display: flex; align-items: center; padding: 15px 20px; color: rgba(255,255,255,0.9); text-decoration: none; transition: all 0.3s; gap: 12px; font-size: 15px; }
        .menu-item a:hover { background-color: rgba(255,255,255,0.1); }
        .menu-item a.active { background-color: #4ade80; color: white; font-weight: 600; }
        .menu-item i { font-size: 20px; width: 25px; }
        .main-content { margin-left: 230px; padding: 0; min-height: 100vh; }
        .top-bar { background-color: white; padding: 15px 30px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); display: flex; justify-content: space-between; align-items: center; }
        .top-bar h4 { margin: 0; color: #333; }
        .admin-badge { display: flex; align-items: center; gap: 10px; background-color: #4ade80; padding: 8px 15px; border-radius: 25px; color: white; font-size: 14px; }
        .admin-badge i { font-size: 18px; }
        .content-area { padding: 30px; }
    </style>
</head>
<body>
    <div class="sidebar">
        <div class="sidebar-header">
            <i class="bi bi-flower2"></i>
            <h5>Sistema Ingreso</h5>
        </div>
        <ul class="sidebar-menu">
            <li class="menu-item">
                <a href="index.php?vista=dashboard" class="<?php echo ($vista == 'dashboard') ? 'active' : ''; ?>">
                    <i class="bi bi-house-door"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li class="menu-item">
                <a href="#usuarios">
                    <i class="bi bi-people"></i>
                    <span>Usuarios</span>
                </a>
            </li>
            <li class="menu-item">
                <a href="views/controldeIngreso/controldeIngresoview.php">
                    <i class="bi bi-grid"></i>
                    <span>Control de Ingreso</span>
                </a>
            </li>
            <li class="menu-item">
                <a href="#control-llaves">
                    <i class="bi bi-key"></i>
                    <span>Control de Llaves</span>
                </a>
            </li>
            <li class="menu-item">
                <a href="#permisos-salida">
                    <i class="bi bi-door-open"></i>
                    <span>Permisos de Salida</span>
                </a>
            </li>
            <li class="menu-item">
                <a href="#reportes">
                    <i class="bi bi-bar-chart"></i>
                    <span>Reportes</span>
                </a>
            </li>
            <li class="menu-item">
                <a href="index.php?vista=personalExterno" class="<?php echo ($vista == 'personalExterno' || $vista == 'registrarEntradabutton' || $vista == 'personasDentrobutton') ? 'active' : ''; ?>">
                    <i class="bi bi-person-badge"></i>
                    <span>Personal Externo</span>
                </a>
            </li>
        </ul>
    </div>

    <div class="main-content">
        <div class="top-bar">
            <h4>
                <?php 
                    // Título dinámico según la vista
                    if($vista == 'personalExterno') {
                        echo "Registro de Personal Externo";
                    } else if($vista == 'registrarEntradabutton') {
                        echo "Registrar Entrada";
                    } else if($vista == 'personasDentrobutton') {
                        echo "Personas Dentro";
                    } else if($vista == 'dashboard') {
                        echo "Dashboard Principal";
                    } else {
                        echo "Sistema de Control";
                    }
                ?>
            </h4>
            <div class="admin-badge">
                <span>Administrador Sistema</span>
                <i class="bi bi-person-circle"></i>
            </div>
        </div>

        <div class="content-area">
            <?php
            // ==========================================
            // RUTAS DE LAS VISTAS (ENRUTADOR)
            // ==========================================
            if ($vista == 'personalExterno') {
                if (file_exists('views/personalExterno/personalExternoview.php')) {
                    include 'views/personalExterno/personalExternoview.php';
                } else {
                    echo "<div class='alert alert-danger'>Error: No se encontró views/personalExterno/personalExternoview.php</div>";
                }
            } 
            else if ($vista == 'registrarEntradabutton') {
                if (file_exists('views/personalExterno/registrarEntradabutton.php')) {
                    include 'views/personalExterno/registrarEntradabutton.php';
                } else {
                    echo "<div class='alert alert-danger'>Error: No se encontró views/personalExterno/registrarEntradabutton.php</div>";
                }
            } 
            // AQUÍ ESTÁ LA LÍNEA QUE FALTABA
            else if ($vista == 'personasDentrobutton') {
                if (file_exists('views/personalExterno/personasDentrobutton.php')) {
                    include 'views/personalExterno/personasDentrobutton.php';
                } else {
                    echo "<div class='alert alert-danger'>Error: No se encontró views/personalExterno/personasDentrobutton.php</div>";
                }
            }
            else if ($vista == 'dashboard') {
                echo "<h3>Bienvenido al Sistema de Ingreso</h3>";
                echo "<p>Seleccione una opción en el menú de la izquierda para comenzar.</p>";
            } 
            else {
                echo "<h3>Vista no encontrada</h3>";
            }
            ?>
        </div>
    </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>
</html>