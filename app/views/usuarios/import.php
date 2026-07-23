<?php
/**
 * Vista: Importar Usuarios desde CSV
 */
require_once APP_PATH . '/views/layouts/header.php';
?>

<div class="main-content">
    <div class="container" style="max-width: 1000px;">
        
        <div style="margin-bottom: 2rem;">
            <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1rem;">
                <a href="<?= baseUrl('/usuarios') ?>" class="btn" style="background: var(--text-muted); color: white; padding: 0.5rem 1rem;">
                    <i class="fas fa-arrow-left"></i> Volver
                </a>
                <h1 style="font-size: 1.75rem; color: var(--text-color); margin: 0;">
                    <i class="fas fa-file-upload"></i> Importar Usuarios desde CSV
                </h1>
            </div>
        </div>

        <!-- Instrucciones -->
        <div class="dashboard-info" style="margin-bottom: 2rem;">
            <h3><i class="fas fa-info-circle"></i> Instrucciones de Importación</h3>
            <div class="info-grid" style="grid-template-columns: 1fr;">
                <div class="info-item">
                    <i class="fas fa-file-csv"></i>
                    <div>
                        <strong>Formato del archivo:</strong>
                        <p>El archivo debe ser CSV (separado por comas). Puede incluir encabezados en la primera fila.</p>
                    </div>
                </div>
                <div class="info-item">
                    <i class="fas fa-columns"></i>
                    <div>
                        <strong>Columnas requeridas (en orden):</strong>
                        <p>documento, nombre, tipo_persona, empresa (opcional), email (opcional), username (opcional)</p>
                    </div>
                </div>
                <div class="info-item">
                    <i class="fas fa-check-double"></i>
                    <div>
                        <strong>Tipos de persona válidos:</strong>
                        <p>aprendiz, instructor, admin, vigilante, contratista, visitante, proveedor</p>
                    </div>
                </div>
                <div class="info-item">
                    <i class="fas fa-exclamation-triangle"></i>
                    <div>
                        <strong>Importante:</strong>
                        <p>El sistema validará cada fila antes de importar. Podrás revisar una vista previa con errores detectados.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Ejemplo de CSV -->
        <div class="dashboard-modules" style="margin-bottom: 2rem;">
            <h3 style="color: var(--primary-color); margin-bottom: 1rem;">
                <i class="fas fa-file-download"></i> Ejemplo de Archivo CSV
            </h3>
            <div style="background: var(--light-color); padding: 1rem; border-radius: 0.5rem; border-left: 4px solid var(--primary-color);">
                <pre style="margin: 0; font-family: monospace; font-size: 0.875rem; overflow-x: auto;">documento,nombre,tipo_persona,empresa,email,username
1234567890,Juan Pérez González,aprendiz,SENA,juan.perez@example.com,
9876543210,María López Ruiz,instructor,SENA,maria.lopez@sena.edu.co,mlopez
CC987654,Carlos Rodríguez,vigilante,Seguridad Total,carlos@example.com,crodriguez
1122334455,Ana García,contratista,Empresa XYZ,ana@xyz.com,
CC112233,Pedro Martínez,visitante,Gobierno,,</pre>
            </div>
            <div style="margin-top: 1rem;">
                <button onclick="descargarEjemplo()" class="btn" style="background: var(--info-color); color: white;">
                    <i class="fas fa-download"></i> Descargar Plantilla CSV
                </button>
            </div>
        </div>

        <!-- Formulario de importación -->
        <div class="dashboard-modules">
            <form method="POST" action="<?= baseUrl('/usuarios/import-preview') ?>" enctype="multipart/form-data">
                <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">

                <h3 style="color: var(--primary-color); margin-bottom: 1.5rem;">
                    <i class="fas fa-upload"></i> Subir Archivo
                </h3>

                <div class="form-group" style="margin-bottom: 2rem;">
                    <label for="archivo">Seleccionar archivo CSV <span style="color: var(--danger-color);">*</span></label>
                    <input type="file" id="archivo" name="archivo" accept=".csv" required class="form-control">
                    <small style="color: var(--text-muted); display: block; margin-top: 0.5rem;">
                        Tamaño máximo: 5MB | Formato: CSV
                    </small>
                </div>

                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
                    <div class="form-group">
                        <label for="delimiter">Separador de columnas</label>
                        <select id="delimiter" name="delimiter" class="form-control">
                            <option value=",">Coma (,)</option>
                            <option value=";">Punto y coma (;)</option>
                            <option value="\t">Tabulador (Tab)</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="mode">Modo de importación</label>
                        <select id="mode" name="mode" class="form-control">
                            <option value="upsert">Insertar y Actualizar (recomendado)</option>
                            <option value="insert">Solo Insertar (omitir duplicados)</option>
                        </select>
                        <small style="color: var(--text-muted); display: block; margin-top: 0.25rem;">
                            Upsert: actualiza si el documento ya existe
                        </small>
                    </div>
                </div>

                <div class="form-group" style="margin-bottom: 2rem;">
                    <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
                        <input type="checkbox" name="has_header" value="1" checked>
                        <span>El archivo incluye encabezados en la primera fila</span>
                    </label>
                </div>

                <div style="display: flex; gap: 1rem; justify-content: flex-end; padding-top: 1.5rem; border-top: 1px solid var(--border-color);">
                    <a href="<?= baseUrl('/usuarios') ?>" class="btn" style="background: var(--text-muted); color: white;">
                        <i class="fas fa-times"></i> Cancelar
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search"></i> Vista Previa
                    </button>
                </div>
            </form>
        </div>

    </div>
</div>

<script>
function descargarEjemplo() {
    const csv = `documento,nombre,tipo_persona,empresa,email,username
1234567890,Juan Pérez González,aprendiz,SENA,juan.perez@example.com,
9876543210,María López Ruiz,instructor,SENA,maria.lopez@sena.edu.co,mlopez
CC987654,Carlos Rodríguez,vigilante,Seguridad Total,carlos@example.com,crodriguez
1122334455,Ana García,contratista,Empresa XYZ,ana@xyz.com,
CC112233,Pedro Martínez,visitante,Gobierno,,`;

    const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
    const link = document.createElement('a');
    link.href = URL.createObjectURL(blob);
    link.download = 'plantilla_usuarios_sena.csv';
    link.click();
}
</script>

<?php require_once APP_PATH . '/views/layouts/footer.php'; ?>
