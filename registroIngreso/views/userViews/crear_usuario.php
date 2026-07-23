<?php
$success = isset($_GET['success']);
?>

<div style="padding: 20px; max-width: 500px;">
    <h1 style="margin: 0 0 20px 0; font-size: 20px;">Crear Usuario</h1>

    <?php if ($success): ?>
        <div style="background: #dcfce7; color: #166534; padding: 10px; border-radius: 4px; margin-bottom: 15px; border-left: 3px solid #22c55e; font-size: 13px;">
            ✓ Usuario creado exitosamente
        </div>
    <?php endif; ?>

    <form method="POST" action="" style="background: white; padding: 20px; border-radius: 4px; border: 1px solid #ddd;">
        <input type="hidden" name="accion" value="crear-usuario">

        <div style="margin-bottom: 15px;">
            <label style="display: block; font-size: 12px; font-weight: 600; color: #333; margin-bottom: 4px;">Documento <span style="color: #ef4444;">*</span></label>
            <input type="text" name="documento" required style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 3px; font-size: 12px; box-sizing: border-box;">
        </div>

        <div style="margin-bottom: 15px;">
            <label style="display: block; font-size: 12px; font-weight: 600; color: #333; margin-bottom: 4px;">Nombre <span style="color: #ef4444;">*</span></label>
            <input type="text" name="nombre" required style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 3px; font-size: 12px; box-sizing: border-box;">
        </div>

        <div style="margin-bottom: 15px;">
            <label style="display: block; font-size: 12px; font-weight: 600; color: #333; margin-bottom: 4px;">Apellido</label>
            <input type="text" name="apellido" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 3px; font-size: 12px; box-sizing: border-box;">
        </div>

        <div style="margin-bottom: 15px;">
            <label style="display: block; font-size: 12px; font-weight: 600; color: #333; margin-bottom: 4px;">Correo <span style="color: #ef4444;">*</span></label>
            <input type="email" name="correo" required style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 3px; font-size: 12px; box-sizing: border-box;">
        </div>

        <div style="margin-bottom: 15px;">
            <label style="display: block; font-size: 12px; font-weight: 600; color: #333; margin-bottom: 4px;">Estado</label>
            <div>
                <label style="display: inline-block; margin-right: 15px;">
                    <input type="radio" name="estado" value="Activo" checked> Activo
                </label>
                <label style="display: inline-block;">
                    <input type="radio" name="estado" value="Inactivo"> Inactivo
                </label>
            </div>
        </div>

        <div style="display: flex; gap: 10px;">
            <a href="?seccion=usuarios" style="flex: 1; background: #666; color: white; padding: 8px; border-radius: 3px; text-decoration: none; text-align: center; font-weight: 600; font-size: 12px;">Cancelar</a>
            <button type="submit" style="flex: 1; background: #22c55e; color: white; padding: 8px; border: none; border-radius: 3px; cursor: pointer; font-weight: 600; font-size: 12px;">Guardar</button>
        </div>
    </form>
</div>
<?php
$error_msg = $_GET['error'] ?? '';
$success = isset($_GET['success']);
?>

<div style="padding: 30px; max-width: 900px;">
    <!-- Encabezado -->
    <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 30px;">
        <a href="?seccion=usuarios" style="background: #666; color: white; padding: 10px 16px; border-radius: 6px; text-decoration: none; font-weight: 600;">← Volver</a>
        <h2 style="margin: 0; font-size: 24px; font-weight: bold;">Crear Nuevo Usuario</h2>
    </div>

    <?php if (!empty($error_msg)): ?>
        <div style="background: #fee2e2; color: #991b1b; padding: 15px; border-radius: 6px; margin-bottom: 20px; border-left: 4px solid #ef4444;">
            <?= htmlspecialchars($error_msg) ?>
        </div>
    <?php endif; ?>

    <?php if ($success): ?>
        <div style="background: #dcfce7; color: #166534; padding: 15px; border-radius: 6px; margin-bottom: 20px; border-left: 4px solid #22c55e;">
            ✓ Usuario creado exitosamente
        </div>
    <?php endif; ?>

    <!-- Formulario -->
    <form method="POST" action="" style="background: white; padding: 30px; border-radius: 8px; border: 1px solid #ddd;">
        <input type="hidden" name="accion" value="crear-usuario">

        <!-- Sección Datos Personales -->
        <h3 style="font-size: 16px; font-weight: 600; color: #22c55e; border-bottom: 2px solid #22c55e; padding-bottom: 10px; margin-bottom: 20px;">📋 Datos Personales</h3>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
            <div>
                <label style="display: block; font-size: 12px; font-weight: 600; color: #333; margin-bottom: 6px;">Documento (DNI) <span style="color: #ef4444;">*</span></label>
                <input type="text" name="documento" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; font-size: 13px; box-sizing: border-box;">
            </div>
            <div>
                <label style="display: block; font-size: 12px; font-weight: 600; color: #333; margin-bottom: 6px;">Nombre Completo <span style="color: #ef4444;">*</span></label>
                <input type="text" name="nombre" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; font-size: 13px; box-sizing: border-box;">
            </div>
            <div>
                <label style="display: block; font-size: 12px; font-weight: 600; color: #333; margin-bottom: 6px;">Correo Electrónico <span style="color: #ef4444;">*</span></label>
                <input type="email" name="correo" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; font-size: 13px; box-sizing: border-box;">
            </div>
            <div>
                <label style="display: block; font-size: 12px; font-weight: 600; color: #333; margin-bottom: 6px;">Tipo de Persona</label>
                <select name="tipo_persona" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; font-size: 13px; box-sizing: border-box;">
                    <option value="">Seleccione...</option>
                    <option value="Vigilante">Vigilante</option>
                    <option value="Contratista">Contratista</option>
                    <option value="Persona">Persona</option>
                </select>
            </div>
        </div>

        <div style="margin-bottom: 20px;">
            <label style="display: block; font-size: 12px; font-weight: 600; color: #333; margin-bottom: 6px;">Estado <span style="color: #ef4444;">*</span></label>
            <div style="display: flex; gap: 20px;">
                <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                    <input type="radio" name="estado" value="Activo" checked required>
                    <span style="font-size: 13px;">Activo</span>
                </label>
                <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                    <input type="radio" name="estado" value="Inactivo" required>
                    <span style="font-size: 13px;">Inactivo</span>
                </label>
            </div>
        </div>

        <hr style="border: none; border-top: 1px solid #ddd; margin: 30px 0;">

        <!-- Botones -->
        <div style="display: flex; gap: 10px; justify-content: flex-end;">
            <a href="?seccion=usuarios" style="background: #666; color: white; padding: 10px 20px; border-radius: 6px; text-decoration: none; font-weight: 600;">Cancelar</a>
            <button type="submit" style="background: #22c55e; color: white; padding: 10px 20px; border: none; border-radius: 6px; cursor: pointer; font-weight: 600;">✓ Guardar Usuario</button>
        </div>
    </form>
