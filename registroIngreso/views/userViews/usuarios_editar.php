<?php
// ==============================================================================
// 1. CONEXIÓN A LA BASE DE DATOS
// ==============================================================================
require_once 'C:\Users\Aprendiz\Documents\GitHub\registro_ingreso_sena\registroIngreso\models\conexion.php'; 

// ==============================================================================
// 2. PROCESAR ACTUALIZACIÓN (Si se envió el formulario)
// ==============================================================================
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $documento = mysqli_real_escape_string($conexion, $_POST['id_usuario']);
    $nombre_completo = mysqli_real_escape_string($conexion, trim($_POST['nombre']));
    $email = mysqli_real_escape_string($conexion, trim($_POST['email']));
    
    // Separar el nombre completo en Nombre y Apellido para la base de datos
    $partes = explode(' ', $nombre_completo, 2);
    $nombre = $partes[0];
    $apellido = $partes[1] ?? '';

    // Actualizar en la base de datos
    // Nota: tipo_persona, empresa y usuario no se actualizan porque no existen en la BD actual
    $query_update = "UPDATE usuarios SET nombre = '$nombre', apellido = '$apellido', correo = '$email' WHERE Dni = '$documento'";
    
    if (mysqli_query($conexion, $query_update)) {
        // Redirigir a la vista de usuarios tras actualizar correctamente
        header("Location: usuariosview.php");
        exit;
    } else {
        $error_msg = "Error al actualizar: " . mysqli_error($conexion);
    }
}

// ==============================================================================
// 3. OBTENER DATOS DEL USUARIO (Al cargar la página)
// ==============================================================================
$id_usuario = $_GET['id'] ?? null;
$usuario_actual = null;

