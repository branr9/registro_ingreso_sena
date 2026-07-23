<?php require_once APP_PATH . '/views/layouts/header.php'; ?>

<div class="main-content">
    <div class="content-header">
        <h1><i class="fas fa-file-signature"></i></h1>
        <div class="header-actions">
            <?php if (Auth::hasRole('admin') || Auth::hasRole('instructor')): ?>
            <a href="<?= baseUrl('/permisos/create') ?>" class="btn btn-primary">
                <i class="fas fa-plus"></i> Crear Permiso
            </a>
            <?php endif; ?>
        </div>
    </div>

    <?php 
    $flash = getFlashMessage();
    if ($flash): 
    ?>
        <div class="alert alert-<?= e($flash['type']) ?>">
            <?= e($flash['message']) ?>
        </div>
    <?php endif; ?>

    <!-- Estadísticas -->
    <div class="stats-cards">
        <div class="stat-card">
            <div class="stat-icon bg-primary">
                <i class="fas fa-clipboard-list"></i>
            </div>
            <div class="stat-details">
                <h3><?= $stats['total'] ?></h3>
                <p>Total Permisos</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon bg-success">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="stat-details">
                <h3><?= $stats['activos'] ?></h3>
                <p>Activos</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon bg-info">
                <i class="fas fa-door-open"></i>
            </div>
            <div class="stat-details">
                <h3><?= $stats['usados'] ?></h3>
                <p>Usados</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon bg-warning">
                <i class="fas fa-clock"></i>
            </div>
            <div class="stat-details">
                <h3><?= $stats['vencidos'] ?></h3>
                <p>Vencidos</p>
            </div>
        </div>
    </div>

    <!-- Filtros -->
    <div class="card">
        <div class="card-header">
            <h3><i class="fas fa-filter"></i> Filtros</h3>
        </div>
        <div class="card-body">
            <form method="GET" action="<?= baseUrl('/permisos') ?>" class="filters-form">
                <div class="form-row">
                    <div class="form-group">
                        <label>Fecha</label>
                        <input type="date" name="fecha" class="form-control" 
                               value="<?= e($filters['fecha']) ?>">
                    </div>

                    <div class="form-group">
                        <label>Documento</label>
                        <input type="text" name="documento" class="form-control" 
                               placeholder="Buscar por documento"
                               value="<?= e($filters['documento']) ?>">
                    </div>

                    <div class="form-group">
                        <label>Estado</label>
                        <select name="estado" class="form-control">
                            <option value="">Todos</option>
                            <option value="ACTIVO" <?= $filters['estado'] === 'ACTIVO' ? 'selected' : '' ?>>Activo</option>
                            <option value="USADO" <?= $filters['estado'] === 'USADO' ? 'selected' : '' ?>>Usado</option>
                            <option value="VENCIDO" <?= $filters['estado'] === 'VENCIDO' ? 'selected' : '' ?>>Vencido</option>
                            <option value="CANCELADO" <?= $filters['estado'] === 'CANCELADO' ? 'selected' : '' ?>>Cancelado</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>&nbsp;</label>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i> Buscar
                        </button>
                        <a href="<?= baseUrl('/permisos') ?>" class="btn btn-secondary">
                            <i class="fas fa-redo"></i> Limpiar
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabla de permisos -->
    <div class="card">
        <div class="card-header">
            <h3><i class="fas fa-list"></i> Listado de Permisos</h3>
        </div>
        <div class="card-body">
            <?php if (empty($permisos)): ?>
                <div class="empty-state">
                    <i class="fas fa-inbox"></i>
                    <p>No se encontraron permisos</p>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>Documento</th>
                                <th>Nombre Aprendiz</th>
                                <th>Hora Salida</th>
                                <th>Motivo</th>
                                <th>Instructor</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($permisos as $permiso): ?>
                            <tr>
                                <td><?= date('d/m/Y', strtotime($permiso['fecha_permiso'])) ?></td>
                                <td><?= e($permiso['documento_aprendiz']) ?></td>
                                <td><?= e($permiso['nombre_aprendiz']) ?></td>
                                <td><?= substr($permiso['hora_salida'], 0, 5) ?></td>
                                <td><?= e(substr($permiso['motivo'], 0, 50)) ?>...</td>
                                <td><?= e($permiso['instructor_nombre']) ?></td>
                                <td>
                                    <?php
                                    $badgeClass = [
                                        'ACTIVO' => 'success',
                                        'USADO' => 'info',
                                        'VENCIDO' => 'warning',
                                        'CANCELADO' => 'danger'
                                    ][$permiso['estado']] ?? 'secondary';
                                    ?>
                                    <span class="badge badge-<?= $badgeClass ?>">
                                        <?= e($permiso['estado']) ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="<?= baseUrl('/permisos/ver/' . $permiso['id']) ?>" 
                                           class="btn btn-sm btn-info" title="Ver detalle">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        
                                        <?php if ($permiso['estado'] === 'ACTIVO' && 
                                                  (Auth::hasRole('admin') || $permiso['instructor_id'] == $_SESSION['user_id'])): ?>
                                        <form method="POST" 
                                              action="<?= baseUrl('/permisos/cancelar/' . $permiso['id']) ?>" 
                                              style="display: inline;"
                                              onsubmit="return confirm('¿Está seguro de cancelar este permiso?')">
                                            <input type="hidden" name="csrf_token" value="<?= generateCSRFToken() ?>">
                                            <button type="submit" class="btn btn-sm btn-danger" title="Cancelar">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </form>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once APP_PATH . '/views/layouts/footer.php'; ?>
