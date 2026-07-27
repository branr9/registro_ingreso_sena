-- Registro de importaciones masivas del módulo de usuarios.
-- Esta tabla también se crea automáticamente al terminar una importación
-- para mantener compatibilidad con instalaciones ya existentes.
CREATE TABLE IF NOT EXISTS importaciones (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    archivo_nombre VARCHAR(255) NOT NULL,
    tipo VARCHAR(30) NOT NULL,
    usuario_id BIGINT UNSIGNED NOT NULL,
    total_filas INT UNSIGNED NOT NULL DEFAULT 0,
    insertados INT UNSIGNED NOT NULL DEFAULT 0,
    actualizados INT UNSIGNED NOT NULL DEFAULT 0,
    omitidos INT UNSIGNED NOT NULL DEFAULT 0,
    errores INT UNSIGNED NOT NULL DEFAULT 0,
    estado VARCHAR(20) NOT NULL DEFAULT 'pendiente',
    log_errores LONGTEXT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    completed_at DATETIME NULL,
    INDEX idx_importaciones_usuario (usuario_id),
    INDEX idx_importaciones_fecha (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
