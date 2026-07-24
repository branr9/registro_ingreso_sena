<?php require_once APP_PATH . '/views/layouts/header.php'; ?>

<div class="page-header">
</div>

<!-- Estadísticas -->
<div class="dashboard-stats">
    <div class="stat-card">
        <div class="stat-icon">
            <i class="fas fa-door-open"></i>
        </div>
        <div class="stat-content">
            <h3><?= $stats['total_aulas'] ?? 0 ?></h3>
            <p>Aulas Activas</p>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon">
            <i class="fas fa-key"></i>
        </div>
        <div class="stat-content">
            <h3><?= $stats['total_llaves'] ?? 0 ?></h3>
            <p>Total Llaves</p>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon" style="background: #dc3545;">
            <i class="fas fa-hand-holding"></i>
        </div>
        <div class="stat-content">
            <h3><?= $stats['llaves_prestadas'] ?? 0 ?></h3>
            <p>Llaves Prestadas</p>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon" style="background: #28a745;">
            <i class="fas fa-clock"></i>
        </div>
        <div class="stat-content">
            <h3><?= $stats['prestamos_hoy'] ?? 0 ?></h3>
            <p>Préstamos Hoy</p>
        </div>
    </div>
</div>

<!-- Acciones -->
<div class="card">
    <div class="card-header">
        <h3><i class="fas fa-door-open"></i> Gestión de Aulas</h3>
        <div class="card-actions">
            <?php if (Auth::hasRole('admin')): ?>
            <a href="<?= baseUrl('/control-llaves/create') ?>" class="btn btn-primary">
                <i class="fas fa-plus"></i> Nueva Aula
            </a>
            <?php endif; ?>
            <a href="<?= baseUrl('/control-llaves/prestamo') ?>" class="btn btn-success">
                <i class="fas fa-hand-holding"></i> Tomar/Devolver Llave
            </a>
            <a href="<?= baseUrl('/control-llaves/historial') ?>" class="btn btn-secondary">
                <i class="fas fa-history"></i> Historial
            </a>
        </div>
    </div>
    <div class="card-body">
        <?php if (empty($aulas)): ?>
            <div class="empty-state">
                <i class="fas fa-door-open"></i>
                <p>No hay aulas registradas</p>
                <?php if (Auth::hasRole('admin')): ?>
                <a href="<?= baseUrl('/control-llaves/create') ?>" class="btn btn-primary">
                    Crear Primera Aula
                </a>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Aula</th>
                            <th>Capacidad</th>
                            <th>Total Llaves</th>
                            <th>Disponibles</th>
                            <th>Prestadas</th>
                            <th>Estado</th>
                            <?php if (Auth::hasRole('admin')): ?>
                            <th>Acciones</th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($aulas as $aula): ?>
                        <tr>
                            <td>
                                <strong><?= e($aula['nombre']) ?></strong>
                                <?php if ($aula['observaciones']): ?>
                                <br><small class="text-muted"><?= e($aula['observaciones']) ?></small>
                                <?php endif; ?>
                            </td>
                            <td><?= e($aula['capacidad']) ?> personas</td>
                            <td><?= e($aula['cantidad_llaves']) ?></td>
                            <td>
                                <span class="badge" style="background: #28a745; color: white;">
                                    <?= $aula['cantidad_llaves'] - $aula['llaves_prestadas'] ?>
                                </span>
                            </td>
                            <td>
                                <?php if ($aula['llaves_prestadas'] > 0): ?>
                                <span class="badge" style="background: #dc3545; color: white;">
                                    <?= $aula['llaves_prestadas'] ?>
                                </span>
                                <?php else: ?>
                                <span class="text-muted">0</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="badge badge-<?= $aula['estado'] === 'ACTIVO' ? 'success' : 'secondary' ?>">
                                    <?= e($aula['estado']) ?>
                                </span>
                            </td>
                            <?php if (Auth::hasRole('admin')): ?>
                            <td>
                                <div class="btn-group">
                                    <a href="<?= baseUrl('/control-llaves/edit/' . $aula['id']) ?>" 
                                       class="btn btn-sm btn-primary" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form method="POST" 
                                          action="<?= baseUrl('/control-llaves/toggle/' . $aula['id']) ?>" 
                                          style="display: inline;">
                                        <button type="submit" 
                                                class="btn btn-sm btn-warning" 
                                                title="Cambiar Estado">
                                            <i class="fas fa-toggle-on"></i>
                                        </button>
                                    </form>
                                    <form method="POST" 
                                          action="<?= baseUrl('/control-llaves/delete/' . $aula['id']) ?>" 
                                          style="display: inline;"
                                          onsubmit="return confirm('¿Eliminar esta aula?');">
                                        <button type="submit" 
                                                class="btn btn-sm btn-danger" 
                                                title="Eliminar">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                            <?php endif; ?>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<style>
