<?php
session_start();

if (isset($_SESSION['usuario'])) {
    header("Location: index.php");
    exit();
}

// ✅ Después
require_once __DIR__ . "/models/conexion.php";
require_once __DIR__ . "/controller/usuarioController.php";

$db = new Conexion();
$conexion = $db->conectar(); // Ahora sí existe $conexion

$controller = new UsuarioController($conexion);
$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $correo = trim($_POST['correo'] ?? '');
    $dni = trim($_POST['dni'] ?? '');

    if ($correo === '' || $dni === '') {
        $error = "Debes ingresar correo y DNI.";
    } else {
        $usuario = $controller->iniciarSesion($correo, $dni);

        if ($usuario) {
            $_SESSION['usuario'] = $usuario;
            header("Location: index.php");
            exit();
        }

        $error = "Credenciales incorrectas. Verifica tu correo y DNI.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistema Ingreso SENA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #4ade80 0%, #6b4db8 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .login-card {
            width: 100%;
            max-width: 420px;
            border: 0;
            border-radius: 18px;
            box-shadow: 0 20px 45px rgba(0, 0, 0, 0.18);
        }
        .card-body {
            padding: 36px;
        }
        .logo {
            width: 64px;
            height: 64px;
            border-radius: 50%;
            background-color: #6b4db8;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 28px;
            margin: 0 auto 18px auto;
        }
    </style>
</head>
<body>
    <div class="card login-card">
        <div class="card-body">
            <div class="logo">
                <i class="bi bi-shield-lock"></i>
            </div>
            <h3 class="text-center mb-1">Iniciar sesión</h3>
            <p class="text-center text-muted mb-4">Sistema de Ingreso SENA</p>

            <?php if ($error !== ''): ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?>
                </div>
            <?php endif; ?>

            <form method="POST" novalidate>
                <div class="mb-3">
                    <label for="correo" class="form-label">Correo</label>
                    <input type="email" class="form-control" id="correo" name="correo" required>
                </div>
                <div class="mb-4">
                    <label for="dni" class="form-label">DNI</label>
                    <input type="text" class="form-control" id="dni" name="dni" required>
                </div>
                <button type="submit" class="btn btn-success w-100">Entrar</button>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>
</html>
