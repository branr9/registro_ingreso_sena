<?php require_once APP_PATH . '/views/layouts/header.php'; ?>

<div class="page-header">
    <h1><i class="fas fa-plus"></i> Nueva Aula</h1>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="<?= baseUrl('/control-llaves/store') ?>">
            <div class="form-group">
                <label for="nombre">
                    <i class="fas fa-door-open"></i> Nombre del Aula *
                </label>
                <input type="text" 
                       id="nombre" 
                       name="nombre" 
                       class="form-control"
                       placeholder="Ej: Aula 101"
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
                           value="30"
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
                           value="1"
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
                          rows="3"
                          placeholder="Información adicional (opcional)"></textarea>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Guardar
                </button>
                <a href="<?= baseUrl('/control-llaves') ?>" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Cancelar
                </a>
            </div>
        </form>
    </div>
</div>

<?php require_once APP_PATH . '/views/layouts/footer.php'; ?>
