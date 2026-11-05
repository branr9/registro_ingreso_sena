<?php require APP_PATH . '/views/layouts/header.php'; ?>

<div>
    <?php require APP_PATH . '/views/ambientes/_nav.php'; ?>
    <div class="flex items-center justify-between flex-wrap gap-4 mb-8">
        <div><h2 class="text-2xl font-extrabold text-primary-700 flex items-center gap-2"><i class="fas fa-building"></i> Gestión de Ambientes</h2><p class="text-sm text-gray-500 mt-1"><?= $pagination['total'] ?> ambiente(s) registrado(s).</p></div>
        <?php if (Auth::hasRole('admin')): ?><a href="<?= baseUrl('/ambientes/crear') ?>" class="inline-flex items-center gap-2 rounded-2xl bg-primary-700 hover:bg-primary-800 transition text-white font-semibold px-5 py-2.5 shadow-md"><i class="fas fa-plus"></i> Crear Ambiente</a><?php endif; ?>
    </div>

    <div class="bg-white/85 backdrop-blur-md border border-primary-100 rounded-3xl shadow-xl overflow-hidden mb-8">
        <div class="px-6 py-4 border-b border-primary-100 bg-gradient-to-r from-white to-primary-50 dark:from-[#1c1830] dark:to-[#241a42]"><h3 class="text-lg font-bold text-primary-700"><i class="fas fa-filter"></i> Filtros</h3></div>
        <form method="GET" action="<?= baseUrl('/ambientes/gestion') ?>" class="p-6 grid grid-cols-1 md:grid-cols-2 xl:grid-cols-[1fr_180px_180px_auto] items-end gap-4">
            <div><label class="block text-xs font-semibold text-gray-600 mb-1">Buscar</label><input type="text" name="search" value="<?= e($filters['search']) ?>" placeholder="Código, nombre o ubicación..." class="w-full h-10 rounded-xl border border-gray-200 px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none"></div>
            <div><label class="block text-xs font-semibold text-gray-600 mb-1">Tipo</label><select name="tipo" class="w-full h-10 rounded-xl border border-gray-200 px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 outline-none"><option value="">Todos</option><?php foreach (['SALA'=>'Sala','LABORATORIO'=>'Laboratorio','AUDITORIO'=>'Auditorio'] as $value=>$label): ?><option value="<?= $value ?>" <?= $filters['tipo']===$value?'selected':'' ?>><?= $label ?></option><?php endforeach; ?></select></div>
            <div><label class="block text-xs font-semibold text-gray-600 mb-1">Estado</label><select name="estado" class="w-full h-10 rounded-xl border border-gray-200 px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 outline-none"><option value="">Todos</option><option value="DISPONIBLE" <?= $filters['estado']==='DISPONIBLE'?'selected':'' ?>>Activo</option><option value="INACTIVO" <?= $filters['estado']==='INACTIVO'?'selected':'' ?>>Inactivo</option><option value="MANTENIMIENTO" <?= $filters['estado']==='MANTENIMIENTO'?'selected':'' ?>>Mantenimiento</option></select></div>
            <div class="flex gap-2"><button class="inline-flex h-10 items-center gap-2 rounded-xl bg-primary-700 hover:bg-primary-800 text-white font-semibold px-4 text-sm"><i class="fas fa-search"></i> Buscar</button><a href="<?= baseUrl('/ambientes/gestion') ?>" class="inline-flex h-10 items-center gap-2 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold px-4 text-sm"><i class="fas fa-redo"></i></a></div>
        </form>
    </div>

    <div class="bg-white/85 backdrop-blur-md border border-primary-100 rounded-3xl shadow-xl overflow-hidden">
        <div class="px-6 py-4 border-b border-primary-100 bg-gradient-to-r from-white to-primary-50 dark:from-[#1c1830] dark:to-[#241a42]"><h3 class="text-lg font-bold text-primary-700"><i class="fas fa-list"></i> Listado de Ambientes</h3></div>
        <div class="overflow-x-auto"><table class="min-w-full text-sm">
            <thead><tr class="bg-primary-50 text-primary-800 text-xs uppercase"><th class="px-4 py-3 text-left font-bold">Código</th><th class="px-4 py-3 text-left font-bold">Ambiente</th><th class="px-4 py-3 text-left font-bold">Ubicación</th><th class="px-4 py-3 text-center font-bold">Capacidad</th><th class="px-4 py-3 text-center font-bold">Estado</th><th class="px-4 py-3 text-center font-bold">Acciones</th></tr></thead>
            <tbody class="divide-y divide-gray-100">
            <?php if (empty($ambientes)): ?><tr><td colspan="6" class="px-4 py-16 text-center text-gray-400"><i class="fas fa-inbox text-4xl mb-3 block"></i>No se encontraron ambientes.</td></tr>
            <?php else: foreach ($ambientes as $ambiente): ?>
                <tr class="hover:bg-primary-50/50 transition">
                    <td class="px-4 py-3 font-bold text-primary-700"><?= e($ambiente['codigo']) ?></td>
                    <td class="px-4 py-3"><span class="font-medium text-gray-700"><?= e($ambiente['nombre']) ?></span><br><span class="text-xs text-gray-400"><?= e(ucfirst(strtolower($ambiente['tipo']))) ?></span></td>
                    <td class="px-4 py-3 text-gray-600"><?= e($ambiente['ubicacion'] ?: '-') ?></td><td class="px-4 py-3 text-center text-gray-600"><?= (int)$ambiente['capacidad'] ?> personas</td>
                    <td class="px-4 py-3 text-center"><?php if ($ambiente['estado']==='INACTIVO'): ?><span class="bg-gray-100 text-gray-600 px-2.5 py-1 rounded-xl text-xs font-semibold">Inactivo</span><?php elseif ((int)$ambiente['ocupado_ahora']>0): ?><span class="bg-amber-100 text-amber-700 px-2.5 py-1 rounded-xl text-xs font-semibold">Ocupado</span><?php else: ?><span class="bg-green-100 text-green-700 px-2.5 py-1 rounded-xl text-xs font-semibold">Disponible</span><?php endif; ?></td>
                    <td class="px-4 py-3"><div class="flex items-center justify-center gap-2"><a href="<?= baseUrl('/ambientes/ver/'.$ambiente['id']) ?>" class="w-8 h-8 rounded-lg bg-blue-100 text-blue-700 flex items-center justify-center hover:bg-blue-200" title="Consultar"><i class="fas fa-eye text-xs"></i></a><?php if (Auth::hasRole('admin')): ?><a href="<?= baseUrl('/ambientes/editar/'.$ambiente['id']) ?>" class="w-8 h-8 rounded-lg bg-purple-100 text-purple-700 flex items-center justify-center hover:bg-purple-200" title="Editar"><i class="fas fa-edit text-xs"></i></a><form method="POST" action="<?= baseUrl('/ambientes/estado/'.$ambiente['id']) ?>"><input type="hidden" name="csrf_token" value="<?= generateCSRFToken() ?>"><button class="w-8 h-8 rounded-lg bg-amber-100 text-amber-700 flex items-center justify-center hover:bg-amber-200" title="<?= $ambiente['estado']==='INACTIVO'?'Activar':'Desactivar' ?>" onclick="return confirm('¿Cambiar el estado de este ambiente?')"><i class="fas fa-<?= $ambiente['estado']==='INACTIVO'?'check':'ban' ?> text-xs"></i></button></form><?php endif; ?></div></td>
                </tr>
            <?php endforeach; endif; ?>
            </tbody>
        </table></div>
        <?php if ($pagination['last_page'] > 1): $query=$_GET; ?><div class="flex items-center justify-center gap-2 py-6"><?php if ($pagination['current_page']>1): ?><a href="<?= baseUrl('/ambientes/gestion?'.http_build_query(array_merge($query,['page'=>$pagination['current_page']-1]))) ?>" class="inline-flex items-center gap-2 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold px-4 py-2 text-sm"><i class="fas fa-chevron-left"></i> Anterior</a><?php endif; ?><span class="rounded-xl bg-primary-700 text-white font-semibold px-4 py-2 text-sm">Página <?= $pagination['current_page'] ?> de <?= $pagination['last_page'] ?></span><?php if ($pagination['current_page']<$pagination['last_page']): ?><a href="<?= baseUrl('/ambientes/gestion?'.http_build_query(array_merge($query,['page'=>$pagination['current_page']+1]))) ?>" class="inline-flex items-center gap-2 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold px-4 py-2 text-sm">Siguiente <i class="fas fa-chevron-right"></i></a><?php endif; ?></div><?php endif; ?>
    </div>
</div>
<?php require APP_PATH . '/views/layouts/footer.php'; ?>