if ($id_usuario) {
    $id_seguro = mysqli_real_escape_string($conexion, $id_usuario);
    $resultado = mysqli_query($conexion, "SELECT * FROM usuarios WHERE Dni = '$id_seguro'");
    
    if ($row = mysqli_fetch_assoc($resultado)) {
        // Adaptamos los datos de la BD a lo que pide el formulario
        $usuario_actual = [
            'documento' => $row['Dni'],
            'nombre' => trim($row['nombre'] . ' ' . $row['apellido']),
            'tipo_persona' => 'Persona', // Valor por defecto
            'empresa' => '-', // Valor por defecto
            'usuario' => '', // Valor por defecto
            'email' => $row['correo']
        ];
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Usuario - Sistema Ingreso</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/heroicons@^2/24/outline.js" defer></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'sidebar-bg': '#5c4cd4',
                        'sidebar-hover': '#7165de',
                        'active-menu': '#4caf50',
                        'card-bg': '#ffffff',
                        'btn-green': '#4caf50',
                        'btn-blue': '#1fb6ff',
                        'btn-gray': '#78909c',
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-50 font-sans antialiased text-gray-800">
    <div class="min-h-screen flex">

        <nav class="w-[230px] flex-shrink-0 bg-gradient-to-b from-[#6b4db8] to-[#5a3d9e] flex flex-col shadow-[2px_0_5px_rgba(0,0,0,0.1)] min-h-screen relative z-10">
            <div class="bg-black/10 py-[20px] px-[15px] flex items-center gap-[10px]">
                <i class="bi bi-flower2 text-[#4ade80] text-[35px]"></i>
                <h5 class="text-white m-0 text-[18px] font-semibold">Sistema Ingreso</h5>
            </div>
            
            <ul class="list-none p-0 my-[20px] flex-grow flex flex-col gap-1">
                <li><a href="../../index.php" class="flex items-center px-[20px] py-[15px] text-white/90 gap-[12px] text-[15px] hover:bg-white/10"><i class="bi bi-house-door text-[20px] w-[25px]"></i><span>Dashboard</span></a></li>
                <li><a href="usuariosview.php" class="flex items-center px-[20px] py-[15px] bg-[#4ade80] text-white font-semibold gap-[12px] text-[15px]"><i class="bi bi-people text-[20px] w-[25px]"></i><span>Usuarios</span></a></li>
                </ul>
        </nav>

        <main class="flex-grow">
            <header class="bg-white p-5 flex items-center justify-between border-b border-gray-200 shadow-sm">
                <h2 class="text-2xl font-bold text-gray-800">Editar Usuario</h2>
                <div class="flex items-center gap-3">
                    <p class="text-sm font-medium">Administrador Sistema</p>
                    <div class="bg-[#4ade80] rounded-full w-9 h-9 flex items-center justify-center font-bold text-lg text-white">
                        <i class="bi bi-person-fill"></i>
                    </div>
                </div>
            </header>

            <div class="p-10 max-w-5xl">
                
                <div class="mb-8 flex items-center gap-4">
                    <a href="usuariosview.php" class="bg-gray-500 text-white px-4 py-2 rounded-lg font-medium flex items-center gap-2 hover:bg-gray-600 transition">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" /></svg>
                        Volver
                    </a>
                    <div class="flex items-center gap-2 text-gray-700">
                        <h3 class="text-3xl font-bold">Editar Usuario</h3>
                    </div>
                </div>

                <?php if (isset($error_msg)): ?>
                    <div class="bg-red-100 text-red-800 p-4 rounded-lg mb-6 border border-red-200">
                        <?= htmlspecialchars($error_msg) ?>
                    </div>
                <?php endif; ?>

                <?php if ($usuario_actual): ?>
                    
                    <p class="text-gray-500 ml-[115px] mb-8 mt-[-30px]">
                        Editando: <span class="font-semibold text-gray-700"><?= htmlspecialchars($usuario_actual['nombre']) ?></span> (<?= htmlspecialchars($usuario_actual['documento']) ?>)
                    </p>

                    <form action="" method="POST" class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100">
                        
                        <input type="hidden" name="id_usuario" value="<?= htmlspecialchars($usuario_actual['documento']) ?>">

                        <div class="mb-10">
                            <div class="flex items-center gap-2 text-btn-green border-b-2 border-btn-green pb-2 mb-6">
                                <h4 class="text-lg font-bold">Datos Personales</h4>
                            </div>
                            
                            <div class="grid grid-cols-2 gap-x-8 gap-y-6">
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-1">Documento <span class="text-red-500">*</span></label>
                                    <input type="text" value="<?= htmlspecialchars($usuario_actual['documento']) ?>" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg bg-gray-50 text-gray-600 focus:outline-none" readonly>
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-1">Nombre Completo <span class="text-red-500">*</span></label>
                                    <input type="text" name="nombre" value="<?= htmlspecialchars($usuario_actual['nombre']) ?>" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-1 focus:ring-btn-green outline-none" required>
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-1">Tipo de Persona <span class="text-red-500">*</span></label>
                                    <select name="tipo_persona" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-1 focus:ring-btn-green outline-none bg-white">
                                        <option value="Vigilante" <?= ($usuario_actual['tipo_persona'] == 'Vigilante') ? 'selected' : '' ?>>Vigilante</option>
                                        <option value="Contratista" <?= ($usuario_actual['tipo_persona'] == 'Contratista') ? 'selected' : '' ?>>Contratista</option>
                                        <option value="Persona" <?= ($usuario_actual['tipo_persona'] == 'Persona') ? 'selected' : '' ?>>Persona</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-1">Empresa/Institución</label>
                                    <input type="text" name="empresa" value="<?= htmlspecialchars($usuario_actual['empresa']) ?>" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-1 focus:ring-btn-green outline-none">
                                </div>
                            </div>
                        </div>

                        <div>
                            <div class="flex items-center gap-2 text-btn-green border-b-2 border-btn-green pb-2 mb-6">
                                <h4 class="text-lg font-bold">Datos de Acceso al Sistema</h4>
                            </div>

                            <div class="grid grid-cols-2 gap-x-8 gap-y-6">
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-1">Usuario</label>
                                    <input type="text" name="usuario" value="<?= htmlspecialchars($usuario_actual['usuario']) ?>" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-1 focus:ring-btn-green outline-none">
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-1">Email</label>
                                    <input type="email" name="email" value="<?= htmlspecialchars($usuario_actual['email']) ?>" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-1 focus:ring-btn-green outline-none">
                                </div>
                            </div>
                        </div>

                        <div class="mt-10 flex justify-end">
                            <button type="submit" class="bg-btn-green text-white font-bold py-3 px-8 rounded-lg shadow-md hover:bg-green-600 transition">
                                Guardar Cambios
                            </button>
                        </div>

                    </form>

                <?php else: ?>
                    <div class="bg-white p-12 rounded-2xl shadow-sm border border-gray-100 flex flex-col items-center justify-center text-center">
                        <div class="bg-gray-100 p-6 rounded-full text-gray-400 mb-4">
                            <i class="bi bi-person-x text-[50px]"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-700">No se encontró el usuario</h3>
                        <p class="text-gray-500 mt-2 max-w-md">El documento no existe en la base de datos.</p>
                        <a href="usuariosview.php" class="mt-8 bg-btn-green text-white font-medium py-2.5 px-6 rounded-lg shadow-md hover:bg-green-600 transition flex items-center gap-2">
                            <i class="bi bi-arrow-left"></i>
                            Regresar a la lista
                        </a>
                    </div>
                <?php endif; ?>

            </div>
        </main>
    </div>
</body>
</html>