<?php require_once APP_PATH . '/views/layouts/header.php'; ?>

<div class="page-header">
    <h1><i class="fas fa-key"></i> Préstamo de Llaves</h1>
    <p>Seleccione el aula para tomar o devolver la llave</p>
</div>

<div class="card">
    <div class="card-body">
        <?php if (empty($aulas)): ?>
            <div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle"></i>
                No hay aulas registradas en el sistema.
            </div>
        <?php else: ?>
            <div class="aulas-grid">
                <?php foreach ($aulas as $aula): ?>
                <div class="aula-card <?= $aula['estado'] !== 'ACTIVO' ? 'disabled' : '' ?>">
                    <div class="aula-header">
                        <h3><i class="fas fa-door-open"></i> <?= e($aula['nombre']) ?></h3>
                        <span class="badge badge-<?= $aula['estado'] === 'ACTIVO' ? 'success' : 'secondary' ?>">
                            <?= e($aula['estado']) ?>
                        </span>
                    </div>
                    
                    <div class="aula-info">
                        <div class="info-item">
                            <i class="fas fa-key"></i>
                            <span>Llaves totales: <strong><?= $aula['cantidad_llaves'] ?></strong></span>
                        </div>
                        <div class="info-item">
                            <i class="fas fa-check-circle" style="color: #28a745;"></i>
                            <span>Disponibles: <strong><?= $aula['llaves_disponibles'] ?></strong></span>
                        </div>
                        <div class="info-item">
                            <i class="fas fa-hand-holding" style="color: #dc3545;"></i>
                            <span>Prestadas: <strong><?= $aula['llaves_prestadas'] ?></strong></span>
                        </div>
                    </div>

                    <?php if (!empty($aula['prestamos_activos'])): ?>
                    <div class="prestamos-activos">
                        <h4><i class="fas fa-users"></i> Llaves prestadas:</h4>
                        <?php foreach ($aula['prestamos_activos'] as $prestamo): ?>
                        <div class="prestamo-item">
                            <div class="prestamo-info">
                                <strong><?= e($prestamo['nombre_receptor']) ?></strong>
                                <small>Doc: <?= e($prestamo['documento_receptor']) ?></small>
                                <?php if ($prestamo['departamento']): ?>
                                <small>Dpto: <?= e($prestamo['departamento']) ?></small>
                                <?php endif; ?>
                                <small class="text-muted">Desde: <?= date('d/m/Y H:i', strtotime($prestamo['fecha_prestamo'])) ?></small>
                            </div>
                            <button type="button" 
                                    class="btn btn-sm btn-primary"
                                    onclick="devolverLlave(<?= $prestamo['id'] ?>, '<?= e($aula['nombre']) ?>', '<?= e($prestamo['nombre_receptor']) ?>')">
                                <i class="fas fa-undo"></i> Devolver
                            </button>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>

                    <div class="aula-actions">
                        <?php if ($aula['estado'] === 'ACTIVO' && $aula['llaves_disponibles'] > 0): ?>
                        <button type="button" 
                                class="btn btn-success btn-block"
                                onclick="tomarLlave(<?= $aula['id'] ?>, '<?= e($aula['nombre']) ?>')">
                            <i class="fas fa-hand-holding"></i> Tomar Llave
                        </button>
                        <?php else: ?>
                        <button type="button" class="btn btn-secondary btn-block" disabled>
                            <i class="fas fa-ban"></i> No Disponible
                        </button>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Modal para tomar llave -->
