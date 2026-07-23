-- =========================================================
-- MÓDULO DE PERMISOS DE SALIDA
-- =========================================================

USE sistema_ingreso;

-- Tabla de permisos de salida
CREATE TABLE IF NOT EXISTS permisos_salida (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    documento_aprendiz VARCHAR(30) NOT NULL,
    nombre_aprendiz VARCHAR(255) NOT NULL,
    fecha_permiso DATE NOT NULL,
    hora_salida TIME NOT NULL,
    hora_regreso TIME NULL,
    motivo TEXT NOT NULL,
    instructor_id INT UNSIGNED NOT NULL,
    instructor_nombre VARCHAR(100) NOT NULL,
    estado ENUM('ACTIVO', 'USADO', 'VENCIDO', 'CANCELADO') NOT NULL DEFAULT 'ACTIVO',
    usado_por INT UNSIGNED NULL COMMENT 'Usuario vigilante que validó la salida',
    fecha_uso DATETIME NULL,
    observaciones TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    INDEX idx_documento (documento_aprendiz),
    INDEX idx_fecha (fecha_permiso),
    INDEX idx_estado (estado),
    INDEX idx_instructor (instructor_id),
    
    FOREIGN KEY (instructor_id) REFERENCES usuarios(id) ON DELETE RESTRICT,
    FOREIGN KEY (usado_por) REFERENCES usuarios(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insertar permisos de ejemplo
INSERT INTO permisos_salida (
    documento_aprendiz, 
    nombre_aprendiz, 
    fecha_permiso, 
    hora_salida, 
    hora_regreso, 
    motivo, 
    instructor_id, 
    instructor_nombre,
    estado
) VALUES 
(
    '1234567890', 
    'Juan Pérez García', 
    CURDATE(), 
    '14:00:00', 
    '16:00:00', 
    'Cita médica urgente', 
    2, 
    'Instructor',
    'ACTIVO'
),
(
    '0987654321', 
    'María Rodríguez López', 
    CURDATE(), 
    '10:30:00', 
    '12:00:00', 
    'Trámite bancario', 
    2, 
    'Instructor',
    'ACTIVO'
);

SELECT 'Tabla permisos_salida creada exitosamente' AS mensaje;
