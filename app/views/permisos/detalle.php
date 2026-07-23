<?php require_once APP_PATH . '/views/layouts/header.php'; ?>

<div class="main-content">
    <div class="content-header">
        <h1><i class="fas fa-file-alt"></i> Detalle del Permiso</h1>
        <div class="header-actions">
            <a href="<?= baseUrl('/permisos') ?>" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3><i class="fas fa-info-circle"></i> Información del Permiso #<?= $permiso['id'] ?></h3>
            <?php
            $badgeClass = [
                'ACTIVO' => 'success',
                'USADO' => 'info',
                'VENCIDO' => 'warning',
                'CANCELADO' => 'danger'
            ][$permiso['estado']] ?? 'secondary';
            ?>
            <span class="badge badge-<?= $badgeClass ?> badge-lg">
                <?= e($permiso['estado']) ?>
            </span>
        </div>
        <div class="card-body">
            <div class="detail-grid">
                <div class="detail-section">
                    <h4><i class="fas fa-user"></i> Datos del Aprendiz</h4>
                    <div class="detail-item">
                        <span class="detail-label">Documento:</span>
                        <span class="detail-value"><?= e($permiso['documento_aprendiz']) ?></span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Nombre Completo:</span>
                        <span class="detail-value"><?= e($permiso['nombre_aprendiz']) ?></span>
                    </div>
                </div>

                <div class="detail-section">
                    <h4><i class="fas fa-calendar-alt"></i> Fechas y Horarios</h4>
                    <div class="detail-item">
                        <span class="detail-label">Fecha del Permiso:</span>
                        <span class="detail-value"><?= date('d/m/Y', strtotime($permiso['fecha_permiso'])) ?></span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Hora de Salida:</span>
                        <span class="detail-value"><?= substr($permiso['hora_salida'], 0, 5) ?></span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Hora de Regreso:</span>
                        <span class="detail-value">
                            <?= $permiso['hora_regreso'] ? substr($permiso['hora_regreso'], 0, 5) : 'No especificada' ?>
                        </span>
                    </div>
                </div>

                <div class="detail-section">
                    <h4><i class="fas fa-clipboard"></i> Motivo</h4>
                    <div class="motivo-box">
                        <?= nl2br(e($permiso['motivo'])) ?>
                    </div>
                </div>

                <?php if (!empty($permiso['observaciones'])): ?>
                <div class="detail-section">
                    <h4><i class="fas fa-sticky-note"></i> Observaciones</h4>
                    <div class="observaciones-box">
                        <?= nl2br(e($permiso['observaciones'])) ?>
                    </div>
                </div>
                <?php endif; ?>

                <div class="detail-section">
                    <h4><i class="fas fa-user-tie"></i> Autorización</h4>
                    <div class="detail-item">
                        <span class="detail-label">Instructor:</span>
                        <span class="detail-value"><?= e($permiso['instructor_nombre']) ?></span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Fecha de Creación:</span>
                        <span class="detail-value">
                            <?= date('d/m/Y H:i', strtotime($permiso['created_at'])) ?>
                        </span>
                    </div>
                </div>

                <?php if ($permiso['estado'] === 'USADO'): ?>
                <div class="detail-section">
                    <h4><i class="fas fa-check-circle"></i> Uso del Permiso</h4>
                    <div class="detail-item">
                        <span class="detail-label">Fecha y Hora de Uso:</span>
                        <span class="detail-value">
                            <?= $permiso['fecha_uso'] ? date('d/m/Y H:i', strtotime($permiso['fecha_uso'])) : 'N/A' ?>
                        </span>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<style>
.detail-grid {
    display: grid;
    gap: 30px;
}

.detail-section {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 10px;
    border-left: 4px solid var(--primary-color);
}

.detail-section h4 {
    color: var(--secondary-color);
    margin-bottom: 15px;
    font-size: 1.2rem;
}

.detail-item {
    display: flex;
    justify-content: space-between;
    padding: 10px 0;
    border-bottom: 1px solid #dee2e6;
}

.detail-item:last-child {
    border-bottom: none;
}

.detail-label {
    font-weight: 600;
    color: #666;
}

.detail-value {
    color: #333;
    font-weight: 500;
}

.motivo-box, .observaciones-box {
    background: white;
    padding: 15px;
    border-radius: 8px;
    border: 1px solid #dee2e6;
    line-height: 1.6;
}

.badge-lg {
    font-size: 1rem;
    padding: 8px 15px;
}
</style>

<?php require_once APP_PATH . '/views/layouts/footer.php'; ?>
