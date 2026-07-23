-- ========================================
-- MÓDULO DE USUARIOS - SISTEMA SENA
-- Extensión del esquema para gestión completa
-- ========================================

USE sistema_ingreso;

-- 1. Alterar tabla usuarios para soportar personas externas
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
    ADD COLUMN deleted_at TIMESTAMP NULL AFTER updated_by,
    ADD INDEX idx_documento (documento),
    ADD INDEX idx_tipo_persona (tipo_persona),
    ADD INDEX idx_empresa (empresa),
    ADD INDEX idx_deleted (deleted_at),
    ADD FOREIGN KEY fk_created_by (created_by) REFERENCES usuarios(id) ON DELETE SET NULL,
    ADD FOREIGN KEY fk_updated_by (updated_by) REFERENCES usuarios(id) ON DELETE SET NULL;

-- 2. Tabla de auditoría de cambios en usuarios
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

-- 3. Tabla de importaciones masivas (tracking)
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

-- 4. Actualizar usuarios existentes con documento (si no tienen)
-- NOTA: Ejecutar manualmente después de crear la columna
-- UPDATE usuarios SET documento = CONCAT('DOC', LPAD(id, 8, '0')) WHERE documento IS NULL OR documento = '';

-- 5. Índices compuestos para búsquedas optimizadas
CREATE INDEX idx_search_usuarios ON usuarios(documento, nombre, tipo_persona, estado, deleted_at);
CREATE INDEX idx_active_users ON usuarios(estado, deleted_at);

-- 6. Vista para usuarios activos (facilita consultas)
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
-- COMENTARIOS Y REGLAS DE NEGOCIO
-- ========================================

/*
TIPOS DE PERSONA:
- admin, instructor, vigilante: Personal del sistema con login
- aprendiz: Estudiantes SENA (pueden tener o no login según necesidad)
- contratista, visitante, proveedor: Personal externo sin login

ROL DEL SISTEMA:
- admin: Acceso total
- instructor: Puede ver usuarios, NO puede crear/editar/eliminar
- vigilante: Puede ver usuarios, NO puede crear/editar/eliminar
- persona: Para aprendices/externos sin privilegios del sistema

REGLAS:
1. documento debe ser ÚNICO
2. Para tipos admin/instructor/vigilante: username y password son OBLIGATORIOS
3. Para aprendices/externos: username y password son OPCIONALES
4. email es OPCIONAL para todos
5. Borrado lógico: deleted_at se establece en lugar de DELETE físico
6. Auditoría: todas las operaciones CUD se registran en auditoria_usuarios
*/
