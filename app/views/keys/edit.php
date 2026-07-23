<?php require_once APP_PATH . '/views/layouts/header.php'; ?>

<div class="page-header">
    <h1><i class="fas fa-edit"></i> Editar Aula</h1>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="<?= baseUrl('/control-llaves/update/' . $aula['id']) ?>">
            <div class="form-group">
                <label for="nombre">
                    <i class="fas fa-door-open"></i> Nombre del Aula *
                </label>
                <input type="text" 
                       id="nombre" 
                       name="nombre" 
                       class="form-control"
                       value="<?= e($aula['nombre']) ?>"
                       required>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="capacidad">
                        <i class="fas fa-users"></i> Capacidad (personas) *
                    </label>
                    <input type="number" 
                           id="capacidad" 
                           name="capacidad" 
                           class="form-control"
                           min="1"
                           value="<?= e($aula['capacidad']) ?>"
                           required>
                </div>

                <div class="form-group">
                    <label for="cantidad_llaves">
                        <i class="fas fa-key"></i> Cantidad de Llaves *
                    </label>
                    <input type="number" 
                           id="cantidad_llaves" 
                           name="cantidad_llaves" 
                           class="form-control"
                           min="1"
                           value="<?= e($aula['cantidad_llaves']) ?>"
                           required>
                </div>
            </div>

            <div class="form-group">
                <label for="observaciones">
                    <i class="fas fa-comment"></i> Observaciones
                </label>
                <textarea id="observaciones" 
                          name="observaciones" 
                          class="form-control"
                          rows="3"><?= e($aula['observaciones']) ?></textarea>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Actualizar
                </button>
                <a href="<?= baseUrl('/control-llaves') ?>" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Cancelar
                </a>
            </div>
        </form>
    </div>
</div>

<?php require_once APP_PATH . '/views/layouts/footer.php'; ?>
