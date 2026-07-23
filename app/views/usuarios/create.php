<?php
/**
 * Vista: Crear Usuario
 */
require_once APP_PATH . '/views/layouts/header.php';
$errors = $_SESSION['errors'] ?? [];
$old = $_SESSION['old'] ?? [];
unset($_SESSION['errors'], $_SESSION['old']);
?>

<div class="main-content">
    <div class="container" style="max-width: 900px;">
        
        <div style="margin-bottom: 2rem;">
            <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1rem;">
                <a href="<?= baseUrl('/usuarios') ?>" class="btn" style="background: var(--text-muted); color: white; padding: 0.5rem 1rem;">
                    <i class="fas fa-arrow-left"></i> Volver
                </a>
                <h1 style="font-size: 1.75rem; color: var(--text-color); margin: 0;">
                    <i class="fas fa-user-plus"></i> Crear Nuevo Usuario
                </h1>
            </div>
        </div>

        <div class="dashboard-modules">
            <form method="POST" action="<?= baseUrl('/usuarios/store') ?>" id="formUsuario">
                <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">

                <!-- Datos Personales -->
                <h3 style="color: var(--primary-color); margin-bottom: 1.5rem; padding-bottom: 0.5rem; border-bottom: 2px solid var(--primary-color);">
                    <i class="fas fa-id-card"></i> Datos Personales
                </h3>

                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
                    <div class="form-group">
                        <label for="documento">Documento <span style="color: var(--danger-color);">*</span></label>
                        <input type="text" id="documento" name="documento" class="form-control" 
                               value="<?= e($old['documento'] ?? '') ?>" required maxlength="20">
                        <?php if (isset($errors['documento'])): ?>
                        <small style="color: var(--danger-color); display: block; margin-top: 0.25rem;">
                            <?= e($errors['documento']) ?>
                        </small>
                        <?php endif; ?>
                    </div>

                    <div class="form-group">
                        <label for="nombre">Nombre Completo <span style="color: var(--danger-color);">*</span></label>
                        <input type="text" id="nombre" name="nombre" class="form-control" 
                               value="<?= e($old['nombre'] ?? '') ?>" required maxlength="100">
                        <?php if (isset($errors['nombre'])): ?>
                        <small style="color: var(--danger-color); display: block; margin-top: 0.25rem;">
                            <?= e($errors['nombre']) ?>
                        </small>
                        <?php endif; ?>
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
                    <div class="form-group">
                        <label for="tipo_persona">Tipo de Persona <span style="color: var(--danger-color);">*</span></label>
                        <select id="tipo_persona" name="tipo_persona" class="form-control" required>
                            <option value="">Seleccione...</option>
                            <option value="aprendiz" <?= ($old['tipo_persona'] ?? '') === 'aprendiz' ? 'selected' : '' ?>>Aprendiz</option>
                            <option value="instructor" <?= ($old['tipo_persona'] ?? '') === 'instructor' ? 'selected' : '' ?>>Instructor</option>
                            <option value="admin" <?= ($old['tipo_persona'] ?? '') === 'admin' ? 'selected' : '' ?>>Administrador</option>
                            <option value="vigilante" <?= ($old['tipo_persona'] ?? '') === 'vigilante' ? 'selected' : '' ?>>Vigilante</option>
                            <option value="contratista" <?= ($old['tipo_persona'] ?? '') === 'contratista' ? 'selected' : '' ?>>Contratista</option>
                            <option value="visitante" <?= ($old['tipo_persona'] ?? '') === 'visitante' ? 'selected' : '' ?>>Visitante</option>
                            <option value="proveedor" <?= ($old['tipo_persona'] ?? '') === 'proveedor' ? 'selected' : '' ?>>Proveedor</option>
                        </select>
                        <?php if (isset($errors['tipo_persona'])): ?>
                        <small style="color: var(--danger-color); display: block; margin-top: 0.25rem;">
                            <?= e($errors['tipo_persona']) ?>
                        </small>
                        <?php endif; ?>
                    </div>

                    <div class="form-group">
                        <label for="empresa">Empresa/Institución</label>
                        <input type="text" id="empresa" name="empresa" class="form-control" 
                               value="<?= e($old['empresa'] ?? '') ?>" maxlength="150" placeholder="Opcional">
                    </div>
                </div>

                <!-- Datos de Acceso (solo para personal del sistema) -->
                <div id="seccionAcceso" style="display: none;">
                    <h3 style="color: var(--primary-color); margin-bottom: 1.5rem; padding-bottom: 0.5rem; border-bottom: 2px solid var(--primary-color);">
                        <i class="fas fa-key"></i> Datos de Acceso al Sistema
                    </h3>

                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
                        <div class="form-group">
                            <label for="username">Usuario <span style="color: var(--danger-color);" class="required-marker">*</span></label>
                            <input type="text" id="username" name="username" class="form-control" 
                                   value="<?= e($old['username'] ?? '') ?>" maxlength="50">
                            <?php if (isset($errors['username'])): ?>
                            <small style="color: var(--danger-color); display: block; margin-top: 0.25rem;">
                                <?= e($errors['username']) ?>
                            </small>
                            <?php endif; ?>
                        </div>

                        <div class="form-group">
                            <label for="email">Email <span style="color: var(--danger-color);" class="required-marker">*</span></label>
                            <input type="email" id="email" name="email" class="form-control" 
                                   value="<?= e($old['email'] ?? '') ?>" maxlength="150">
                            <?php if (isset($errors['email'])): ?>
                            <small style="color: var(--danger-color); display: block; margin-top: 0.25rem;">
                                <?= e($errors['email']) ?>
                            </small>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
                        <div class="form-group">
                            <label for="password">Contraseña <span style="color: var(--danger-color);" class="required-marker">*</span></label>
                            <input type="password" id="password" name="password" class="form-control" minlength="8">
                            <small style="color: var(--text-muted); display: block; margin-top: 0.25rem;">
                                Mínimo 8 caracteres
                            </small>
                            <?php if (isset($errors['password'])): ?>
                            <small style="color: var(--danger-color); display: block; margin-top: 0.25rem;">
                                <?= e($errors['password']) ?>
                            </small>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Estado -->
                <div style="margin-bottom: 2rem;">
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 500;">
                        Estado <span style="color: var(--danger-color);">*</span>
                    </label>
                    <div style="display: flex; gap: 1.5rem;">
                        <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
                            <input type="radio" name="estado" value="activo" <?= ($old['estado'] ?? 'activo') === 'activo' ? 'checked' : '' ?> required>
                            <span>Activo</span>
                        </label>
                        <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
                            <input type="radio" name="estado" value="inactivo" <?= ($old['estado'] ?? '') === 'inactivo' ? 'checked' : '' ?>>
                            <span>Inactivo</span>
                        </label>
                    </div>
                </div>

                <!-- Botones -->
                <div style="display: flex; gap: 1rem; justify-content: flex-end; padding-top: 1.5rem; border-top: 1px solid var(--border-color);">
                    <a href="<?= baseUrl('/usuarios') ?>" class="btn" style="background: var(--text-muted); color: white;">
                        <i class="fas fa-times"></i> Cancelar
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Guardar Usuario
                    </button>
                </div>
            </form>
        </div>

    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const tipoPersonaSelect = document.getElementById('tipo_persona');
    const seccionAcceso = document.getElementById('seccionAcceso');
    const usernameInput = document.getElementById('username');
    const passwordInput = document.getElementById('password');
    const emailInput = document.getElementById('email');

    function actualizarFormulario() {
        const tipoPersona = tipoPersonaSelect.value;
        const esPersonalSistema = ['admin', 'instructor', 'vigilante'].includes(tipoPersona);

        // Mostrar/ocultar sección de acceso
        seccionAcceso.style.display = esPersonalSistema ? 'block' : 'none';

        // Configurar campos requeridos
        usernameInput.required = esPersonalSistema;
        passwordInput.required = esPersonalSistema;
        emailInput.required = esPersonalSistema;
    }

    tipoPersonaSelect.addEventListener('change', actualizarFormulario);
    actualizarFormulario(); // Ejecutar al cargar
});
</script>

<?php require_once APP_PATH . '/views/layouts/footer.php'; ?>
