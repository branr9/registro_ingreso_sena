<?php
$id = $_GET['id'] ?? '';
$usuario = null;
$error = '';

if (!empty($id)) {
    $id_esc = mysqli_real_escape_string($conexion, $id);
    $res = mysqli_query($conexion, "SELECT * FROM usuarios WHERE Dni='$id_esc'");
    if ($res && mysqli_num_rows($res) > 0) {
        $usuario = mysqli_fetch_assoc($res);
    } else {
        $error = 'Usuario no encontrado';
    }
}
?>

<div style="padding: 20px; max-width: 500px;">
    <h1 style="margin: 0 0 20px 0; font-size: 20px;">Editar Usuario</h1>

    <?php if (!empty($error)): ?>
        <div style="background: #fee2e2; color: #991b1b; padding: 10px; border-radius: 4px; margin-bottom: 15px; border-left: 3px solid #ef4444; font-size: 13px;">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php elseif ($usuario): ?>

        <form method="POST" action="" style="background: white; padding: 20px; border-radius: 4px; border: 1px solid #ddd;">
            <input type="hidden" name="accion" value="actualizar-usuario">
            <input type="hidden" name="user_id" value="<?= htmlspecialchars($usuario['Dni']) ?>">

            <div style="margin-bottom: 15px;">
                <label style="display: block; font-size: 12px; font-weight: 600; color: #333; margin-bottom: 4px;">Documento</label>
                <input type="text" value="<?= htmlspecialchars($usuario['Dni']) ?>" disabled style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 3px; font-size: 12px; background: #f5f5f5; box-sizing: border-box;">
            </div>

            <div style="margin-bottom: 15px;">
                <label style="display: block; font-size: 12px; font-weight: 600; color: #333; margin-bottom: 4px;">Nombre</label>
                <input type="text" name="nombre" value="<?= htmlspecialchars($usuario['nombre']) ?>" required style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 3px; font-size: 12px; box-sizing: border-box;">
            </div>

            <div style="margin-bottom: 15px;">
                <label style="display: block; font-size: 12px; font-weight: 600; color: #333; margin-bottom: 4px;">Apellido</label>
                <input type="text" name="apellido" value="<?= htmlspecialchars($usuario['apellido']) ?>" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 3px; font-size: 12px; box-sizing: border-box;">
            </div>

            <div style="margin-bottom: 15px;">
                <label style="display: block; font-size: 12px; font-weight: 600; color: #333; margin-bottom: 4px;">Correo</label>
                <input type="email" name="correo" value="<?= htmlspecialchars($usuario['correo']) ?>" required style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 3px; font-size: 12px; box-sizing: border-box;">
            </div>

            <div style="margin-bottom: 15px;">
                <label style="display: block; font-size: 12px; font-weight: 600; color: #333; margin-bottom: 4px;">Estado</label>
                <div>
                    <label style="display: inline-block; margin-right: 15px;">
                        <input type="radio" name="estado" value="Activo" <?= $usuario['estado'] === 'Activo' ? 'checked' : '' ?>> Activo
                    </label>
                    <label style="display: inline-block;">
                        <input type="radio" name="estado" value="Inactivo" <?= $usuario['estado'] === 'Inactivo' ? 'checked' : '' ?>> Inactivo
                    </label>
                </div>
            </div>

            <div style="display: flex; gap: 10px;">
                <a href="?seccion=usuarios" style="flex: 1; background: #666; color: white; padding: 8px; border-radius: 3px; text-decoration: none; text-align: center; font-weight: 600; font-size: 12px;">Cancelar</a>
                <button type="submit" style="flex: 1; background: #3b82f6; color: white; padding: 8px; border: none; border-radius: 3px; cursor: pointer; font-weight: 600; font-size: 12px;">Guardar Cambios</button>
            </div>
        </form>

    <?php endif; ?>
</div>
<?php
$id = $_GET['id'] ?? '';
$usuario = null;
$error_msg = '';

if (!empty($id)) {
    $id_esc = mysqli_real_escape_string($conexion, $id);
    $resultado = mysqli_query($conexion, "SELECT * FROM usuarios WHERE Dni = '$id_esc'");
    if ($resultado && mysqli_num_rows($resultado) > 0) {
        $usuario = mysqli_fetch_assoc($resultado);
    } else {
        $error_msg = "Usuario no encontrado.";
    }
}
?>

