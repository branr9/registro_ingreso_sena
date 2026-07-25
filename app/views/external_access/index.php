<?php
/**
 * Vista: Listado de Registros de Acceso Externo
 */
require_once APP_PATH . '/views/layouts/header.php';
?>

<div class="main-content">
    <div class="container">
        
        <!-- Estilos específicos para mejorar la tabla en esta vista -->
        <style>
            .table-nexus {
                width: 100%;
                border-collapse: collapse;
                margin-top: 1rem;
            }
            .table-nexus th {
                background-color: #f8f9fa;
                color: #495057;
                font-weight: 600;
                padding: 15px;
                text-align: left;
                border-bottom: 2px solid #dee2e6;
                white-space: nowrap; /* Evita que los títulos se rompan en dos líneas */
            }
            .table-nexus td {
                padding: 15px;
                vertical-align: middle; /* Centra el contenido verticalmente */
                border-bottom: 1px solid #eff2f5;
                color: #2c3e50;
            }
            .table-nexus tbody tr:hover {
                background-color: #fdfdfd;
                transition: background-color 0.2s ease;
            }
            /* Diseño de Badges con gradientes pastel */
            .badge-pastel-dentro {
                background: linear-gradient(135deg, #d4f0df 0%, #b2e2c6 100%);
                color: #1e5631;
                padding: 6px 14px;
                border-radius: 20px;
                font-size: 0.85rem;
                font-weight: 600;
                display: inline-block;
                box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            }
            .badge-pastel-salio {
                background: linear-gradient(135deg, #ffe5e5 0%, #ffcaca 100%);
                color: #8a2b2b;
                padding: 6px 14px;
                border-radius: 20px;
                font-size: 0.85rem;
                font-weight: 600;
                display: inline-block;
                box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            }
            /* Ajuste de textos secundarios */
            .text-muted-custom {
                color: #8898aa;
                font-size: 0.85em;
                display: block;
                margin-top: 3px;
            }
            /* Contenedor de acciones */
            .actions-container {
                display: flex; 
                gap: 0.5rem; 
                align-items: center;
            }
        </style>

        <div style="margin-bottom: 2rem;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; flex-wrap: wrap; gap: 1rem;">
                <h1 style="font-size: 1.75rem; color: var(--text-color); margin: 0;">
                    <i class="fas fa-users"></i> Registro de Personal Externo
                </h1>
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
                <table class="table-nexus">
                    <thead>
                        <tr>
                            <th>Fecha/Hora Entrada</th>
                            <th>Documento</th>
                            <th>Nombre Completo</th>
                            <th>Empresa</th>
                            <th>Motivo</th>
                            <th>Estado</th>
                            <th>Permanencia</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($result['data'])): ?>
                        <tr>
                            <td colspan="8" style="text-align: center; padding: 3rem; color: var(--text-muted);">
                                <i class="fas fa-inbox" style="font-size: 3rem; margin-bottom: 1rem; display: block; color: #ced4da;"></i>
                                No hay registros para mostrar
                            </td>
                        </tr>
                        <?php else: ?>
                            <?php foreach ($result['data'] as $registro): ?>
                            <tr>
                                <td>
                                    <strong><?= date('d/m/Y', strtotime($registro['fecha_entrada'])) ?></strong>
                                    <span class="text-muted-custom"><?= date('h:i A', strtotime($registro['fecha_entrada'])) ?></span>
                                </td>
                                <td>
                                    <strong><?= e($registro['documento']) ?></strong>
                                    <span class="text-muted-custom"><?= e($registro['tipo_documento']) ?></span>
                                </td>
                                <td>
                                    <strong><?= e($registro['nombre_completo']) ?></strong>
                                    <?php if ($registro['telefono']): ?>
                                        <span class="text-muted-custom"><i class="fas fa-phone fa-sm"></i> <?= e($registro['telefono']) ?></span>
                                    <?php endif; ?>
                                </td>
                                <td><?= e($registro['empresa'] ?? '-') ?></td>
                                <td style="max-width: 200px; white-space: normal; line-height: 1.4;">
                                    <small><?= e(substr($registro['motivo_visita'], 0, 50)) ?><?= strlen($registro['motivo_visita']) > 50 ? '...' : '' ?></small>
                                </td>
                                <td>
                                    <?php if ($registro['estado'] === 'DENTRO'): ?>
                                        <span class="badge-pastel-dentro">Dentro</span>
                                    <?php else: ?>
                                        <span class="badge-pastel-salio">Salió</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($registro['estado'] === 'DENTRO'): ?>
                                        <span style="color: var(--info-color); font-weight: 500;">
                                            <?= floor($registro['minutos_transcurridos'] / 60) ?>h <?= $registro['minutos_transcurridos'] % 60 ?>m
                                        </span>
                                    <?php elseif ($registro['tiempo_permanencia']): ?>
                                        <?= floor($registro['tiempo_permanencia'] / 60) ?>h <?= $registro['tiempo_permanencia'] % 60 ?>m
                                    <?php else: ?>
                                        -
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="actions-container">
                                        <a href="<?= baseUrl('/acceso-externo/detalle/' . $registro['id']) ?>" 
                                           class="btn-icon" title="Ver detalle" style="color: #6c757d; border: 1px solid #dee2e6; padding: 6px 10px; border-radius: 6px;">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        
                                        <?php if ($registro['estado'] === 'DENTRO'): ?>
                                        <form method="POST" action="<?= baseUrl('/acceso-externo/registrar-salida/' . $registro['id']) ?>" 
                                              style="display: inline; margin: 0;" 
                                              onsubmit="return confirm('¿Confirmar salida de <?= e($registro['nombre_completo']) ?>?')">
                                            <input type="hidden" name="csrf_token" value="<?= generateCSRFToken() ?>">
                                            <button type="submit" class="btn-icon" style="background: var(--success-color); color: white; border: none; padding: 6px 10px; border-radius: 6px; cursor: pointer;" title="Registrar salida">
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
            <div class="pagination" style="margin-top: 1.5rem; padding: 1rem; border-top: 1px solid #eff2f5;">
                <?php if ($result['page'] > 1): ?>
                    <a href="<?= baseUrl('/acceso-externo?page=' . ($result['page'] - 1) . '&' . http_build_query($_GET)) ?>" class="btn">
                        <i class="fas fa-chevron-left"></i> Anterior
                    </a>
                <?php endif; ?>

                <span style="padding: 0.5rem 1rem; font-weight: 500; color: #495057;">
                    Página <?= $result['page'] ?> de <?= $result['last_page'] ?> 
                    <span style="color: #8898aa; font-weight: normal;">(<?= $result['total'] ?> registros)</span>
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