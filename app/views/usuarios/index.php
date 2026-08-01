<?php
/**
 * Vista: Listado de Usuarios
 * @var array   $stats      Estadísticas de usuarios (total, activos, etc.)
 * @var array   $usuarios   Lista paginada de usuarios
 * @var array   $pagination Datos de paginación (current_page, last_page, total, per_page)
 * @var string  $pageTitle  Título de la página
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
                        <option value="admin" <?= ($_GET['tipo_persona'] ?? '') === 'admin' ? 'selected' : '' ?>>Administrador</option>
                        <option value="vigilante" <?= ($_GET['tipo_persona'] ?? '') === 'vigilante' ? 'selected' : '' ?>>Vigilante</option>
                        <option value="planta" <?= ($_GET['tipo_persona'] ?? '') === 'planta' ? 'selected' : '' ?>>Personal de Planta</option>
                        <option value="contratista" <?= ($_GET['tipo_persona'] ?? '') === 'contratista' ? 'selected' : '' ?>>Contratista</option>
                        <option value="externo" <?= in_array($_GET['tipo_persona'] ?? '', ['externo', 'visitante']) ? 'selected' : '' ?>>Externo / Visitante</option>
                        <option value="proveedor" <?= ($_GET['tipo_persona'] ?? '') === 'proveedor' ? 'selected' : '' ?>>Proveedor</option>
                    </select>
                </div>
                <div class="min-w-[160px]">
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Estado</label>
                    <select name="estado" class="w-full rounded-xl border border-gray-200 px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none">
                        <option value="">Todos</option>
                        <option value="ACTIVO" <?= strtolower($_GET['estado'] ?? '') === 'activo' ? 'selected' : '' ?>>Activo</option>
                        <option value="INACTIVO" <?= strtolower($_GET['estado'] ?? '') === 'inactivo' ? 'selected' : '' ?>>Inactivo</option>
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
    <div class="bg-white/85 backdrop-blur-md border border-primary-100 rounded-3xl shadow-xl overflow-hidden relative">
        <div class="px-6 py-4 border-b border-primary-100 bg-gradient-to-r from-white to-primary-50 dark:from-[#1c1830] dark:to-[#241a42] flex flex-wrap items-center justify-between gap-3">
            <div>
                <h3 class="text-lg font-bold text-primary-700"><i class="fas fa-list"></i> Listado de Usuarios</h3>
                <?php if (Auth::hasRole('admin')): ?>
                <p class="text-xs text-gray-500 mt-1">Filtre por categoría o seleccione los usuarios para aplicar acciones en lote.</p>
                <?php endif; ?>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead>
                    <tr class="bg-primary-50 text-primary-800 text-xs uppercase">
                        <?php if (Auth::hasRole('admin')): ?>
                        <th class="px-4 py-3 text-center font-bold w-12">
                            <input id="selectAllUsers" type="checkbox" class="w-4 h-4 rounded border-gray-300 text-primary-700 focus:ring-primary-500" aria-label="Seleccionar todos los usuarios visibles">
                        </th>
                        <?php endif; ?>
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
                        <td colspan="<?= Auth::hasRole('admin') ? 8 : 6 ?>" class="px-4 py-16 text-center text-gray-400">
                            <i class="fas fa-inbox text-4xl mb-3 block"></i>
                            No se encontraron usuarios
                        </td>
                    </tr>
                    <?php else: ?>
                    <?php foreach ($usuarios as $usuario): ?>
                    <tr class="hover:bg-primary-50/50 transition">
                        <?php if (Auth::hasRole('admin')): ?>
                        <td class="px-4 py-3 text-center">
                            <?php if ((int) $usuario['id'] !== (int) Auth::user()['id']): ?>
                            <input type="checkbox" name="usuario_ids[]" value="<?= (int) $usuario['id'] ?>"
                                   data-estado="<?= strtoupper(e($usuario['estado'])) ?>"
                                   class="user-select-checkbox w-4 h-4 rounded border-gray-300 text-primary-700 focus:ring-primary-500 cursor-pointer"
                                   aria-label="Seleccionar a <?= e(trim($usuario['nombres'] . ' ' . ($usuario['apellidos'] ?? ''))) ?>">
                            <?php endif; ?>
                        </td>
                        <?php endif; ?>
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
                                'ADMIN' => 'bg-purple-100 text-purple-700',
                                'INSTRUCTOR' => 'bg-emerald-100 text-emerald-700',
                                'VIGILANTE' => 'bg-blue-100 text-blue-700',
                                'APRENDIZ' => 'bg-indigo-100 text-indigo-700',
                                'PLANTA' => 'bg-teal-100 text-teal-700',
                                'EXTERNO' => 'bg-sky-100 text-sky-700',
                                'VISITANTE' => 'bg-sky-100 text-sky-700',
                                'CONTRATISTA' => 'bg-amber-100 text-amber-700',
                                'PROVEEDOR' => 'bg-orange-100 text-orange-700'
                            ];
                            $displayName = $usuario['rol_nombre'] ?? $usuario['tipo_persona_nombre'] ?? 'Persona';
                            $codigo = strtoupper($usuario['rol'] ?? $usuario['tipo_persona'] ?? '');
                            $badgeClass = $badgeColors[$codigo] ?? 'bg-gray-100 text-gray-600';
                            ?>
                            <span class="<?= $badgeClass ?> px-2.5 py-0.5 rounded-xl text-xs font-semibold">
                                <?= e($displayName) ?>
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
                                <?php if ((int) $usuario['id'] !== (int) Auth::user()['id']): ?>
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

<?php if (Auth::hasRole('admin')): ?>
<!-- Dock Flotante de Acciones en Lote -->
<div id="bulkFloatingBar" 
     class="fixed bottom-6 left-1/2 -translate-x-1/2 z-50 transition-all duration-300 transform opacity-0 pointer-events-none translate-y-8 flex items-center gap-3 bg-white/95 dark:bg-[#1a152e]/95 backdrop-blur-xl border border-primary-200/80 dark:border-primary-800/80 shadow-2xl rounded-2xl px-5 py-3 text-sm">
    <div class="flex items-center gap-2 pr-3 border-r border-gray-200 dark:border-gray-700/80">
        <span id="floatingSelectedCount" class="w-7 h-7 rounded-full bg-primary-100 dark:bg-primary-900/80 text-primary-700 dark:text-primary-300 flex items-center justify-center font-bold text-xs shadow-inner">
            0
        </span>
        <span class="font-semibold text-gray-700 dark:text-gray-200 text-xs sm:text-sm">seleccionado(s)</span>
    </div>

    <!-- Formulario Activar en Lote -->
    <form id="bulkActivateForm" method="POST" action="<?= baseUrl('/usuarios/bulk-status') ?>" class="inline">
        <input type="hidden" name="csrf_token" value="<?= generateCSRFToken() ?>">
        <input type="hidden" name="status" value="ACTIVO">
        <div class="hidden-inputs-container"></div>
        <button type="submit" class="inline-flex items-center gap-1.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-semibold px-3.5 py-2 text-xs shadow-sm transition active:scale-95">
            <i class="fas fa-check-circle"></i> Activar
        </button>
    </form>

    <!-- Formulario Desactivar en Lote -->
    <form id="bulkDeactivateForm" method="POST" action="<?= baseUrl('/usuarios/bulk-status') ?>" class="inline">
        <input type="hidden" name="csrf_token" value="<?= generateCSRFToken() ?>">
        <input type="hidden" name="status" value="INACTIVO">
        <div class="hidden-inputs-container"></div>
        <button type="submit" class="inline-flex items-center gap-1.5 rounded-xl bg-amber-600 hover:bg-amber-700 text-white font-semibold px-3.5 py-2 text-xs shadow-sm transition active:scale-95">
            <i class="fas fa-ban"></i> Desactivar
        </button>
    </form>

    <!-- Formulario Eliminar en Lote -->
    <form id="bulkDeleteForm" method="POST" action="<?= baseUrl('/usuarios/bulk-delete') ?>" class="inline">
        <input type="hidden" name="csrf_token" value="<?= generateCSRFToken() ?>">
        <div class="hidden-inputs-container"></div>
        <button type="submit" class="inline-flex items-center gap-1.5 rounded-xl bg-red-600 hover:bg-red-700 text-white font-semibold px-3.5 py-2 text-xs shadow-sm transition active:scale-95">
            <i class="fas fa-trash-alt"></i> Eliminar
        </button>
    </form>

    <div class="pl-2 border-l border-gray-200 dark:border-gray-700/80">
        <button type="button" id="clearSelectionButton" 
                class="w-7 h-7 rounded-full hover:bg-gray-100 dark:hover:bg-gray-800 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 flex items-center justify-center transition"
                title="Desmarcar todos">
            <i class="fas fa-times text-xs"></i>
        </button>
    </div>
</div>

<script>
    (function () {
        const STORAGE_KEY = 'sena_selected_users_v1';
        const checkboxes = Array.from(document.querySelectorAll('.user-select-checkbox'));
        const selectAll = document.getElementById('selectAllUsers');
        const floatingBar = document.getElementById('bulkFloatingBar');
        const selectedCount = document.getElementById('floatingSelectedCount');
        const clearButton = document.getElementById('clearSelectionButton');
        const activateForm = document.getElementById('bulkActivateForm');
        const deactivateForm = document.getElementById('bulkDeactivateForm');
        const deleteForm = document.getElementById('bulkDeleteForm');

        function getStoredSelection() {
            try {
                return JSON.parse(sessionStorage.getItem(STORAGE_KEY)) || {};
            } catch (e) {
                return {};
            }
        }

        function saveStoredSelection(map) {
            try {
                sessionStorage.setItem(STORAGE_KEY, JSON.stringify(map));
            } catch (e) {}
        }

        function clearStoredSelection() {
            try {
                sessionStorage.removeItem(STORAGE_KEY);
            } catch (e) {}
        }

        const selectedMap = getStoredSelection();

        // Restaurar estado de casillas visibles desde sessionStorage
        checkboxes.forEach((cb) => {
            if (selectedMap[cb.value]) {
                cb.checked = true;
                if (cb.dataset.estado) {
                    selectedMap[cb.value].estado = cb.dataset.estado;
                }
            }
        });
        saveStoredSelection(selectedMap);

        function syncHiddenInputs() {
            const selectedItems = Object.values(selectedMap);
            [activateForm, deactivateForm, deleteForm].forEach(form => {
                if (!form) return;
                const container = form.querySelector('.hidden-inputs-container');
                if (!container) return;
                container.innerHTML = '';
                selectedItems.forEach(item => {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'usuario_ids[]';
                    input.value = item.id;
                    container.appendChild(input);
                });
            });
        }

        function updateUI() {
            const selectedItems = Object.values(selectedMap);
            const totalSelected = selectedItems.length;

            // 1. Resaltado dinámico de filas en la página actual
            checkboxes.forEach((cb) => {
                const tr = cb.closest('tr');
                const isChecked = !!selectedMap[cb.value];
                cb.checked = isChecked;
                if (tr) {
                    tr.classList.toggle('bg-primary-50/80', isChecked);
                    tr.classList.toggle('dark:bg-primary-900/30', isChecked);
                    tr.classList.toggle('font-medium', isChecked);
                }
            });

            // 2. Contador y visibilidad general del dock flotante
            if (selectedCount) selectedCount.textContent = totalSelected;
            if (floatingBar) {
                if (totalSelected > 0) {
                    floatingBar.classList.remove('opacity-0', 'pointer-events-none', 'translate-y-8');
                    floatingBar.classList.add('opacity-100', 'pointer-events-auto', 'translate-y-0');
                } else {
                    floatingBar.classList.add('opacity-0', 'pointer-events-none', 'translate-y-8');
                    floatingBar.classList.remove('opacity-100', 'pointer-events-auto', 'translate-y-0');
                }
            }

            // 3. Visibilidad inteligente de los botones Activar y Desactivar
            const hasInactive = selectedItems.some(item => (item.estado || '').toUpperCase() === 'INACTIVO');
            const hasActive = selectedItems.some(item => (item.estado || '').toUpperCase() === 'ACTIVO');

            if (activateForm) {
                activateForm.classList.toggle('hidden', !hasInactive);
            }
            if (deactivateForm) {
                deactivateForm.classList.toggle('hidden', !hasActive);
            }

            // 4. Sincronizar inputs ocultos en los 3 formularios
            syncHiddenInputs();

            // 5. Estado del checkbox "Seleccionar Todo" en la página actual
            if (selectAll) {
                const visibleCheckedCount = checkboxes.filter(cb => cb.checked).length;
                selectAll.checked = checkboxes.length > 0 && visibleCheckedCount === checkboxes.length;
                selectAll.indeterminate = visibleCheckedCount > 0 && visibleCheckedCount < checkboxes.length;
            }
        }

        // Eventos para checkboxes en la página actual
        checkboxes.forEach((cb) => {
            cb.addEventListener('change', function () {
                if (this.checked) {
                    selectedMap[this.value] = {
                        id: this.value,
                        estado: (this.dataset.estado || 'ACTIVO').toUpperCase()
                    };
                } else {
                    delete selectedMap[this.value];
                }
                saveStoredSelection(selectedMap);
                updateUI();
            });
        });

        // Evento para "Seleccionar todo"
        if (selectAll) {
            selectAll.addEventListener('change', function () {
                checkboxes.forEach((cb) => {
                    cb.checked = this.checked;
                    if (this.checked) {
                        selectedMap[cb.value] = {
                            id: cb.value,
                            estado: (cb.dataset.estado || 'ACTIVO').toUpperCase()
                        };
                    } else {
                        delete selectedMap[cb.value];
                    }
                });
                saveStoredSelection(selectedMap);
                updateUI();
            });
        }

        // Botón Desmarcar Todos
        if (clearButton) {
            clearButton.addEventListener('click', function () {
                for (const key in selectedMap) {
                    delete selectedMap[key];
                }
                clearStoredSelection();
                updateUI();
            });
        }

        // Manejo de envíos de formularios masivos
        function attachSubmitHandler(form, confirmMsg) {
            if (!form) return;
            form.addEventListener('submit', function (event) {
                const count = Object.keys(selectedMap).length;
                if (count === 0 || !window.confirm(confirmMsg.replace('{n}', count).replace('{s}', count === 1 ? '' : 's'))) {
                    event.preventDefault();
                    return;
                }
                clearStoredSelection();
            });
        }

        attachSubmitHandler(activateForm, '¿Activar {n} usuario{s} seleccionado{s}?');
        attachSubmitHandler(deactivateForm, '¿Desactivar {n} usuario{s} seleccionado{s}?');
        attachSubmitHandler(deleteForm, '¿Eliminar {n} usuario{s} seleccionado{s}?');

        // Inicializar al cargar
        updateUI();
    })();
</script>
<?php endif; ?>

<?php require_once APP_PATH . '/views/layouts/footer.php'; ?>
