<?php require_once APP_PATH . '/views/layouts/header.php'; ?>

<div class="page-header">
    <h1><i class="fas fa-undo"></i> Devolver Llave</h1>
    <p>Registre la devolución de la llave</p>
</div>

<div class="card">
    <div class="card-body">
        <div class="alert alert-info">
            <i class="fas fa-info-circle"></i>
            <strong>Tiene una llave prestada:</strong>
            <br>
            Aula: <strong><?= e($prestamoActivo['aula_nombre']) ?></strong>
            <br>
            Entregada a: <strong><?= e($prestamoActivo['nombre_receptor']) ?></strong>
            <br>
            Fecha préstamo: <?= date('d/m/Y H:i', strtotime($prestamoActivo['fecha_prestamo'])) ?>
            <?php if (!empty($prestamoActivo['observaciones_prestamo'])): ?>
            <br>
            Observaciones del préstamo: <em><?= e($prestamoActivo['observaciones_prestamo']) ?></em>
            <?php endif; ?>
        </div>

        <form method="POST" action="<?= baseUrl('/control-llaves/procesar-devolucion') ?>">
            <input type="hidden" name="prestamo_id" value="<?= $prestamoActivo['id'] ?>">
            
            <div class="form-group">
                <label for="observaciones">
                    <i class="fas fa-comment"></i> Observaciones de Devolución (Opcional)
                </label>
                <textarea id="observaciones" 
                          name="observaciones" 
                          class="form-control"
                          rows="3"
                          placeholder="Estado del aula, incidencias, etc."></textarea>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-check"></i> Devolver Llave
                </button>
                <a href="<?= baseUrl('/control-llaves') ?>" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Cancelar
                </a>
            </div>
        </form>
    </div>
</div>

<style>
.alert {
    padding: 1rem;
    border-radius: 0.5rem;
    margin-bottom: 1.5rem;
}

.alert-info {
    background-color: #d1ecf1;
    border: 1px solid #bee5eb;
    color: #0c5460;
}

.alert-warning {
    background-color: #fff3cd;
    border: 1px solid #ffeeba;
    color: #856404;
}
</style>

<?php require_once APP_PATH . '/views/layouts/footer.php'; ?>
