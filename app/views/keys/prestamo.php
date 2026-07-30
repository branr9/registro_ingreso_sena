<?php require_once APP_PATH . '/views/layouts/header.php'; ?>

<style>
    @keyframes pulse-soft {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.15); }
    }
    .pulse-icon { animation: pulse-soft 2s infinite; }
</style>

<div>
    <div class="flex items-start justify-between gap-4 flex-wrap mb-6">
        <div>
            <h1 class="text-2xl font-extrabold text-primary-700 flex items-center gap-2 mb-1">
                <i class="fas fa-key"></i> Préstamo de Llaves
            </h1>
            <p class="text-sm text-gray-500">Seleccione el aula para tomar o devolver la llave</p>
        </div>
        <a href="<?= baseUrl('/control-llaves') ?>"
           class="inline-flex items-center gap-2 rounded-xl bg-gray-100 hover:bg-gray-200 transition text-gray-700 font-semibold px-4 py-2 text-sm">
            <i class="fas fa-times"></i> Cancelar
        </a>
    </div>

    <?php if (empty($aulas)): ?>
        <div class="rounded-2xl bg-amber-50 border border-amber-200 text-amber-800 px-5 py-4 flex items-center gap-2">
            <i class="fas fa-exclamation-triangle"></i>
            No hay aulas registradas en el sistema.
        </div>
    <?php else: ?>
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <?php foreach ($aulas as $aula): ?>
            <div class="bg-white rounded-3xl shadow-md border border-primary-100 p-6 <?= $aula['estado'] !== 'ACTIVO' ? 'opacity-60' : '' ?> hover:shadow-lg transition">
                <div class="flex items-center justify-between pb-4 mb-4 border-b border-gray-100">
                    <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                        <i class="fas fa-door-open text-primary-600"></i> <?= e($aula['nombre']) ?>
                    </h3>
                    <span class="px-2 py-0.5 rounded-xl text-xs font-semibold <?= $aula['estado'] === 'ACTIVO' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' ?>">
                        <?= e($aula['estado']) ?>
                    </span>
                </div>

                <div class="space-y-2 mb-4 text-sm text-gray-600">
                    <div class="flex items-center gap-2">
                        <i class="fas fa-key text-gray-400 w-5 text-center"></i>
                        <span>Llaves totales: <strong class="text-gray-800"><?= $aula['cantidad_llaves'] ?></strong></span>
                    </div>
                    <div class="flex items-center gap-2">
                        <i class="fas fa-check-circle text-green-500 w-5 text-center"></i>
                        <span>Disponibles: <strong class="text-gray-800"><?= $aula['llaves_disponibles'] ?></strong></span>
                    </div>
                    <div class="flex items-center gap-2">
                        <i class="fas fa-hand-holding text-red-500 w-5 text-center"></i>
                        <span>Prestadas: <strong class="text-gray-800"><?= $aula['llaves_prestadas'] ?></strong></span>
                    </div>
                </div>

                <?php if (!empty($aula['prestamos_activos'])): ?>
                <div class="bg-gray-50 rounded-2xl p-4 mb-4">
                    <h4 class="text-xs font-bold text-gray-500 uppercase mb-3 flex items-center gap-2">
                        <i class="fas fa-users"></i> Llaves prestadas
                    </h4>
                    <div class="space-y-2">
                        <?php foreach ($aula['prestamos_activos'] as $prestamo): ?>
                        <div class="flex items-center justify-between gap-3 bg-white rounded-xl px-3 py-2 shadow-sm">
                            <div class="flex flex-col min-w-0">
                                <strong class="text-sm text-gray-800 truncate"><?= e($prestamo['nombre_receptor']) ?></strong>
                                <small class="text-xs text-gray-500">Doc: <?= e($prestamo['documento_receptor']) ?></small>
                                <?php if ($prestamo['departamento']): ?>
                                <small class="text-xs text-gray-500">Dpto: <?= e($prestamo['departamento']) ?></small>
                                <?php endif; ?>
                                <small class="text-xs text-gray-400">Desde: <?= date('d/m/Y H:i', strtotime($prestamo['fecha_prestamo'])) ?></small>
                            </div>
                            <button type="button"
                                    class="shrink-0 inline-flex items-center gap-1 rounded-lg bg-primary-100 text-primary-700 hover:bg-primary-200 transition text-xs font-semibold px-3 py-1.5"
                                    onclick="devolverLlave(<?= $prestamo['id'] ?>, '<?= e($aula['nombre']) ?>', '<?= e($prestamo['nombre_receptor']) ?>')">
                                <i class="fas fa-undo"></i> Devolver
                            </button>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>

                <div>
                    <?php if ($aula['estado'] === 'ACTIVO' && $aula['llaves_disponibles'] > 0): ?>
                    <button type="button"
                            class="w-full rounded-2xl bg-gradient-to-r from-green-600 to-emerald-500 hover:from-green-700 hover:to-emerald-600 transition text-white font-bold uppercase tracking-wide text-sm py-3 shadow-md"
                            onclick="tomarLlave(<?= $aula['id'] ?>, '<?= e($aula['nombre']) ?>')">
                        <i class="fas fa-hand-holding pulse-icon"></i> Tomar Llave
                    </button>
                    <?php else: ?>
                    <button type="button" class="w-full rounded-2xl bg-gray-200 text-gray-500 font-bold uppercase tracking-wide text-sm py-3 cursor-not-allowed" disabled>
                        <i class="fas fa-ban"></i> No Disponible
                    </button>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<!-- Modal para tomar llave -->