<div id="modalTomarLlave" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2><i class="fas fa-hand-holding"></i> Tomar Llave</h2>
            <button type="button" class="close" onclick="cerrarModal()">&times;</button>
        </div>
        <form method="POST" action="<?= baseUrl('/control-llaves/procesar-prestamo') ?>">
            <div class="modal-body">
                <input type="hidden" id="aula_id" name="aula_id">
                <p>Aula: <strong id="aula_nombre"></strong></p>
                
                <div class="form-group">
                    <label for="nombre_receptor">
                        <i class="fas fa-user"></i> Nombre Completo *
                    </label>
                    <input type="text" 
                           id="nombre_receptor" 
                           name="nombre_receptor" 
                           class="form-control"
                           placeholder="Nombre completo"
                           required
                           maxlength="150">
                </div>

                <div class="form-group">
                    <label for="documento_receptor">
                        <i class="fas fa-id-card"></i> Documento *
                    </label>
                    <input type="text" 
                           id="documento_receptor" 
                           name="documento_receptor" 
                           class="form-control"
                           placeholder="Número de documento"
                           required
                           maxlength="20">
                </div>

                <div class="form-group">
                    <label for="departamento">
                        <i class="fas fa-building"></i> Departamento
                    </label>
                    <input type="text" 
                           id="departamento" 
                           name="departamento" 
                           class="form-control"
                           placeholder="Departamento al que pertenece"
                           maxlength="100">
                </div>

                <div class="form-group">
                    <label for="telefono">
                        <i class="fas fa-phone"></i> Teléfono
                    </label>
                    <input type="tel" 
                           id="telefono" 
                           name="telefono" 
                           class="form-control"
                           placeholder="Número de teléfono"
                           maxlength="20">
                </div>

                <div class="form-group">
                    <label for="observaciones">
                        <i class="fas fa-comment"></i> Observaciones
                    </label>
                    <textarea id="observaciones" 
                              name="observaciones" 
                              class="form-control"
                              rows="3"
                              placeholder="Motivo del préstamo u otra información"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="cerrarModal()">
                    <i class="fas fa-times"></i> Cancelar
                </button>
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-check"></i> Registrar Préstamo
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal para devolver llave -->
<div id="modalDevolverLlave" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2><i class="fas fa-undo"></i> Devolver Llave</h2>
            <button type="button" class="close" onclick="cerrarModalDevolver()">&times;</button>
        </div>
        <form method="POST" action="<?= baseUrl('/control-llaves/procesar-devolucion') ?>">
            <div class="modal-body">
                <input type="hidden" id="prestamo_id_dev" name="prestamo_id">
                <p>Aula: <strong id="aula_nombre_dev"></strong></p>
                <p>Receptor: <strong id="receptor_nombre_dev"></strong></p>
                
                <div class="form-group">
                    <label for="observaciones_dev">
                        <i class="fas fa-comment"></i> Observaciones de Devolución
                    </label>
                    <textarea id="observaciones_dev" 
                              name="observaciones" 
                              class="form-control"
                              rows="3"
                              placeholder="Estado del aula, incidencias, etc."></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="cerrarModalDevolver()">
                    <i class="fas fa-times"></i> Cancelar
                </button>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-check"></i> Confirmar Devolución
                </button>
            </div>
        </form>
    </div>
</div>

<style>
.aulas-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(400px, 1fr));
    gap: 1.5rem;
    margin-top: 1rem;
}

.aula-card {
    border: 1px solid #dee2e6;
    border-radius: 0.5rem;
    padding: 1.5rem;
    background: white;
    transition: box-shadow 0.3s;
}

.aula-card:hover {
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.aula-card.disabled {
    opacity: 0.6;
    background: #f8f9fa;
}

.aula-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
    padding-bottom: 1rem;
    border-bottom: 2px solid #e9ecef;
}

.aula-header h3 {
    margin: 0;
    font-size: 1.25rem;
    color: #333;
}

.aula-info {
    margin-bottom: 1rem;
}

.info-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 0;
    font-size: 0.95rem;
}

.info-item i {
    width: 20px;
    text-align: center;
}

.prestamos-activos {
    background: #f8f9fa;
    padding: 1rem;
    border-radius: 0.375rem;
    margin-bottom: 1rem;
}

.prestamos-activos h4 {
    font-size: 0.95rem;
    margin: 0 0 0.75rem 0;
    color: #495057;
}

.prestamo-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: white;
    padding: 0.75rem;
    border-radius: 0.25rem;
    margin-bottom: 0.5rem;
}

.prestamo-item:last-child {
    margin-bottom: 0;
}

