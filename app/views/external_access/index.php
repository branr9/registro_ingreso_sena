<?php
/**
 * Vista: Listado de Registros de Acceso Externo
 */
require_once APP_PATH . '/views/layouts/header.php';
?>

<div>
    <!-- Header -->
    <div class="flex items-center justify-between flex-wrap gap-4 mb-2">
        <h1 class="text-2xl font-extrabold text-primary-700 flex items-center gap-2">
            <i class="fas fa-users"></i> Registro de Personal Externo
        </h1>
        <div class="flex items-center gap-3">
            <a href="<?= baseUrl('/acceso-externo/personas-dentro') ?>"
               class="inline-flex items-center gap-2 rounded-2xl bg-accent-600 hover:bg-accent-700 transition text-white font-semibold px-5 py-2.5 shadow-md">
                <i class="fas fa-door-open"></i> Personas Dentro
            </a>
            <a href="<?= baseUrl('/acceso-externo/registro-entrada') ?>"
               class="inline-flex items-center gap-2 rounded-2xl bg-primary-700 hover:bg-primary-800 transition text-white font-semibold px-5 py-2.5 shadow-md">
                <i class="fas fa-user-plus"></i> Registrar Entrada
            </a>
        </div>
    </div>
    <p class="text-sm text-gray-500 mb-8">Control de entrada y salida de personal sin carnet (visitantes, contratistas, proveedores)</p>

    <!-- Filtros de búsqueda -->
    <div class="bg-white/85 backdrop-blur-md border border-primary-100 rounded-3xl shadow-xl overflow-hidden mb-8">
        <div class="px-6 py-4 border-b border-primary-100 bg-gradient-to-r from-white to-primary-50 dark:from-[#1c1830] dark:to-[#241a42]">
            <h3 class="text-lg font-bold text-primary-700"><i class="fas fa-filter"></i> Filtros</h3>
        </div>
        <div class="p-6">
            <form method="GET" action="<?= baseUrl('/acceso-externo') ?>" class="flex flex-wrap items-end gap-4">
                <div class="flex-1 min-w-[200px]">
                    <label for="search" class="block text-xs font-semibold text-gray-600 mb-1">Buscar</label>
                    <input type="text" id="search" name="search"
                           class="w-full rounded-xl border border-gray-200 px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none"
                           value="<?= e($_GET['search'] ?? '') ?>"
                           placeholder="Documento, nombre, empresa...">
                </div>

                <div class="min-w-[160px]">
                    <label for="estado" class="block text-xs font-semibold text-gray-600 mb-1">Estado</label>
                    <select id="estado" name="estado"
                            class="w-full rounded-xl border border-gray-200 px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none">
                        <option value="">Todos</option>
                        <option value="dentro" <?= ($_GET['estado'] ?? '') === 'dentro' ? 'selected' : '' ?>>Dentro</option>
                        <option value="salio" <?= ($_GET['estado'] ?? '') === 'salio' ? 'selected' : '' ?>>Salió</option>
                    </select>
                </div>

                <div class="min-w-[160px]">
                    <label for="fecha_desde" class="block text-xs font-semibold text-gray-600 mb-1">Desde</label>
                    <input type="date" id="fecha_desde" name="fecha_desde"
                           class="w-full rounded-xl border border-gray-200 px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none"
                           value="<?= e($_GET['fecha_desde'] ?? '') ?>">
                </div>

                <div class="min-w-[160px]">
                    <label for="fecha_hasta" class="block text-xs font-semibold text-gray-600 mb-1">Hasta</label>
                    <input type="date" id="fecha_hasta" name="fecha_hasta"
                           class="w-full rounded-xl border border-gray-200 px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none"
                           value="<?= e($_GET['fecha_hasta'] ?? '') ?>">
                </div>

                <div class="flex items-center gap-2">
                    <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-primary-700 hover:bg-primary-800 transition text-white font-semibold px-4 py-2 text-sm">
                        <i class="fas fa-search"></i> Buscar
                    </button>
                    <a href="<?= baseUrl('/acceso-externo') ?>" class="inline-flex items-center gap-2 rounded-xl bg-gray-100 hover:bg-gray-200 transition text-gray-700 font-semibold px-4 py-2 text-sm">
                        <i class="fas fa-redo"></i> Limpiar
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabla de registros -->
    <div class="bg-white/85 backdrop-blur-md border border-primary-100 rounded-3xl shadow-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead>
                    <tr class="bg-primary-50 text-primary-800 text-xs uppercase">
                        <th class="px-4 py-3 text-left font-bold">Fecha/Hora Entrada</th>
                        <th class="px-4 py-3 text-left font-bold">Documento</th>
                        <th class="px-4 py-3 text-left font-bold">Nombre Completo</th>
                        <th class="px-4 py-3 text-left font-bold">Empresa</th>
                        <th class="px-4 py-3 text-left font-bold">Motivo</th>
                        <th class="px-4 py-3 text-center font-bold">Estado</th>
                        <th class="px-4 py-3 text-left font-bold">Tiempo Permanencia</th>
                        <th class="px-4 py-3 text-center font-bold">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <?php if (empty($result['data'])): ?>
                    <tr>
                        <td colspan="8" class="px-4 py-16 text-center text-gray-400">
                            <i class="fas fa-inbox text-4xl mb-3 block"></i>
                            No hay registros para mostrar
                        </td>
                    </tr>
                    <?php else: ?>
                        <?php foreach ($result['data'] as $registro): ?>
                        <tr class="hover:bg-primary-50/50 transition">
                            <td class="px-4 py-3 whitespace-nowrap">
                                <strong class="text-gray-700"><?= date('d/m/Y', strtotime($registro['fecha_entrada'])) ?></strong><br>
                                <small class="text-gray-400"><?= date('h:i A', strtotime($registro['fecha_entrada'])) ?></small>
                            </td>
                            <td class="px-4 py-3">
                                <strong class="text-gray-700"><?= e($registro['documento']) ?></strong><br>
                                <small class="text-gray-400"><?= e($registro['tipo_documento']) ?></small>
                            </td>
                            <td class="px-4 py-3">
                                <strong class="text-gray-700"><?= e($registro['nombre_completo']) ?></strong>
                                <?php if ($registro['telefono']): ?>
                                <br><small class="text-gray-400"><i class="fas fa-phone"></i> <?= e($registro['telefono']) ?></small>
                                <?php endif; ?>
                            </td>
                            <td class="px-4 py-3 text-gray-500"><?= e($registro['empresa'] ?? '-') ?></td>
                            <td class="px-4 py-3 text-gray-500">
                                <small><?= e(substr($registro['motivo_visita'], 0, 50)) ?><?= strlen($registro['motivo_visita']) > 50 ? '...' : '' ?></small>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <?php if ($registro['estado'] === 'DENTRO'): ?>
                                    <span class="bg-green-100 text-green-700 px-2 py-0.5 rounded-xl text-xs font-semibold">Dentro</span>
                                <?php else: ?>
                                    <span class="bg-gray-100 text-gray-600 px-2 py-0.5 rounded-xl text-xs font-semibold">Salió</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <?php if ($registro['estado'] === 'DENTRO'): ?>
                                    <span class="text-accent-700 font-medium">
                                        <?= floor($registro['minutos_transcurridos'] / 60) ?>h <?= $registro['minutos_transcurridos'] % 60 ?>m
                                    </span>
                                <?php elseif ($registro['tiempo_permanencia']): ?>
                                    <span class="text-gray-600"><?= floor($registro['tiempo_permanencia'] / 60) ?>h <?= $registro['tiempo_permanencia'] % 60 ?>m</span>
                                <?php else: ?>
                                    <span class="text-gray-400">-</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="<?= baseUrl('/acceso-externo/detalle/' . $registro['id']) ?>"
                                       class="w-8 h-8 rounded-lg bg-blue-100 text-blue-700 flex items-center justify-center hover:bg-blue-200 transition" title="Ver detalle">
                                        <i class="fas fa-eye text-xs"></i>
                                    </a>

                                    <?php if ($registro['estado'] === 'DENTRO'): ?>
                                    <form method="POST" action="<?= baseUrl('/acceso-externo/registrar-salida/' . $registro['id']) ?>" class="inline"
                                          onsubmit="return confirm('¿Confirmar salida de <?= e($registro['nombre_completo']) ?>?')">
                                        <input type="hidden" name="csrf_token" value="<?= generateCSRFToken() ?>">
                                        <button type="submit"
                                                class="w-8 h-8 rounded-lg bg-green-100 text-green-700 flex items-center justify-center hover:bg-green-200 transition" title="Registrar salida">
                                            <i class="fas fa-sign-out-alt text-xs"></i>
                                        </button>
                                    </form>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Paginación -->
        <?php if ($result['last_page'] > 1): ?>
        <div class="flex items-center justify-center gap-2 py-6">
            <?php if ($result['page'] > 1): ?>
                <a href="<?= baseUrl('/acceso-externo?page=' . ($result['page'] - 1) . '&' . http_build_query($_GET)) ?>"
                   class="inline-flex items-center gap-2 rounded-xl bg-gray-100 hover:bg-gray-200 transition text-gray-700 font-semibold px-4 py-2 text-sm">
                    <i class="fas fa-chevron-left"></i> Anterior
                </a>
            <?php endif; ?>

            <span class="rounded-xl bg-primary-700 text-white font-semibold px-4 py-2 text-sm">
                Página <?= $result['page'] ?> de <?= $result['last_page'] ?>
                (<?= $result['total'] ?> registros)
            </span>

            <?php if ($result['page'] < $result['last_page']): ?>
                <a href="<?= baseUrl('/acceso-externo?page=' . ($result['page'] + 1) . '&' . http_build_query($_GET)) ?>"
                   class="inline-flex items-center gap-2 rounded-xl bg-gray-100 hover:bg-gray-200 transition text-gray-700 font-semibold px-4 py-2 text-sm">
                    Siguiente <i class="fas fa-chevron-right"></i>
                </a>
            <?php endif; ?>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once APP_PATH . '/views/layouts/footer.php'; ?>
