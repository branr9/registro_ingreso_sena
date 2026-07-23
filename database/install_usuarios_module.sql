-- ========================================
-- INSTALACIÓN DEL MÓDULO DE USUARIOS
-- Sistema de Control de Ingreso SENA
-- ========================================

-- IMPORTANTE: Ejecutar después de tener la base de datos inicial
-- Si ya tienes datos en 'usuarios', haz backup primero

USE senaaccses; -- O sistema_ingreso según tu configuración

-- ========================================
-- PASO 1: Verificar y alterar tabla usuarios
-- ========================================

-- Verificar si la columna 'documento' ya existe
SELECT COUNT(*) 
FROM INFORMATION_SCHEMA.COLUMNS 
WHERE TABLE_SCHEMA = 'senaaccses' 
  AND TABLE_NAME = 'usuarios' 
  AND COLUMN_NAME = 'documento';

-- Si NO existe (resultado = 0), ejecutar este bloque:
ALTER TABLE usuarios
    ADD COLUMN documento VARCHAR(20) UNIQUE NOT NULL AFTER id,
    ADD COLUMN tipo_persona ENUM('admin', 'instructor', 'vigilante', 'aprendiz', 'contratista', 'visitante', 'proveedor') NOT NULL DEFAULT 'aprendiz' AFTER nombre,
    ADD COLUMN empresa VARCHAR(150) NULL AFTER tipo_persona,
    MODIFY COLUMN email VARCHAR(150) NULL,
    MODIFY COLUMN username VARCHAR(50) NULL,
    MODIFY COLUMN password_hash VARCHAR(255) NULL,
    MODIFY COLUMN rol ENUM('admin', 'instructor', 'vigilante', 'persona') NOT NULL DEFAULT 'persona',
    ADD COLUMN created_by INT UNSIGNED NULL AFTER created_at,
    ADD COLUMN updated_by INT UNSIGNED NULL AFTER updated_at,
    ADD COLUMN deleted_at TIMESTAMP NULL AFTER updated_by;

-- Agregar índices
ALTER TABLE usuarios
    ADD INDEX idx_documento (documento),
    ADD INDEX idx_tipo_persona (tipo_persona),
    ADD INDEX idx_empresa (empresa),
    ADD INDEX idx_deleted (deleted_at);

-- Agregar foreign keys
ALTER TABLE usuarios
    ADD CONSTRAINT fk_created_by FOREIGN KEY (created_by) REFERENCES usuarios(id) ON DELETE SET NULL,
    ADD CONSTRAINT fk_updated_by FOREIGN KEY (updated_by) REFERENCES usuarios(id) ON DELETE SET NULL;

-- ========================================
-- PASO 2: Actualizar usuarios existentes
-- ========================================

-- IMPORTANTE: Si ya tienes usuarios sin documento, asigna uno:
UPDATE usuarios 
SET documento = CONCAT('DOC', LPAD(id, 8, '0'))
WHERE documento IS NULL OR documento = '';

-- Actualizar tipo_persona según el rol actual
UPDATE usuarios SET tipo_persona = 'admin' WHERE rol = 'admin';
UPDATE usuarios SET tipo_persona = 'instructor' WHERE rol = 'instructor';
UPDATE usuarios SET tipo_persona = 'vigilante' WHERE rol = 'vigilante';

-- ========================================
-- PASO 3: Crear tabla de auditoría
-- ========================================

CREATE TABLE IF NOT EXISTS auditoria_usuarios (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT UNSIGNED NOT NULL,
    accion ENUM('crear', 'editar', 'eliminar', 'activar', 'desactivar', 'cambio_password') NOT NULL,
    usuario_ejecutor_id INT UNSIGNED NULL,
    datos_anteriores TEXT NULL COMMENT 'JSON con datos antes del cambio',
    datos_nuevos TEXT NULL COMMENT 'JSON con datos después del cambio',
    ip_address VARCHAR(45) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    FOREIGN KEY (usuario_ejecutor_id) REFERENCES usuarios(id) ON DELETE SET NULL,
    INDEX idx_usuario (usuario_id),
    INDEX idx_accion (accion),
    INDEX idx_fecha (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ========================================
-- PASO 4: Crear tabla de importaciones
-- ========================================

CREATE TABLE IF NOT EXISTS importaciones (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    archivo_nombre VARCHAR(255) NOT NULL,
    tipo ENUM('usuarios', 'aprendices', 'instructores') NOT NULL,
    usuario_id INT UNSIGNED NOT NULL,
    total_filas INT UNSIGNED DEFAULT 0,
    insertados INT UNSIGNED DEFAULT 0,
    actualizados INT UNSIGNED DEFAULT 0,
    omitidos INT UNSIGNED DEFAULT 0,
    errores INT UNSIGNED DEFAULT 0,
    estado ENUM('pendiente', 'procesando', 'completado', 'error') NOT NULL DEFAULT 'pendiente',
    log_errores TEXT NULL COMMENT 'JSON con errores detallados',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    completed_at TIMESTAMP NULL,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    INDEX idx_usuario (usuario_id),
    INDEX idx_estado (estado),
    INDEX idx_fecha (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ========================================
-- PASO 5: Crear índices compuestos
-- ========================================

CREATE INDEX idx_search_usuarios ON usuarios(documento, nombre, tipo_persona, estado, deleted_at);
CREATE INDEX idx_active_users ON usuarios(estado, deleted_at);

-- ========================================
-- PASO 6: Crear vista de usuarios activos
-- ========================================

CREATE OR REPLACE VIEW v_usuarios_activos AS
SELECT 
    u.id,
    u.documento,
    u.nombre,
    u.tipo_persona,
    u.empresa,
    u.email,
    u.username,
    u.rol,
    u.estado,
    u.ultimo_acceso,
    u.created_at,
    u.updated_at,
    creador.nombre AS creado_por,
    actualizador.nombre AS actualizado_por
FROM usuarios u
LEFT JOIN usuarios creador ON u.created_by = creador.id
LEFT JOIN usuarios actualizador ON u.updated_by = actualizador.id
WHERE u.deleted_at IS NULL;

-- ========================================
-- VERIFICACIÓN FINAL
-- ========================================

-- Mostrar estructura de la tabla usuarios
DESCRIBE usuarios;

-- Contar registros
SELECT 
    'Total usuarios' AS descripcion,
    COUNT(*) AS cantidad
FROM usuarios
WHERE deleted_at IS NULL
UNION ALL
SELECT 
    'Usuarios eliminados',
    COUNT(*)
FROM usuarios
WHERE deleted_at IS NOT NULL;

-- Verificar tablas creadas
SHOW TABLES LIKE '%usuarios%';
SHOW TABLES LIKE 'importaciones';

SELECT '✅ Instalación del módulo de usuarios completada exitosamente!' AS resultado;
