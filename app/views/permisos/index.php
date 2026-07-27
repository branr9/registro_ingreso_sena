<?php require_once APP_PATH . '/views/layouts/header.php'; ?>

<div>
    <div class="flex items-center justify-between flex-wrap gap-4 mb-8">
        <h1 class="text-2xl font-extrabold text-primary-700 flex items-center gap-2">
            <i class="fas fa-file-signature"></i> Permisos de Salida
        </h1>
        <div>
            <?php if (Auth::hasRole('admin') || Auth::hasRole('instructor')): ?>
            <a href="<?= baseUrl('/permisos/create') ?>"
               class="inline-flex items-center gap-2 rounded-2xl bg-primary-700 hover:bg-primary-800 transition text-white font-semibold px-5 py-2.5 shadow-md">
                <i class="fas fa-plus"></i> Crear Permiso
            </a>
            <?php endif; ?>
        </div>
    </div>

    <?php
    $flash = getFlashMessage();
    if ($flash):
    ?>
        <div class="mb-6 rounded-2xl px-5 py-4 shadow-md
                    <?= $flash['type'] === 'success' ? 'bg-green-50 border border-green-200 text-green-800' : '' ?>
                    <?= $flash['type'] === 'error' ? 'bg-red-50 border border-red-200 text-red-800' : '' ?>
                    <?= !in_array($flash['type'], ['success','error']) ? 'bg-blue-50 border border-blue-200 text-blue-800' : '' ?>">
            <?= e($flash['message']) ?>
        </div>
    <?php endif; ?>

    <!-- Estadísticas -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">

        <div class="bg-white rounded-3xl shadow-md border border-primary-100 p-5 flex items-center gap-4 hover:shadow-lg transition">
            <div class="w-14 h-14 rounded-2xl bg-primary-100 text-primary-700 flex items-center justify-center text-2xl flex-shrink-0">
                <i class="fas fa-clipboard-list"></i>
            </div>
            <div>
                <h3 class="text-3xl font-extrabold text-gray-800"><?= $stats['total'] ?></h3>
                <p class="text-sm text-gray-500">Total Permisos</p>
            </div>
        </div>

        <div class="bg-white rounded-3xl shadow-md border border-primary-100 p-5 flex items-center gap-4 hover:shadow-lg transition">
            <div class="w-14 h-14 rounded-2xl bg-accent-100 text-accent-700 flex items-center justify-center text-2xl flex-shrink-0">
                <i class="fas fa-check-circle"></i>
            </div>
            <div>
                <h3 class="text-3xl font-extrabold text-gray-800"><?= $stats['activos'] ?></h3>
                <p class="text-sm text-gray-500">Activos</p>
            </div>
        </div>

        <div class="bg-white rounded-3xl shadow-md border border-primary-100 p-5 flex items-center gap-4 hover:shadow-lg transition">
            <div class="w-14 h-14 rounded-2xl bg-blue-100 text-blue-700 flex items-center justify-center text-2xl flex-shrink-0">
                <i class="fas fa-door-open"></i>
            </div>
            <div>
                <h3 class="text-3xl font-extrabold text-gray-800"><?= $stats['usados'] ?></h3>
                <p class="text-sm text-gray-500">Usados</p>
            </div>
        </div>

        <div class="bg-white rounded-3xl shadow-md border border-primary-100 p-5 flex items-center gap-4 hover:shadow-lg transition">
            <div class="w-14 h-14 rounded-2xl bg-amber-100 text-amber-700 flex items-center justify-center text-2xl flex-shrink-0">
                <i class="fas fa-clock"></i>
            </div>
            <div>
                <h3 class="text-3xl font-extrabold text-gray-800"><?= $stats['vencidos'] ?></h3>
                <p class="text-sm text-gray-500">Vencidos</p>
            </div>
        </div>
    </div>

    <!-- Filtros -->
    <div class="bg-white/85 backdrop-blur-md border border-primary-100 rounded-3xl shadow-xl overflow-hidden mb-8">
        <div class="px-6 py-4 border-b border-primary-100 bg-gradient-to-r from-white to-primary-50 dark:from-[#1c1830] dark:to-[#241a42]">
            <h3 class="text-lg font-bold text-primary-700"><i class="fas fa-filter"></i> Filtros</h3>
        </div>
        <div class="p-6">
            <form method="GET" action="<?= baseUrl('/permisos') ?>" class="flex flex-wrap items-end gap-4">
                <div class="flex-1 min-w-[160px]">
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Fecha</label>
                    <input type="date" name="fecha"
                           class="w-full rounded-xl border border-gray-200 px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none"
                           value="<?= e($filters['fecha']) ?>">
                </div>

                <div class="flex-1 min-w-[200px]">
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Documento</label>
                    <input type="text" name="documento"
                           class="w-full rounded-xl border border-gray-200 px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none"
                           placeholder="Buscar por documento"
                           value="<?= e($filters['documento']) ?>">
                </div>

                <div class="flex-1 min-w-[160px]">
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Estado</label>
                    <select name="estado"
                            class="w-full rounded-xl border border-gray-200 px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none">
                        <option value="">Todos</option>
                        <option value="ACTIVO" <?= $filters['estado'] === 'ACTIVO' ? 'selected' : '' ?>>Activo</option>
                        <option value="USADO" <?= $filters['estado'] === 'USADO' ? 'selected' : '' ?>>Usado</option>
                        <option value="VENCIDO" <?= $filters['estado'] === 'VENCIDO' ? 'selected' : '' ?>>Vencido</option>
                        <option value="CANCELADO" <?= $filters['estado'] === 'CANCELADO' ? 'selected' : '' ?>>Cancelado</option>
                    </select>
                </div>

                <div class="flex items-center gap-2">
                    <button type="submit"
                            class="inline-flex items-center gap-2 rounded-xl bg-primary-700 hover:bg-primary-800 transition text-white font-semibold px-4 py-2 text-sm">
                        <i class="fas fa-search"></i> Buscar
                    </button>
                    <a href="<?= baseUrl('/permisos') ?>"
                       class="inline-flex items-center gap-2 rounded-xl bg-gray-100 hover:bg-gray-200 transition text-gray-700 font-semibold px-4 py-2 text-sm">
                        <i class="fas fa-redo"></i> Limpiar
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabla de permisos -->
    <div class="bg-white/85 backdrop-blur-md border border-primary-100 rounded-3xl shadow-xl overflow-hidden">
        <div class="px-6 py-4 border-b border-primary-100 bg-gradient-to-r from-white to-primary-50 dark:from-[#1c1830] dark:to-[#241a42]">
            <h3 class="text-lg font-bold text-primary-700"><i class="fas fa-list"></i> Listado de Permisos</h3>
        </div>
        <div class="p-2 md:p-0">
            <?php if (empty($permisos)): ?>
                <div class="flex flex-col items-center justify-center gap-3 text-gray-400 py-16">
                    <i class="fas fa-inbox text-4xl"></i>
                    <p>No se encontraron permisos</p>
                </div>
            <?php else: ?>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead>
                            <tr class="bg-primary-50 text-primary-800 text-xs uppercase">
                                <th class="px-4 py-3 text-left font-bold">Fecha</th>
                                <th class="px-4 py-3 text-left font-bold">Documento</th>
                                <th class="px-4 py-3 text-left font-bold">Nombre Aprendiz</th>
                                <th class="px-4 py-3 text-left font-bold">Hora Salida</th>
                                <th class="px-4 py-3 text-left font-bold">Motivo</th>
                                <th class="px-4 py-3 text-left font-bold">Instructor</th>
                                <th class="px-4 py-3 text-left font-bold">Estado</th>
                                <th class="px-4 py-3 text-left font-bold">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <?php foreach ($permisos as $permiso): ?>
                            <tr class="hover:bg-primary-50/50 transition">
                                <td class="px-4 py-3 text-gray-600"><?= date('d/m/Y', strtotime($permiso['fecha_permiso'])) ?></td>
                                <td class="px-4 py-3 font-medium text-gray-700"><?= e($permiso['documento_aprendiz']) ?></td>
                                <td class="px-4 py-3 text-gray-700"><?= e($permiso['nombre_aprendiz']) ?></td>
                                <td class="px-4 py-3 text-gray-600"><?= substr($permiso['hora_salida'], 0, 5) ?></td>
                                <td class="px-4 py-3 text-gray-500"><?= e(substr($permiso['motivo'], 0, 50)) ?>...</td>
                                <td class="px-4 py-3 text-gray-600"><?= e($permiso['instructor_nombre']) ?></td>
                                <td class="px-4 py-3">
                                    <?php
                                    $badgeClass = [
                                        'ACTIVO' => 'bg-primary-100 text-primary-700',
                                        'USADO' => 'bg-blue-100 text-blue-700',
                                        'VENCIDO' => 'bg-amber-100 text-amber-700',
                                        'CANCELADO' => 'bg-red-100 text-red-700'
                                    ][$permiso['estado']] ?? 'bg-gray-100 text-gray-600';
                                    ?>
                                    <span class="<?= $badgeClass ?> px-2 py-0.5 rounded-xl text-xs font-semibold">
                                        <?= e($permiso['estado']) ?>
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-2">
                                        <a href="<?= baseUrl('/permisos/ver/' . $permiso['id']) ?>"
                                           class="w-8 h-8 rounded-lg bg-blue-100 text-blue-700 flex items-center justify-center hover:bg-blue-200 transition" title="Ver detalle">
                                            <i class="fas fa-eye text-xs"></i>
                                        </a>

                                        <?php if ($permiso['estado'] === 'ACTIVO' &&
                                                  (Auth::hasRole('admin') || $permiso['instructor_id'] == $_SESSION['user_id'])): ?>
                                        <form method="POST"
                                              action="<?= baseUrl('/permisos/cancelar/' . $permiso['id']) ?>"
                                              class="inline"
                                              onsubmit="return confirm('¿Está seguro de cancelar este permiso?')">
                                            <input type="hidden" name="csrf_token" value="<?= generateCSRFToken() ?>">
                                            <button type="submit" class="w-8 h-8 rounded-lg bg-red-100 text-red-700 flex items-center justify-center hover:bg-red-200 transition" title="Cancelar">
                                                <i class="fas fa-times text-xs"></i>
                                            </button>
                                        </form>
                                        <?php endif; ?>
                                    </div>
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
