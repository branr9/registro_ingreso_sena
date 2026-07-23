<?php
/**
 * Vista: Formulario de Registro de Entrada - Personal Externo
 */
require_once APP_PATH . '/views/layouts/header.php';
$errors = $_SESSION['errors'] ?? [];
$old = $_SESSION['old'] ?? [];
unset($_SESSION['errors'], $_SESSION['old']);
?>

<div class="main-content">
    <div class="container" style="max-width: 1000px;">
        
        <div style="margin-bottom: 2rem;">
            <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1rem;">
                <a href="<?= baseUrl('/acceso-externo') ?>" class="btn" style="background: var(--text-muted); color: white; padding: 0.5rem 1rem;">
                    <i class="fas fa-arrow-left"></i> Volver
                </a>
                <h1 style="font-size: 1.75rem; color: var(--text-color); margin: 0;">
                    <i class="fas fa-user-plus"></i> Registrar Entrada - Personal Externo
                </h1>
            </div>
            <p style="color: var(--text-muted);">Complete el formulario para registrar el ingreso de personal sin carnet (visitantes, contratistas, proveedores)</p>
        </div>

        <div class="dashboard-modules">
            <form method="POST" action="<?= baseUrl('/acceso-externo/guardar-entrada') ?>">
                <input type="hidden" name="csrf_token" value="<?= generateCSRFToken() ?>">

                <!-- Datos del Visitante -->
                <h3 style="color: var(--primary-color); margin-bottom: 1.5rem; padding-bottom: 0.5rem; border-bottom: 2px solid var(--primary-color);">
                    <i class="fas fa-address-card"></i> Datos del Visitante
                </h3>

                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
                    <div class="form-group">
                        <label for="tipo_documento">Tipo de Documento <span style="color: var(--danger-color);">*</span></label>
                        <select id="tipo_documento" name="tipo_documento" class="form-control" required>
                            <option value="CC" <?= ($old['tipo_documento'] ?? 'CC') === 'CC' ? 'selected' : '' ?>>Cédula de Ciudadanía</option>
                            <option value="CE" <?= ($old['tipo_documento'] ?? '') === 'CE' ? 'selected' : '' ?>>Cédula de Extranjería</option>
                            <option value="TI" <?= ($old['tipo_documento'] ?? '') === 'TI' ? 'selected' : '' ?>>Tarjeta de Identidad</option>
                            <option value="PAS" <?= ($old['tipo_documento'] ?? '') === 'PAS' ? 'selected' : '' ?>>Pasaporte</option>
                            <option value="NIT" <?= ($old['tipo_documento'] ?? '') === 'NIT' ? 'selected' : '' ?>>NIT</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="documento">Número de Documento <span style="color: var(--danger-color);">*</span></label>
                        <input type="text" id="documento" name="documento" class="form-control" 
                               value="<?= e($old['documento'] ?? '') ?>" required maxlength="20"
                               placeholder="Ej: 1234567890">
                        <?php if (isset($errors['documento'])): ?>
                        <small style="color: var(--danger-color);"><?= e($errors['documento']) ?></small>
                        <?php endif; ?>
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
                    <div class="form-group">
                        <label for="nombres">Nombres <span style="color: var(--danger-color);">*</span></label>
                        <input type="text" id="nombres" name="nombres" class="form-control" 
                               value="<?= e($old['nombres'] ?? '') ?>" required maxlength="100"
                               placeholder="Ej: Juan Carlos">
                        <?php if (isset($errors['nombres'])): ?>
                        <small style="color: var(--danger-color);"><?= e($errors['nombres']) ?></small>
                        <?php endif; ?>
                    </div>

                    <div class="form-group">
                        <label for="apellidos">Apellidos</label>
                        <input type="text" id="apellidos" name="apellidos" class="form-control" 
                               value="<?= e($old['apellidos'] ?? '') ?>" maxlength="100"
                               placeholder="Ej: Pérez González">
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
                    <div class="form-group">
                        <label for="empresa">Empresa/Institución</label>
                        <input type="text" id="empresa" name="empresa" class="form-control" 
                               value="<?= e($old['empresa'] ?? '') ?>" maxlength="150"
                               placeholder="Ej: Tech Solutions S.A.S">
                    </div>

                    <div class="form-group">
                        <label for="telefono">Teléfono</label>
                        <input type="tel" id="telefono" name="telefono" class="form-control" 
                               value="<?= e($old['telefono'] ?? '') ?>" maxlength="20"
                               placeholder="Ej: 3001234567">
                    </div>

                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" class="form-control" 
                               value="<?= e($old['email'] ?? '') ?>" maxlength="150"
                               placeholder="ejemplo@empresa.com">
                        <?php if (isset($errors['email'])): ?>
                        <small style="color: var(--danger-color);"><?= e($errors['email']) ?></small>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Información de la Visita -->
                <h3 style="color: var(--primary-color); margin-bottom: 1.5rem; padding-bottom: 0.5rem; border-bottom: 2px solid var(--primary-color);">
                    <i class="fas fa-clipboard-list"></i> Información de la Visita
                </h3>

                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
                    <div class="form-group">
                        <label for="motivo_visita">Motivo de la Visita <span style="color: var(--danger-color);">*</span></label>
                        <input type="text" id="motivo_visita" name="motivo_visita" class="form-control" 
                               value="<?= e($old['motivo_visita'] ?? '') ?>" required maxlength="255"
                               placeholder="Ej: Reunión con instructor, Mantenimiento de equipos">
                        <?php if (isset($errors['motivo_visita'])): ?>
                        <small style="color: var(--danger-color);"><?= e($errors['motivo_visita']) ?></small>
                        <?php endif; ?>
                    </div>

                    <div class="form-group">
                        <label for="persona_visitada">Persona a Visitar</label>
                        <input type="text" id="persona_visitada" name="persona_visitada" class="form-control" 
                               value="<?= e($old['persona_visitada'] ?? '') ?>" maxlength="150"
                               placeholder="Ej: Ing. María López">
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr; gap: 1.5rem; margin-bottom: 2rem;">
                    <div class="form-group">
                        <label for="area_destino">Área/Departamento de Destino</label>
                        <input type="text" id="area_destino" name="area_destino" class="form-control" 
                               value="<?= e($old['area_destino'] ?? '') ?>" maxlength="100"
                               placeholder="Ej: Coordinación Académica, Laboratorio de Redes">
                    </div>

                    <div class="form-group">
                        <label for="observaciones">Observaciones</label>
                        <textarea id="observaciones" name="observaciones" class="form-control" 
                                  rows="3" maxlength="500" 
                                  placeholder="Información adicional relevante"><?= e($old['observaciones'] ?? '') ?></textarea>
                        <small style="color: var(--text-muted);">Opcional. Máximo 500 caracteres</small>
                    </div>
                </div>

                <!-- Botones -->
                <div style="display: flex; gap: 1rem; justify-content: flex-end; padding-top: 1.5rem; border-top: 1px solid var(--border-color);">
                    <a href="<?= baseUrl('/acceso-externo') ?>" class="btn" style="background: var(--text-muted); color: white;">
                        <i class="fas fa-times"></i> Cancelar
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Registrar Entrada
                    </button>
                </div>
            </form>
        </div>

    </div>
</div>

<?php require_once APP_PATH . '/views/layouts/footer.php'; ?>
