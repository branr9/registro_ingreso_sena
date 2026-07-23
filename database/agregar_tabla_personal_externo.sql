-- SQL para agregar tabla de Personal Externo
-- Ejecutar este script en phpMyAdmin

-- Tabla para registros de acceso de personal externo
CREATE TABLE IF NOT EXISTS `registros_acceso_externo` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    
    -- Datos del visitante
    `documento` VARCHAR(20) NOT NULL,
    `tipo_documento` ENUM('CC', 'CE', 'TI', 'PAS', 'NIT') DEFAULT 'CC',
    `nombres` VARCHAR(100) NOT NULL,
    `apellidos` VARCHAR(100),
    `empresa` VARCHAR(150),
    `telefono` VARCHAR(20),
    `email` VARCHAR(150),
    
    -- Motivo de la visita
    `motivo_visita` VARCHAR(255) NOT NULL,
    `persona_visitada` VARCHAR(150),
    `area_destino` VARCHAR(100),
    
    -- Control de entrada/salida
    `fecha_entrada` DATETIME NOT NULL,
    `fecha_salida` DATETIME NULL,
    `tiempo_permanencia` INT NULL COMMENT 'Minutos de permanencia',
    
    -- Vigilante que registra
    `vigilante_entrada_id` BIGINT UNSIGNED,
    `vigilante_salida_id` BIGINT UNSIGNED NULL,
    
    -- Observaciones
    `observaciones` TEXT,
    `estado` ENUM('DENTRO', 'SALIO') DEFAULT 'DENTRO',
    
    -- Auditoría
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    -- Índices
    INDEX `idx_documento` (`documento`),
    INDEX `idx_fecha_entrada` (`fecha_entrada`),
    INDEX `idx_estado` (`estado`),
    INDEX `idx_empresa` (`empresa`),
    
    -- Foreign keys
    FOREIGN KEY (`vigilante_entrada_id`) REFERENCES `usuarios_sistema`(`id`) ON DELETE SET NULL,
    FOREIGN KEY (`vigilante_salida_id`) REFERENCES `usuarios_sistema`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Vista para consultas rápidas de acceso externo
DROP VIEW IF EXISTS `vista_acceso_externo`;

CREATE VIEW `vista_acceso_externo` AS
SELECT 
    rae.*,
    CONCAT(rae.nombres, ' ', COALESCE(rae.apellidos, '')) as nombre_completo,
    CONCAT(pe.nombres, ' ', COALESCE(pe.apellidos, '')) as vigilante_entrada_nombre,
    CONCAT(ps.nombres, ' ', COALESCE(ps.apellidos, '')) as vigilante_salida_nombre,
    TIMESTAMPDIFF(MINUTE, rae.fecha_entrada, COALESCE(rae.fecha_salida, NOW())) as minutos_transcurridos
FROM registros_acceso_externo rae
LEFT JOIN usuarios_sistema use1 ON rae.vigilante_entrada_id = use1.id
LEFT JOIN personas pe ON use1.persona_id = pe.id
LEFT JOIN usuarios_sistema use2 ON rae.vigilante_salida_id = use2.id
LEFT JOIN personas ps ON use2.persona_id = ps.id
ORDER BY rae.fecha_entrada DESC;

-- Insertar datos de ejemplo (opcional)
INSERT INTO `registros_acceso_externo` (
    `documento`, `tipo_documento`, `nombres`, `apellidos`, `empresa`, `telefono`, `email`,
    `motivo_visita`, `persona_visitada`, `area_destino`,
    `fecha_entrada`, `vigilante_entrada_id`, `estado`
) VALUES 
(
    '9999999999', 'CC', 'Pedro', 'González', 'Empresa Demo', '3001234567', 'pedro@empresa.com',
    'Reunión administrativa', 'Coordinación', 'Área administrativa',
    NOW(), 2, 'DENTRO'
);