</div>
<?php
// ==============================================================================
// LA LÓGICA DE CREAR USUARIO SE PROCESA EN index.php
// ==============================================================================
$error_msg = $_POST['error_msg'] ?? '';
?>

<!-- Creador de Usuarios -->
<div class="p-10 max-w-5xl">
                
                <div class="mb-8 flex items-center gap-4">
                    <a href="?seccion=usuarios" class="bg-btn-gray text-white px-4 py-2 rounded-lg font-medium flex items-center gap-2 hover:opacity-90 transition">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" /></svg>
                        Volver
                    </a>
                    <div class="flex items-center gap-3 text-gray-800">
                        <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M15 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm-9-2V7H4v3H1v2h3v3h2v-3h3v-2H6zm9 4c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                        </svg>
                        <h3 class="text-3xl font-bold">Crear Nuevo Usuario</h3>
                    </div>
                </div>

                <?php if (!empty($error_msg)): ?>
                    <div class="bg-red-100 text-red-800 p-4 rounded-lg mb-6 border border-red-200">
                        <?= htmlspecialchars($error_msg) ?>
                    </div>
                <?php endif; ?>

                <form method="POST" action="" class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100">
                    <input type="hidden" name="accion" value="crear-usuario">
                    
                    <div class="mb-8">
                        <div class="flex items-center gap-2 text-btn-green border-b-2 border-btn-green pb-2 mb-6">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 9h3.75M15 12h3.75M15 15h3.75M4.5 19.5h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5zm6-10.125a1.875 1.875 0 11-3.75 0 1.875 1.875 0 013.75 0zm1.294 6.336a6.721 6.721 0 01-3.17.789 6.721 6.721 0 01-3.168-.789 3.376 3.376 0 016.338 0z" />
                            </svg>
                            <h4 class="text-lg font-bold">Datos Personales</h4>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-x-8 gap-y-6">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-1">Documento (DNI) <span class="text-red-500">*</span></label>
                                <input type="text" name="documento" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-1 focus:ring-btn-green outline-none" required>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-1">Nombre Completo <span class="text-red-500">*</span></label>
                                <input type="text" name="nombre" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-1 focus:ring-btn-green outline-none" required>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-1">Tipo de Persona</label>
                                <select name="tipo_persona" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-1 focus:ring-btn-green outline-none bg-white">
                                    <option value="" disabled selected>Seleccione...</option>
                                    <option value="Vigilante">Vigilante</option>
                                    <option value="Contratista">Contratista</option>
                                    <option value="Persona">Persona</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-1">Empresa/Institución</label>
                                <input type="text" name="empresa" placeholder="Opcional" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-1 focus:ring-btn-green outline-none">
                            </div>

                            <div class="col-span-2">
                                <label class="block text-sm font-bold text-gray-700 mb-1">Correo Electrónico <span class="text-red-500">*</span></label>
                                <input type="email" name="correo" placeholder="ejemplo@sena.edu.co" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-1 focus:ring-btn-green outline-none" required>
                            </div>

                            <div class="col-span-2 mt-2">
                                <label class="block text-sm font-bold text-gray-700 mb-3">Estado <span class="text-red-500">*</span></label>
                                <div class="flex items-center gap-6">
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="radio" name="estado" value="Activo" class="w-4 h-4 text-btn-blue focus:ring-btn-blue border-gray-300" checked>
                                        <span class="text-gray-700">Activo</span>
                                    </label>
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="radio" name="estado" value="Inactivo" class="w-4 h-4 text-btn-blue focus:ring-btn-blue border-gray-300">
                                        <span class="text-gray-700">Inactivo</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr class="border-gray-200 mb-6">

                    <div class="flex justify-end gap-3">
                        <a href="?seccion=usuarios" class="bg-btn-gray text-white font-medium py-2.5 px-6 rounded-lg flex items-center gap-2 hover:opacity-90 transition">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                            Cancelar
                        </a>
                        <button type="submit" class="bg-btn-green text-white font-medium py-2.5 px-6 rounded-lg flex items-center gap-2 hover:bg-green-600 transition">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M17 3H5a2 2 0 00-2 2v14a2 2 0 002 2h14c1.1 0 2-.9 2-2V7l-4-4zm-5 16c-1.66 0-3-1.34-3-3s1.34-3 3-3 3 1.34 3 3-1.34 3-3 3zm3-10H5V5h10v4z"/>
                            </svg>
                            Guardar Usuario
                        </button>
                    </div>

                </form>
            </div>