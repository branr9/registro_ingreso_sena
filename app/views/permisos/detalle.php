<?php require_once APP_PATH . '/views/layouts/header.php'; ?>

<?php
$estadoBadge = [
    'ACTIVO'    => 'bg-green-100 text-green-700 dark:bg-green-950/40 dark:text-green-400',
    'USADO'     => 'bg-blue-100 text-blue-700 dark:bg-blue-950/40 dark:text-blue-400',
    'VENCIDO'   => 'bg-amber-100 text-amber-700 dark:bg-amber-950/40 dark:text-amber-400',
    'CANCELADO' => 'bg-red-100 text-red-600 dark:bg-red-950/40 dark:text-red-400',
][$permiso['estado']] ?? 'bg-gray-100 text-gray-600 dark:bg-gray-800 dark:text-gray-400';

$estadoBorder = [
    'ACTIVO'    => 'border-l-green-500',
    'USADO'     => 'border-l-blue-500',
    'VENCIDO'   => 'border-l-amber-400',
    'CANCELADO' => 'border-l-red-500',
][$permiso['estado']] ?? 'border-l-gray-400';
?>

<!-- Page Header -->
<div class="flex items-center justify-between mb-8 flex-wrap gap-4">
    <div class="flex items-center gap-3">
        <div class="w-10 h-10 rounded-2xl bg-primary-100 text-primary-700 flex items-center justify-center text-lg flex-shrink-0">
            <i class="fas fa-file-alt"></i>
        </div>
        <div>
            <h1 class="text-2xl font-extrabold text-primary-700">Detalle del Permiso</h1>
        </div>
    </div>
    <a href="<?= baseUrl('/permisos') ?>"
       class="inline-flex items-center gap-2 rounded-xl bg-gray-100 hover:bg-gray-200 dark:bg-[#2d2550] dark:hover:bg-[#3a2e66] transition text-gray-700 dark:text-gray-300 font-semibold px-5 py-2.5 text-sm">
        <i class="fas fa-arrow-left"></i> Volver
    </a>
</div>

