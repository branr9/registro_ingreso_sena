<?php
/**
 * Vista: Importar Usuarios desde CSV
 */
require_once APP_PATH . '/views/layouts/header.php';
?>

<div class="w-full">
    <div class="flex items-center gap-4 mb-8">
        <a href="<?= baseUrl('/usuarios') ?>"
           class="inline-flex items-center gap-2 rounded-xl bg-gray-100 hover:bg-gray-200 transition text-gray-700 font-semibold px-4 py-2 text-sm">
            <i class="fas fa-arrow-left"></i> Volver
        </a>
        <h1 class="text-2xl font-extrabold text-primary-700 flex items-center gap-2">
            <i class="fas fa-file-upload"></i> Importar Usuarios desde CSV
        </h1>
    </div>

    <!-- Instrucciones -->
    <div class="bg-white/85 backdrop-blur-md border border-primary-100 rounded-3xl shadow-xl p-6 md:p-8 mb-6">
        <h3 class="text-lg font-bold text-primary-700 mb-4"><i class="fas fa-info-circle"></i> Instrucciones de Importación</h3>
        <div class="space-y-4">
            <div class="flex items-start gap-3">
                <i class="fas fa-file-csv text-primary-600 mt-1"></i>
                <div>
                    <strong class="block text-sm text-gray-800">Formato del archivo:</strong>
                    <p class="text-sm text-gray-500">El archivo debe ser CSV (separado por comas). Puede incluir encabezados en la primera fila.</p>
                </div>
            </div>
            <div class="flex items-start gap-3">
                <i class="fas fa-columns text-primary-600 mt-1"></i>
                <div>
                    <strong class="block text-sm text-gray-800">Columnas requeridas (en orden):</strong>
                    <p class="text-sm text-gray-500">documento, nombre, tipo_persona, empresa (opcional), email (opcional), username (opcional)</p>
                </div>
            </div>
            <div class="flex items-start gap-3">
                <i class="fas fa-check-double text-primary-600 mt-1"></i>
                <div>
                    <strong class="block text-sm text-gray-800">Tipos de persona válidos:</strong>
                    <p class="text-sm text-gray-500">aprendiz, instructor, admin, vigilante, contratista, visitante, proveedor</p>
                </div>
            </div>
            <div class="flex items-start gap-3">
                <i class="fas fa-exclamation-triangle text-amber-500 mt-1"></i>
                <div>
                    <strong class="block text-sm text-gray-800">Importante:</strong>
                    <p class="text-sm text-gray-500">El sistema validará cada fila antes de importar. Podrás revisar una vista previa con errores detectados. Digita únicamente los números del documento (sin CC ni puntos).</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Ejemplo de CSV -->
    <div class="bg-white/85 backdrop-blur-md border border-primary-100 rounded-3xl shadow-xl p-6 md:p-8 mb-6">
        <h3 class="text-lg font-bold text-primary-700 mb-4"><i class="fas fa-file-download"></i> Ejemplo de Archivo CSV</h3>
        <div class="bg-primary-50 border-l-4 border-primary-600 rounded-xl p-4 overflow-x-auto">
            <pre class="text-xs font-mono m-0">documento,nombre,tipo_persona,empresa,email,username
1234567890,Juan Pérez González,aprendiz,SENA,juan.perez@example.com,
9876543210,María López Ruiz,instructor,SENA,maria.lopez@sena.edu.co,mlopez
98765432,Carlos Rodríguez,vigilante,Seguridad Total,carlos@example.com,crodriguez
1122334455,Ana García,contratista,Empresa XYZ,ana@xyz.com,
11223344,Pedro Martínez,visitante,Gobierno,,</pre>
        </div>
        <div class="mt-4">
            <button onclick="descargarEjemplo()"
                    class="inline-flex items-center gap-2 rounded-xl bg-blue-600 hover:bg-blue-700 transition text-white font-semibold px-4 py-2 text-sm">
                <i class="fas fa-download"></i> Descargar Plantilla CSV
            </button>
        </div>
    </div>

    <!-- Formulario de importación -->
    <div class="bg-white/85 backdrop-blur-md border border-primary-100 rounded-3xl shadow-xl p-6 md:p-8">
        <form method="POST" action="<?= baseUrl('/usuarios/import-preview') ?>" enctype="multipart/form-data">
            <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">

            <h3 class="text-primary-700 font-bold text-lg mb-5"><i class="fas fa-upload"></i> Subir Archivo</h3>

            <div class="mb-8">
                <label for="archivo" class="block text-sm font-semibold text-gray-700 mb-1">Seleccionar archivo CSV <span class="text-red-500">*</span></label>
                <input type="file" id="archivo" name="archivo" accept=".csv" required
                       class="w-full rounded-xl border border-gray-200 px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none">
                <small class="text-gray-500 text-xs mt-1 block">Tamaño máximo: 5MB | Formato: CSV</small>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 mb-8">
                <div>
                    <label for="delimiter" class="block text-sm font-semibold text-gray-700 mb-1">Separador de columnas</label>
                    <select id="delimiter" name="delimiter"
                            class="w-full rounded-xl border border-gray-200 px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none">
                        <option value=",">Coma (,)</option>
                        <option value=";">Punto y coma (;)</option>
                        <option value="\t">Tabulador (Tab)</option>
                    </select>
                </div>

                <div>
                    <label for="mode" class="block text-sm font-semibold text-gray-700 mb-1">Modo de importación</label>
                    <select id="mode" name="mode"
                            class="w-full rounded-xl border border-gray-200 px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none">
                        <option value="upsert">Insertar y Actualizar (recomendado)</option>
                        <option value="insert">Solo Insertar (omitir duplicados)</option>
                    </select>
                    <small class="text-gray-500 text-xs mt-1 block">Upsert: actualiza si el documento ya existe</small>
                </div>
            </div>

            <div class="mb-8">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="has_header" value="1" checked class="accent-primary-700">
                    <span class="text-sm text-gray-700">El archivo incluye encabezados en la primera fila</span>
                </label>
            </div>

            <div class="flex gap-3 justify-end pt-6 border-t border-gray-100">
                <a href="<?= baseUrl('/usuarios') ?>"
                   class="inline-flex items-center gap-2 rounded-xl bg-gray-100 hover:bg-gray-200 transition text-gray-700 font-semibold px-5 py-2.5">
                    <i class="fas fa-times"></i> Cancelar
                </a>
                <button type="submit"
                        class="inline-flex items-center gap-2 rounded-2xl bg-primary-700 hover:bg-primary-800 transition text-white font-semibold px-5 py-2.5 shadow-md">
                    <i class="fas fa-search"></i> Vista Previa
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function descargarEjemplo() {
    const csv = `documento,nombre,tipo_persona,empresa,email,username
1234567890,Juan Pérez González,aprendiz,SENA,juan.perez@example.com,
9876543210,María López Ruiz,instructor,SENA,maria.lopez@sena.edu.co,mlopez
98765432,Carlos Rodríguez,vigilante,Seguridad Total,carlos@example.com,crodriguez
1122334455,Ana García,contratista,Empresa XYZ,ana@xyz.com,
11223344,Pedro Martínez,visitante,Gobierno,,`;

    const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
    const link = document.createElement('a');
    link.href = URL.createObjectURL(blob);
    link.download = 'plantilla_usuarios_sena.csv';
    link.click();
}
</script>

<?php require_once APP_PATH . '/views/layouts/footer.php'; ?>