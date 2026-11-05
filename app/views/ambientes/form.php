<?php require APP_PATH . '/views/layouts/header.php'; $editing = !empty($ambiente); ?>
<div class="w-full max-w-3xl mx-auto">
    <?php require APP_PATH . '/views/ambientes/_nav.php'; ?>
    <h2 class="text-2xl font-extrabold text-primary-700 flex items-center gap-2 mb-6"><i class="fas fa-<?= $editing?'edit':'plus' ?>"></i> <?= $editing?'Editar':'Nuevo' ?> Ambiente</h2>
    <div class="bg-white/85 backdrop-blur-md border border-primary-100 rounded-3xl shadow-xl p-6 md:p-8">
        <form method="POST" action="<?= baseUrl($editing?'/ambientes/actualizar/'.$ambiente['id']:'/ambientes/guardar') ?>" class="space-y-5">
            <input type="hidden" name="csrf_token" value="<?= generateCSRFToken() ?>">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div><label for="codigo" class="block text-sm font-semibold text-gray-700 mb-1"><i class="fas fa-barcode text-primary-600"></i> Código *</label><input id="codigo" name="codigo" type="text" maxlength="40" pattern="[A-Za-z0-9._-]{2,40}" value="<?= e($ambiente['codigo']??'') ?>" placeholder="Ej: LAB-201" class="w-full rounded-xl border border-gray-200 px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none" required></div>
                <div><label for="tipo" class="block text-sm font-semibold text-gray-700 mb-1"><i class="fas fa-layer-group text-primary-600"></i> Tipo *</label><select id="tipo" name="tipo" class="w-full rounded-xl border border-gray-200 px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 outline-none" required><option value="">Seleccione...</option><?php foreach(['SALA'=>'Sala','LABORATORIO'=>'Laboratorio','AUDITORIO'=>'Auditorio'] as $value=>$label): ?><option value="<?= $value ?>" <?= ($ambiente['tipo']??'')===$value?'selected':'' ?>><?= $label ?></option><?php endforeach; ?></select></div>
            </div>
            <div><label for="nombre" class="block text-sm font-semibold text-gray-700 mb-1"><i class="fas fa-door-open text-primary-600"></i> Nombre *</label><input id="nombre" name="nombre" type="text" minlength="3" maxlength="120" value="<?= e($ambiente['nombre']??'') ?>" placeholder="Ej: Laboratorio de Sistemas" class="w-full rounded-xl border border-gray-200 px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none" required></div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div><label for="ubicacion" class="block text-sm font-semibold text-gray-700 mb-1"><i class="fas fa-map-marker-alt text-primary-600"></i> Ubicación</label><input id="ubicacion" name="ubicacion" type="text" maxlength="160" value="<?= e($ambiente['ubicacion']??'') ?>" placeholder="Bloque, piso..." class="w-full rounded-xl border border-gray-200 px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 outline-none"></div>
                <div><label for="capacidad" class="block text-sm font-semibold text-gray-700 mb-1"><i class="fas fa-users text-primary-600"></i> Capacidad *</label><input id="capacidad" name="capacidad" type="number" min="1" max="1000" value="<?= (int)($ambiente['capacidad']??30) ?>" class="w-full rounded-xl border border-gray-200 px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 outline-none" required></div>
            </div>
            <div class="bg-blue-50 border border-blue-200 text-blue-800 rounded-2xl px-4 py-3 text-sm"><i class="fas fa-info-circle mr-1"></i> La ocupación se controla mediante reservas temporales; crear un ambiente no lo asigna permanentemente a ningún instructor.</div>
            <div class="flex flex-wrap gap-3 pt-2"><button class="inline-flex items-center gap-2 rounded-2xl bg-primary-700 hover:bg-primary-800 text-white font-semibold px-5 py-2.5 shadow-md"><i class="fas fa-save"></i> Guardar</button><a href="<?= baseUrl('/ambientes/gestion') ?>" class="inline-flex items-center gap-2 rounded-2xl bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold px-5 py-2.5"><i class="fas fa-times"></i> Cancelar</a></div>
        </form>
    </div>
</div>
<?php require APP_PATH . '/views/layouts/footer.php'; ?>
