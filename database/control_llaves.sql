-- ========================================
-- MÓDULO DE CONTROL DE LLAVES
-- ========================================

-- Tabla de aulas
CREATE TABLE IF NOT EXISTS aulas (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL UNIQUE,
    capacidad INT UNSIGNED NOT NULL DEFAULT 0 COMMENT 'Capacidad de personas',
    cantidad_llaves INT UNSIGNED NOT NULL DEFAULT 1 COMMENT 'Número de juegos de llaves disponibles',
    estado ENUM('ACTIVO', 'INACTIVO') DEFAULT 'ACTIVO',
    observaciones TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_estado (estado),
    INDEX idx_nombre (nombre)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla de préstamos de llaves
CREATE TABLE IF NOT EXISTS prestamos_llaves (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    aula_id BIGINT UNSIGNED NOT NULL,
    usuario_id BIGINT UNSIGNED NOT NULL COMMENT 'ID del usuario (persona_id)',
    fecha_prestamo TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    fecha_devolucion TIMESTAMP NULL,
    estado ENUM('PRESTADO', 'DEVUELTO', 'VENCIDO') DEFAULT 'PRESTADO',
    observaciones_prestamo TEXT NULL,
    observaciones_devolucion TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (aula_id) REFERENCES aulas(id) ON DELETE CASCADE,
    FOREIGN KEY (usuario_id) REFERENCES personas(id) ON DELETE CASCADE,
    
    INDEX idx_aula (aula_id),
    INDEX idx_usuario (usuario_id),
    INDEX idx_estado (estado),
    INDEX idx_fecha_prestamo (fecha_prestamo),
    INDEX idx_fecha_devolucion (fecha_devolucion)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insertar datos de ejemplo
INSERT INTO aulas (nombre, capacidad, cantidad_llaves, observaciones) VALUES
('Aula 101', 30, 2, 'Aula de sistemas'),
('Aula 102', 25, 1, 'Aula de inglés'),
('Laboratorio A', 20, 2, 'Laboratorio de electrónica'),
('Taller B', 15, 1, 'Taller de mecánica');