<!-- Card principal -->
<div class="bg-white dark:bg-[#1c1830] border border-primary-100 dark:border-[#2d2550] rounded-3xl shadow-xl overflow-hidden">

    <!-- Card Header -->
    <div class="px-6 py-4 border-b border-primary-100 dark:border-[#2d2550] bg-gradient-to-r from-white to-primary-50 dark:from-[#1c1830] dark:to-[#241a42] flex items-center justify-between flex-wrap gap-3">
        <h3 class="text-lg font-bold text-primary-700 flex items-center gap-2">
            <i class="fas fa-info-circle"></i> Información del Permiso #<?= $permiso['id'] ?>
        </h3>
        <span class="inline-flex items-center px-3 py-1 rounded-xl text-sm font-bold <?= $estadoBadge ?>">
            <?= e($permiso['estado']) ?>
        </span>
    </div>

    <div class="p-6 md:p-8 space-y-5">

        <!-- Datos del Aprendiz -->
        <div class="bg-gray-50 dark:bg-[#241a42] border-l-4 border-l-primary-500 rounded-2xl p-5">
            <h4 class="text-base font-bold text-primary-600 dark:text-primary-400 flex items-center gap-2 mb-4">
                <i class="fas fa-user"></i> Datos del Aprendiz
            </h4>
            <div class="flex items-center justify-between py-2.5 border-b border-gray-200 dark:border-[#2d2550]">
                <span class="text-sm font-semibold text-gray-500 dark:text-gray-400">Documento:</span>
                <span class="text-sm font-semibold text-gray-800 dark:text-gray-100"><?= e($permiso['documento_aprendiz']) ?></span>
            </div>
            <div class="flex items-center justify-between py-2.5">
                <span class="text-sm font-semibold text-gray-500 dark:text-gray-400">Nombre Completo:</span>
                <span class="text-sm font-semibold text-gray-800 dark:text-gray-100"><?= e($permiso['nombre_aprendiz']) ?></span>
            </div>
        </div>

        <!-- Fechas y Horarios -->
        <div class="bg-gray-50 dark:bg-[#241a42] border-l-4 border-l-primary-500 rounded-2xl p-5">
            <h4 class="text-base font-bold text-primary-600 dark:text-primary-400 flex items-center gap-2 mb-4">
                <i class="fas fa-calendar-alt"></i> Fechas y Horarios
            </h4>
            <div class="flex items-center justify-between py-2.5 border-b border-gray-200 dark:border-[#2d2550]">
                <span class="text-sm font-semibold text-gray-500 dark:text-gray-400">Fecha del Permiso:</span>
                <span class="text-sm font-semibold text-gray-800 dark:text-gray-100"><?= date('d/m/Y', strtotime($permiso['fecha_permiso'])) ?></span>
            </div>
            <div class="flex items-center justify-between py-2.5 border-b border-gray-200 dark:border-[#2d2550]">
                <span class="text-sm font-semibold text-gray-500 dark:text-gray-400">Hora de Salida:</span>
                <span class="text-sm font-semibold text-gray-800 dark:text-gray-100"><?= substr($permiso['hora_salida'], 0, 5) ?></span>
            </div>
            <div class="flex items-center justify-between py-2.5">
                <span class="text-sm font-semibold text-gray-500 dark:text-gray-400">Hora de Regreso:</span>
                <span class="text-sm font-semibold text-gray-800 dark:text-gray-100">
                    <?= $permiso['hora_regreso'] ? substr($permiso['hora_regreso'], 0, 5) : '<span class="text-gray-400 dark:text-gray-500 font-normal">No especificada</span>' ?>
                </span>
            </div>
        </div>

        <!-- Motivo -->
        <div class="bg-gray-50 dark:bg-[#241a42] border-l-4 border-l-primary-500 rounded-2xl p-5">
            <h4 class="text-base font-bold text-primary-600 dark:text-primary-400 flex items-center gap-2 mb-3">
                <i class="fas fa-clipboard"></i> Motivo
            </h4>
            <div class="bg-white dark:bg-[#1c1830] border border-gray-200 dark:border-[#2d2550] rounded-xl px-4 py-3 text-sm text-gray-700 dark:text-gray-300 leading-relaxed">
                <?= nl2br(e($permiso['motivo'])) ?>
            </div>
        </div>

        <?php if (!empty($permiso['observaciones'])): ?>
        <!-- Observaciones -->
        <div class="bg-gray-50 dark:bg-[#241a42] border-l-4 border-l-primary-500 rounded-2xl p-5">
            <h4 class="text-base font-bold text-primary-600 dark:text-primary-400 flex items-center gap-2 mb-3">
                <i class="fas fa-sticky-note"></i> Observaciones
            </h4>
            <div class="bg-white dark:bg-[#1c1830] border border-gray-200 dark:border-[#2d2550] rounded-xl px-4 py-3 text-sm text-gray-700 dark:text-gray-300 leading-relaxed">
                <?= nl2br(e($permiso['observaciones'])) ?>
            </div>
        </div>
        <?php endif; ?>

        <!-- Autorización -->
        <div class="bg-gray-50 dark:bg-[#241a42] border-l-4 border-l-primary-500 rounded-2xl p-5">
            <h4 class="text-base font-bold text-primary-600 dark:text-primary-400 flex items-center gap-2 mb-4">
                <i class="fas fa-user-tie"></i> Autorización
            </h4>
            <div class="flex items-center justify-between py-2.5 border-b border-gray-200 dark:border-[#2d2550]">
                <span class="text-sm font-semibold text-gray-500 dark:text-gray-400">Instructor:</span>
                <span class="text-sm font-semibold text-gray-800 dark:text-gray-100"><?= e($permiso['instructor_nombre']) ?></span>
            </div>
            <div class="flex items-center justify-between py-2.5">
                <span class="text-sm font-semibold text-gray-500 dark:text-gray-400">Fecha de Creación:</span>
                <span class="text-sm font-semibold text-gray-800 dark:text-gray-100"><?= date('d/m/Y H:i', strtotime($permiso['created_at'])) ?></span>
            </div>
        </div>

        <?php if ($permiso['estado'] === 'USADO'): ?>
        <!-- Uso del Permiso -->
        <div class="bg-blue-50 dark:bg-blue-950/20 border-l-4 border-l-blue-500 rounded-2xl p-5">
            <h4 class="text-base font-bold text-blue-600 dark:text-blue-400 flex items-center gap-2 mb-4">
                <i class="fas fa-check-circle"></i> Uso del Permiso
            </h4>
            <div class="flex items-center justify-between py-2.5">
                <span class="text-sm font-semibold text-gray-500 dark:text-gray-400">Fecha y Hora de Uso:</span>
                <span class="text-sm font-semibold text-gray-800 dark:text-gray-100">
                    <?= $permiso['fecha_uso'] ? date('d/m/Y H:i', strtotime($permiso['fecha_uso'])) : 'N/A' ?>
                </span>
            </div>
        </div>
        <?php endif; ?>

    </div>
</div>

<?php require_once APP_PATH . '/views/layouts/footer.php'; ?>
