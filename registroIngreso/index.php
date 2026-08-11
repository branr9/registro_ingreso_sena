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
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
</body>
</html>