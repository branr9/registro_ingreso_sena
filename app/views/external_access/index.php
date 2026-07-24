<?php
/**
 * Vista: Listado de Registros de Acceso Externo
 */
require_once APP_PATH . '/views/layouts/header.php';
?>

<div class="main-content">
    <div class="container">
        
        <div style="margin-bottom: 2rem;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; flex-wrap: wrap; gap: 1rem;">
                <p style="color: var(--text-muted);">Control de entrada y salida de personal sin carnet (visitantes, contratistas, proveedores)</p>
                <div style="display: flex; gap: 1rem;">
                    <a href="<?= baseUrl('/acceso-externo/personas-dentro') ?>" class="btn" style="background: var(--info-color); color: white;">
                        <i class="fas fa-door-open"></i> Personas Dentro
                    </a>
                    <a href="<?= baseUrl('/acceso-externo/registro-entrada') ?>" class="btn btn-primary">
                        <i class="fas fa-user-plus"></i> Registrar Entrada
                    </a>
                </div>
            </div>
        </div>

        <!-- Filtros de búsqueda -->
        <div class="dashboard-modules" style="margin-bottom: 1.5rem;">
            <form method="GET" action="<?= baseUrl('/acceso-externo') ?>" style="padding: 1.5rem;">
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; align-items: end;">
                    <div class="form-group" style="margin: 0;">
                        <label for="search" style="display: block; margin-bottom: 0.5rem;">Buscar</label>
                        <input type="text" id="search" name="search" class="form-control" 
                               value="<?= e($_GET['search'] ?? '') ?>" 
                               placeholder="Documento, nombre, empresa...">
                    </div>

                    <div class="form-group" style="margin: 0;">
                        <label for="estado" style="display: block; margin-bottom: 0.5rem;">Estado</label>
                        <select id="estado" name="estado" class="form-control">
                            <option value="">Todos</option>
                            <option value="dentro" <?= ($_GET['estado'] ?? '') === 'dentro' ? 'selected' : '' ?>>Dentro</option>
                            <option value="salio" <?= ($_GET['estado'] ?? '') === 'salio' ? 'selected' : '' ?>>Salió</option>
                        </select>
                    </div>

                    <div class="form-group" style="margin: 0;">
                        <label for="fecha_desde" style="display: block; margin-bottom: 0.5rem;">Desde</label>
                        <input type="date" id="fecha_desde" name="fecha_desde" class="form-control" 
                               value="<?= e($_GET['fecha_desde'] ?? '') ?>">
                    </div>

                    <div class="form-group" style="margin: 0;">
                        <label for="fecha_hasta" style="display: block; margin-bottom: 0.5rem;">Hasta</label>
                        <input type="date" id="fecha_hasta" name="fecha_hasta" class="form-control" 
                               value="<?= e($_GET['fecha_hasta'] ?? '') ?>">
                    </div>

                    <div style="display: flex; gap: 0.5rem;">
                        <button type="submit" class="btn btn-primary" style="flex: 1;">
                            <i class="fas fa-search"></i> Buscar
                        </button>
                        <a href="<?= baseUrl('/acceso-externo') ?>" class="btn" style="background: var(--text-muted); color: white;">
                            <i class="fas fa-redo"></i>
                        </a>
                    </div>
                </div>
            </form>
        </div>

        <!-- Tabla de registros -->
        <div class="dashboard-modules">
            <div style="overflow-x: auto;">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Fecha/Hora Entrada</th>
                            <th>Documento</th>
                            <th>Nombre Completo</th>
                            <th>Empresa</th>
                            <th>Motivo</th>
                            <th>Estado</th>
                            <th>Tiempo Permanencia</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($result['data'])): ?>
                        <tr>
                            <td colspan="8" style="text-align: center; padding: 2rem; color: var(--text-muted);">
                                <i class="fas fa-inbox" style="font-size: 3rem; margin-bottom: 1rem; display: block;"></i>
                                No hay registros para mostrar
                            </td>
                        </tr>
                        <?php else: ?>
                            <?php foreach ($result['data'] as $registro): ?>
                            <tr>
                                <td>
                                    <strong><?= date('d/m/Y', strtotime($registro['fecha_entrada'])) ?></strong><br>
                                    <small style="color: var(--text-muted);"><?= date('h:i A', strtotime($registro['fecha_entrada'])) ?></small>
                                </td>
                                <td>
                                    <strong><?= e($registro['documento']) ?></strong><br>
                                    <small style="color: var(--text-muted);"><?= e($registro['tipo_documento']) ?></small>
                                </td>
                                <td>
                                    <strong><?= e($registro['nombre_completo']) ?></strong>
                                    <?php if ($registro['telefono']): ?>
                                    <br><small style="color: var(--text-muted);"><i class="fas fa-phone"></i> <?= e($registro['telefono']) ?></small>
                                    <?php endif; ?>
                                </td>
                                <td><?= e($registro['empresa'] ?? '-') ?></td>
                                <td>
                                    <small><?= e(substr($registro['motivo_visita'], 0, 50)) ?><?= strlen($registro['motivo_visita']) > 50 ? '...' : '' ?></small>
                                </td>
                                <td>
                                    <?php if ($registro['estado'] === 'DENTRO'): ?>
                                        <span class="badge badge-active">Dentro</span>
                                    <?php else: ?>
                                        <span class="badge badge-inactive">Salió</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($registro['estado'] === 'DENTRO'): ?>
                                        <span style="color: var(--info-color);">
                                            <?= floor($registro['minutos_transcurridos'] / 60) ?>h <?= $registro['minutos_transcurridos'] % 60 ?>m
                                        </span>
                                    <?php elseif ($registro['tiempo_permanencia']): ?>
                                        <?= floor($registro['tiempo_permanencia'] / 60) ?>h <?= $registro['tiempo_permanencia'] % 60 ?>m
                                    <?php else: ?>
                                        -
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div style="display: flex; gap: 0.5rem;">
                                        <a href="<?= baseUrl('/acceso-externo/detalle/' . $registro['id']) ?>" 
                                           class="btn-icon" title="Ver detalle">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        
                                        <?php if ($registro['estado'] === 'DENTRO'): ?>
                                        <form method="POST" action="<?= baseUrl('/acceso-externo/registrar-salida/' . $registro['id']) ?>" 
                                              style="display: inline;" 
                                              onsubmit="return confirm('¿Confirmar salida de <?= e($registro['nombre_completo']) ?>?')">
                                            <input type="hidden" name="csrf_token" value="<?= generateCSRFToken() ?>">
                                            <button type="submit" class="btn-icon" style="background: var(--success-color);" title="Registrar salida">
                                                <i class="fas fa-sign-out-alt"></i>
                                            </button>
                                        </form>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Paginación -->
            <?php if ($result['last_page'] > 1): ?>
            <div class="pagination">
                <?php if ($result['page'] > 1): ?>
                    <a href="<?= baseUrl('/acceso-externo?page=' . ($result['page'] - 1) . '&' . http_build_query($_GET)) ?>" class="btn">
                        <i class="fas fa-chevron-left"></i> Anterior
                    </a>
                <?php endif; ?>

                <span style="padding: 0.5rem 1rem;">
                    Página <?= $result['page'] ?> de <?= $result['last_page'] ?> 
                    (<?= $result['total'] ?> registros)
                </span>

                <?php if ($result['page'] < $result['last_page']): ?>
                    <a href="<?= baseUrl('/acceso-externo?page=' . ($result['page'] + 1) . '&' . http_build_query($_GET)) ?>" class="btn">
                        Siguiente <i class="fas fa-chevron-right"></i>
                    </a>
                <?php endif; ?>
            </div>
            <?php endif; ?>
        </div>

    </div>
</div>
<style>
/* Tabla más amplia y legible */
.table {
    width: 100%;
    border-collapse: collapse;
}

.table thead th {
    text-align: left;
    padding: 0.9rem 1rem;
    background: rgba(0,0,0,0.03);
    font-weight: 700;
    border-bottom: 2px solid rgba(0,0,0,0.08);
    white-space: nowrap;
}

.table tbody td {
    padding: 1rem;
    vertical-align: middle;
    border-bottom: 1px solid rgba(0,0,0,0.06);
}

.table tbody tr:hover {
    background: rgba(0,0,0,0.02);
}

/* Iconos de acciones más consistentes */
.btn-icon {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 34px;
    height: 34px;
    border-radius: 6px;
    background: var(--text-muted);
    color: #fff;
    text-decoration: none;
    border: none;
}

.btn-icon:hover {
    opacity: 0.85;
}

/* Paginación alineada */
.pagination {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 1rem;
    padding: 1.5rem;
    flex-wrap: wrap;
}
/* Que el contenido use más ancho de pantalla en esta vista */
.main-content .container {
    max-width: 1400px;
    width: 100%;
    padding: 0 2rem;
}
</style>
<?php require_once APP_PATH . '/views/layouts/footer.php'; ?>
