<?php require APP_PATH . '/views/layouts/header.php'; ?>
<link rel="stylesheet" href="<?= asset('css/ambientes.css') ?>?v=1">
<div>
    <?php require APP_PATH . '/views/ambientes/_nav.php'; ?>
    <div class="mb-8"><h2 class="text-2xl font-extrabold text-primary-700 flex items-center gap-2"><i class="fas fa-search"></i> Disponibilidad de Ambientes</h2><p class="text-sm text-gray-500 mt-1">Consulte espacios libres para un horario específico.</p></div>
    <div class="bg-white/85 backdrop-blur-md border border-primary-100 rounded-3xl shadow-xl overflow-hidden mb-8">
        <div class="px-6 py-4 border-b border-primary-100 bg-gradient-to-r from-white to-primary-50 dark:from-[#1c1830] dark:to-[#241a42]"><h3 class="text-lg font-bold text-primary-700"><i class="fas fa-filter"></i> Fecha y horario</h3></div>
        <form method="GET" action="<?= baseUrl('/ambientes/disponibilidad') ?>" class="p-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 items-end gap-4">
            <div><label class="block text-xs font-semibold text-gray-600 mb-1">Fecha</label><input type="date" name="fecha" value="<?= e($fecha) ?>" min="<?= date('Y-m-d') ?>" class="w-full h-10 rounded-xl border border-gray-200 px-3 text-sm focus:ring-2 focus:ring-primary-500 outline-none" required></div>
            <div><label class="block text-xs font-semibold text-gray-600 mb-1">Hora inicio</label><input type="time" name="hora_inicio" value="<?= e($horaInicio) ?>" class="w-full h-10 rounded-xl border border-gray-200 px-3 text-sm focus:ring-2 focus:ring-primary-500 outline-none" required></div>
            <div><label class="block text-xs font-semibold text-gray-600 mb-1">Hora fin</label><input type="time" name="hora_fin" value="<?= e($horaFin) ?>" class="w-full h-10 rounded-xl border border-gray-200 px-3 text-sm focus:ring-2 focus:ring-primary-500 outline-none" required></div>
            <div><label class="block text-xs font-semibold text-gray-600 mb-1">Capacidad mínima</label><input type="number" name="capacidad" value="<?= $capacidad?:'' ?>" min="1" max="1000" placeholder="Cualquiera" class="w-full h-10 rounded-xl border border-gray-200 px-3 text-sm focus:ring-2 focus:ring-primary-500 outline-none"></div>
            <div><label class="block text-xs font-semibold text-gray-600 mb-1">Tipo</label><select name="tipo" class="w-full h-10 rounded-xl border border-gray-200 px-3 text-sm focus:ring-2 focus:ring-primary-500 outline-none"><option value="">Todos</option><?php foreach(['SALA'=>'Sala','LABORATORIO'=>'Laboratorio','AUDITORIO'=>'Auditorio'] as $v=>$l): ?><option value="<?= $v ?>" <?= $tipo===$v?'selected':'' ?>><?= $l ?></option><?php endforeach; ?></select></div>
            <button class="inline-flex h-10 items-center justify-center gap-2 rounded-xl bg-primary-700 hover:bg-primary-800 text-white font-semibold px-4 text-sm"><i class="fas fa-search"></i> Consultar</button>
        </form>
    </div>
    <?php if($errorHorario): ?><div class="bg-red-50 border border-red-200 text-red-800 rounded-2xl px-5 py-4 mb-6"><i class="fas fa-exclamation-circle"></i> La hora final debe ser posterior a la hora de inicio.</div><?php else: ?>
    <?php $availableCount=count(array_filter($ambientes,static fn($a)=>(int)$a['ocupado']===0)); ?>
    <div class="flex items-center justify-between flex-wrap gap-3 mb-5"><h3 class="text-lg font-bold text-primary-700"><i class="fas fa-door-open"></i> Resultados</h3><span class="bg-green-100 text-green-700 px-3 py-1 rounded-xl text-sm font-semibold"><?= $availableCount ?> disponible(s) de <?= count($ambientes) ?></span></div>
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">
        <?php if(empty($ambientes)): ?><div class="md:col-span-2 xl:col-span-3 bg-white/85 border border-primary-100 rounded-3xl p-12 text-center text-gray-400 shadow-md"><i class="fas fa-search text-4xl mb-3 block"></i>No hay ambientes que cumplan los filtros.</div><?php else: foreach($ambientes as $a): $free=(int)$a['ocupado']===0; ?>
        <article class="amb-availability-card bg-white rounded-3xl shadow-md border <?= $free?'border-green-200':'border-red-100' ?> p-6">
            <div class="flex items-start justify-between gap-3 mb-4"><div class="w-12 h-12 rounded-2xl <?= $free?'bg-green-100 text-green-700':'bg-red-100 text-red-700' ?> flex items-center justify-center text-xl"><i class="fas fa-building"></i></div><span class="<?= $free?'bg-green-100 text-green-700':'bg-red-100 text-red-700' ?> px-3 py-1 rounded-xl text-xs font-semibold"><i class="fas fa-<?= $free?'check':'clock' ?>-circle"></i> <?= $free?'Disponible':'Ocupado' ?></span></div>
            <p class="text-xs font-bold text-primary-700"><?= e($a['codigo']) ?></p><h4 class="text-lg font-bold text-gray-800 mt-1"><?= e($a['nombre']) ?></h4><p class="text-sm text-gray-500 mt-1"><i class="fas fa-map-marker-alt w-4"></i> <?= e($a['ubicacion']?:'Sin ubicación') ?></p>
            <div class="flex gap-4 text-sm text-gray-600 mt-4"><span><i class="fas fa-users text-primary-600"></i> <?= (int)$a['capacidad'] ?></span><span><i class="fas fa-layer-group text-primary-600"></i> <?= e(ucfirst(strtolower($a['tipo']))) ?></span></div>
            <?php if($free): ?><a href="<?= baseUrl('/ambientes/reservas/crear?'.http_build_query(['ambiente_id'=>$a['id'],'fecha'=>$fecha,'hora_inicio'=>$horaInicio,'hora_fin'=>$horaFin])) ?>" class="mt-5 w-full inline-flex items-center justify-center gap-2 rounded-2xl bg-primary-700 hover:bg-primary-800 text-white font-semibold px-5 py-2.5 shadow-md"><i class="fas fa-calendar-plus"></i> Reservar este ambiente</a><?php else: ?><a href="<?= baseUrl('/ambientes/calendario') ?>" class="mt-5 w-full inline-flex items-center justify-center gap-2 rounded-2xl bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold px-5 py-2.5"><i class="fas fa-calendar-alt"></i> Ver calendario</a><?php endif; ?>
        </article>
        <?php endforeach; endif; ?>
    </div><?php endif; ?>
</div>
<?php require APP_PATH . '/views/layouts/footer.php'; ?>
