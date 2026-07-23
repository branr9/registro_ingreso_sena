<?php
/**
 * Vista: Detalle de Registro de Acceso Externo
 */
require_once APP_PATH . '/views/layouts/header.php';
?>

<div class="main-content">
    <div class="container" style="max-width: 900px;">
        
        <div style="margin-bottom: 2rem;">
            <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1rem;">
                <a href="<?= baseUrl('/acceso-externo') ?>" class="btn" style="background: var(--text-muted); color: white; padding: 0.5rem 1rem;">
                    <i class="fas fa-arrow-left"></i> Volver
                </a>
                <h1 style="font-size: 1.75rem; color: var(--text-color); margin: 0;">
                    <i class="fas fa-file-alt"></i> Detalle del Registro
                </h1>
                <?php if ($registro['estado'] === 'DENTRO'): ?>
                    <span class="badge badge-active" style="font-size: 1rem;">Dentro</span>
                <?php else: ?>
                    <span class="badge badge-inactive" style="font-size: 1rem;">Salió</span>
                <?php endif; ?>
            </div>
        </div>

        <div class="dashboard-modules">
            <!-- Datos del Visitante -->
            <section style="padding: 1.5rem; border-bottom: 1px solid var(--border-color);">
                <h3 style="color: var(--primary-color); margin-bottom: 1rem;">
                    <i class="fas fa-user"></i> Datos del Visitante
                </h3>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem;">
                    <div>
                        <label style="font-weight: bold; color: var(--text-muted); display: block; margin-bottom: 0.25rem;">Documento:</label>
                        <p style="margin: 0;"><?= e($registro['tipo_documento']) ?> - <?= e($registro['documento']) ?></p>
                    </div>
                    <div>
                        <label style="font-weight: bold; color: var(--text-muted); display: block; margin-bottom: 0.25rem;">Nombre Completo:</label>
                        <p style="margin: 0; font-weight: 500;"><?= e($registro['nombre_completo']) ?></p>
                    </div>
                    <div>
                        <label style="font-weight: bold; color: var(--text-muted); display: block; margin-bottom: 0.25rem;">Empresa:</label>
                        <p style="margin: 0;"><?= e($registro['empresa'] ?? '-') ?></p>
                    </div>
                    <div>
                        <label style="font-weight: bold; color: var(--text-muted); display: block; margin-bottom: 0.25rem;">Teléfono:</label>
                        <p style="margin: 0;"><?= e($registro['telefono'] ?? '-') ?></p>
                    </div>
                    <div>
                        <label style="font-weight: bold; color: var(--text-muted); display: block; margin-bottom: 0.25rem;">Email:</label>
                        <p style="margin: 0;"><?= e($registro['email'] ?? '-') ?></p>
                    </div>
                </div>
            </section>

            <!-- Información de la Visita -->
            <section style="padding: 1.5rem; border-bottom: 1px solid var(--border-color);">
                <h3 style="color: var(--primary-color); margin-bottom: 1rem;">
                    <i class="fas fa-clipboard-list"></i> Información de la Visita
                </h3>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem;">
                    <div>
                        <label style="font-weight: bold; color: var(--text-muted); display: block; margin-bottom: 0.25rem;">Motivo:</label>
                        <p style="margin: 0;"><?= e($registro['motivo_visita']) ?></p>
                    </div>
                    <div>
                        <label style="font-weight: bold; color: var(--text-muted); display: block; margin-bottom: 0.25rem;">Persona Visitada:</label>
                        <p style="margin: 0;"><?= e($registro['persona_visitada'] ?? '-') ?></p>
                    </div>
                    <div>
                        <label style="font-weight: bold; color: var(--text-muted); display: block; margin-bottom: 0.25rem;">Área Destino:</label>
                        <p style="margin: 0;"><?= e($registro['area_destino'] ?? '-') ?></p>
                    </div>
                </div>
            </section>

            <!-- Control de Horarios -->
            <section style="padding: 1.5rem; border-bottom: 1px solid var(--border-color);">
                <h3 style="color: var(--primary-color); margin-bottom: 1rem;">
                    <i class="fas fa-clock"></i> Control de Horarios
                </h3>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem;">
                    <div>
                        <label style="font-weight: bold; color: var(--text-muted); display: block; margin-bottom: 0.25rem;">Fecha/Hora Entrada:</label>
                        <p style="margin: 0; color: var(--success-color); font-weight: 500;">
                            <i class="fas fa-sign-in-alt"></i> 
                            <?= date('d/m/Y h:i A', strtotime($registro['fecha_entrada'])) ?>
                        </p>
                    </div>
                    <div>
                        <label style="font-weight: bold; color: var(--text-muted); display: block; margin-bottom: 0.25rem;">Fecha/Hora Salida:</label>
                        <p style="margin: 0; <?= $registro['fecha_salida'] ? 'color: var(--danger-color);' : 'color: var(--info-color);' ?> font-weight: 500;">
                            <?php if ($registro['fecha_salida']): ?>
                                <i class="fas fa-sign-out-alt"></i> 
                                <?= date('d/m/Y h:i A', strtotime($registro['fecha_salida'])) ?>
                            <?php else: ?>
                                <i class="fas fa-user-clock"></i> Aún dentro
                            <?php endif; ?>
                        </p>
                    </div>
                    <div>
                        <label style="font-weight: bold; color: var(--text-muted); display: block; margin-bottom: 0.25rem;">Tiempo de Permanencia:</label>
                        <p style="margin: 0; color: var(--info-color); font-weight: 500;">
                            <?php
                            $minutos = $registro['estado'] === 'DENTRO' 
                                ? $registro['minutos_transcurridos']
                                : $registro['tiempo_permanencia'];
                            $horas = floor($minutos / 60);
                            $mins = $minutos % 60;
                            echo "{$horas} horas {$mins} minutos";
                            ?>
                        </p>
                    </div>
                </div>
            </section>

            <!-- Vigilantes -->
            <section style="padding: 1.5rem; border-bottom: 1px solid var(--border-color);">
                <h3 style="color: var(--primary-color); margin-bottom: 1rem;">
                    <i class="fas fa-user-shield"></i> Control de Vigilancia
                </h3>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem;">
                    <div>
                        <label style="font-weight: bold; color: var(--text-muted); display: block; margin-bottom: 0.25rem;">Vigilante Entrada:</label>
                        <p style="margin: 0;"><?= e($registro['vigilante_entrada_nombre'] ?? 'No registrado') ?></p>
                    </div>
                    <div>
                        <label style="font-weight: bold; color: var(--text-muted); display: block; margin-bottom: 0.25rem;">Vigilante Salida:</label>
                        <p style="margin: 0;"><?= e($registro['vigilante_salida_nombre'] ?? '-') ?></p>
                    </div>
                </div>
            </section>

            <!-- Observaciones -->
            <?php if ($registro['observaciones']): ?>
            <section style="padding: 1.5rem;">
                <h3 style="color: var(--primary-color); margin-bottom: 1rem;">
                    <i class="fas fa-sticky-note"></i> Observaciones
                </h3>
                <div style="background: var(--bg-color); padding: 1rem; border-radius: 8px; border-left: 4px solid var(--primary-color);">
                    <p style="margin: 0; white-space: pre-wrap;"><?= e($registro['observaciones']) ?></p>
                </div>
            </section>
            <?php endif; ?>

            <!-- Botones de acción -->
            <?php if ($registro['estado'] === 'DENTRO'): ?>
            <section style="padding: 1.5rem; border-top: 1px solid var(--border-color);">
                <form method="POST" action="<?= baseUrl('/acceso-externo/registrar-salida/' . $registro['id']) ?>" 
                      onsubmit="return confirm('¿Confirmar salida de <?= e($registro['nombre_completo']) ?>?')">
                    <input type="hidden" name="csrf_token" value="<?= generateCSRFToken() ?>">
                    
                    <div class="form-group" style="margin-bottom: 1rem;">
                        <label for="observaciones">Observaciones de Salida (Opcional):</label>
                        <textarea id="observaciones" name="observaciones" class="form-control" rows="2" 
                                  placeholder="Agregar observaciones..."></textarea>
                    </div>
                    
                    <button type="submit" class="btn btn-primary" style="background: var(--success-color);">
                        <i class="fas fa-sign-out-alt"></i> Registrar Salida
                    </button>
                </form>
            </section>
            <?php endif; ?>
        </div>

    </div>
</div>

<?php require_once APP_PATH . '/views/layouts/footer.php'; ?>
