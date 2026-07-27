<?php require_once APP_PATH . '/views/layouts/header.php'; ?>

<div>
    <!-- Header -->
    <div class="flex items-center justify-between flex-wrap gap-4 mb-8">
        <h1 class="text-2xl font-extrabold text-primary-700 flex items-center gap-2">
            <i class="fas fa-key"></i> Control de Llaves
        </h1>
        <p class="text-sm text-gray-500 w-full -mt-4">Gestión de aulas y préstamos de llaves</p>
    </div>

    <!-- Estadísticas -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
        <div class="bg-white rounded-3xl shadow-md border border-primary-100 p-5 flex items-center gap-4 hover:shadow-lg transition">
            <div class="w-14 h-14 rounded-2xl bg-primary-100 text-primary-700 flex items-center justify-center text-2xl flex-shrink-0">
                <i class="fas fa-door-open"></i>
            </div>
            <div>
                <h3 class="text-3xl font-extrabold text-gray-800"><?= $stats['total_aulas'] ?? 0 ?></h3>
                <p class="text-sm text-gray-500">Aulas Activas</p>
            </div>
        </div>

        <div class="bg-white rounded-3xl shadow-md border border-primary-100 p-5 flex items-center gap-4 hover:shadow-lg transition">
            <div class="w-14 h-14 rounded-2xl bg-accent-100 text-accent-700 flex items-center justify-center text-2xl flex-shrink-0">
                <i class="fas fa-key"></i>
            </div>
            <div>
                <h3 class="text-3xl font-extrabold text-gray-800"><?= $stats['total_llaves'] ?? 0 ?></h3>
                <p class="text-sm text-gray-500">Total Llaves</p>
            </div>
        </div>

        <div class="bg-white rounded-3xl shadow-md border border-primary-100 p-5 flex items-center gap-4 hover:shadow-lg transition">
            <div class="w-14 h-14 rounded-2xl bg-red-100 text-red-700 flex items-center justify-center text-2xl flex-shrink-0">
                <i class="fas fa-hand-holding"></i>
            </div>
            <div>
                <h3 class="text-3xl font-extrabold text-red-700"><?= $stats['llaves_prestadas'] ?? 0 ?></h3>
                <p class="text-sm text-gray-500">Llaves Prestadas</p>
            </div>
        </div>

        <div class="bg-white rounded-3xl shadow-md border border-primary-100 p-5 flex items-center gap-4 hover:shadow-lg transition">
            <div class="w-14 h-14 rounded-2xl bg-green-100 text-green-700 flex items-center justify-center text-2xl flex-shrink-0">
                <i class="fas fa-clock"></i>
            </div>
            <div>
                <h3 class="text-3xl font-extrabold text-green-700"><?= $stats['prestamos_hoy'] ?? 0 ?></h3>
                <p class="text-sm text-gray-500">Préstamos Hoy</p>
            </div>
        </div>
    </div>

    <!-- Acciones y tabla de aulas -->
    <div class="bg-white/85 backdrop-blur-md border border-primary-100 rounded-3xl shadow-xl overflow-hidden">
        <div class="px-6 py-4 border-b border-primary-100 bg-gradient-to-r from-white to-primary-50 dark:from-[#1c1830] dark:to-[#241a42] flex items-center justify-between flex-wrap gap-3">
            <h3 class="text-lg font-bold text-primary-700"><i class="fas fa-door-open"></i> Gestión de Aulas</h3>
            <div class="flex items-center gap-2 flex-wrap">
                <?php if (Auth::hasRole('admin')): ?>
                <a href="<?= baseUrl('/control-llaves/create') ?>"
                   class="inline-flex items-center gap-2 rounded-xl bg-primary-700 hover:bg-primary-800 transition text-white font-semibold px-4 py-2 text-sm">
                    <i class="fas fa-plus"></i> Nueva Aula
                </a>
                <?php endif; ?>
                <a href="<?= baseUrl('/control-llaves/prestamo') ?>"
                   class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-green-600 to-emerald-500 hover:from-green-700 hover:to-emerald-600 transition text-white font-bold px-5 py-2 text-sm shadow-md">
                    <i class="fas fa-hand-holding animate-pulse"></i> Tomar/Devolver Llave
                </a>
                <a href="<?= baseUrl('/control-llaves/historial') ?>"
                   class="inline-flex items-center gap-2 rounded-xl bg-gray-100 hover:bg-gray-200 transition text-gray-700 font-semibold px-4 py-2 text-sm">
                    <i class="fas fa-history"></i> Historial
                </a>
            </div>
        </div>

        <div class="p-6">
            <?php if (empty($aulas)): ?>
                <div class="text-center py-16 text-gray-400">
                    <i class="fas fa-door-open text-5xl mb-4 block"></i>
                    <p class="mb-4">No hay aulas registradas</p>
                    <?php if (Auth::hasRole('admin')): ?>
                    <a href="<?= baseUrl('/control-llaves/create') ?>"
                       class="inline-flex items-center gap-2 rounded-xl bg-primary-700 hover:bg-primary-800 transition text-white font-semibold px-5 py-2.5 text-sm">
                        Crear Primera Aula
                    </a>
                    <?php endif; ?>
                </div>
            <?php else: ?>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead>
                            <tr class="bg-primary-50 text-primary-800 text-xs uppercase">
                                <th class="px-4 py-3 text-left font-bold">Aula</th>
                                <th class="px-4 py-3 text-left font-bold">Capacidad</th>
                                <th class="px-4 py-3 text-left font-bold">Total Llaves</th>
                                <th class="px-4 py-3 text-left font-bold">Disponibles</th>
                                <th class="px-4 py-3 text-left font-bold">Prestadas</th>
                                <th class="px-4 py-3 text-left font-bold">Estado</th>
                                <?php if (Auth::hasRole('admin')): ?>
                                <th class="px-4 py-3 text-center font-bold">Acciones</th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <?php foreach ($aulas as $aula): ?>
                            <tr class="hover:bg-primary-50/50 transition">
                                <td class="px-4 py-3">
                                    <strong class="text-gray-800"><?= e($aula['nombre']) ?></strong>
                                    <?php if ($aula['observaciones']): ?>
                                    <br><small class="text-gray-400"><?= e($aula['observaciones']) ?></small>
                                    <?php endif; ?>
                                </td>
                                <td class="px-4 py-3 text-gray-600"><?= e($aula['capacidad']) ?> personas</td>
                                <td class="px-4 py-3 text-gray-600"><?= e($aula['cantidad_llaves']) ?></td>
                                <td class="px-4 py-3">
                                    <span class="bg-green-100 text-green-700 px-2 py-0.5 rounded-xl text-xs font-semibold">
                                        <?= $aula['cantidad_llaves'] - $aula['llaves_prestadas'] ?>
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    <?php if ($aula['llaves_prestadas'] > 0): ?>
                                    <span class="bg-red-100 text-red-700 px-2 py-0.5 rounded-xl text-xs font-semibold">
                                        <?= $aula['llaves_prestadas'] ?>
                                    </span>
                                    <?php else: ?>
                                    <span class="text-gray-400">0</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-4 py-3">
                                    <span class="px-2 py-0.5 rounded-xl text-xs font-semibold <?= $aula['estado'] === 'ACTIVO' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' ?>">
                                        <?= e($aula['estado']) ?>
                                    </span>
                                </td>
                                <?php if (Auth::hasRole('admin')): ?>
                                <td class="px-4 py-3">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="<?= baseUrl('/control-llaves/edit/' . $aula['id']) ?>"
                                           class="w-8 h-8 rounded-lg bg-blue-100 text-blue-700 flex items-center justify-center hover:bg-blue-200 transition" title="Editar">
                                            <i class="fas fa-edit text-xs"></i>
                                        </a>
                                        <form method="POST" action="<?= baseUrl('/control-llaves/toggle/' . $aula['id']) ?>" class="inline">
                                            <button type="submit"
                                                    class="w-8 h-8 rounded-lg bg-amber-100 text-amber-700 flex items-center justify-center hover:bg-amber-200 transition"
                                                    title="Cambiar Estado">
                                                <i class="fas fa-toggle-on text-xs"></i>
                                            </button>
                                        </form>
                                        <form method="POST" action="<?= baseUrl('/control-llaves/delete/' . $aula['id']) ?>" class="inline"
                                              onsubmit="return confirm('¿Eliminar esta aula?');">
                                            <button type="submit"
                                                    class="w-8 h-8 rounded-lg bg-red-100 text-red-700 flex items-center justify-center hover:bg-red-200 transition"
                                                    title="Eliminar">
                                                <i class="fas fa-trash text-xs"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                                <?php endif; ?>
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
