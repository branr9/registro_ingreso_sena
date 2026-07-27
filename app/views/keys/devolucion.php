<?php require_once APP_PATH . '/views/layouts/header.php'; ?>

<div class="w-full max-w-3xl mx-auto">
    <h1 class="text-2xl font-extrabold text-primary-700 flex items-center gap-2 mb-1">
        <i class="fas fa-undo"></i> Devolver Llave
    </h1>
    <p class="text-sm text-gray-500 mb-6">Registre la devolución de la llave</p>

    <div class="bg-white/85 backdrop-blur-md border border-primary-100 rounded-3xl shadow-xl p-6 md:p-8 w-full">
        <div class="rounded-2xl bg-accent-50 border border-accent-200 text-accent-900 px-5 py-4 mb-6 text-sm leading-relaxed">
            <p class="flex items-center gap-2 font-semibold mb-1">
                <i class="fas fa-info-circle"></i> Tiene una llave prestada:
            </p>
            <p>Aula: <strong><?= e($prestamoActivo['aula_nombre']) ?></strong></p>
            <p>Entregada a: <strong><?= e($prestamoActivo['nombre_receptor']) ?></strong></p>
            <p>Fecha préstamo: <?= date('d/m/Y H:i', strtotime($prestamoActivo['fecha_prestamo'])) ?></p>
            <?php if (!empty($prestamoActivo['observaciones_prestamo'])): ?>
            <p>Observaciones del préstamo: <em><?= e($prestamoActivo['observaciones_prestamo']) ?></em></p>
            <?php endif; ?>
        </div>

        <form method="POST" action="<?= baseUrl('/control-llaves/procesar-devolucion') ?>" class="space-y-5">
            <input type="hidden" name="prestamo_id" value="<?= $prestamoActivo['id'] ?>">

            <div>
                <label for="observaciones" class="block text-sm font-semibold text-gray-700 mb-1">
                    <i class="fas fa-comment text-primary-600"></i> Observaciones de Devolución (Opcional)
                </label>
                <textarea id="observaciones"
                          name="observaciones"
                          rows="3"
                          class="w-full rounded-xl border border-gray-200 px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none"
                          placeholder="Estado del aula, incidencias, etc."></textarea>
            </div>

            <div class="flex items-center gap-3 pt-2">
                <button type="submit"
                        class="inline-flex items-center gap-2 rounded-2xl bg-primary-700 hover:bg-primary-800 transition text-white font-semibold px-5 py-2.5 shadow-md">
                    <i class="fas fa-check"></i> Devolver Llave
                </button>
                <a href="<?= baseUrl('/control-llaves') ?>"
                   class="inline-flex items-center gap-2 rounded-2xl bg-gray-100 hover:bg-gray-200 transition text-gray-700 font-semibold px-5 py-2.5">
                    <i class="fas fa-times"></i> Cancelar
                </a>
            </div>
        </form>
    </div>
</div>

<?php require_once APP_PATH . '/views/layouts/footer.php'; ?>
