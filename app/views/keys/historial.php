<?php require_once APP_PATH . '/views/layouts/header.php'; ?>

<div>
    <div class="flex items-center justify-between flex-wrap gap-4 mb-6">
        <h1 class="text-2xl font-extrabold text-primary-700 flex items-center gap-2">
            <i class="fas fa-history"></i> Historial de Préstamos
        </h1>
    </div>

    <div class="bg-white/85 backdrop-blur-md border border-primary-100 rounded-3xl shadow-xl overflow-hidden">
        <div class="px-6 py-4 border-b border-primary-100 bg-gradient-to-r from-white to-primary-50 dark:from-[#1c1830] dark:to-[#241a42] flex items-center justify-between flex-wrap gap-3">
            <h3 class="text-lg font-bold text-primary-700">Últimos Movimientos de Llaves</h3>
            <div class="flex items-center gap-2 flex-wrap">
                <a href="<?= baseUrl('/control-llaves/export-historial') ?>"
                   class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 transition text-white font-semibold px-4 py-2 text-sm shadow-sm"
                   title="Exportar a Excel / CSV">
                    <i class="fas fa-file-excel"></i> Exportar CSV
                </a>
                <a href="<?= baseUrl('/control-llaves/export-pdf') ?>"
                   target="_blank"
                   class="inline-flex items-center gap-2 rounded-xl bg-red-600 hover:bg-red-700 transition text-white font-semibold px-4 py-2 text-sm shadow-sm"
                   title="Imprimir / Exportar PDF">
                    <i class="fas fa-file-pdf"></i> Exportar PDF
                </a>
                <a href="<?= baseUrl('/control-llaves') ?>"
                   class="inline-flex items-center gap-2 rounded-xl bg-gray-100 hover:bg-gray-200 transition text-gray-700 font-semibold px-4 py-2 text-sm">
                    <i class="fas fa-arrow-left"></i> Volver
                </a>
            </div>
        </div>

        <div class="p-6">
            <?php if (empty($prestamos)): ?>
                <div class="text-center py-16 text-gray-400">
                    <i class="fas fa-history text-5xl mb-4 block"></i>
                    <p>No hay movimientos registrados</p>
                </div>
            <?php else: ?>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead>
                            <tr class="bg-primary-50 text-primary-800 text-xs uppercase">
                                <th class="px-4 py-3 text-left font-bold">Fecha Préstamo</th>
                                <th class="px-4 py-3 text-left font-bold">Aula</th>
                                <th class="px-4 py-3 text-left font-bold">Receptor de la Llave</th>
                                <th class="px-4 py-3 text-left font-bold">Registrado Por</th>
                                <th class="px-4 py-3 text-left font-bold">Fecha Devolución</th>
                                <th class="px-4 py-3 text-left font-bold">Estado</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <?php foreach ($prestamos as $prestamo): ?>
                            <tr class="hover:bg-primary-50/50 transition align-top">
                                <td class="px-4 py-3 text-gray-600 whitespace-nowrap"><?= date('d/m/Y H:i', strtotime($prestamo['fecha_prestamo'])) ?></td>
                                <td class="px-4 py-3"><strong class="text-gray-800"><?= e($prestamo['aula_nombre']) ?></strong></td>
                                <td class="px-4 py-3">
                                    <strong class="text-gray-800 block"><?= e($prestamo['nombre_receptor']) ?></strong>
                                    <small class="text-gray-500 block">Doc: <?= e($prestamo['documento_receptor']) ?></small>
                                    <?php if (!empty($prestamo['departamento'])): ?>
                                    <small class="text-gray-500 block">Dpto: <?= e($prestamo['departamento']) ?></small>
                                    <?php endif; ?>
                                    <?php if (!empty($prestamo['telefono'])): ?>
                                    <small class="text-gray-500 block"><i class="fas fa-phone"></i> <?= e($prestamo['telefono']) ?></small>
                                    <?php endif; ?>
                                    <?php if (!empty($prestamo['observaciones_prestamo'])): ?>
                                    <small class="text-gray-400 block"><i class="fas fa-comment"></i> <?= e($prestamo['observaciones_prestamo']) ?></small>
                                    <?php endif; ?>
                                </td>
                                <td class="px-4 py-3">
                                    <span class="text-gray-700"><?= e($prestamo['nombres'] . ' ' . $prestamo['apellidos']) ?></span>
                                    <small class="text-gray-400 block"><?= e($prestamo['documento']) ?></small>
                                    <?php if ($prestamo['tipo_persona']): ?>
                                    <span class="inline-block mt-1 bg-gray-100 text-gray-600 px-2 py-0.5 rounded-xl text-xs font-semibold"><?= e($prestamo['tipo_persona']) ?></span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-4 py-3 text-gray-600 whitespace-nowrap">
                                    <?php if ($prestamo['fecha_devolucion']): ?>
                                        <?= date('d/m/Y H:i', strtotime($prestamo['fecha_devolucion'])) ?>
                                    <?php else: ?>
                                        <span class="text-gray-400">Pendiente</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-4 py-3">
                                    <?php
                                    $badgeClass = 'bg-gray-100 text-gray-600';
                                    $badgeIcon = 'clock';
                                    if ($prestamo['estado'] === 'PRESTADO') {
                                        $badgeClass = 'bg-amber-100 text-amber-700';
                                        $badgeIcon = 'hand-holding';
                                    } elseif ($prestamo['estado'] === 'DEVUELTO') {
                                        $badgeClass = 'bg-green-100 text-green-700';
                                        $badgeIcon = 'check';
                                    }
                                    ?>
                                    <span class="<?= $badgeClass ?> px-2 py-0.5 rounded-xl text-xs font-semibold whitespace-nowrap">
                                        <i class="fas fa-<?= $badgeIcon ?>"></i>
                                        <?= e($prestamo['estado']) ?>
                                    </span>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once APP_PATH . '/views/layouts/footer.php'; ?>
