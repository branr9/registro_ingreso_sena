<?php require_once APP_PATH . '/views/layouts/header.php'; ?>

<!-- Page Header -->
<div class="flex items-center justify-between mb-8 flex-wrap gap-4">
    <div class="flex items-center gap-3">
        <div class="w-10 h-10 rounded-2xl bg-primary-100 text-primary-700 flex items-center justify-center text-lg flex-shrink-0">
            <i class="fas fa-plus-circle"></i>
        </div>
        <div>
            <h1 class="text-2xl font-extrabold text-primary-700">Crear Permiso de Salida</h1>
        </div>
    </div>
    <a href="<?= baseUrl('/permisos') ?>"
       class="inline-flex items-center gap-2 rounded-xl bg-gray-100 hover:bg-gray-200 dark:bg-[#2d2550] dark:hover:bg-[#3a2e66] transition text-gray-700 dark:text-gray-300 font-semibold px-5 py-2.5 text-sm">
        <i class="fas fa-arrow-left"></i> Volver
    </a>
</div>

<?php
$flash = getFlashMessage();
if ($flash): ?>
<div class="mb-6 px-4 py-3 rounded-xl text-sm font-medium
    <?= $flash['type'] === 'success' ? 'bg-green-50 dark:bg-green-950/30 text-green-700 dark:text-green-400 border border-green-200 dark:border-green-800'
      : ($flash['type'] === 'error' ? 'bg-red-50 dark:bg-red-950/30 text-red-700 dark:text-red-400 border border-red-200 dark:border-red-800'
      : 'bg-blue-50 dark:bg-blue-950/30 text-blue-700 dark:text-blue-400 border border-blue-200 dark:border-blue-800') ?>">
    <?= e($flash['message']) ?>
</div>
<?php endif; ?>

