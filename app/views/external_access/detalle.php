<?php
/**
 * Vista: Detalle de Registro de Acceso Externo
 */
require_once APP_PATH . '/views/layouts/header.php';
?>

<div class="w-full max-w-5xl mx-auto">
    <!-- Header -->
    <div class="flex items-center gap-4 flex-wrap mb-6">
        <a href="<?= baseUrl('/acceso-externo') ?>"
           class="inline-flex items-center gap-2 rounded-xl bg-gray-100 hover:bg-gray-200 transition text-gray-700 font-semibold px-4 py-2 text-sm">
            <i class="fas fa-arrow-left"></i> Volver
        </a>
        <h1 class="text-2xl font-extrabold text-primary-700 flex items-center gap-2">
            <i class="fas fa-file-alt"></i> Detalle del Registro
        </h1>
        <?php if ($registro['estado'] === 'DENTRO'): ?>
            <span class="bg-green-100 text-green-700 px-3 py-1 rounded-xl text-sm font-semibold">Dentro</span>
        <?php else: ?>
            <span class="bg-gray-100 text-gray-600 px-3 py-1 rounded-xl text-sm font-semibold">Salió</span>
        <?php endif; ?>
    </div>

    <div class="bg-white/85 backdrop-blur-md border border-primary-100 rounded-3xl shadow-xl divide-y divide-gray-100 overflow-hidden">
        <!-- Datos del Visitante -->
        <section class="p-6">
            <h3 class="text-lg font-bold text-primary-700 mb-4 flex items-center gap-2">
                <i class="fas fa-user"></i> Datos del Visitante
            </h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                <div>
                    <label class="block text-xs font-bold text-gray-500 mb-1">Documento</label>
                    <p class="text-sm text-gray-800"><?= e($registro['tipo_documento']) ?> - <?= e($registro['documento']) ?></p>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 mb-1">Nombre Completo</label>
                    <p class="text-sm font-medium text-gray-800"><?= e($registro['nombre_completo']) ?></p>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 mb-1">Empresa</label>
                    <p class="text-sm text-gray-800"><?= e($registro['empresa'] ?? '-') ?></p>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 mb-1">Teléfono</label>
                    <p class="text-sm text-gray-800"><?= e($registro['telefono'] ?? '-') ?></p>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 mb-1">Email</label>
                    <p class="text-sm text-gray-800"><?= e($registro['email'] ?? '-') ?></p>
                </div>
            </div>
        </section>

        <!-- Información de la Visita -->
        <section class="p-6">
            <h3 class="text-lg font-bold text-primary-700 mb-4 flex items-center gap-2">
                <i class="fas fa-clipboard-list"></i> Información de la Visita
            </h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                <div>
                    <label class="block text-xs font-bold text-gray-500 mb-1">Motivo</label>
                    <p class="text-sm text-gray-800"><?= e($registro['motivo_visita']) ?></p>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 mb-1">Persona Visitada</label>
                    <p class="text-sm text-gray-800"><?= e($registro['persona_visitada'] ?? '-') ?></p>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 mb-1">Área Destino</label>
                    <p class="text-sm text-gray-800"><?= e($registro['area_destino'] ?? '-') ?></p>
                </div>
            </div>
        </section>

        <!-- Control de Horarios -->
        <section class="p-6">
            <h3 class="text-lg font-bold text-primary-700 mb-4 flex items-center gap-2">
                <i class="fas fa-clock"></i> Control de Horarios
            </h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                <div>
                    <label class="block text-xs font-bold text-gray-500 mb-1">Fecha/Hora Entrada</label>
                    <p class="text-sm font-medium text-green-700">
                        <i class="fas fa-sign-in-alt"></i>
                        <?= date('d/m/Y h:i A', strtotime($registro['fecha_entrada'])) ?>
                    </p>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 mb-1">Fecha/Hora Salida</label>
                    <p class="text-sm font-medium <?= $registro['fecha_salida'] ? 'text-red-600' : 'text-accent-700' ?>">
                        <?php if ($registro['fecha_salida']): ?>
                            <i class="fas fa-sign-out-alt"></i>
                            <?= date('d/m/Y h:i A', strtotime($registro['fecha_salida'])) ?>
                        <?php else: ?>
                            <i class="fas fa-user-clock"></i> Aún dentro
                        <?php endif; ?>
                    </p>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 mb-1">Tiempo de Permanencia</label>
                    <p class="text-sm font-medium text-accent-700">
                        <?php
                        $minutos = $registro['estado'] === 'DENTRO'
                            ? $registro['minutos_transcurridos']
                            : $registro['tiempo_permanencia'];
                        $horas = floor($minutos / 60);
                        $mins = $minutos % 60;
                        echo "{$horas} horas {$mins} minutos";
                        ?>
                    </p>
                </div>
            </div>
        </section>

        <!-- Vigilantes -->
        <section class="p-6">
            <h3 class="text-lg font-bold text-primary-700 mb-4 flex items-center gap-2">
                <i class="fas fa-user-shield"></i> Control de Vigilancia
            </h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                <div>
                    <label class="block text-xs font-bold text-gray-500 mb-1">Vigilante Entrada</label>
                    <p class="text-sm text-gray-800"><?= e($registro['vigilante_entrada_nombre'] ?? 'No registrado') ?></p>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 mb-1">Vigilante Salida</label>
                    <p class="text-sm text-gray-800"><?= e($registro['vigilante_salida_nombre'] ?? '-') ?></p>
                </div>
            </div>
        </section>

        <!-- Observaciones -->
        <?php if ($registro['observaciones']): ?>
        <section class="p-6">
            <h3 class="text-lg font-bold text-primary-700 mb-4 flex items-center gap-2">
                <i class="fas fa-sticky-note"></i> Observaciones
            </h3>
            <div class="bg-primary-50 rounded-xl border-l-4 border-primary-600 p-4">
                <p class="text-sm text-gray-700 whitespace-pre-wrap"><?= e($registro['observaciones']) ?></p>
            </div>
        </section>
        <?php endif; ?>

        <!-- Botones de acción -->
        <?php if ($registro['estado'] === 'DENTRO'): ?>
        <section class="p-6">
            <form method="POST" action="<?= baseUrl('/acceso-externo/registrar-salida/' . $registro['id']) ?>"
                  onsubmit="return confirm('¿Confirmar salida de <?= e($registro['nombre_completo']) ?>?')" class="space-y-4">
                <input type="hidden" name="csrf_token" value="<?= generateCSRFToken() ?>">

                <div>
                    <label for="observaciones" class="block text-sm font-semibold text-gray-700 mb-1">Observaciones de Salida (Opcional)</label>
                    <textarea id="observaciones" name="observaciones" rows="2"
                              class="w-full rounded-xl border border-gray-200 px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none"
                              placeholder="Agregar observaciones..."></textarea>
                </div>

                <button type="submit"
                        class="inline-flex items-center gap-2 rounded-2xl bg-green-600 hover:bg-green-700 transition text-white font-semibold px-5 py-2.5 shadow-md">
                    <i class="fas fa-sign-out-alt"></i> Registrar Salida
                </button>
            </form>
        </section>
        <?php endif; ?>
    </div>
</div>

<?php require_once APP_PATH . '/views/layouts/footer.php'; ?>