<div style="padding: 30px; max-width: 900px;">
    <!-- Encabezado -->
    <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 30px;">
        <a href="?seccion=usuarios" style="background: #666; color: white; padding: 10px 16px; border-radius: 6px; text-decoration: none; font-weight: 600;">← Volver</a>
        <h2 style="margin: 0; font-size: 24px; font-weight: bold;">Editar Usuario</h2>
    </div>

    <?php if (!empty($error_msg)): ?>
        <div style="background: #fee2e2; color: #991b1b; padding: 15px; border-radius: 6px; margin-bottom: 20px; border-left: 4px solid #ef4444;">
            <?= htmlspecialchars($error_msg) ?>
        </div>
    <?php elseif ($usuario): ?>

        <!-- Formulario -->
        <form method="POST" action="" style="background: white; padding: 30px; border-radius: 8px; border: 1px solid #ddd;">
            <input type="hidden" name="accion" value="actualizar-usuario">
            <input type="hidden" name="user_id" value="<?= htmlspecialchars($usuario['Dni']) ?>">

            <!-- Sección Datos Personales -->
            <h3 style="font-size: 16px; font-weight: 600; color: #3b82f6; border-bottom: 2px solid #3b82f6; padding-bottom: 10px; margin-bottom: 20px;">📋 Datos Personales</h3>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                <div>
                    <label style="display: block; font-size: 12px; font-weight: 600; color: #333; margin-bottom: 6px;">Documento (DNI)</label>
                    <input type="text" value="<?= htmlspecialchars($usuario['Dni']) ?>" disabled style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; font-size: 13px; background: #f5f5f5; box-sizing: border-box;">
                </div>
                <div>
                    <label style="display: block; font-size: 12px; font-weight: 600; color: #333; margin-bottom: 6px;">Nombre Completo <span style="color: #ef4444;">*</span></label>
                    <input type="text" name="nombre" value="<?= htmlspecialchars($usuario['nombre']) ?>" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; font-size: 13px; box-sizing: border-box;">
                </div>
                <div>
                    <label style="display: block; font-size: 12px; font-weight: 600; color: #333; margin-bottom: 6px;">Apellido <span style="color: #ef4444;">*</span></label>
                    <input type="text" name="apellido" value="<?= htmlspecialchars($usuario['apellido']) ?>" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; font-size: 13px; box-sizing: border-box;">
                </div>
                <div>
                    <label style="display: block; font-size: 12px; font-weight: 600; color: #333; margin-bottom: 6px;">Correo Electrónico <span style="color: #ef4444;">*</span></label>
                    <input type="email" name="correo" value="<?= htmlspecialchars($usuario['correo']) ?>" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; font-size: 13px; box-sizing: border-box;">
                </div>
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display: block; font-size: 12px; font-weight: 600; color: #333; margin-bottom: 6px;">Estado <span style="color: #ef4444;">*</span></label>
                <div style="display: flex; gap: 20px;">
                    <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                        <input type="radio" name="estado" value="Activo" <?= $usuario['estado'] === 'Activo' ? 'checked' : '' ?> required>
                        <span style="font-size: 13px;">Activo</span>
                    </label>
                    <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                        <input type="radio" name="estado" value="Inactivo" <?= $usuario['estado'] === 'Inactivo' ? 'checked' : '' ?> required>
                        <span style="font-size: 13px;">Inactivo</span>
                    </label>
                </div>
            </div>

            <hr style="border: none; border-top: 1px solid #ddd; margin: 30px 0;">

            <!-- Botones -->
            <div style="display: flex; gap: 10px; justify-content: flex-end;">
                <a href="?seccion=usuarios" style="background: #666; color: white; padding: 10px 20px; border-radius: 6px; text-decoration: none; font-weight: 600;">Cancelar</a>
                <button type="submit" style="background: #3b82f6; color: white; padding: 10px 20px; border: none; border-radius: 6px; cursor: pointer; font-weight: 600;">✓ Guardar Cambios</button>
            </div>
        </form>

    <?php endif; ?>
</div>
<?php
// ==============================================================================
// OBTENER DATOS DEL USUARIO Y MOSTRAR FORMULARIO
// ==============================================================================
$id_usuario = $_GET['id'] ?? null;
$usuario_actual = null;
$error_msg = $_POST['error_msg'] ?? '';

if ($id_usuario) {
    $id_seguro = mysqli_real_escape_string($conexion, $id_usuario);
    $resultado = mysqli_query($conexion, "SELECT * FROM usuarios WHERE Dni = '$id_seguro'");
    
    if ($row = mysqli_fetch_assoc($resultado)) {
        $usuario_actual = [
            'documento' => $row['Dni'],
            'nombre' => trim($row['nombre'] . ' ' . $row['apellido']),
            'tipo_persona' => 'Persona',
            'empresa' => '-',
            'usuario' => '',
            'email' => $row['correo']
        ];
    }
}
?>

<!-- Formulario de Edición de Usuario -->
<div class="p-10 max-w-5xl">

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

<!-- Formulario de Edición de Usuario -->
<div class="p-10 max-w-5xl">
                
                <div class="mb-8 flex items-center gap-4">
                    <a href="?seccion=usuarios" class="bg-gray-500 text-white px-4 py-2 rounded-lg font-medium flex items-center gap-2 hover:bg-gray-600 transition">
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
                        <input type="hidden" name="accion" value="actualizar-usuario">
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
                        <a href="?seccion=usuarios" class="mt-8 bg-btn-green text-white font-medium py-2.5 px-6 rounded-lg shadow-md hover:bg-green-600 transition flex items-center gap-2">
                            <i class="bi bi-arrow-left"></i>
                            Regresar a la lista
                        </a>
                    </div>
                <?php endif; ?>

            </div>