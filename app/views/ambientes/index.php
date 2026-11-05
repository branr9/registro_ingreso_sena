<?php require APP_PATH . '/views/layouts/header.php'; ?>

<div>
    <?php require APP_PATH . '/views/ambientes/_nav.php'; ?>

    <div class="flex items-center justify-between flex-wrap gap-4 mb-8">
        <div>
            <h2 class="text-2xl font-extrabold text-primary-700 flex items-center gap-2">
                <i class="fas fa-building"></i> Panel de Ambientes
            </h2>
            <p class="text-sm text-gray-500 mt-1">Administre espacios y reservas temporales desde un solo lugar.</p>
        </div>
        <div class="flex flex-wrap items-center gap-3">
            <?php if (Auth::hasRole('admin')): ?>
            <a href="<?= baseUrl('/ambientes/crear') ?>"
               class="inline-flex items-center gap-2 rounded-2xl bg-accent-600 hover:bg-accent-700 transition text-white font-semibold px-5 py-2.5 shadow-md">
                <i class="fas fa-plus"></i> Crear Ambiente
            </a>
            <?php endif; ?>
            <a href="<?= baseUrl('/ambientes/reservas/crear') ?>"
               class="inline-flex items-center gap-2 rounded-2xl bg-primary-700 hover:bg-primary-800 transition text-white font-semibold px-5 py-2.5 shadow-md">
                <i class="fas fa-calendar-plus"></i> Nueva Reserva
            </a>
        </div>
    </div>

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <div class="bg-white rounded-3xl shadow-md border border-primary-100 p-5 flex items-center gap-4 hover:shadow-lg transition">
            <div class="w-14 h-14 rounded-2xl bg-primary-100 text-primary-700 flex items-center justify-center text-2xl flex-shrink-0"><i class="fas fa-building"></i></div>
            <div><h3 class="text-3xl font-extrabold text-gray-800"><?= $stats['total'] ?></h3><p class="text-sm text-gray-500">Ambientes</p></div>
        </div>
        <div class="bg-white rounded-3xl shadow-md border border-primary-100 p-5 flex items-center gap-4 hover:shadow-lg transition">
            <div class="w-14 h-14 rounded-2xl bg-green-100 text-green-700 flex items-center justify-center text-2xl flex-shrink-0"><i class="fas fa-check-circle"></i></div>
            <div><h3 class="text-3xl font-extrabold text-green-700"><?= $stats['activos'] ?></h3><p class="text-sm text-gray-500">Activos</p></div>
        </div>
        <div class="bg-white rounded-3xl shadow-md border border-primary-100 p-5 flex items-center gap-4 hover:shadow-lg transition">
            <div class="w-14 h-14 rounded-2xl bg-accent-100 text-accent-700 flex items-center justify-center text-2xl flex-shrink-0"><i class="fas fa-calendar-day"></i></div>
            <div><h3 class="text-3xl font-extrabold text-accent-700"><?= $stats['reservas_hoy'] ?></h3><p class="text-sm text-gray-500">Reservas Hoy</p></div>
        </div>
        <div class="bg-white rounded-3xl shadow-md border border-primary-100 p-5 flex items-center gap-4 hover:shadow-lg transition">
            <div class="w-14 h-14 rounded-2xl bg-amber-100 text-amber-700 flex items-center justify-center text-2xl flex-shrink-0"><i class="fas fa-clock"></i></div>
            <div><h3 class="text-3xl font-extrabold text-amber-700"><?= $stats['ocupados_ahora'] ?></h3><p class="text-sm text-gray-500">Ocupados Ahora</p></div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <a href="<?= baseUrl('/ambientes/disponibilidad') ?>" class="bg-white rounded-3xl shadow-md border border-primary-100 p-6 hover:shadow-xl hover:-translate-y-0.5 transition">
            <div class="w-12 h-12 rounded-2xl bg-green-100 text-green-700 flex items-center justify-center text-xl mb-4"><i class="fas fa-search"></i></div>
            <h3 class="font-bold text-gray-800 mb-1">Consultar disponibilidad</h3>
            <p class="text-sm text-gray-500">Encuentre ambientes libres por fecha, horario y capacidad.</p>
        </a>
        <a href="<?= baseUrl('/ambientes/calendario') ?>" class="bg-white rounded-3xl shadow-md border border-primary-100 p-6 hover:shadow-xl hover:-translate-y-0.5 transition">
            <div class="w-12 h-12 rounded-2xl bg-blue-100 text-blue-700 flex items-center justify-center text-xl mb-4"><i class="fas fa-calendar-alt"></i></div>
            <h3 class="font-bold text-gray-800 mb-1">Calendario de ocupación</h3>
            <p class="text-sm text-gray-500">Visualice las reservas mensuales sin salir del módulo.</p>
        </a>
        <a href="<?= baseUrl('/ambientes/reservas') ?>" class="bg-white rounded-3xl shadow-md border border-primary-100 p-6 hover:shadow-xl hover:-translate-y-0.5 transition">
            <div class="w-12 h-12 rounded-2xl bg-purple-100 text-purple-700 flex items-center justify-center text-xl mb-4"><i class="fas fa-history"></i></div>
            <h3 class="font-bold text-gray-800 mb-1">Historial y reportes</h3>
            <p class="text-sm text-gray-500">Consulte, filtre y exporte el historial de reservas.</p>
        </a>
    </div>

    <div class="bg-white/85 backdrop-blur-md border border-primary-100 rounded-3xl shadow-xl overflow-hidden">
        <div class="px-6 py-4 border-b border-primary-100 bg-gradient-to-r from-white to-primary-50 dark:from-[#1c1830] dark:to-[#241a42] flex items-center justify-between gap-3">
            <h3 class="text-lg font-bold text-primary-700"><i class="fas fa-calendar-check"></i> Próximas Reservas</h3>
            <a href="<?= baseUrl('/ambientes/reservas') ?>" class="text-sm font-semibold text-primary-700 hover:text-primary-800">Ver historial</a>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead><tr class="bg-primary-50 text-primary-800 text-xs uppercase">
                    <th class="px-4 py-3 text-left font-bold">Ambiente</th><th class="px-4 py-3 text-left font-bold">Actividad</th>
                    <th class="px-4 py-3 text-left font-bold">Responsable</th><th class="px-4 py-3 text-left font-bold">Fecha y horario</th><th class="px-4 py-3 text-center font-bold">Acción</th>
                </tr></thead>
                <tbody class="divide-y divide-gray-100">
                <?php if (empty($proximasReservas)): ?>
                    <tr><td colspan="5" class="px-4 py-12 text-center text-gray-400"><i class="fas fa-calendar-check text-3xl mb-2 block"></i>No hay reservas próximas.</td></tr>
                <?php else: foreach ($proximasReservas as $reserva): ?>
                    <tr class="hover:bg-primary-50/50 transition">
                        <td class="px-4 py-3"><span class="font-bold text-gray-700"><?= e($reserva['ambiente_codigo']) ?></span><br><span class="text-xs text-gray-500"><?= e($reserva['ambiente_nombre']) ?></span></td>
                        <td class="px-4 py-3 text-gray-700"><?= e($reserva['titulo']) ?></td>
                        <td class="px-4 py-3 text-gray-600"><?= e($reserva['responsable_nombre']) ?></td>
                        <td class="px-4 py-3 text-gray-600"><?= date('d/m/Y', strtotime($reserva['inicio'])) ?><br><span class="text-xs"><?= date('H:i', strtotime($reserva['inicio'])) ?> - <?= date('H:i', strtotime($reserva['fin'])) ?></span></td>
                        <td class="px-4 py-3"><a href="<?= baseUrl('/ambientes/reservas/ver/' . $reserva['id']) ?>" class="mx-auto w-8 h-8 rounded-lg bg-blue-100 text-blue-700 flex items-center justify-center hover:bg-blue-200 transition" title="Consultar"><i class="fas fa-eye text-xs"></i></a></td>
                    </tr>
                <?php endforeach; endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require APP_PATH . '/views/layouts/footer.php'; ?>
