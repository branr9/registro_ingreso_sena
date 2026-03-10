<<<<<<< HEAD
<?php 
require_once __DIR__ . "/models/conexion.php";
require_once __DIR__ . "/controller/usuarioController.php";

// Instanciar el controlador
$controller = new UsuarioController($conexion);

// Procesar registro de usuario
$mensaje = $controller->registrarUsuario();

// Obtener lista de usuarios
$usuarios = $controller->listarUsuarios();
?>
=======
>>>>>>> main
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
<<<<<<< HEAD
    <title>CRUD en PHP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
</head>
<body>
    <h1 class="text-center">FORMULARIO</h1>
    <div class="container-fluid row">
        <form method="POST" class="col-4 p-3">
            <h3 class="text-center text-secondary">Registro usuarios</h3>
            <?php echo $mensaje; ?>
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre del usuario</label>
                <input type="text" name="nombre" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="apellido" class="form-label">Apellido del usuario</label>
                <input type="text" name="apellido" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="fecha" class="form-label">Fecha</label>
                <input type="date" name="fecha" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="dni" class="form-label">DNI del usuario</label>
                <input type="text" name="dni" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="correo" class="form-label">Correo del usuario</label>
                <input type="email" name="correo" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary" name="btnenviar" value="ok">Enviar</button>
        </form>

        <div class="col-8 p-3">
            <table class="table table-dark table-striped-columns">
                <thead>
                    <tr>
                        <th scope="col">Id_usuario</th>
                        <th scope="col">Nombre</th>
                        <th scope="col">Apellido</th>
                        <th scope="col">Fecha</th>
                        <th scope="col">DNI</th>
                        <th scope="col">Correo</th>
                        <th scope="col">Acción</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($datos = $usuarios->fetch_object()){ ?>
                    <tr>
                        <td><?php echo $datos->Id_usuario ?></td>
                        <td><?php echo $datos->nombre ?></td>
                        <td><?php echo $datos->apellido ?></td>
                        <td><?php echo $datos->fecha ?></td>
                        <td><?php echo $datos->Dni ?></td>
                        <td><?php echo $datos->correo ?></td>
                        <td>
                            <a href="views/userViews/modificar.php?id=<?php echo $datos->Id_usuario ?>" class="btn btn-warning">Editar</a>
                            <a href="eliminar.php?id=<?php echo $datos->Id_usuario ?>" class="btn btn-danger">Eliminar</a>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>   
=======
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
                <a href="#dashboard">
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
                <a href="#control-ingreso">
                    <i class="bi bi-grid"></i>
                    <span>Control de Ingreso</span>
                </a>
            </li>
            <li class="menu-item">
                <a href="#control-llaves" class="active">
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
                <a href="#personal-externo">
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
            <h4>Control de Llaves</h4>
            <div class="admin-badge">
                <span>Administrador Sistema</span>
                <i class="bi bi-person-circle"></i>
            </div>
        </div>

        <!-- Content Area -->
        <div class="content-area">
            <!-- Contenido dinámico aquí -->
        </div>
    </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
<script>
    // Activar el menú según la sección actual
    document.querySelectorAll('.menu-item a').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            document.querySelectorAll('.menu-item a').forEach(a => a.classList.remove('active'));
            this.classList.add('active');
        });
    });
</script>
>>>>>>> main
</body>
</html>