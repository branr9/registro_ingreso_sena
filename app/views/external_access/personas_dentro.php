<?php
/**
 * Vista: Personal Externo Actualmente Dentro
 */
require_once APP_PATH . '/views/layouts/header.php';
?>

<div>
    <div class="flex items-center gap-4 flex-wrap mb-2">
        <a href="<?= baseUrl('/acceso-externo') ?>"
           class="inline-flex items-center gap-2 rounded-xl bg-gray-100 hover:bg-gray-200 transition text-gray-700 font-semibold px-4 py-2 text-sm">
            <i class="fas fa-arrow-left"></i> Volver
        </a>
        <h1 class="text-2xl font-extrabold text-primary-700 flex items-center gap-2">
            <i class="fas fa-door-open"></i> Personal Externo Dentro
        </h1>
        <span class="bg-green-100 text-green-700 px-3 py-1 rounded-xl text-sm font-bold">
            <?= count($personas) ?> Personas
        </span>
    </div>
    <p class="text-sm text-gray-500 mb-8">Lista de personal externo actualmente dentro de las instalaciones</p>

    <div class="bg-white/85 backdrop-blur-md border border-primary-100 rounded-3xl shadow-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead>
                    <tr class="bg-primary-50 text-primary-800 text-xs uppercase">
                        <th class="px-4 py-3 text-left font-bold">Hora Entrada</th>
                        <th class="px-4 py-3 text-left font-bold">Documento</th>
                        <th class="px-4 py-3 text-left font-bold">Nombre</th>
                        <th class="px-4 py-3 text-left font-bold">Empresa</th>
                        <th class="px-4 py-3 text-left font-bold">Motivo</th>
                        <th class="px-4 py-3 text-left font-bold">Persona Visitada</th>
                        <th class="px-4 py-3 text-left font-bold">Tiempo</th>
                        <th class="px-4 py-3 text-center font-bold">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <?php if (empty($personas)): ?>
                    <tr>
                        <td colspan="8" class="px-4 py-16 text-center text-gray-400">
                            <i class="fas fa-check-circle text-5xl text-green-400 mb-4 block"></i>
                            <strong>No hay personal externo dentro en este momento</strong>
                        </td>
                    </tr>
                    <?php else: ?>
                        <?php foreach ($personas as $persona): ?>
                        <tr class="hover:bg-primary-50/50 transition">
                            <td class="px-4 py-3 whitespace-nowrap">
                                <strong class="text-gray-700"><?= date('h:i A', strtotime($persona['fecha_entrada'])) ?></strong><br>
                                <small class="text-gray-400"><?= date('d/m/Y', strtotime($persona['fecha_entrada'])) ?></small>
                            </td>
                            <td class="px-4 py-3">
                                <strong class="text-gray-700"><?= e($persona['documento']) ?></strong><br>
                                <small class="text-gray-400"><?= e($persona['tipo_documento']) ?></small>
                            </td>
                            <td class="px-4 py-3">
                                <strong class="text-gray-700"><?= e($persona['nombre_completo']) ?></strong>
                                <?php if ($persona['telefono']): ?>
                                <br><small class="text-gray-400"><i class="fas fa-phone"></i> <?= e($persona['telefono']) ?></small>
                                <?php endif; ?>
                            </td>
                            <td class="px-4 py-3 text-gray-600"><?= e($persona['empresa'] ?? '-') ?></td>
                            <td class="px-4 py-3 text-gray-500"><small><?= e($persona['motivo_visita']) ?></small></td>
                            <td class="px-4 py-3 text-gray-600"><?= e($persona['persona_visitada'] ?? '-') ?></td>
                            <td class="px-4 py-3">
                                <span class="text-accent-700 font-bold">
                                    <?php
                                    $horas = floor($persona['minutos_transcurridos'] / 60);
                                    $minutos = $persona['minutos_transcurridos'] % 60;
                                    echo "{$horas}h {$minutos}m";
                                    ?>
                                </span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <form method="POST" action="<?= baseUrl('/acceso-externo/registrar-salida/' . $persona['id']) ?>"
                                      class="inline"
                                      onsubmit="return confirm('¿Confirmar salida de <?= e($persona['nombre_completo']) ?>?')">
                                    <input type="hidden" name="csrf_token" value="<?= generateCSRFToken() ?>">
                                    <button type="submit"
                                            class="inline-flex items-center gap-2 rounded-xl bg-green-600 hover:bg-green-700 transition text-white font-semibold px-3 py-1.5 text-xs">
                                        <i class="fas fa-sign-out-alt"></i> Registrar Salida
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once APP_PATH . '/views/layouts/footer.php'; ?>