<!-- Form Card -->
<div class="bg-white dark:bg-[#1c1830] border border-primary-100 dark:border-[#2d2550] rounded-3xl shadow-xl overflow-hidden">

    <!-- Card Header -->
    <div class="px-6 py-4 border-b border-primary-100 dark:border-[#2d2550] bg-gradient-to-r from-white to-primary-50 dark:from-[#1c1830] dark:to-[#241a42]">
        <h3 class="text-lg font-bold text-primary-700 flex items-center gap-2">
            <i class="fas fa-edit"></i> Información del Permiso
        </h3>
    </div>

    <div class="p-6 md:p-8">
        <form method="POST" action="<?= baseUrl('/permisos/store') ?>">
            <input type="hidden" name="csrf_token" value="<?= generateCSRFToken() ?>">

            <!-- Info alert -->
            <div class="mb-6 flex items-start gap-3 bg-blue-50 dark:bg-blue-950/30 border border-blue-200 dark:border-blue-800 rounded-2xl px-4 py-3 text-sm text-blue-700 dark:text-blue-300">
                <i class="fas fa-info-circle mt-0.5 flex-shrink-0"></i>
                <span>Complete la información del aprendiz que requiere permiso de salida. Los campos marcados con <strong>*</strong> son obligatorios.</span>
            </div>

            <!-- Fila: Documento + Nombre -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
                <div>
                    <label for="documento_aprendiz" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">
                        <i class="fas fa-id-card mr-1 text-primary-500"></i> Documento del Aprendiz *
                    </label>
                    <input type="text"
                           id="documento_aprendiz"
                           name="documento_aprendiz"
                           required
                           placeholder="Ingrese el documento y presione Tab"
                           maxlength="30"
                           autocomplete="off"
                           class="w-full rounded-xl border border-gray-200 dark:border-[#3a2e66] bg-white dark:bg-[#241a42] text-gray-800 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500 px-4 py-2.5 text-sm outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition">
                    <p id="busqueda-mensaje" class="text-xs text-gray-400 dark:text-gray-500 mt-1.5">
                        Ingrese el documento y presione <kbd class="px-1 py-0.5 rounded bg-gray-100 dark:bg-[#2d2550] text-gray-600 dark:text-gray-300 text-xs font-mono">Tab</kbd> para buscar
                    </p>
                </div>
                <div>
                    <label for="nombre_aprendiz" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">
                        <i class="fas fa-user mr-1 text-primary-500"></i> Nombre Completo del Aprendiz
                    </label>
                    <input type="text"
                           id="nombre_aprendiz"
                           name="nombre_aprendiz"
                           readonly
                           placeholder="Se mostrará automáticamente"
                           class="w-full rounded-xl border border-gray-200 dark:border-[#3a2e66] bg-gray-50 dark:bg-[#1a1328] text-gray-700 dark:text-gray-300 placeholder-gray-400 dark:placeholder-gray-500 px-4 py-2.5 text-sm outline-none cursor-not-allowed opacity-80 transition">
                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-1.5">El nombre se obtiene automáticamente del sistema</p>
                </div>
            </div>

            <!-- Fila: Fecha + Hora salida + Hora regreso -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-5">
                <div>
                    <label for="fecha_permiso" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">
                        <i class="fas fa-calendar mr-1 text-primary-500"></i> Fecha del Permiso *
                    </label>
                    <input type="date"
                           id="fecha_permiso"
                           name="fecha_permiso"
                           required
                           min="<?= date('Y-m-d') ?>"
                           value="<?= date('Y-m-d') ?>"
                           class="w-full rounded-xl border border-gray-200 dark:border-[#3a2e66] bg-white dark:bg-[#241a42] text-gray-800 dark:text-gray-100 px-4 py-2.5 text-sm outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition">
                </div>
                <div>
                    <label for="hora_salida" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">
                        <i class="fas fa-clock mr-1 text-primary-500"></i> Hora de Salida *
                    </label>
                    <input type="time"
                           id="hora_salida"
                           name="hora_salida"
                           required
                           class="w-full rounded-xl border border-gray-200 dark:border-[#3a2e66] bg-white dark:bg-[#241a42] text-gray-800 dark:text-gray-100 px-4 py-2.5 text-sm outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition">
                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-1.5">Hora aproximada de salida</p>
                </div>
                <div>
                    <label for="hora_regreso" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">
                        <i class="fas fa-clock mr-1 text-primary-500"></i> Hora de Regreso <span class="font-normal text-gray-400">(Opcional)</span>
                    </label>
                    <input type="time"
                           id="hora_regreso"
                           name="hora_regreso"
                           class="w-full rounded-xl border border-gray-200 dark:border-[#3a2e66] bg-white dark:bg-[#241a42] text-gray-800 dark:text-gray-100 px-4 py-2.5 text-sm outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition">
                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-1.5">Hora estimada de regreso</p>
                </div>
            </div>

            <!-- Motivo -->
            <div class="mb-5">
                <label for="motivo" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">
                    <i class="fas fa-clipboard mr-1 text-primary-500"></i> Motivo de la Salida *
                </label>
                <textarea id="motivo"
                          name="motivo"
                          rows="3"
                          required
                          maxlength="500"
                          placeholder="Describa el motivo por el cual el aprendiz necesita salir (Ej: Cita médica, trámite bancario, emergencia familiar, etc.)"
                          class="w-full rounded-xl border border-gray-200 dark:border-[#3a2e66] bg-white dark:bg-[#241a42] text-gray-800 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500 px-4 py-2.5 text-sm outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition resize-none"></textarea>
                <p class="text-xs text-gray-400 dark:text-gray-500 mt-1.5">Máximo 500 caracteres</p>
            </div>

            <!-- Observaciones -->
            <div class="mb-6">
                <label for="observaciones" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">
                    <i class="fas fa-sticky-note mr-1 text-primary-500"></i> Observaciones Adicionales <span class="font-normal text-gray-400">(Opcional)</span>
                </label>
                <textarea id="observaciones"
                          name="observaciones"
                          rows="2"
                          maxlength="500"
                          placeholder="Información adicional relevante"
                          class="w-full rounded-xl border border-gray-200 dark:border-[#3a2e66] bg-white dark:bg-[#241a42] text-gray-800 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500 px-4 py-2.5 text-sm outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition resize-none"></textarea>
            </div>

            <!-- Instructor que autoriza -->
            <div class="mb-6 flex items-center gap-3 bg-green-50 dark:bg-green-950/30 border border-green-200 dark:border-green-800 rounded-2xl px-4 py-3 text-sm text-green-700 dark:text-green-300">
                <i class="fas fa-user-tie flex-shrink-0"></i>
                <span><strong>Instructor que autoriza:</strong> <?= e($_SESSION['user_name']) ?></span>
            </div>

            <!-- Botones -->
            <div class="flex items-center gap-3 flex-wrap">
                <button type="submit"
                        id="btn-submit"
                        class="inline-flex items-center gap-2 rounded-xl bg-primary-700 hover:bg-primary-800 transition text-white font-semibold px-6 py-2.5 text-sm shadow-md disabled:opacity-50 disabled:cursor-not-allowed">
                    <i class="fas fa-save"></i> Crear Permiso
                </button>
                <a href="<?= baseUrl('/permisos') ?>"
                   class="inline-flex items-center gap-2 rounded-xl bg-gray-100 hover:bg-gray-200 dark:bg-[#2d2550] dark:hover:bg-[#3a2e66] transition text-gray-700 dark:text-gray-300 font-semibold px-5 py-2.5 text-sm">
                    <i class="fas fa-times"></i> Cancelar
                </a>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-sugerir hora actual + 15 minutos para hora de salida
    const horaSalidaInput = document.getElementById('hora_salida');
    if (!horaSalidaInput.value) {
        const now = new Date();
        now.setMinutes(now.getMinutes() + 15);
        const hours   = String(now.getHours()).padStart(2, '0');
        const minutes = String(now.getMinutes()).padStart(2, '0');
        horaSalidaInput.value = `${hours}:${minutes}`;
    }

    const documentoInput   = document.getElementById('documento_aprendiz');
    const nombreInput      = document.getElementById('nombre_aprendiz');
    const mensajeBusqueda  = document.getElementById('busqueda-mensaje');
    const submitBtn        = document.getElementById('btn-submit');

    documentoInput.addEventListener('blur', buscarAprendiz);
    documentoInput.addEventListener('keydown', function(e) {
        if (e.key === 'Tab' || e.key === 'Enter') {
            e.preventDefault();
            buscarAprendiz();
        }
    });

    async function buscarAprendiz() {
        const documento = documentoInput.value.trim();

        if (!documento) {
            nombreInput.value = '';
            mensajeBusqueda.textContent = 'Ingrese el documento y presione Tab para buscar';
            mensajeBusqueda.className = 'text-xs text-gray-400 dark:text-gray-500 mt-1.5';
            submitBtn.disabled = false;
            return;
        }

        mensajeBusqueda.textContent = 'Buscando aprendiz...';
        mensajeBusqueda.className = 'text-xs text-blue-500 mt-1.5';
        nombreInput.value = 'Buscando...';
        submitBtn.disabled = true;

        try {
            const response = await fetch(`<?= baseUrl('/permisos/buscar-aprendiz') ?>?documento=${encodeURIComponent(documento)}`);
            const data = await response.json();

            if (data.success) {
                nombreInput.value = data.persona.nombre_completo;
                mensajeBusqueda.textContent = '✓ Aprendiz encontrado en el sistema';
                mensajeBusqueda.className = 'text-xs text-green-600 dark:text-green-400 mt-1.5';
                submitBtn.disabled = false;
                document.getElementById('fecha_permiso').focus();
            } else {
                nombreInput.value = '';
                mensajeBusqueda.textContent = '✗ ' + data.message;
                mensajeBusqueda.className = 'text-xs text-red-500 mt-1.5';
                submitBtn.disabled = true;
            }
        } catch (error) {
            console.error('Error:', error);
            nombreInput.value = '';
            mensajeBusqueda.textContent = '✗ Error al buscar aprendiz';
            mensajeBusqueda.className = 'text-xs text-red-500 mt-1.5';
            submitBtn.disabled = true;
        }
    }

    documentoInput.addEventListener('input', function() {
        if (nombreInput.value && nombreInput.value !== 'Buscando...') {
            nombreInput.value = '';
            mensajeBusqueda.textContent = 'Presione Tab para buscar nuevamente';
            mensajeBusqueda.className = 'text-xs text-gray-400 dark:text-gray-500 mt-1.5';
        }
    });
});
</script>

<?php require_once APP_PATH . '/views/layouts/footer.php'; ?>
