<?php
/**
 * Vista: Listado de Usuarios
 */
require_once APP_PATH . '/views/layouts/header.php';
?>

<div>
    <!-- Header -->
    <div class="flex items-center justify-between flex-wrap gap-4 mb-8">
        <h1 class="text-2xl font-extrabold text-primary-700 flex items-center gap-2">
            <i class="fas fa-users"></i> Gestión de Usuarios
        </h1>
        <?php if (Auth::hasRole('admin')): ?>
        <div class="flex items-center gap-3">
            <a href="<?= baseUrl('/usuarios/import') ?>"
               class="inline-flex items-center gap-2 rounded-2xl bg-blue-600 hover:bg-blue-700 transition text-white font-semibold px-5 py-2.5 shadow-md">
                <i class="fas fa-file-upload"></i> Importar CSV
            </a>
            <a href="<?= baseUrl('/usuarios/create') ?>"
               class="inline-flex items-center gap-2 rounded-2xl bg-primary-700 hover:bg-primary-800 transition text-white font-semibold px-5 py-2.5 shadow-md">
                <i class="fas fa-plus"></i> Crear Usuario
            </a>
        </div>
        <?php endif; ?>
    </div>

    <!-- Estadísticas rápidas -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
        <div class="bg-white rounded-3xl shadow-md border border-primary-100 p-5 flex items-center gap-4 hover:shadow-lg transition">
            <div class="w-14 h-14 rounded-2xl bg-primary-100 text-primary-700 flex items-center justify-center text-2xl flex-shrink-0">
                <i class="fas fa-users"></i>
            </div>
            <div>
                <h3 class="text-3xl font-extrabold text-gray-800"><?= $stats['total'] ?></h3>
                <p class="text-sm text-gray-500">Total Usuarios</p>
            </div>
        </div>
        <div class="bg-white rounded-3xl shadow-md border border-primary-100 p-5 flex items-center gap-4 hover:shadow-lg transition">
            <div class="w-14 h-14 rounded-2xl bg-accent-100 text-accent-700 flex items-center justify-center text-2xl flex-shrink-0">
                <i class="fas fa-check-circle"></i>
            </div>
            <div>
                <h3 class="text-3xl font-extrabold text-green-700"><?= $stats['activos'] ?></h3>
                <p class="text-sm text-gray-500">Activos</p>
            </div>
        </div>
        <div class="bg-white rounded-3xl shadow-md border border-primary-100 p-5 flex items-center gap-4 hover:shadow-lg transition">
            <div class="w-14 h-14 rounded-2xl bg-gray-100 text-gray-500 flex items-center justify-center text-2xl flex-shrink-0">
                <i class="fas fa-times-circle"></i>
            </div>
            <div>
                <h3 class="text-3xl font-extrabold text-gray-500"><?= $stats['inactivos'] ?></h3>
                <p class="text-sm text-gray-500">Inactivos</p>
            </div>
        </div>
        <?php if (!empty($stats['por_tipo']['aprendiz'])): ?>
        <div class="bg-white rounded-3xl shadow-md border border-primary-100 p-5 flex items-center gap-4 hover:shadow-lg transition">
            <div class="w-14 h-14 rounded-2xl bg-blue-100 text-blue-700 flex items-center justify-center text-2xl flex-shrink-0">
                <i class="fas fa-graduation-cap"></i>
            </div>
            <div>
                <h3 class="text-3xl font-extrabold text-gray-800"><?= $stats['por_tipo']['aprendiz'] ?? 0 ?></h3>
                <p class="text-sm text-gray-500">Aprendices</p>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <!-- Filtros de búsqueda -->
    <div class="bg-white/85 backdrop-blur-md border border-primary-100 rounded-3xl shadow-xl overflow-hidden mb-8">
        <div class="px-6 py-4 border-b border-primary-100 bg-gradient-to-r from-white to-primary-50 dark:from-[#1c1830] dark:to-[#241a42]">
            <h3 class="text-lg font-bold text-primary-700"><i class="fas fa-filter"></i> Filtros</h3>
        </div>
        <div class="p-6">
            <form method="GET" action="<?= baseUrl('/usuarios') ?>" class="flex flex-wrap items-end gap-4">
                <div class="flex-1 min-w-[220px]">
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Buscar</label>
                    <input type="text" name="search" value="<?= e($_GET['search'] ?? '') ?>"
                           placeholder="Documento, nombre, email..."
                           class="w-full rounded-xl border border-gray-200 px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none">
                </div>
                <div class="min-w-[160px]">
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Tipo</label>
                    <select name="tipo_persona" class="w-full rounded-xl border border-gray-200 px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none">
                        <option value="">Todos</option>
                        <option value="aprendiz" <?= ($_GET['tipo_persona'] ?? '') === 'aprendiz' ? 'selected' : '' ?>>Aprendiz</option>
                        <option value="instructor" <?= ($_GET['tipo_persona'] ?? '') === 'instructor' ? 'selected' : '' ?>>Instructor</option>
                        <option value="admin" <?= ($_GET['tipo_persona'] ?? '') === 'admin' ? 'selected' : '' ?>>Admin</option>
                        <option value="vigilante" <?= ($_GET['tipo_persona'] ?? '') === 'vigilante' ? 'selected' : '' ?>>Vigilante</option>
                        <option value="contratista" <?= ($_GET['tipo_persona'] ?? '') === 'contratista' ? 'selected' : '' ?>>Contratista</option>
                        <option value="visitante" <?= ($_GET['tipo_persona'] ?? '') === 'visitante' ? 'selected' : '' ?>>Visitante</option>
                    </select>
                </div>
                <div class="min-w-[160px]">
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Estado</label>
                    <select name="estado" class="w-full rounded-xl border border-gray-200 px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none">
                        <option value="">Todos</option>
                        <option value="ACTIVO" <?= ($_GET['estado'] ?? '') === 'ACTIVO' ? 'selected' : '' ?>>Activo</option>
                        <option value="INACTIVO" <?= ($_GET['estado'] ?? '') === 'INACTIVO' ? 'selected' : '' ?>>Inactivo</option>
                    </select>
                </div>
                <div class="flex items-center gap-2">
                    <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-primary-700 hover:bg-primary-800 transition text-white font-semibold px-4 py-2 text-sm">
                        <i class="fas fa-search"></i> Buscar
                    </button>
                    <a href="<?= baseUrl('/usuarios') ?>" class="inline-flex items-center gap-2 rounded-xl bg-gray-100 hover:bg-gray-200 transition text-gray-700 font-semibold px-4 py-2 text-sm">
                        <i class="fas fa-redo"></i> Limpiar
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabla de usuarios -->
    <div class="bg-white/85 backdrop-blur-md border border-primary-100 rounded-3xl shadow-xl overflow-hidden">
        <div class="px-6 py-4 border-b border-primary-100 bg-gradient-to-r from-white to-primary-50 dark:from-[#1c1830] dark:to-[#241a42]">
            <h3 class="text-lg font-bold text-primary-700"><i class="fas fa-list"></i> Listado de Usuarios</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead>
                    <tr class="bg-primary-50 text-primary-800 text-xs uppercase">
                        <th class="px-4 py-3 text-left font-bold">Documento</th>
                        <th class="px-4 py-3 text-left font-bold">Nombre</th>
                        <th class="px-4 py-3 text-left font-bold">Empresa</th>
                        <th class="px-4 py-3 text-left font-bold">Email/Usuario</th>
                        <th class="px-4 py-3 text-left font-bold">Rol</th>
                        <th class="px-4 py-3 text-center font-bold">Estado</th>
                        <?php if (Auth::hasRole('admin')): ?>
                        <th class="px-4 py-3 text-center font-bold">Acciones</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <?php if (empty($usuarios)): ?>
                    <tr>
                        <td colspan="7" class="px-4 py-16 text-center text-gray-400">
                            <i class="fas fa-inbox text-4xl mb-3 block"></i>
                            No se encontraron usuarios
                        </td>
                    </tr>
                    <?php else: ?>
                    <?php foreach ($usuarios as $usuario): ?>
                    <tr class="hover:bg-primary-50/50 transition">
                        <td class="px-4 py-3 font-medium text-gray-700"><?= e($usuario['documento']) ?></td>
                        <td class="px-4 py-3 text-gray-700">
                            <?= e(trim($usuario['nombres'] . ' ' . ($usuario['apellidos'] ?? ''))) ?>
                        </td>
                        <td class="px-4 py-3 text-gray-500"><?= e($usuario['empresa'] ?? '-') ?></td>
                        <td class="px-4 py-3 text-gray-500 text-xs">
                            <?= $usuario['email'] ? e($usuario['email']) : '' ?>
                            <?= $usuario['username'] ? '<br><span class="text-gray-400">@' . e($usuario['username']) . '</span>' : '' ?>
                            <?= !$usuario['email'] && !$usuario['username'] ? '<span class="text-gray-400">-</span>' : '' ?>
                        </td>
                        <td class="px-4 py-3">
                            <?php
                            $badgeColors = [
                                'admin' => 'bg-primary-100 text-primary-700',
                                'instructor' => 'bg-accent-100 text-accent-700',
                                'vigilante' => 'bg-blue-100 text-blue-700',
                                'persona' => 'bg-gray-100 text-gray-600'
                            ];
                            $rol = $usuario['rol'] ?? 'persona';
                            $badgeClass = $badgeColors[$rol] ?? 'bg-gray-100 text-gray-600';
                            ?>
                            <span class="<?= $badgeClass ?> px-2 py-0.5 rounded-xl text-xs font-semibold">
                                <?= e($usuario['rol_nombre'] ?? ucfirst($rol)) ?>
                            </span>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <?php if (strtoupper($usuario['estado']) === 'ACTIVO'): ?>
                            <span class="text-green-700 font-semibold text-xs">
                                <i class="fas fa-check-circle"></i> Activo
                            </span>
                            <?php else: ?>
                            <span class="text-gray-400 font-semibold text-xs">
                                <i class="fas fa-times-circle"></i> Inactivo
                            </span>
                            <?php endif; ?>
                        </td>
                        <?php if (Auth::hasRole('admin')): ?>
                        <td class="px-4 py-3">
                            <div class="flex items-center justify-center gap-2">
                                <a href="<?= baseUrl('/usuarios/edit/' . $usuario['id']) ?>"
                                   class="w-8 h-8 rounded-lg bg-blue-100 text-blue-700 flex items-center justify-center hover:bg-blue-200 transition" title="Editar">
                                    <i class="fas fa-edit text-xs"></i>
                                </a>
                                <form method="POST" action="<?= baseUrl('/usuarios/toggle/' . $usuario['id']) ?>" class="inline">
                                    <input type="hidden" name="csrf_token" value="<?= generateCSRFToken() ?>">
                                    <button type="submit"
                                            class="w-8 h-8 rounded-lg bg-amber-100 text-amber-700 flex items-center justify-center hover:bg-amber-200 transition"
                                            title="<?= strtoupper($usuario['estado']) === 'ACTIVO' ? 'Desactivar' : 'Activar' ?>"
                                            onclick="return confirm('¿Cambiar estado del usuario?')">
                                        <i class="fas fa-<?= strtoupper($usuario['estado']) === 'ACTIVO' ? 'ban' : 'check' ?> text-xs"></i>
                                    </button>
                                </form>
                                <?php if ($usuario['id'] !== Auth::user()['id']): ?>
                                <form method="POST" action="<?= baseUrl('/usuarios/delete/' . $usuario['id']) ?>" class="inline">
                                    <input type="hidden" name="csrf_token" value="<?= generateCSRFToken() ?>">
                                    <button type="submit"
                                            class="w-8 h-8 rounded-lg bg-red-100 text-red-700 flex items-center justify-center hover:bg-red-200 transition"
                                            title="Eliminar"
                                            onclick="return confirm('¿Está seguro de eliminar este usuario?')">
                                        <i class="fas fa-trash text-xs"></i>
                                    </button>
                                </form>
                                <?php endif; ?>
                            </div>
                        </td>
                        <?php endif; ?>
                    </tr>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Paginación -->
        <?php if ($pagination['last_page'] > 1): ?>
        <div class="flex items-center justify-center gap-2 py-6">
            <?php
            $currentPage = $pagination['current_page'];
            $lastPage = $pagination['last_page'];
            $queryParams = $_GET;
            ?>

            <?php if ($currentPage > 1): ?>
            <a href="<?= baseUrl('/usuarios?' . http_build_query(array_merge($queryParams, ['page' => $currentPage - 1]))) ?>"
               class="inline-flex items-center gap-2 rounded-xl bg-gray-100 hover:bg-gray-200 transition text-gray-700 font-semibold px-4 py-2 text-sm">
                <i class="fas fa-chevron-left"></i> Anterior
            </a>
            <?php endif; ?>

            <span class="rounded-xl bg-primary-700 text-white font-semibold px-4 py-2 text-sm">
                Página <?= $currentPage ?> de <?= $lastPage ?>
            </span>

            <?php if ($currentPage < $lastPage): ?>
            <a href="<?= baseUrl('/usuarios?' . http_build_query(array_merge($queryParams, ['page' => $currentPage + 1]))) ?>"
               class="inline-flex items-center gap-2 rounded-xl bg-gray-100 hover:bg-gray-200 transition text-gray-700 font-semibold px-4 py-2 text-sm">
                Siguiente <i class="fas fa-chevron-right"></i>
            </a>
            <?php endif; ?>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once APP_PATH . '/views/layouts/footer.php'; ?>