.prestamo-info {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.prestamo-info strong {
    color: #333;
}

.prestamo-info small {
    color: #6c757d;
    font-size: 0.85rem;
}

.aula-actions {
    margin-top: 1rem;
}

.btn-block {
    width: 100%;
    padding: 1rem;
    font-size: 1.1rem;
    font-weight: 600;
    border: none;
    border-radius: 0.5rem;
    cursor: pointer;
    transition: all 0.3s ease;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.btn-success {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    color: white;
    box-shadow: 0 4px 15px rgba(40, 167, 69, 0.4);
    position: relative;
    overflow: hidden;
}

.btn-success::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 0;
    height: 0;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.3);
    transform: translate(-50%, -50%);
    transition: width 0.6s, height 0.6s;
}

.btn-success:hover::before {
    width: 300px;
    height: 300px;
}

.btn-success:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(40, 167, 69, 0.6);
}

.btn-success:active {
    transform: translateY(0);
    box-shadow: 0 2px 10px rgba(40, 167, 69, 0.4);
}

.btn-success i {
    margin-right: 0.5rem;
    font-size: 1.2rem;
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0%, 100% {
        transform: scale(1);
    }
    50% {
        transform: scale(1.1);
    }
}

/* Modal styles */
.modal {
    display: none;
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0,0,0,0.5);
    animation: fadeIn 0.3s;
}

.modal.show {
    display: flex;
    align-items: center;
    justify-content: center;
}

.modal-content {
    background-color: white;
    border-radius: 0.5rem;
    width: 90%;
    max-width: 600px;
    max-height: 90vh;
    overflow-y: auto;
    animation: slideIn 0.3s;
}

.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1.5rem;
    border-bottom: 1px solid #dee2e6;
}

.modal-header h2 {
    margin: 0;
    font-size: 1.5rem;
}

.close {
    background: none;
    border: none;
    font-size: 2rem;
    cursor: pointer;
    color: #6c757d;
    line-height: 1;
}

.close:hover {
    color: #000;
}

.modal-body {
    padding: 1.5rem;
}

.modal-footer {
    display: flex;
    justify-content: flex-end;
    gap: 0.5rem;
    padding: 1.5rem;
    border-top: 1px solid #dee2e6;
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

@keyframes slideIn {
    from {
        transform: translateY(-50px);
        opacity: 0;
    }
    to {
        transform: translateY(0);
        opacity: 1;
    }
}

.alert {
    padding: 1rem;
    border-radius: 0.5rem;
    margin-bottom: 1rem;
}

.alert-warning {
    background-color: #fff3cd;
    border: 1px solid #ffeeba;
    color: #856404;
}

@media (max-width: 768px) {
    .aulas-grid {
        grid-template-columns: 1fr;
    }
    
    .modal-content {
        width: 95%;
        margin: 1rem;
    }
}
</style>

<script>
function tomarLlave(aulaId, aulaNombre) {
    document.getElementById('aula_id').value = aulaId;
    document.getElementById('aula_nombre').textContent = aulaNombre;
    document.getElementById('modalTomarLlave').classList.add('show');
    document.getElementById('nombre_receptor').focus();
}

function devolverLlave(prestamoId, aulaNombre, receptorNombre) {
    document.getElementById('prestamo_id_dev').value = prestamoId;
    document.getElementById('aula_nombre_dev').textContent = aulaNombre;
    document.getElementById('receptor_nombre_dev').textContent = receptorNombre;
    document.getElementById('modalDevolverLlave').classList.add('show');
}

function cerrarModal() {
    document.getElementById('modalTomarLlave').classList.remove('show');
    document.getElementById('nombre_receptor').value = '';
    document.getElementById('documento_receptor').value = '';
    document.getElementById('departamento').value = '';
    document.getElementById('telefono').value = '';
    document.getElementById('observaciones').value = '';
}

function cerrarModalDevolver() {
    document.getElementById('modalDevolverLlave').classList.remove('show');
    document.getElementById('observaciones_dev').value = '';
}

// Cerrar modal al hacer clic fuera
window.onclick = function(event) {
    const modalTomar = document.getElementById('modalTomarLlave');
    const modalDevolver = document.getElementById('modalDevolverLlave');
    if (event.target === modalTomar) {
        cerrarModal();
    }
    if (event.target === modalDevolver) {
        cerrarModalDevolver();
    }
}

// Cerrar con tecla Escape
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        cerrarModal();
        cerrarModalDevolver();
    }
});
</script>

<?php require_once APP_PATH . '/views/layouts/footer.php'; ?>
