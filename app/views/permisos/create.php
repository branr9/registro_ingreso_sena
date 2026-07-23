<?php require_once APP_PATH . '/views/layouts/header.php'; ?>

<div class="main-content">
    <div class="content-header">
        <h1><i class="fas fa-plus-circle"></i> Crear Permiso de Salida</h1>
        <div class="header-actions">
            <a href="<?= baseUrl('/permisos') ?>" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
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

    <div class="card">
        <div class="card-header">
            <h3><i class="fas fa-edit"></i> Información del Permiso</h3>
        </div>
        <div class="card-body">
            <form method="POST" action="<?= baseUrl('/permisos/store') ?>" class="form">
                <input type="hidden" name="csrf_token" value="<?= generateCSRFToken() ?>">

                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i>
                    Complete la información del aprendiz que requiere permiso de salida.
                    Los campos marcados con * son obligatorios.
                </div>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="documento_aprendiz">
                            <i class="fas fa-id-card"></i> Documento del Aprendiz *
                        </label>
                        <input type="text" 
                               id="documento_aprendiz" 
                               name="documento_aprendiz" 
                               class="form-control" 
                               required
                               placeholder="Ingrese el documento y presione Tab"
                               maxlength="30"
                               autocomplete="off">
                        <small class="form-text" id="busqueda-mensaje">Ingrese el documento y presione Tab para buscar</small>
                    </div>

                    <div class="form-group col-md-6">
                        <label for="nombre_aprendiz">
                            <i class="fas fa-user"></i> Nombre Completo del Aprendiz
                        </label>
                        <input type="text" 
                               id="nombre_aprendiz" 
                               name="nombre_aprendiz" 
                               class="form-control" 
                               readonly
                               placeholder="Se mostrará automáticamente"
                               style="background-color: #f8f9fa;">
                        <small class="form-text">El nombre se obtiene automáticamente del sistema</small>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label for="fecha_permiso">
                            <i class="fas fa-calendar"></i> Fecha del Permiso *
                        </label>
                        <input type="date" 
                               id="fecha_permiso" 
                               name="fecha_permiso" 
                               class="form-control" 
                               required
                               min="<?= date('Y-m-d') ?>"
                               value="<?= date('Y-m-d') ?>">
                    </div>

                    <div class="form-group col-md-4">
                        <label for="hora_salida">
                            <i class="fas fa-clock"></i> Hora de Salida *
                        </label>
                        <input type="time" 
                               id="hora_salida" 
                               name="hora_salida" 
                               class="form-control" 
                               required>
                        <small class="form-text">Hora aproximada de salida</small>
                    </div>

                    <div class="form-group col-md-4">
                        <label for="hora_regreso">
                            <i class="fas fa-clock"></i> Hora de Regreso (Opcional)
                        </label>
                        <input type="time" 
                               id="hora_regreso" 
                               name="hora_regreso" 
                               class="form-control">
                        <small class="form-text">Hora estimada de regreso</small>
                    </div>
                </div>

                <div class="form-group">
                    <label for="motivo">
                        <i class="fas fa-clipboard"></i> Motivo de la Salida *
                    </label>
                    <textarea id="motivo" 
                              name="motivo" 
                              class="form-control" 
                              rows="3" 
                              required
                              placeholder="Describa el motivo por el cual el aprendiz necesita salir (Ej: Cita médica, trámite bancario, emergencia familiar, etc.)"
                              maxlength="500"></textarea>
                    <small class="form-text">Máximo 500 caracteres</small>
                </div>

                <div class="form-group">
                    <label for="observaciones">
                        <i class="fas fa-sticky-note"></i> Observaciones Adicionales (Opcional)
                    </label>
                    <textarea id="observaciones" 
                              name="observaciones" 
                              class="form-control" 
                              rows="2"
                              placeholder="Información adicional relevante"
                              maxlength="500"></textarea>
                </div>

                <div class="alert alert-success">
                    <i class="fas fa-user-tie"></i>
                    <strong>Instructor que autoriza:</strong> <?= e($_SESSION['user_name']) ?>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Crear Permiso
                    </button>
                    <a href="<?= baseUrl('/permisos') ?>" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Auto-sugerir hora actual + 15 minutos para hora de salida
document.addEventListener('DOMContentLoaded', function() {
    const horaSalidaInput = document.getElementById('hora_salida');
    if (!horaSalidaInput.value) {
        const now = new Date();
        now.setMinutes(now.getMinutes() + 15);
        const hours = String(now.getHours()).padStart(2, '0');
        const minutes = String(now.getMinutes()).padStart(2, '0');
        horaSalidaInput.value = `${hours}:${minutes}`;
    }

    // Buscar aprendiz al salir del campo documento (blur) o presionar Tab
    const documentoInput = document.getElementById('documento_aprendiz');
    const nombreInput = document.getElementById('nombre_aprendiz');
    const mensajeBusqueda = document.getElementById('busqueda-mensaje');
    const submitBtn = document.querySelector('button[type="submit"]');

    documentoInput.addEventListener('blur', buscarAprendiz);
    documentoInput.addEventListener('keydown', function(e) {
        if (e.key === 'Tab' || e.key === 'Enter') {
            e.preventDefault();
            buscarAprendiz();
        }
    });

    async function buscarAprendiz() {
        const documento = documentoInput.value.trim();
        
        if (!documento) {
            nombreInput.value = '';
            mensajeBusqueda.textContent = 'Ingrese el documento y presione Tab para buscar';
            mensajeBusqueda.style.color = '';
            submitBtn.disabled = false;
            return;
        }

        // Mostrar loading
        mensajeBusqueda.textContent = 'Buscando aprendiz...';
        mensajeBusqueda.style.color = '#007bff';
        nombreInput.value = 'Buscando...';
        submitBtn.disabled = true;

        try {
            const response = await fetch(`<?= baseUrl('/permisos/buscar-aprendiz') ?>?documento=${encodeURIComponent(documento)}`);
            const data = await response.json();

            if (data.success) {
                // Aprendiz encontrado
                nombreInput.value = data.persona.nombre_completo;
                mensajeBusqueda.textContent = '✓ Aprendiz encontrado en el sistema';
                mensajeBusqueda.style.color = '#28a745';
                submitBtn.disabled = false;
                
                // Enfocar el siguiente campo
                document.getElementById('fecha_permiso').focus();
            } else {
                // No encontrado
                nombreInput.value = '';
                mensajeBusqueda.textContent = '✗ ' + data.message;
                mensajeBusqueda.style.color = '#dc3545';
                submitBtn.disabled = true;
            }
        } catch (error) {
            console.error('Error:', error);
            nombreInput.value = '';
            mensajeBusqueda.textContent = '✗ Error al buscar aprendiz';
            mensajeBusqueda.style.color = '#dc3545';
            submitBtn.disabled = true;
        }
    }

    // Limpiar al cambiar documento
    documentoInput.addEventListener('input', function() {
        if (nombreInput.value && nombreInput.value !== 'Buscando...') {
            nombreInput.value = '';
            mensajeBusqueda.textContent = 'Presione Tab para buscar nuevamente';
            mensajeBusqueda.style.color = '#6c757d';
        }
    });
});
</script>

<?php require_once APP_PATH . '/views/layouts/footer.php'; ?>