<div id="modalTomarLlave" class="hidden fixed inset-0 z-50 items-center justify-center bg-black/50 p-4">
    <div class="bg-white rounded-3xl w-full max-w-lg max-h-[90vh] overflow-y-auto shadow-2xl">
        <div class="flex items-center justify-between px-6 py-5 border-b border-gray-100">
            <h2 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                <i class="fas fa-hand-holding text-primary-600"></i> Tomar Llave
            </h2>
            <button type="button" onclick="cerrarModal()" class="text-gray-400 hover:text-gray-700 text-2xl leading-none">&times;</button>
        </div>
        <form method="POST" action="<?= baseUrl('/control-llaves/procesar-prestamo') ?>">
            <div class="px-6 py-5 space-y-4">
                <input type="hidden" id="aula_id" name="aula_id">
                <p class="text-sm text-gray-600">Aula: <strong id="aula_nombre" class="text-gray-800"></strong></p>

                <div>
                    <label for="nombre_receptor" class="block text-sm font-semibold text-gray-700 mb-1">
                        <i class="fas fa-user text-primary-600"></i> Nombre Completo *
                    </label>
                    <input type="text" id="nombre_receptor" name="nombre_receptor"
                           class="w-full rounded-xl border border-gray-200 px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none"
                           placeholder="Nombre completo" required maxlength="150">
                </div>

                <div>
                    <label for="documento_receptor" class="block text-sm font-semibold text-gray-700 mb-1">
                        <i class="fas fa-id-card text-primary-600"></i> Documento *
                    </label>
                    <input type="text" id="documento_receptor" name="documento_receptor"
                           class="w-full rounded-xl border border-gray-200 px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none"
                           placeholder="Número de documento" required maxlength="20">
                </div>

                <div>
                    <label for="departamento" class="block text-sm font-semibold text-gray-700 mb-1">
                        <i class="fas fa-building text-primary-600"></i> Departamento
                    </label>
                    <input type="text" id="departamento" name="departamento"
                           class="w-full rounded-xl border border-gray-200 px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none"
                           placeholder="Departamento al que pertenece" maxlength="100">
                </div>

                <div>
                    <label for="telefono" class="block text-sm font-semibold text-gray-700 mb-1">
                        <i class="fas fa-phone text-primary-600"></i> Teléfono
                    </label>
                    <input type="tel" id="telefono" name="telefono"
                           class="w-full rounded-xl border border-gray-200 px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none"
                           placeholder="Número de teléfono" maxlength="20">
                </div>

                <div>
                    <label for="observaciones" class="block text-sm font-semibold text-gray-700 mb-1">
                        <i class="fas fa-comment text-primary-600"></i> Observaciones
                    </label>
                    <textarea id="observaciones" name="observaciones" rows="3"
                              class="w-full rounded-xl border border-gray-200 px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none"
                              placeholder="Motivo del préstamo u otra información"></textarea>
                </div>
            </div>
            <div class="flex items-center justify-end gap-3 px-6 py-4 border-t border-gray-100">
                <button type="button" onclick="cerrarModal()"
                        class="inline-flex items-center gap-2 rounded-xl bg-gray-100 hover:bg-gray-200 transition text-gray-700 font-semibold px-4 py-2 text-sm">
                    <i class="fas fa-times"></i> Cancelar
                </button>
                <button type="submit"
                        class="inline-flex items-center gap-2 rounded-xl bg-green-600 hover:bg-green-700 transition text-white font-semibold px-4 py-2 text-sm">
                    <i class="fas fa-check"></i> Registrar Préstamo
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal para devolver llave -->
<div id="modalDevolverLlave" class="hidden fixed inset-0 z-50 items-center justify-center bg-black/50 p-4">
    <div class="bg-white rounded-3xl w-full max-w-lg max-h-[90vh] overflow-y-auto shadow-2xl">
        <div class="flex items-center justify-between px-6 py-5 border-b border-gray-100">
            <h2 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                <i class="fas fa-undo text-primary-600"></i> Devolver Llave
            </h2>
            <button type="button" onclick="cerrarModalDevolver()" class="text-gray-400 hover:text-gray-700 text-2xl leading-none">&times;</button>
        </div>
        <form method="POST" action="<?= baseUrl('/control-llaves/procesar-devolucion') ?>">
            <div class="px-6 py-5 space-y-3">
                <input type="hidden" id="prestamo_id_dev" name="prestamo_id">
                <p class="text-sm text-gray-600">Aula: <strong id="aula_nombre_dev" class="text-gray-800"></strong></p>
                <p class="text-sm text-gray-600">Receptor: <strong id="receptor_nombre_dev" class="text-gray-800"></strong></p>

                <div>
                    <label for="observaciones_dev" class="block text-sm font-semibold text-gray-700 mb-1">
                        <i class="fas fa-comment text-primary-600"></i> Observaciones de Devolución
                    </label>
                    <textarea id="observaciones_dev" name="observaciones" rows="3"
                              class="w-full rounded-xl border border-gray-200 px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none"
                              placeholder="Estado del aula, incidencias, etc."></textarea>
                </div>
            </div>
            <div class="flex items-center justify-end gap-3 px-6 py-4 border-t border-gray-100">
                <button type="button" onclick="cerrarModalDevolver()"
                        class="inline-flex items-center gap-2 rounded-xl bg-gray-100 hover:bg-gray-200 transition text-gray-700 font-semibold px-4 py-2 text-sm">
                    <i class="fas fa-times"></i> Cancelar
                </button>
                <button type="submit"
                        class="inline-flex items-center gap-2 rounded-xl bg-primary-700 hover:bg-primary-800 transition text-white font-semibold px-4 py-2 text-sm">
                    <i class="fas fa-check"></i> Confirmar Devolución
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function tomarLlave(aulaId, aulaNombre) {
    document.getElementById('aula_id').value = aulaId;
    document.getElementById('aula_nombre').textContent = aulaNombre;
    const modal = document.getElementById('modalTomarLlave');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    document.getElementById('nombre_receptor').focus();
}

