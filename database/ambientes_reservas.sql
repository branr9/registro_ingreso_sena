-- Módulo AMBIENTES. Compatible con la estructura anticipada en
-- migracion_schema_completo.sql y con la base activa del proyecto.

CREATE TABLE IF NOT EXISTS ambientes (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    codigo VARCHAR(40) NOT NULL,
    nombre VARCHAR(120) NOT NULL,
    tipo ENUM('SALA','LABORATORIO','AUDITORIO') NOT NULL,
    ubicacion VARCHAR(160) NULL,
    capacidad SMALLINT UNSIGNED NULL,
    estado ENUM('DISPONIBLE','EN_USO','MANTENIMIENTO','INACTIVO') NOT NULL DEFAULT 'DISPONIBLE',
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uq_ambiente_codigo (codigo),
    KEY idx_ambiente_tipo (tipo),
    KEY idx_ambiente_estado (estado)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS reservas (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    ambiente_id BIGINT UNSIGNED NOT NULL,
    solicitante_id BIGINT UNSIGNED NULL,
    responsable_nombre VARCHAR(150) NOT NULL,
    responsable_documento VARCHAR(30) NULL,
    creado_por BIGINT UNSIGNED NULL COMMENT 'Usuario del sistema que registró la reserva',
    titulo VARCHAR(160) NULL,
    descripcion VARCHAR(255) NULL,
    inicio DATETIME NOT NULL,
    fin DATETIME NOT NULL,
    estado ENUM('CREADA','CANCELADA','FINALIZADA') NOT NULL DEFAULT 'CREADA',
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    KEY idx_reserva_ambiente (ambiente_id, inicio, fin),
    KEY idx_reserva_solicitante (solicitante_id),
    KEY idx_reserva_creado_por (creado_por),
    KEY idx_reserva_estado (estado),
    CONSTRAINT fk_reserva_ambiente FOREIGN KEY (ambiente_id) REFERENCES ambientes(id) ON DELETE CASCADE,
    CONSTRAINT fk_reserva_solicitante FOREIGN KEY (solicitante_id) REFERENCES personas(id) ON DELETE RESTRICT,
    CONSTRAINT chk_reserva_horario CHECK (fin > inicio)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Actualización compatible para instalaciones que ya tenían las tablas.
ALTER TABLE reservas
    MODIFY COLUMN solicitante_id BIGINT UNSIGNED NULL,
    ADD COLUMN IF NOT EXISTS responsable_nombre VARCHAR(150) NULL AFTER solicitante_id,
    ADD COLUMN IF NOT EXISTS responsable_documento VARCHAR(30) NULL AFTER responsable_nombre,
    ADD COLUMN IF NOT EXISTS creado_por BIGINT UNSIGNED NULL COMMENT 'Usuario del sistema que registró la reserva' AFTER responsable_documento;

UPDATE reservas r
LEFT JOIN personas p ON p.id = r.solicitante_id
SET r.responsable_nombre = COALESCE(
        NULLIF(r.responsable_nombre, ''),
        NULLIF(TRIM(CONCAT_WS(' ', p.nombres, p.apellidos)), ''),
        'Responsable no registrado'
    ),
    r.responsable_documento = COALESCE(r.responsable_documento, p.documento)
WHERE r.responsable_nombre IS NULL OR r.responsable_nombre = '';

-- Conserva la propiedad de las reservas antiguas para que el usuario que
-- estaba asociado como solicitante pueda seguir administrándolas.
UPDATE reservas r
LEFT JOIN usuarios_sistema us ON us.persona_id = r.solicitante_id
SET r.creado_por = us.id
WHERE r.creado_por IS NULL AND us.id IS NOT NULL;

ALTER TABLE reservas
    MODIFY COLUMN responsable_nombre VARCHAR(150) NOT NULL;
