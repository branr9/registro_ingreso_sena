<?php require_once APP_PATH . '/views/layouts/header.php'; ?>

<div class="page-header">
    <h1><i class="fas fa-history"></i> Historial de Préstamos</h1>
</div>

<div class="card">
    <div class="card-header">
        <h3>Últimos 100 Movimientos</h3>
        <div class="card-actions">
            <a href="<?= baseUrl('/control-llaves') ?>" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
        </div>
    </div>
    <div class="card-body">
        <?php if (empty($prestamos)): ?>
            <div class="empty-state">
                <i class="fas fa-history"></i>
                <p>No hay movimientos registrados</p>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Fecha Préstamo</th>
                            <th>Aula</th>
                            <th>Receptor de la Llave</th>
                            <th>Registrado Por</th>
                            <th>Fecha Devolución</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($prestamos as $prestamo): ?>
                        <tr>
                            <td><?= date('d/m/Y H:i', strtotime($prestamo['fecha_prestamo'])) ?></td>
                            <td><strong><?= e($prestamo['aula_nombre']) ?></strong></td>
                            <td>
                                <strong><?= e($prestamo['nombre_receptor']) ?></strong>
                                <br>
                                <small>Doc: <?= e($prestamo['documento_receptor']) ?></small>
                                <?php if (!empty($prestamo['departamento'])): ?>
                                <br>
                                <small>Dpto: <?= e($prestamo['departamento']) ?></small>
                                <?php endif; ?>
                                <?php if (!empty($prestamo['telefono'])): ?>
                                <br>
                                <small><i class="fas fa-phone"></i> <?= e($prestamo['telefono']) ?></small>
                                <?php endif; ?>
                                <?php if (!empty($prestamo['observaciones_prestamo'])): ?>
                                <br>
                                <small class="text-muted"><i class="fas fa-comment"></i> <?= e($prestamo['observaciones_prestamo']) ?></small>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?= e($prestamo['nombres'] . ' ' . $prestamo['apellidos']) ?>
                                <br>
                                <small class="text-muted"><?= e($prestamo['documento']) ?></small>
                                <?php if ($prestamo['tipo_persona']): ?>
                                <br>
                                <span class="badge badge-secondary"><?= e($prestamo['tipo_persona']) ?></span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($prestamo['fecha_devolucion']): ?>
                                    <?= date('d/m/Y H:i', strtotime($prestamo['fecha_devolucion'])) ?>
                                <?php else: ?>
                                    <span class="text-muted">Pendiente</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php
                                $badgeClass = 'secondary';
                                $badgeIcon = 'clock';
                                if ($prestamo['estado'] === 'PRESTADO') {
                                    $badgeClass = 'warning';
                                    $badgeIcon = 'hand-holding';
                                } elseif ($prestamo['estado'] === 'DEVUELTO') {
                                    $badgeClass = 'success';
                                    $badgeIcon = 'check';
                                }
                                ?>
                                <span class="badge badge-<?= $badgeClass ?>">
                                    <i class="fas fa-<?= $badgeIcon ?>"></i>
                                    <?= e($prestamo['estado']) ?>
                                </span>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<style>
.badge-warning {
    background-color: #ffc107;
    color: #000;
}
</style>

<?php require_once APP_PATH . '/views/layouts/footer.php'; ?>
