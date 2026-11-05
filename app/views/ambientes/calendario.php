<?php require APP_PATH . '/views/layouts/header.php'; ?>
<link rel="stylesheet" href="<?= asset('css/ambientes.css') ?>?v=1">
<div>
    <?php require APP_PATH . '/views/ambientes/_nav.php'; ?>
    <div class="flex items-center justify-between flex-wrap gap-4 mb-8">
        <div><h2 class="text-2xl font-extrabold text-primary-700 flex items-center gap-2"><i class="fas fa-calendar-alt"></i> Calendario de Reservas</h2><p class="text-sm text-gray-500 mt-1">Vista mensual de la ocupación programada.</p></div>
        <a href="<?= baseUrl('/ambientes/reservas/crear') ?>" class="inline-flex items-center gap-2 rounded-2xl bg-primary-700 hover:bg-primary-800 text-white font-semibold px-5 py-2.5 shadow-md"><i class="fas fa-plus"></i> Nueva Reserva</a>
    </div>
    <div class="bg-white/85 backdrop-blur-md border border-primary-100 rounded-3xl shadow-xl overflow-hidden">
        <div class="px-6 py-4 border-b border-primary-100 bg-gradient-to-r from-white to-primary-50 dark:from-[#1c1830] dark:to-[#241a42] flex flex-wrap items-end justify-between gap-4">
            <div class="flex items-center gap-2"><button id="calendarPrev" type="button" class="w-10 h-10 rounded-xl bg-white border border-primary-100 text-primary-700 hover:bg-primary-50" aria-label="Mes anterior"><i class="fas fa-chevron-left"></i></button><button id="calendarToday" type="button" class="h-10 rounded-xl bg-primary-700 text-white font-semibold px-4 hover:bg-primary-800">Hoy</button><button id="calendarNext" type="button" class="w-10 h-10 rounded-xl bg-white border border-primary-100 text-primary-700 hover:bg-primary-50" aria-label="Mes siguiente"><i class="fas fa-chevron-right"></i></button></div>
            <h3 id="calendarTitle" class="text-xl font-extrabold text-primary-700 capitalize"></h3>
            <div class="min-w-[230px]"><label for="calendarAmbiente" class="block text-xs font-semibold text-gray-600 mb-1">Filtrar ambiente</label><select id="calendarAmbiente" class="w-full h-10 rounded-xl border border-gray-200 px-3 text-sm focus:ring-2 focus:ring-primary-500 outline-none"><option value="">Todos los ambientes</option><?php foreach($ambientes as $a): ?><option value="<?= $a['id'] ?>"><?= e($a['codigo'].' - '.$a['nombre']) ?></option><?php endforeach; ?></select></div>
        </div>
        <div id="ambientesCalendar" class="amb-calendar-wrap" data-endpoint="<?= baseUrl('/ambientes/calendario/eventos') ?>">
            <div class="amb-calendar-loading"><i class="fas fa-spinner fa-spin"></i> Cargando calendario...</div>
        </div>
        <div class="px-6 py-4 border-t border-primary-100 flex flex-wrap gap-4 text-xs text-gray-500"><span><span class="inline-block w-3 h-3 rounded bg-primary-600 mr-1"></span> Reserva activa</span><span><i class="fas fa-info-circle text-accent-600"></i> Seleccione una reserva propia para consultar su detalle.</span></div>
    </div>
</div>
<script src="<?= asset('js/ambientes-calendar.js') ?>?v=1"></script>
<?php require APP_PATH . '/views/layouts/footer.php'; ?>
