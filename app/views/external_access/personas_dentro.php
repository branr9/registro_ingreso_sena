<?php
/**
 * Vista: Personal Externo Actualmente Dentro
 */
require_once APP_PATH . '/views/layouts/header.php';
?>

<div class="main-content">
    <div class="container">
        
        <div style="margin-bottom: 2rem;">
            <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1rem;">
                <a href="<?= baseUrl('/acceso-externo') ?>" class="btn" style="background: var(--text-muted); color: white; padding: 0.5rem 1rem;">
                    <i class="fas fa-arrow-left"></i> Volver
                </a>
                <h1 style="font-size: 1.75rem; color: var(--text-color); margin: 0;">
                    <i class="fas fa-door-open"></i> Personal Externo Dentro
                </h1>
                <span class="badge badge-active" style="font-size: 1.2rem; padding: 0.5rem 1rem;">
                    <?= count($personas) ?> Personas
                </span>
            </div>
            <p style="color: var(--text-muted);">Lista de personal externo actualmente dentro de las instalaciones</p>
        </div>

        <div class="dashboard-modules">
            <div style="overflow-x: auto;">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Hora Entrada</th>
                            <th>Documento</th>
                            <th>Nombre</th>
                            <th>Empresa</th>
                            <th>Motivo</th>
                            <th>Persona Visitada</th>
                            <th>Tiempo</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($personas)): ?>
                        <tr>
                            <td colspan="8" style="text-align: center; padding: 2rem; color: var(--text-muted);">
                                <i class="fas fa-check-circle" style="font-size: 3rem; margin-bottom: 1rem; display: block; color: var(--success-color);"></i>
                                <strong>No hay personal externo dentro en este momento</strong>
                            </td>
                        </tr>
                        <?php else: ?>
                            <?php foreach ($personas as $persona): ?>
                            <tr>
                                <td>
                                    <strong><?= date('h:i A', strtotime($persona['fecha_entrada'])) ?></strong><br>
                                    <small style="color: var(--text-muted);"><?= date('d/m/Y', strtotime($persona['fecha_entrada'])) ?></small>
                                </td>
                                <td>
                                    <strong><?= e($persona['documento']) ?></strong><br>
                                    <small><?= e($persona['tipo_documento']) ?></small>
                                </td>
                                <td>
                                    <strong><?= e($persona['nombre_completo']) ?></strong>
                                    <?php if ($persona['telefono']): ?>
                                    <br><small><i class="fas fa-phone"></i> <?= e($persona['telefono']) ?></small>
                                    <?php endif; ?>
                                </td>
                                <td><?= e($persona['empresa'] ?? '-') ?></td>
                                <td><small><?= e($persona['motivo_visita']) ?></small></td>
                                <td><?= e($persona['persona_visitada'] ?? '-') ?></td>
                                <td>
                                    <span style="color: var(--info-color); font-weight: bold;">
                                        <?php
                                        $horas = floor($persona['minutos_transcurridos'] / 60);
                                        $minutos = $persona['minutos_transcurridos'] % 60;
                                        echo "{$horas}h {$minutos}m";
                                        ?>
                                    </span>
                                </td>
                                <td>
                                    <form method="POST" action="<?= baseUrl('/acceso-externo/registrar-salida/' . $persona['id']) ?>" 
                                          style="display: inline;" 
                                          onsubmit="return confirm('¿Confirmar salida de <?= e($persona['nombre_completo']) ?>?')">
                                        <input type="hidden" name="csrf_token" value="<?= generateCSRFToken() ?>">
                                        <button type="submit" class="btn btn-primary" style="background: var(--success-color);">
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
</div>

<?php require_once APP_PATH . '/views/layouts/footer.php'; ?>