.page-header {
    margin-bottom: 2rem;
}

.btn-group {
    display: flex;
    gap: 0.25rem;
}

.btn-sm {
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
}

.empty-state {
    text-align: center;
    padding: 3rem 1rem;
    color: #666;
}

.empty-state i {
    font-size: 4rem;
    color: #ddd;
    margin-bottom: 1rem;
}

.text-muted {
    color: #6c757d;
}

.badge-success {
    background-color: #28a745;
    color: white;
}

.badge-secondary {
    background-color: #6c757d;
    color: white;
}

/* Botón Tomar/Devolver Llave destacado */
.card-actions .btn-success {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    color: white;
    font-weight: 600;
    padding: 0.65rem 1.25rem;   /* igual que los demás botones */
    font-size: 1rem;             /* igual que los demás botones */
    border: none;
    box-shadow: 0 4px 15px rgba(40, 167, 69, 0.4);
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.card-actions .btn-success::before {
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

.card-actions .btn-success:hover::before {
    width: 300px;
    height: 300px;
}

.card-actions .btn-success:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(40, 167, 69, 0.6);
}

.card-actions .btn-success:active {
    transform: translateY(0);
}

.card-actions .btn-success i {
    font-size: 1.2rem;
    margin-right: 0.5rem;
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0%, 100% {
        transform: scale(1);
    }
    50% {
        transform: scale(1.15);
    }
}

/* Alineación pareja de los botones de acciones */
.card-header {
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    gap: 0.75rem;
}

.card-actions {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    flex-wrap: wrap;
}

.card-actions .btn {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.65rem 1.25rem;
    border-radius: 8px;
    text-decoration: none;
    font-weight: 600;
    white-space: nowrap;
    line-height: 1;
}

/* Refuerzo del botón secundario (Historial) para que se vea como botón real */
.card-actions .btn-secondary {
    background: #6c757d;
    color: #fff;
    border: none;
}

.card-actions .btn-secondary:hover {
    background: #5a6268;
    color: #fff;
}

/* Responsive: en pantallas pequeñas que se apilen bien */
@media (max-width: 576px) {
    .card-actions {
        width: 100%;
        flex-direction: column;
        align-items: stretch;
    }
    .card-actions .btn {
        justify-content: center;
    }
}

/* Tabla más amplia y legible */
.table-responsive {
    width: 100%;
    overflow-x: auto;
}

.table {
    width: 100%;
    border-collapse: collapse;
}

.table thead th {
    text-align: left;
    padding: 0.9rem 1rem;
    background: #f8f9fa;
    font-weight: 700;
    border-bottom: 2px solid #dee2e6;
    white-space: nowrap;
}

.table tbody td {
    padding: 1rem;
    vertical-align: middle;
    border-bottom: 1px solid #eee;
}

.table tbody tr:hover {
    background: #f8f9fa;
}

/* Asegura que la tarjeta contenedora use todo el ancho */
.card {
    width: 100%;
}

.card-body {
    padding: 1.5rem;
}
</style>

<?php require_once APP_PATH . '/views/layouts/footer.php'; ?>
