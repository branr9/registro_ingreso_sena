<?php
/**
 * Vista: Vista Previa de Importación
 */
require_once APP_PATH . '/views/layouts/header.php';
$preview = $_SESSION['import_data']['preview'] ?? [];
$previewData = $preview['preview'] ?? [];
$errors = $preview['errors'] ?? [];
$total = $preview['total'] ?? 0;
$valid = $preview['valid'] ?? 0;
$invalid = count($errors);
?>

<div class="main-content">
    <div class="container" style="max-width: 1400px;">
        
        <div style="margin-bottom: 2rem;">
            <h1 style="font-size: 1.75rem; color: var(--text-color); margin-bottom: 0.5rem;">
                <i class="fas fa-eye"></i> Vista Previa de Importación
            </h1>
            <p style="color: var(--text-muted);">Revisa los datos antes de confirmar la importación</p>
        </div>

        <!-- Resumen de Validación -->
        <div class="dashboard-stats" style="grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); margin-bottom: 2rem;">
            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-file-alt"></i></div>
                <div class="stat-content">
                    <h3><?= $total ?></h3>
                    <p>Total de Registros</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-check-circle"></i></div>
                <div class="stat-content">
                    <h3 style="color: var(--primary-color);"><?= $valid ?></h3>
                    <p>Registros Válidos</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-exclamation-triangle"></i></div>
                <div class="stat-content">
                    <h3 style="color: var(--danger-color);"><?= $invalid ?></h3>
                    <p>Registros con Errores</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-percent"></i></div>
                <div class="stat-content">
                    <h3><?= $total > 0 ? round(($valid / $total) * 100, 1) : 0 ?>%</h3>
                    <p>Tasa de Éxito</p>
                </div>
            </div>
        </div>

        <?php if ($invalid > 0): ?>
        <!-- Errores Encontrados -->
        <div class="dashboard-modules" style="margin-bottom: 2rem; border-left: 4px solid var(--danger-color);">
            <h3 style="color: var(--danger-color); margin-bottom: 1rem;">
                <i class="fas fa-exclamation-circle"></i> Errores Encontrados (<?= $invalid ?>)
            </h3>
            <div style="max-height: 300px; overflow-y: auto; background: var(--light-color); padding: 1rem; border-radius: 0.5rem;">
                <?php foreach (array_slice($errors, 0, 50) as $error): ?>
                <div style="padding: 0.75rem; margin-bottom: 0.5rem; background: white; border-left: 3px solid var(--danger-color); border-radius: 0.25rem;">
                    <strong style="color: var(--danger-color);">Fila <?= $error['line'] ?>:</strong>
                    <ul style="margin: 0.5rem 0 0 1.5rem; padding: 0;">
                        <?php foreach ($error['errors'] as $err): ?>
                        <li style="color: var(--text-color);"><?= e($err) ?></li>
                        <?php endforeach; ?>
                    </ul>
                    <small style="color: var(--text-muted);">
                        Datos: <?= e(json_encode($error['data'], JSON_UNESCAPED_UNICODE)) ?>
                    </small>
                </div>
                <?php endforeach; ?>
                <?php if ($invalid > 50): ?>
                <p style="color: var(--text-muted); text-align: center; margin-top: 1rem;">
                    Mostrando solo los primeros 50 errores de <?= $invalid ?> totales.
                </p>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>

        <!-- Vista Previa de Datos -->
        <div class="dashboard-modules" style="margin-bottom: 2rem;">
            <h3 style="color: var(--primary-color); margin-bottom: 1rem;">
                <i class="fas fa-table"></i> Vista Previa (Primeras 20 filas)
            </h3>
            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse; font-size: 0.875rem;">
                    <thead>
                        <tr style="background: var(--light-color); border-bottom: 2px solid var(--border-color);">
                            <th style="padding: 0.75rem; text-align: left;">Estado</th>
                            <th style="padding: 0.75rem; text-align: left;">Documento</th>
                            <th style="padding: 0.75rem; text-align: left;">Nombre</th>
                            <th style="padding: 0.75rem; text-align: left;">Tipo</th>
                            <th style="padding: 0.75rem; text-align: left;">Empresa</th>
                            <th style="padding: 0.75rem; text-align: left;">Email</th>
                            <th style="padding: 0.75rem; text-align: left;">Usuario</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($previewData as $row): ?>
                        <tr style="border-bottom: 1px solid var(--border-color); <?= $row['_status'] === 'error' ? 'background: rgba(220, 53, 69, 0.05);' : '' ?>">
                            <td style="padding: 0.75rem;">
                                <?php if ($row['_status'] === 'valid'): ?>
                                <i class="fas fa-check-circle" style="color: var(--primary-color);"></i>
                                <?php else: ?>
                                <i class="fas fa-times-circle" style="color: var(--danger-color);"></i>
                                <?php endif; ?>
                            </td>
                            <td style="padding: 0.75rem; font-weight: 500;"><?= e($row['documento'] ?? '') ?></td>
                            <td style="padding: 0.75rem;"><?= e($row['nombre'] ?? '') ?></td>
                            <td style="padding: 0.75rem;"><?= e($row['tipo_persona'] ?? '') ?></td>
                            <td style="padding: 0.75rem; color: var(--text-muted);"><?= e($row['empresa'] ?? '-') ?></td>
                            <td style="padding: 0.75rem; font-size: 0.8rem;"><?= e($row['email'] ?? '-') ?></td>
                            <td style="padding: 0.75rem; font-size: 0.8rem;"><?= e($row['username'] ?? '-') ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php if ($total > 20): ?>
            <p style="color: var(--text-muted); text-align: center; margin-top: 1rem;">
                Mostrando 20 de <?= $total ?> registros totales. Todos serán procesados al confirmar.
            </p>
            <?php endif; ?>
        </div>

        <!-- Botones de Acción -->
        <div class="dashboard-modules">
            <div style="display: flex; gap: 1rem; justify-content: space-between; align-items: center; flex-wrap: wrap;">
                <div>
                    <?php if ($invalid > 0): ?>
                    <p style="color: var(--warning-color); margin: 0;">
                        <i class="fas fa-exclamation-triangle"></i>
                        <strong>Atención:</strong> Hay <?= $invalid ?> registros con errores que serán omitidos.
                    </p>
                    <?php else: ?>
                    <p style="color: var(--primary-color); margin: 0;">
                        <i class="fas fa-check-circle"></i>
                        <strong>Todo listo:</strong> Todos los registros son válidos.
                    </p>
                    <?php endif; ?>
                </div>

                <div style="display: flex; gap: 1rem;">
                    <a href="<?= baseUrl('/usuarios/import') ?>" class="btn" style="background: var(--text-muted); color: white;">
                        <i class="fas fa-times"></i> Cancelar
                    </a>
                    <?php if ($valid > 0): ?>
                    <form method="POST" action="<?= baseUrl('/usuarios/import-confirm') ?>" style="display: inline;">
                        <input type="hidden" name="csrf_token" value="<?= generateCSRFToken() ?>">
                        <button type="submit" class="btn btn-primary" onclick="return confirm('¿Confirmar la importación de <?= $valid ?> registros válidos?')">
                            <i class="fas fa-check"></i> Confirmar Importación (<?= $valid ?> registros)
                        </button>
                    </form>
                    <?php else: ?>
                    <button type="button" class="btn" style="background: var(--text-muted); color: white; cursor: not-allowed;" disabled>
                        <i class="fas fa-ban"></i> No hay registros válidos para importar
                    </button>
                    <?php endif; ?>
                </div>
            </div>
        </div>

    </div>
</div>

<?php require_once APP_PATH . '/views/layouts/footer.php'; ?>
