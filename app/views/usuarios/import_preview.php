<?php
/**
 * Vista: Vista Previa de Importación
 */
require_once APP_PATH . '/views/layouts/header.php';
$preview = $_SESSION['import_data']['preview'] ?? [];
$previewData = $preview['preview'] ?? [];
$errors = $preview['errors'] ?? [];
$total = $preview['total'] ?? 0;
$valid = $preview['valid'] ?? 0;
$invalid = count($errors);
$tasa = $total > 0 ? round(($valid / $total) * 100, 1) : 0;
?>

<div>
    <!-- Header -->
    <div class="flex items-center gap-3 mb-8">
        <div class="w-12 h-12 rounded-2xl bg-primary-100 text-primary-700 flex items-center justify-center text-xl flex-shrink-0">
            <i class="fas fa-eye"></i>
        </div>
        <div>
            <h1 class="text-2xl font-extrabold text-primary-700">Vista Previa de Importación</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Revisa los datos antes de confirmar la importación</p>
        </div>
    </div>

    <!-- Tarjetas de resumen -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
        <div class="bg-white dark:bg-[#1c1830] rounded-3xl shadow-md border border-primary-100 dark:border-[#2d2550] p-5 flex items-center gap-4">
            <div class="w-14 h-14 rounded-2xl bg-primary-100 text-primary-700 flex items-center justify-center text-2xl flex-shrink-0">
                <i class="fas fa-file-alt"></i>
            </div>
            <div>
                <h3 class="text-3xl font-extrabold text-gray-800 dark:text-white"><?= $total ?></h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">Total de Registros</p>
            </div>
        </div>
        <div class="bg-white dark:bg-[#1c1830] rounded-3xl shadow-md border border-primary-100 dark:border-[#2d2550] p-5 flex items-center gap-4">
            <div class="w-14 h-14 rounded-2xl bg-green-100 text-green-700 flex items-center justify-center text-2xl flex-shrink-0">
                <i class="fas fa-check-circle"></i>
            </div>
            <div>
                <h3 class="text-3xl font-extrabold text-green-700"><?= $valid ?></h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">Registros Válidos</p>
            </div>
        </div>
        <div class="bg-white dark:bg-[#1c1830] rounded-3xl shadow-md border border-primary-100 dark:border-[#2d2550] p-5 flex items-center gap-4">
            <div class="w-14 h-14 rounded-2xl <?= $invalid > 0 ? 'bg-red-100 text-red-600' : 'bg-gray-100 text-gray-400' ?> flex items-center justify-center text-2xl flex-shrink-0">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <div>
                <h3 class="text-3xl font-extrabold <?= $invalid > 0 ? 'text-red-600' : 'text-gray-400 dark:text-gray-500' ?>"><?= $invalid ?></h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">Registros con Errores</p>
            </div>
        </div>
        <div class="bg-white dark:bg-[#1c1830] rounded-3xl shadow-md border border-primary-100 dark:border-[#2d2550] p-5 flex items-center gap-4">
            <div class="w-14 h-14 rounded-2xl bg-primary-100 text-primary-700 flex items-center justify-center text-2xl flex-shrink-0">
                <i class="fas fa-percent"></i>
            </div>
            <div>
                <h3 class="text-3xl font-extrabold text-gray-800 dark:text-white"><?= $tasa ?>%</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">Tasa de Éxito</p>
            </div>
        </div>
    </div>

    <?php if ($invalid > 0): ?>
    <!-- Errores encontrados -->
    <div class="bg-white dark:bg-[#1c1830] border border-red-200 dark:border-red-900 rounded-3xl shadow-xl overflow-hidden mb-8">
        <div class="px-6 py-4 border-b border-red-100 dark:border-red-900 bg-red-50 dark:bg-red-950/30">
            <h3 class="text-lg font-bold text-red-600 dark:text-red-400 flex items-center gap-2">
                <i class="fas fa-exclamation-circle"></i> Errores Encontrados (<?= $invalid ?>)
            </h3>
        </div>
        <div class="p-6 max-h-72 overflow-y-auto space-y-3">
            <?php foreach (array_slice($errors, 0, 50) as $error): ?>
            <div class="bg-red-50 dark:bg-red-950/30 border-l-4 border-red-500 rounded-xl px-4 py-3">
                <p class="font-semibold text-red-600 dark:text-red-400 text-sm mb-1">Fila <?= $error['line'] ?>:</p>
                <ul class="list-disc list-inside text-sm text-gray-700 dark:text-gray-300 space-y-0.5">
                    <?php foreach ($error['errors'] as $err): ?>
                    <li><?= e($err) ?></li>
                    <?php endforeach; ?>
                </ul>
                <p class="text-xs text-gray-400 dark:text-gray-500 mt-2">
                    Datos: <?= e(json_encode($error['data'], JSON_UNESCAPED_UNICODE)) ?>
                </p>
            </div>
            <?php endforeach; ?>
            <?php if ($invalid > 50): ?>
            <p class="text-center text-sm text-gray-400 dark:text-gray-500 pt-2">
                Mostrando solo los primeros 50 errores de <?= $invalid ?> totales.
            </p>
            <?php endif; ?>
        </div>
    </div>
    <?php endif; ?>

    <!-- Vista previa de datos -->
    <div class="bg-white dark:bg-[#1c1830] border border-primary-100 dark:border-[#2d2550] rounded-3xl shadow-xl overflow-hidden mb-8">
        <div class="px-6 py-4 border-b border-primary-100 dark:border-[#2d2550] bg-gradient-to-r from-white to-primary-50 dark:from-[#1c1830] dark:to-[#241a42]">
            <h3 class="text-lg font-bold text-primary-700 flex items-center gap-2">
                <i class="fas fa-table"></i> Vista Previa (Primeras 20 filas)
            </h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead>
                    <tr class="bg-primary-50 dark:bg-[#241a42] text-primary-800 dark:text-primary-300 text-xs uppercase">
                        <th class="px-4 py-3 text-left font-bold">Estado</th>
                        <th class="px-4 py-3 text-left font-bold">Documento</th>
                        <th class="px-4 py-3 text-left font-bold">Nombre</th>
                        <th class="px-4 py-3 text-left font-bold">Tipo</th>
                        <th class="px-4 py-3 text-left font-bold">Empresa</th>
                        <th class="px-4 py-3 text-left font-bold">Email</th>
                        <th class="px-4 py-3 text-left font-bold">Usuario</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-[#2d2550]">
                    <?php foreach ($previewData as $row): ?>
                    <tr class="<?= $row['_status'] === 'error' ? 'bg-red-50 dark:bg-red-950/20' : 'hover:bg-primary-50/50 dark:hover:bg-[#241a42]/50' ?> transition">
                        <td class="px-4 py-3">
                            <?php if ($row['_status'] === 'valid'): ?>
                            <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-green-100 text-green-600">
                                <i class="fas fa-check text-xs"></i>
                            </span>
                            <?php else: ?>
                            <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-red-100 text-red-600">
                                <i class="fas fa-times text-xs"></i>
                            </span>
                            <?php endif; ?>
                        </td>
                        <td class="px-4 py-3 font-semibold text-gray-800 dark:text-gray-100"><?= e($row['documento'] ?? '') ?></td>
                        <td class="px-4 py-3 text-gray-700 dark:text-gray-200"><?= e($row['nombre'] ?? '') ?></td>
                        <td class="px-4 py-3">
                            <?php
                            $tipoBadge = [
                                'aprendiz'   => 'bg-indigo-100 text-indigo-700',
                                'instructor' => 'bg-emerald-100 text-emerald-700',
                                'admin'      => 'bg-purple-100 text-purple-700',
                                'vigilante'  => 'bg-blue-100 text-blue-700',
                                'contratista'=> 'bg-amber-100 text-amber-700',
                                'proveedor'  => 'bg-orange-100 text-orange-700',
                                'externo'    => 'bg-sky-100 text-sky-700',
                                'visitante'  => 'bg-sky-100 text-sky-700',
                                'planta'     => 'bg-teal-100 text-teal-700',
                            ];
                            $tipo = strtolower($row['tipo_persona'] ?? '');
                            $tipoClass = $tipoBadge[$tipo] ?? 'bg-gray-100 text-gray-600';
                            ?>
                            <span class="<?= $tipoClass ?> px-2.5 py-0.5 rounded-xl text-xs font-semibold">
                                <?= e($row['tipo_persona'] ?? '-') ?>
                            </span>
                        </td>
                        <td class="px-4 py-3 text-gray-500 dark:text-gray-400"><?= e($row['empresa'] ?? '-') ?></td>
                        <td class="px-4 py-3 text-gray-500 dark:text-gray-400 text-xs"><?= e($row['email'] ?? '-') ?></td>
                        <td class="px-4 py-3 text-gray-500 dark:text-gray-400 text-xs">
                            <?= $row['username'] ?? '' ? '<span class="text-gray-400">@</span>' . e($row['username']) : '-' ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php if ($total > 20): ?>
        <div class="px-6 py-4 border-t border-gray-100 dark:border-[#2d2550] text-center">
            <p class="text-sm text-gray-400 dark:text-gray-500">
                Mostrando 20 de <?= $total ?> registros totales. Todos serán procesados al confirmar.
            </p>
        </div>
        <?php endif; ?>
    </div>

    <!-- Botones de acción -->
    <div class="bg-white dark:bg-[#1c1830] border border-primary-100 dark:border-[#2d2550] rounded-3xl shadow-xl p-6">
        <div class="flex items-center justify-between flex-wrap gap-4">
            <div>
                <?php if ($invalid > 0): ?>
                <p class="text-amber-600 dark:text-amber-400 font-medium flex items-center gap-2">
                    <i class="fas fa-exclamation-triangle"></i>
                    <span><strong>Atención:</strong> Hay <?= $invalid ?> registros con errores que serán omitidos.</span>
                </p>
                <?php else: ?>
                <p class="text-green-600 dark:text-green-400 font-medium flex items-center gap-2">
                    <i class="fas fa-check-circle"></i>
                    <span><strong>Todo listo:</strong> Todos los registros son válidos.</span>
                </p>
                <?php endif; ?>
            </div>
            <div class="flex items-center gap-3">
                <a href="<?= baseUrl('/usuarios/import') ?>"
                   class="inline-flex items-center gap-2 rounded-xl bg-gray-100 hover:bg-gray-200 dark:bg-[#2d2550] dark:hover:bg-[#3a2e66] transition text-gray-700 dark:text-gray-300 font-semibold px-5 py-2.5 text-sm">
                    <i class="fas fa-times"></i> Cancelar
                </a>
                <?php if ($valid > 0): ?>
                <form method="POST" action="<?= baseUrl('/usuarios/import-confirm') ?>" style="display: inline;">
                    <input type="hidden" name="csrf_token" value="<?= generateCSRFToken() ?>">
                    <button type="submit"
                            class="inline-flex items-center gap-2 rounded-xl bg-primary-700 hover:bg-primary-800 transition text-white font-semibold px-5 py-2.5 text-sm shadow-md"
                            onclick="return confirm('¿Confirmar la importación de <?= $valid ?> registros válidos?')">
                        <i class="fas fa-check"></i> Confirmar Importación (<?= $valid ?> registros)
                    </button>
                </form>
                <?php else: ?>
                <button type="button" disabled
                        class="inline-flex items-center gap-2 rounded-xl bg-gray-300 dark:bg-[#2d2550] text-gray-500 font-semibold px-5 py-2.5 text-sm cursor-not-allowed opacity-60">
                    <i class="fas fa-ban"></i> No hay registros válidos para importar
                </button>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php require_once APP_PATH . '/views/layouts/footer.php'; ?>
