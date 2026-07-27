<?php
/**
 * Vista: Formulario de Registro de Entrada - Personal Externo
 */
require_once APP_PATH . '/views/layouts/header.php';
$errors = $_SESSION['errors'] ?? [];
$old = $_SESSION['old'] ?? [];
unset($_SESSION['errors'], $_SESSION['old']);
?>

<div class="max-w-4xl">
    <div class="flex items-center gap-4 flex-wrap mb-2">
        <a href="<?= baseUrl('/acceso-externo') ?>"
           class="inline-flex items-center gap-2 rounded-xl bg-gray-100 hover:bg-gray-200 transition text-gray-700 font-semibold px-4 py-2 text-sm">
            <i class="fas fa-arrow-left"></i> Volver
        </a>
        <h1 class="text-2xl font-extrabold text-primary-700 flex items-center gap-2">
            <i class="fas fa-user-plus"></i> Registrar Entrada - Personal Externo
        </h1>
    </div>
    <p class="text-sm text-gray-500 mb-8">Complete el formulario para registrar el ingreso de personal sin carnet (visitantes, contratistas, proveedores)</p>

    <div class="bg-white/85 backdrop-blur-md border border-primary-100 rounded-3xl shadow-xl p-6 md:p-8">
        <form method="POST" action="<?= baseUrl('/acceso-externo/guardar-entrada') ?>">
            <input type="hidden" name="csrf_token" value="<?= generateCSRFToken() ?>">

            <!-- Datos del Visitante -->
            <h3 class="text-primary-700 font-bold text-lg mb-5 pb-2 border-b-2 border-primary-700 flex items-center gap-2">
                <i class="fas fa-address-card"></i> Datos del Visitante
            </h3>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 mb-6">
                <div>
                    <label for="tipo_documento" class="block text-sm font-semibold text-gray-700 mb-1">Tipo de Documento <span class="text-red-500">*</span></label>
                    <select id="tipo_documento" name="tipo_documento" required
                            class="w-full rounded-xl border border-gray-200 px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none">
                        <option value="CC" <?= ($old['tipo_documento'] ?? 'CC') === 'CC' ? 'selected' : '' ?>>Cédula de Ciudadanía</option>
                        <option value="CE" <?= ($old['tipo_documento'] ?? '') === 'CE' ? 'selected' : '' ?>>Cédula de Extranjería</option>
                        <option value="TI" <?= ($old['tipo_documento'] ?? '') === 'TI' ? 'selected' : '' ?>>Tarjeta de Identidad</option>
                        <option value="PAS" <?= ($old['tipo_documento'] ?? '') === 'PAS' ? 'selected' : '' ?>>Pasaporte</option>
                        <option value="NIT" <?= ($old['tipo_documento'] ?? '') === 'NIT' ? 'selected' : '' ?>>NIT</option>
                    </select>
                </div>

                <div>
                    <label for="documento" class="block text-sm font-semibold text-gray-700 mb-1">Número de Documento <span class="text-red-500">*</span></label>
                    <input type="text" id="documento" name="documento"
                           class="w-full rounded-xl border border-gray-200 px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none"
                           value="<?= e($old['documento'] ?? '') ?>" required maxlength="20"
                           placeholder="Ej: 1234567890">
                    <?php if (isset($errors['documento'])): ?>
                    <small class="text-red-500"><?= e($errors['documento']) ?></small>
                    <?php endif; ?>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 mb-6">
                <div>
                    <label for="nombres" class="block text-sm font-semibold text-gray-700 mb-1">Nombres <span class="text-red-500">*</span></label>
                    <input type="text" id="nombres" name="nombres"
                           class="w-full rounded-xl border border-gray-200 px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none"
                           value="<?= e($old['nombres'] ?? '') ?>" required maxlength="100"
                           placeholder="Ej: Juan Carlos">
                    <?php if (isset($errors['nombres'])): ?>
                    <small class="text-red-500"><?= e($errors['nombres']) ?></small>
                    <?php endif; ?>
                </div>

                <div>
                    <label for="apellidos" class="block text-sm font-semibold text-gray-700 mb-1">Apellidos</label>
                    <input type="text" id="apellidos" name="apellidos"
                           class="w-full rounded-xl border border-gray-200 px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none"
                           value="<?= e($old['apellidos'] ?? '') ?>" maxlength="100"
                           placeholder="Ej: Pérez González">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-5 mb-8">
                <div>
                    <label for="empresa" class="block text-sm font-semibold text-gray-700 mb-1">Empresa/Institución</label>
                    <input type="text" id="empresa" name="empresa"
                           class="w-full rounded-xl border border-gray-200 px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none"
                           value="<?= e($old['empresa'] ?? '') ?>" maxlength="150"
                           placeholder="Ej: Tech Solutions S.A.S">
                </div>

                <div>
                    <label for="telefono" class="block text-sm font-semibold text-gray-700 mb-1">Teléfono</label>
                    <input type="tel" id="telefono" name="telefono"
                           class="w-full rounded-xl border border-gray-200 px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none"
                           value="<?= e($old['telefono'] ?? '') ?>" maxlength="20"
                           placeholder="Ej: 3001234567">
                </div>

                <div>
                    <label for="email" class="block text-sm font-semibold text-gray-700 mb-1">Email</label>
                    <input type="email" id="email" name="email"
                           class="w-full rounded-xl border border-gray-200 px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none"
                           value="<?= e($old['email'] ?? '') ?>" maxlength="150"
                           placeholder="ejemplo@empresa.com">
                    <?php if (isset($errors['email'])): ?>
                    <small class="text-red-500"><?= e($errors['email']) ?></small>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Información de la Visita -->
            <h3 class="text-primary-700 font-bold text-lg mb-5 pb-2 border-b-2 border-primary-700 flex items-center gap-2">
                <i class="fas fa-clipboard-list"></i> Información de la Visita
            </h3>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 mb-6">
                <div>
                    <label for="motivo_visita" class="block text-sm font-semibold text-gray-700 mb-1">Motivo de la Visita <span class="text-red-500">*</span></label>
                    <input type="text" id="motivo_visita" name="motivo_visita"
                           class="w-full rounded-xl border border-gray-200 px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none"
                           value="<?= e($old['motivo_visita'] ?? '') ?>" required maxlength="255"
                           placeholder="Ej: Reunión con instructor, Mantenimiento de equipos">
                    <?php if (isset($errors['motivo_visita'])): ?>
                    <small class="text-red-500"><?= e($errors['motivo_visita']) ?></small>
                    <?php endif; ?>
                </div>

                <div>
                    <label for="persona_visitada" class="block text-sm font-semibold text-gray-700 mb-1">Persona a Visitar</label>
                    <input type="text" id="persona_visitada" name="persona_visitada"
                           class="w-full rounded-xl border border-gray-200 px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none"
                           value="<?= e($old['persona_visitada'] ?? '') ?>" maxlength="150"
                           placeholder="Ej: Ing. María López">
                </div>
            </div>

            <div class="space-y-5 mb-8">
                <div>
                    <label for="area_destino" class="block text-sm font-semibold text-gray-700 mb-1">Área/Departamento de Destino</label>
                    <input type="text" id="area_destino" name="area_destino"
                           class="w-full rounded-xl border border-gray-200 px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none"
                           value="<?= e($old['area_destino'] ?? '') ?>" maxlength="100"
                           placeholder="Ej: Coordinación Académica, Laboratorio de Redes">
                </div>

                <div>
                    <label for="observaciones" class="block text-sm font-semibold text-gray-700 mb-1">Observaciones</label>
                    <textarea id="observaciones" name="observaciones" rows="3" maxlength="500"
                              class="w-full rounded-xl border border-gray-200 px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none"
                              placeholder="Información adicional relevante"><?= e($old['observaciones'] ?? '') ?></textarea>
                    <small class="text-gray-400">Opcional. Máximo 500 caracteres</small>
                </div>
            </div>

            <!-- Botones -->
            <div class="flex items-center justify-end gap-3 pt-5 border-t border-gray-100">
                <a href="<?= baseUrl('/acceso-externo') ?>"
                   class="inline-flex items-center gap-2 rounded-2xl bg-gray-100 hover:bg-gray-200 transition text-gray-700 font-semibold px-5 py-2.5">
                    <i class="fas fa-times"></i> Cancelar
                </a>
                <button type="submit"
                        class="inline-flex items-center gap-2 rounded-2xl bg-primary-700 hover:bg-primary-800 transition text-white font-semibold px-5 py-2.5 shadow-md">
                    <i class="fas fa-save"></i> Registrar Entrada
                </button>
            </div>
        </form>
    </div>
</div>

<?php require_once APP_PATH . '/views/layouts/footer.php'; ?>
