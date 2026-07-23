<?php
/**
 * Vista: Listado de Usuarios
 */
require_once APP_PATH . '/views/layouts/header.php';
?>

<div class="main-content">
    <div class="container" style="max-width: 1400px;">
        
        <!-- Header con estadísticas -->
        <div class="page-header" style="margin-bottom: 2rem;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
                <h1 style="font-size: 1.75rem; color: var(--text-color); margin: 0;">
                    <i class="fas fa-users"></i> 
                </h1>
                <?php if (Auth::hasRole('admin')): ?>
                <div style="display: flex; gap: 1rem;">
                    <a href="<?= baseUrl('/usuarios/import') ?>" class="btn" style="background: var(--info-color); color: white;">
                        <i class="fas fa-file-upload"></i> Importar CSV
                    </a>
                    <a href="<?= baseUrl('/usuarios/create') ?>" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Crear Usuario
                    </a>
                </div>
                <?php endif; ?>
            </div>

            <!-- Estadísticas rápidas -->
            <div class="dashboard-stats" style="grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
                <div class="stat-card" style="padding: 1rem;">
                    <div class="stat-icon" style="width: 50px; height: 50px; font-size: 1.5rem;">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stat-content">
                        <h3 style="font-size: 1.5rem; margin: 0;"><?= $stats['total'] ?></h3>
                        <p style="font-size: 0.875rem; margin: 0;">Total Usuarios</p>
                    </div>
                </div>
                <div class="stat-card" style="padding: 1rem;">
                    <div class="stat-icon" style="width: 50px; height: 50px; font-size: 1.5rem;">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="stat-content">
                        <h3 style="font-size: 1.5rem; margin: 0; color: var(--primary-color);"><?= $stats['activos'] ?></h3>
                        <p style="font-size: 0.875rem; margin: 0;">Activos</p>
                    </div>
                </div>
                <div class="stat-card" style="padding: 1rem;">
                    <div class="stat-icon" style="width: 50px; height: 50px; font-size: 1.5rem;">
                        <i class="fas fa-times-circle"></i>
                    </div>
                    <div class="stat-content">
                        <h3 style="font-size: 1.5rem; margin: 0; color: var(--text-muted);"><?= $stats['inactivos'] ?></h3>
                        <p style="font-size: 0.875rem; margin: 0;">Inactivos</p>
                    </div>
                </div>
                <?php if (!empty($stats['por_tipo']['aprendiz'])): ?>
                <div class="stat-card" style="padding: 1rem;">
                    <div class="stat-icon" style="width: 50px; height: 50px; font-size: 1.5rem;">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                    <div class="stat-content">
                        <h3 style="font-size: 1.5rem; margin: 0;"><?= $stats['por_tipo']['aprendiz'] ?? 0 ?></h3>
                        <p style="font-size: 0.875rem; margin: 0;">Aprendices</p>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Filtros de búsqueda -->
        <div class="dashboard-modules" style="margin-bottom: 2rem;">
            <form method="GET" action="<?= baseUrl('/usuarios') ?>" style="display: flex; gap: 1rem; flex-wrap: wrap; align-items: flex-end;">
                <div style="flex: 1; min-width: 250px;">
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Buscar</label>
                    <input type="text" name="search" value="<?= e($_GET['search'] ?? '') ?>" 
                           placeholder="Documento, nombre, email..." class="form-control">
                </div>
                <div style="min-width: 150px;">
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Tipo</label>
                    <select name="tipo_persona" class="form-control">
                        <option value="">Todos</option>
                        <option value="aprendiz" <?= ($_GET['tipo_persona'] ?? '') === 'aprendiz' ? 'selected' : '' ?>>Aprendiz</option>
                        <option value="instructor" <?= ($_GET['tipo_persona'] ?? '') === 'instructor' ? 'selected' : '' ?>>Instructor</option>
                        <option value="admin" <?= ($_GET['tipo_persona'] ?? '') === 'admin' ? 'selected' : '' ?>>Admin</option>
                        <option value="vigilante" <?= ($_GET['tipo_persona'] ?? '') === 'vigilante' ? 'selected' : '' ?>>Vigilante</option>
                        <option value="contratista" <?= ($_GET['tipo_persona'] ?? '') === 'contratista' ? 'selected' : '' ?>>Contratista</option>
                        <option value="visitante" <?= ($_GET['tipo_persona'] ?? '') === 'visitante' ? 'selected' : '' ?>>Visitante</option>
                    </select>
                </div>
                <div style="min-width: 150px;">
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Estado</label>
                    <select name="estado" class="form-control">
                        <option value="">Todos</option>
                        <option value="ACTIVO" <?= ($_GET['estado'] ?? '') === 'ACTIVO' ? 'selected' : '' ?>>Activo</option>
                        <option value="INACTIVO" <?= ($_GET['estado'] ?? '') === 'INACTIVO' ? 'selected' : '' ?>>Inactivo</option>
                    </select>
                </div>
                
                <!-- BOTONES DE BÚSQUEDA IGUALADOS -->
                <div style="display: flex; gap: 0.5rem; align-items: center;">
                    <button type="submit" class="btn btn-primary" style="width: 120px; height: 38px; display: inline-flex; justify-content: center; align-items: center; gap: 0.5rem; border: none; cursor: pointer;">
                        <i class="fas fa-search"></i> Buscar
                    </button>
                    <a href="<?= baseUrl('/usuarios') ?>" class="btn" style="width: 120px; height: 38px; display: inline-flex; justify-content: center; align-items: center; gap: 0.5rem; background: var(--text-muted); color: white; text-decoration: none;">
                        <i class="fas fa-redo"></i> Limpiar
                    </a>
                </div>
            </form>
        </div>

        <!-- Tabla de usuarios -->
        <div class="dashboard-modules">
            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="background: var(--light-color); border-bottom: 2px solid var(--border-color);">
                            <!-- TODOS LOS ENCABEZADOS CENTRADOS -->
                            <th style="padding: 0.75rem; text-align: center; font-weight: 600; white-space: nowrap;">Documento</th>
                            <th style="padding: 0.75rem; text-align: center; font-weight: 600; white-space: nowrap;">Nombre</th>
                            <th style="padding: 0.75rem; text-align: center; font-weight: 600; white-space: nowrap;">Empresa</th>
                            <th style="padding: 0.75rem; text-align: center; font-weight: 600; white-space: nowrap;">Email/Usuario</th>
                            <!-- ENCABEZADO ROL CENTRADO -->
                            <th style="padding: 0.75rem; text-align: center; font-weight: 600; white-space: nowrap;">Rol</th>
                            <th style="padding: 0.75rem; text-align: center; font-weight: 600; white-space: nowrap;">Estado</th>
                            <?php if (Auth::hasRole('admin')): ?>
                            <!-- ENCABEZADO ACCIONES CENTRADO -->
                            <th style="padding: 0.75rem; text-align: center; font-weight: 600; white-space: nowrap;">Acciones</th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($usuarios)): ?>
                        <tr>
                            <!-- Ajustado a 7 columnas por haber quitado el Tipo -->
                            <td colspan="7" style="padding: 2rem; text-align: center; color: var(--text-muted);">
                                <i class="fas fa-inbox" style="font-size: 3rem; margin-bottom: 1rem; display: block;"></i>
                                No se encontraron usuarios
                            </td>
                        </tr>
                        <?php else: ?>
                        <?php foreach ($usuarios as $usuario): ?>
                        <tr style="border-bottom: 1px solid var(--border-color);">
                            
                            <!-- TODAS LAS CELDAS CENTRADAS VERTICAL Y HORIZONTALMENTE -->
                            <td style="padding: 0.75rem; font-weight: 500; text-align: center; vertical-align: middle;"><?= e($usuario['documento']) ?></td>
                            
                            <td style="padding: 0.75rem; text-align: center; vertical-align: middle;">
                                <?= e(trim($usuario['nombres'] . ' ' . ($usuario['apellidos'] ?? ''))) ?>
                            </td>
                            
                            <td style="padding: 0.75rem; color: var(--text-muted); text-align: center; vertical-align: middle;"><?= e($usuario['empresa'] ?? '-') ?></td>
                            
                            <td style="padding: 0.75rem; font-size: 0.875rem; text-align: center; vertical-align: middle;">
                                <?= $usuario['email'] ? e($usuario['email']) : '' ?>
                                <?= $usuario['username'] ? '<br><small style="color: var(--text-muted);">@' . e($usuario['username']) . '</small>' : '' ?>
                                <?= !$usuario['email'] && !$usuario['username'] ? '<span style="color: var(--text-muted);">-</span>' : '' ?>
                            </td>
                            
                            <!-- CELDA ROL: CENTRADA Y ADAPTABLE -->
                            <td style="padding: 0.75rem; text-align: center; vertical-align: middle;">
                                <?php
                                $badgeColors = [
                                    'admin' => 'var(--primary-color)',
                                    'instructor' => 'var(--primary-dark)',
                                    'vigilante' => 'var(--secondary-color)',
                                    'persona' => 'var(--text-muted)'
                                ];
                                $rol = $usuario['rol'] ?? 'persona';
                                $bgColor = $badgeColors[$rol] ?? 'var(--text-muted)';
                                ?>
                                <span class="badge" style="background: <?= $bgColor ?>; color: white; padding: 0.35rem 0.75rem; border-radius: 0.25rem; font-size: 0.75rem; white-space: nowrap; display: inline-block;">
                                    <?= e($usuario['rol_nombre'] ?? ucfirst($rol)) ?>
                                </span>
                            </td>
                            
                            <td style="padding: 0.75rem; text-align: center; vertical-align: middle;">
                                <?php if (strtoupper($usuario['estado']) === 'ACTIVO'): ?>
                                <span style="color: var(--primary-color); font-weight: 600; white-space: nowrap;">
                                    <i class="fas fa-check-circle"></i> Activo
                                </span>
                                <?php else: ?>
                                <span style="color: var(--text-muted); font-weight: 600; white-space: nowrap;">
                                    <i class="fas fa-times-circle"></i> Inactivo
                                </span>
                                <?php endif; ?>
                            </td>
                            
                            <?php if (Auth::hasRole('admin')): ?>
                            <!-- CELDA ACCIONES: CENTRADAS, CUADRADAS Y DEL MISMO TAMAÑO -->
                            <td style="padding: 0.75rem; text-align: center; vertical-align: middle;">
                                <div style="display: flex; gap: 0.5rem; justify-content: center; align-items: center;">
                                    
                                    <!-- Botón Editar -->
                                    <a href="<?= baseUrl('/usuarios/edit/' . $usuario['id']) ?>" 
                                       class="btn" style="width: 36px; height: 36px; display: inline-flex; justify-content: center; align-items: center; padding: 0; background: var(--info-color); color: white; font-size: 0.875rem; border-radius: 0.25rem; text-decoration: none;" 
                                       title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    
                                    <!-- Botón Estado -->
                                    <form method="POST" action="<?= baseUrl('/usuarios/toggle/' . $usuario['id']) ?>" style="display: inline-block; margin: 0;">
                                        <input type="hidden" name="csrf_token" value="<?= generateCSRFToken() ?>">
                                        <button type="submit" class="btn" 
                                                style="width: 36px; height: 36px; display: inline-flex; justify-content: center; align-items: center; padding: 0; background: var(--warning-color); color: white; font-size: 0.875rem; border: none; cursor: pointer; border-radius: 0.25rem;" 
                                                title="<?= strtoupper($usuario['estado']) === 'ACTIVO' ? 'Desactivar' : 'Activar' ?>"
                                                onclick="return confirm('¿Cambiar estado del usuario?')">
                                            <i class="fas fa-<?= strtoupper($usuario['estado']) === 'ACTIVO' ? 'ban' : 'check' ?>"></i>
                                        </button>
                                    </form>
                                    
                                    <!-- Botón Eliminar -->
                                    <?php if ($usuario['id'] !== Auth::user()['id']): ?>
                                    <form method="POST" action="<?= baseUrl('/usuarios/delete/' . $usuario['id']) ?>" style="display: inline-block; margin: 0;">
                                        <input type="hidden" name="csrf_token" value="<?= generateCSRFToken() ?>">
                                        <button type="submit" class="btn" 
                                                style="width: 36px; height: 36px; display: inline-flex; justify-content: center; align-items: center; padding: 0; background: var(--danger-color); color: white; font-size: 0.875rem; border: none; cursor: pointer; border-radius: 0.25rem;" 
                                                title="Eliminar"
                                                onclick="return confirm('¿Está seguro de eliminar este usuario?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                    <?php endif; ?>
                                    
                                </div>
                            </td>
                            <?php endif; ?>
                        </tr>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Paginación -->
            <?php if ($pagination['last_page'] > 1): ?>
            <div style="margin-top: 2rem; display: flex; justify-content: center; gap: 0.5rem;">
                <?php
                $currentPage = $pagination['current_page'];
                $lastPage = $pagination['last_page'];
                $queryParams = $_GET;
                ?>
                
                <?php if ($currentPage > 1): ?>
                <a href="<?= baseUrl('/usuarios?' . http_build_query(array_merge($queryParams, ['page' => $currentPage - 1]))) ?>" 
                   class="btn" style="padding: 0.5rem 1rem; background: var(--light-color); color: var(--text-color); text-decoration: none;">
                    <i class="fas fa-chevron-left"></i> Anterior
                </a>
                <?php endif; ?>
                
                <span style="padding: 0.5rem 1rem; background: var(--primary-color); color: white; border-radius: 0.25rem; font-weight: 600;">
                    Página <?= $currentPage ?> de <?= $lastPage ?>
                </span>
                
                <?php if ($currentPage < $lastPage): ?>
                <a href="<?= baseUrl('/usuarios?' . http_build_query(array_merge($queryParams, ['page' => $currentPage + 1]))) ?>" 
                   class="btn" style="padding: 0.5rem 1rem; background: var(--light-color); color: var(--text-color); text-decoration: none;">
                    Siguiente <i class="fas fa-chevron-right"></i>
                </a>
                <?php endif; ?>
            </div>
            <?php endif; ?>
        </div>

    </div>
</div>

<?php require_once APP_PATH . '/views/layouts/footer.php'; ?>