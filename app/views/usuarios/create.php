<?php
/**
 * Vista: Crear Usuario
 */
require_once APP_PATH . '/views/layouts/header.php';
$errors = $_SESSION['errors'] ?? [];
$old = $_SESSION['old'] ?? [];
unset($_SESSION['errors'], $_SESSION['old']);
?>

<div class="max-w-4xl">
    <div class="flex items-center gap-4 mb-8">
        <a href="<?= baseUrl('/usuarios') ?>"
           class="inline-flex items-center gap-2 rounded-xl bg-gray-100 hover:bg-gray-200 transition text-gray-700 font-semibold px-4 py-2 text-sm">
            <i class="fas fa-arrow-left"></i> Volver
        </a>
        <h1 class="text-2xl font-extrabold text-primary-700 flex items-center gap-2">
            <i class="fas fa-user-plus"></i> Crear Nuevo Usuario
        </h1>
    </div>

    <div class="bg-white/85 backdrop-blur-md border border-primary-100 rounded-3xl shadow-xl p-6 md:p-8">
        <form method="POST" action="<?= baseUrl('/usuarios/store') ?>" id="formUsuario">
            <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">

            <!-- Datos Personales -->
            <h3 class="text-primary-700 font-bold text-lg mb-5 pb-2 border-b-2 border-primary-700 flex items-center gap-2">
                <i class="fas fa-id-card"></i> Datos Personales
            </h3>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 mb-8">
                <div>
                    <label for="documento" class="block text-sm font-semibold text-gray-700 mb-1">Documento <span class="text-red-500">*</span></label>
                    <input type="text" id="documento" name="documento"
                           class="w-full rounded-xl border border-gray-200 px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none"
                           value="<?= e($old['documento'] ?? '') ?>" required maxlength="20">
                    <?php if (isset($errors['documento'])): ?>
                    <small class="text-red-600 text-xs mt-1 block"><?= e($errors['documento']) ?></small>
                    <?php endif; ?>
                </div>

                <div>
                    <label for="nombre" class="block text-sm font-semibold text-gray-700 mb-1">Nombre Completo <span class="text-red-500">*</span></label>
                    <input type="text" id="nombre" name="nombre"
                           class="w-full rounded-xl border border-gray-200 px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none"
                           value="<?= e($old['nombre'] ?? '') ?>" required maxlength="100">
                    <?php if (isset($errors['nombre'])): ?>
                    <small class="text-red-600 text-xs mt-1 block"><?= e($errors['nombre']) ?></small>
                    <?php endif; ?>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 mb-8">
                <div>
                    <label for="tipo_persona" class="block text-sm font-semibold text-gray-700 mb-1">Tipo de Persona <span class="text-red-500">*</span></label>
                    <select id="tipo_persona" name="tipo_persona" required
                            class="w-full rounded-xl border border-gray-200 px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none">
                        <option value="">Seleccione...</option>
                        <option value="aprendiz" <?= ($old['tipo_persona'] ?? '') === 'aprendiz' ? 'selected' : '' ?>>Aprendiz</option>
                        <option value="instructor" <?= ($old['tipo_persona'] ?? '') === 'instructor' ? 'selected' : '' ?>>Instructor</option>
                        <option value="admin" <?= ($old['tipo_persona'] ?? '') === 'admin' ? 'selected' : '' ?>>Administrador</option>
                        <option value="vigilante" <?= ($old['tipo_persona'] ?? '') === 'vigilante' ? 'selected' : '' ?>>Vigilante</option>
                        <option value="contratista" <?= ($old['tipo_persona'] ?? '') === 'contratista' ? 'selected' : '' ?>>Contratista</option>
                        <option value="visitante" <?= ($old['tipo_persona'] ?? '') === 'visitante' ? 'selected' : '' ?>>Visitante</option>
                        <option value="proveedor" <?= ($old['tipo_persona'] ?? '') === 'proveedor' ? 'selected' : '' ?>>Proveedor</option>
                    </select>
                    <?php if (isset($errors['tipo_persona'])): ?>
                    <small class="text-red-600 text-xs mt-1 block"><?= e($errors['tipo_persona']) ?></small>
                    <?php endif; ?>
                </div>

                <div>
                    <label for="empresa" class="block text-sm font-semibold text-gray-700 mb-1">Empresa/Institución</label>
                    <input type="text" id="empresa" name="empresa"
                           class="w-full rounded-xl border border-gray-200 px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none"
                           value="<?= e($old['empresa'] ?? '') ?>" maxlength="150" placeholder="Opcional">
                </div>
            </div>

            <!-- Datos de Acceso (solo para personal del sistema) -->
            <div id="seccionAcceso" class="hidden">
                <h3 class="text-primary-700 font-bold text-lg mb-5 pb-2 border-b-2 border-primary-700 flex items-center gap-2">
                    <i class="fas fa-key"></i> Datos de Acceso al Sistema
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 mb-8">
                    <div>
                        <label for="username" class="block text-sm font-semibold text-gray-700 mb-1">Usuario <span class="text-red-500">*</span></label>
                        <input type="text" id="username" name="username"
                               class="w-full rounded-xl border border-gray-200 px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none"
                               value="<?= e($old['username'] ?? '') ?>" maxlength="50">
                        <?php if (isset($errors['username'])): ?>
                        <small class="text-red-600 text-xs mt-1 block"><?= e($errors['username']) ?></small>
                        <?php endif; ?>
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-semibold text-gray-700 mb-1">Email <span class="text-red-500">*</span></label>
                        <input type="email" id="email" name="email"
                               class="w-full rounded-xl border border-gray-200 px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none"
                               value="<?= e($old['email'] ?? '') ?>" maxlength="150">
                        <?php if (isset($errors['email'])): ?>
                        <small class="text-red-600 text-xs mt-1 block"><?= e($errors['email']) ?></small>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 mb-8">
                    <div>
                        <label for="password" class="block text-sm font-semibold text-gray-700 mb-1">Contraseña <span class="text-red-500">*</span></label>
                        <input type="password" id="password" name="password" minlength="8"
                               class="w-full rounded-xl border border-gray-200 px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none">
                        <small class="text-gray-500 text-xs mt-1 block">Mínimo 8 caracteres</small>
                        <?php if (isset($errors['password'])): ?>
                        <small class="text-red-600 text-xs mt-1 block"><?= e($errors['password']) ?></small>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Estado -->
            <div class="mb-8">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Estado <span class="text-red-500">*</span></label>
                <div class="flex gap-6">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="estado" value="activo" <?= ($old['estado'] ?? 'activo') === 'activo' ? 'checked' : '' ?> required class="accent-primary-700">
                        <span class="text-sm text-gray-700">Activo</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="estado" value="inactivo" <?= ($old['estado'] ?? '') === 'inactivo' ? 'checked' : '' ?> class="accent-primary-700">
                        <span class="text-sm text-gray-700">Inactivo</span>
                    </label>
                </div>
            </div>

            <!-- Botones -->
            <div class="flex gap-3 justify-end pt-6 border-t border-gray-100">
                <a href="<?= baseUrl('/usuarios') ?>"
                   class="inline-flex items-center gap-2 rounded-xl bg-gray-100 hover:bg-gray-200 transition text-gray-700 font-semibold px-5 py-2.5">
                    <i class="fas fa-times"></i> Cancelar
                </a>
                <button type="submit"
                        class="inline-flex items-center gap-2 rounded-2xl bg-primary-700 hover:bg-primary-800 transition text-white font-semibold px-5 py-2.5 shadow-md">
                    <i class="fas fa-save"></i> Guardar Usuario
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const tipoPersonaSelect = document.getElementById('tipo_persona');
    const seccionAcceso = document.getElementById('seccionAcceso');
    const usernameInput = document.getElementById('username');
    const passwordInput = document.getElementById('password');
    const emailInput = document.getElementById('email');

    function actualizarFormulario() {
        const tipoPersona = tipoPersonaSelect.value;
        const esPersonalSistema = ['admin', 'instructor', 'vigilante'].includes(tipoPersona);

        seccionAcceso.classList.toggle('hidden', !esPersonalSistema);

        usernameInput.required = esPersonalSistema;
        passwordInput.required = esPersonalSistema;
        emailInput.required = esPersonalSistema;
    }

    tipoPersonaSelect.addEventListener('change', actualizarFormulario);
    actualizarFormulario();
});
</script>

<?php require_once APP_PATH . '/views/layouts/footer.php'; ?>
