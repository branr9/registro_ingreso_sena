<?php
require_once __DIR__ . "/../../models/conexion.php";
require_once __DIR__ . "/../../controller/usuarioController.php";

// Instanciar el controlador
$controller = new UsuarioController($conexion);

// Obtener el ID del usuario a modificar
$id = $_GET['id'];

// Procesar modificación si se envió el formulario
$mensaje = $controller->modificarUsuario($id);

// Obtener datos del usuario
$usuario = $controller->obtenerUsuario($id);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modificar Usuario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
</head>
<body>
    <form method="POST" class="col-4 p-3 m-auto">
        <input type="hidden" name="id" value="<?php echo $id ?>">
        <h3 class="text-center text-secondary">Modificar Usuario</h3>
        
        <?php 
        echo $mensaje;
        while($datos = $usuario->fetch_object()){
        ?>
        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre del usuario</label>
            <input type="text" name="nombre" class="form-control" value="<?php echo $datos->nombre ?>" required>
        </div>
        <div class="mb-3">
            <label for="apellido" class="form-label">Apellido del usuario</label>
            <input type="text" name="apellido" class="form-control" value="<?php echo $datos->apellido ?>" required>
        </div>
        <div class="mb-3">
            <label for="fecha" class="form-label">Fecha</label>
            <input type="date" name="fecha" class="form-control" value="<?php echo $datos->fecha ?>" required>
        </div>
        <div class="mb-3">
            <label for="dni" class="form-label">DNI del usuario</label>
            <input type="text" name="dni" class="form-control" value="<?php echo $datos->Dni ?>" required>
        </div>
        <div class="mb-3">
            <label for="correo" class="form-label">Correo del usuario</label>
            <input type="email" name="correo" class="form-control" value="<?php echo $datos->correo ?>" required>
        </div>
        <?php } ?>
        
        <button type="submit" class="btn btn-primary" name="btnenviar" value="ok">Modificar</button>
        <a href="../../index.php" class="btn btn-secondary">Cancelar</a>
    </form>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>
</html>