function devolverLlave(prestamoId, aulaNombre, receptorNombre) {
    document.getElementById('prestamo_id_dev').value = prestamoId;
    document.getElementById('aula_nombre_dev').textContent = aulaNombre;
    document.getElementById('receptor_nombre_dev').textContent = receptorNombre;
    const modal = document.getElementById('modalDevolverLlave');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function cerrarModal() {
    const modal = document.getElementById('modalTomarLlave');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
    document.getElementById('nombre_receptor').value = '';
    document.getElementById('documento_receptor').value = '';
    document.getElementById('departamento').value = '';
    document.getElementById('telefono').value = '';
    document.getElementById('observaciones').value = '';
}

function cerrarModalDevolver() {
    const modal = document.getElementById('modalDevolverLlave');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
    document.getElementById('observaciones_dev').value = '';
}

// Cerrar modal al hacer clic fuera
window.onclick = function(event) {
    const modalTomar = document.getElementById('modalTomarLlave');
    const modalDevolver = document.getElementById('modalDevolverLlave');
    if (event.target === modalTomar) {
        cerrarModal();
    }
    if (event.target === modalDevolver) {
        cerrarModalDevolver();
    }
}

// Cerrar con tecla Escape
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        cerrarModal();
        cerrarModalDevolver();
    }
});
</script>

<?php require_once APP_PATH . '/views/layouts/footer.php'; ?>
