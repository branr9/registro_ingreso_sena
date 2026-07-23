<?php
/**
 * Vista: Editar Usuario
 */
require_once APP_PATH . '/views/layouts/header.php';
$errors = $_SESSION['errors'] ?? [];
$old = $_SESSION['old'] ?? $usuario; // Usar datos antiguos o actuales
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
                    <i class="fas fa-user-edit"></i> Editar Usuario
                </h1>
            </div>
            <p style="color: var(--text-muted);">Editando: <strong><?= e($usuario['nombre']) ?></strong> (<?= e($usuario['documento']) ?>)</p>
        </div>

        <div class="dashboard-modules">
            <form method="POST" action="<?= baseUrl('/usuarios/update/' . $usuario['id']) ?>">
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
                        <small style="color: var(--danger-color);"><?= e($errors['documento']) ?></small>
                        <?php endif; ?>
                    </div>

                    <div class="form-group">
                        <label for="nombre">Nombre Completo <span style="color: var(--danger-color);">*</span></label>
                        <input type="text" id="nombre" name="nombre" class="form-control" 
                               value="<?= e($old['nombre'] ?? '') ?>" required maxlength="100">
                        <?php if (isset($errors['nombre'])): ?>
                        <small style="color: var(--danger-color);"><?= e($errors['nombre']) ?></small>
                        <?php endif; ?>
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
                    <div class="form-group">
                        <label for="tipo_persona">Tipo de Persona <span style="color: var(--danger-color);">*</span></label>
                        <select id="tipo_persona" name="tipo_persona" class="form-control" required>
                            <?php
                            $tipos = ['aprendiz', 'instructor', 'admin', 'vigilante', 'contratista', 'visitante', 'proveedor'];
                            foreach ($tipos as $tipo):
                            ?>
                            <option value="<?= $tipo ?>" <?= ($old['tipo_persona'] ?? '') === $tipo ? 'selected' : '' ?>>
                                <?= ucfirst($tipo) ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="empresa">Empresa/Institución</label>
                        <input type="text" id="empresa" name="empresa" class="form-control" 
                               value="<?= e($old['empresa'] ?? '') ?>" maxlength="150">
                    </div>
                </div>

                <!-- Datos de Acceso (solo para admin, instructor, vigilante) -->
                <?php
                $tipoPersonaActual = $old['tipo_persona'] ?? '';
                $tieneAccesoSistema = in_array($tipoPersonaActual, ['admin', 'instructor', 'vigilante']);
                ?>
                <div id="datos-acceso-section" style="<?= !$tieneAccesoSistema ? 'display: none;' : '' ?>">
                    <h3 style="color: var(--primary-color); margin-bottom: 1.5rem; padding-bottom: 0.5rem; border-bottom: 2px solid var(--primary-color);">
                        <i class="fas fa-key"></i> Datos de Acceso al Sistema
                    </h3>

                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
                        <div class="form-group">
                            <label for="username">Usuario</label>
                            <input type="text" id="username" name="username" class="form-control" 
                                   value="<?= e($old['username'] ?? '') ?>" maxlength="50">
                            <?php if (isset($errors['username'])): ?>
                            <small style="color: var(--danger-color);"><?= e($errors['username']) ?></small>
                            <?php endif; ?>
                        </div>

                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" id="email" name="email" class="form-control" 
                                   value="<?= e($old['email'] ?? '') ?>" maxlength="150">
                            <?php if (isset($errors['email'])): ?>
                            <small style="color: var(--danger-color);"><?= e($errors['email']) ?></small>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
                        <div class="form-group">
                            <label for="password">Nueva Contraseña</label>
                            <input type="password" id="password" name="password" class="form-control" minlength="8">
                            <small style="color: var(--text-muted);">Dejar en blanco para mantener la actual</small>
                            <?php if (isset($errors['password'])): ?>
                            <small style="color: var(--danger-color);"><?= e($errors['password']) ?></small>
                            <?php endif; ?>
                        </div>

                        <div class="form-group">
                            <label for="rol">Rol del Sistema <span style="color: var(--danger-color);">*</span></label>
                            <select id="rol" name="rol" class="form-control">
                                <option value="admin" <?= ($old['rol'] ?? '') === 'admin' ? 'selected' : '' ?>>Admin</option>
                                <option value="instructor" <?= ($old['rol'] ?? '') === 'instructor' ? 'selected' : '' ?>>Instructor</option>
                                <option value="vigilante" <?= ($old['rol'] ?? '') === 'vigilante' ? 'selected' : '' ?>>Vigilante</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Estado -->
                <div style="margin-bottom: 2rem;">
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Estado <span style="color: var(--danger-color);">*</span></label>
                    <div style="display: flex; gap: 1.5rem;">
                        <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
                            <input type="radio" name="estado" value="activo" <?= ($old['estado'] ?? 'activo') === 'activo' ? 'checked' : '' ?> required>
                            <span>Activo</span>
                        </label>
                        <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
                            <input type="radio" name="estado" value="inactivo" <?= ($old['estado'] ?? 'activo') === 'inactivo' ? 'checked' : '' ?>>
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
                        <i class="fas fa-save"></i> Guardar Cambios
                    </button>
                </div>
            </form>
        </div>

    </div>
</div>

<script>
// Mostrar/ocultar sección de Datos de Acceso según tipo de persona
document.getElementById('tipo_persona').addEventListener('change', function() {
    const tiposConAcceso = ['admin', 'instructor', 'vigilante'];
    const datosAccesoSection = document.getElementById('datos-acceso-section');
    const rolSelect = document.getElementById('rol');
    
    if (tiposConAcceso.includes(this.value)) {
        datosAccesoSection.style.display = 'block';
        rolSelect.required = true;
    } else {
        datosAccesoSection.style.display = 'none';
        rolSelect.required = false;
        // Limpiar campos de acceso cuando no se necesitan
        document.getElementById('username').value = '';
        document.getElementById('email').value = '';
        document.getElementById('password').value = '';
    }
});
</script>

<?php require_once APP_PATH . '/views/layouts/footer.php'; ?>
