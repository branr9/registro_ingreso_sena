<?php $ambientesPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH); ?>
<div class="flex flex-wrap gap-2 mb-6" aria-label="Navegación del módulo Ambientes">
    <a href="<?= baseUrl('/ambientes') ?>"
       class="inline-flex items-center gap-2 rounded-xl px-4 py-2 text-sm font-semibold transition <?= $ambientesPath === '/ambientes' ? 'bg-primary-700 text-white shadow-md' : 'bg-white text-gray-600 border border-primary-100 hover:bg-primary-50' ?>">
        <i class="fas fa-chart-pie"></i> Panel
    </a>
    <a href="<?= baseUrl('/ambientes/gestion') ?>"
       class="inline-flex items-center gap-2 rounded-xl px-4 py-2 text-sm font-semibold transition <?= str_starts_with($ambientesPath, '/ambientes/gestion') || str_starts_with($ambientesPath, '/ambientes/ver') || str_starts_with($ambientesPath, '/ambientes/editar') || $ambientesPath === '/ambientes/crear' ? 'bg-primary-700 text-white shadow-md' : 'bg-white text-gray-600 border border-primary-100 hover:bg-primary-50' ?>">
        <i class="fas fa-building"></i> Gestión
    </a>
    <a href="<?= baseUrl('/ambientes/reservas') ?>"
       class="inline-flex items-center gap-2 rounded-xl px-4 py-2 text-sm font-semibold transition <?= str_starts_with($ambientesPath, '/ambientes/reservas') ? 'bg-primary-700 text-white shadow-md' : 'bg-white text-gray-600 border border-primary-100 hover:bg-primary-50' ?>">
        <i class="fas fa-calendar-check"></i> Reservas
    </a>
    <a href="<?= baseUrl('/ambientes/calendario') ?>"
       class="inline-flex items-center gap-2 rounded-xl px-4 py-2 text-sm font-semibold transition <?= str_starts_with($ambientesPath, '/ambientes/calendario') ? 'bg-primary-700 text-white shadow-md' : 'bg-white text-gray-600 border border-primary-100 hover:bg-primary-50' ?>">
        <i class="fas fa-calendar-alt"></i> Calendario
    </a>
    <a href="<?= baseUrl('/ambientes/disponibilidad') ?>"
       class="inline-flex items-center gap-2 rounded-xl px-4 py-2 text-sm font-semibold transition <?= str_starts_with($ambientesPath, '/ambientes/disponibilidad') ? 'bg-primary-700 text-white shadow-md' : 'bg-white text-gray-600 border border-primary-100 hover:bg-primary-50' ?>">
        <i class="fas fa-search"></i> Disponibilidad
    </a>
</div>
