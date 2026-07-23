/* =========================================================
   MIGRACIÓN: senaaccses → sena_control (Schema Completo)
   Fecha: Enero 2026
   ========================================================= */

-- PASO 1: Crear respaldo de datos actuales
CREATE DATABASE IF NOT EXISTS senaaccses_backup;

USE senaaccses;

-- Backup de tablas actuales
CREATE TABLE IF NOT EXISTS senaaccses_backup.usuarios_backup AS SELECT * FROM usuarios;
CREATE TABLE IF NOT EXISTS senaaccses_backup.auditoria_accesos_backup AS SELECT * FROM auditoria_accesos;
CREATE TABLE IF NOT EXISTS senaaccses_backup.sesiones_backup AS SELECT * FROM sesiones;

SELECT '✅ PASO 1: Backup completado en senaaccses_backup' AS status;

-- PASO 2: Crear nueva base de datos con schema completo
/* =========================================================
   BD: Control de Ingreso y Ambientes - SENA
   Motor: MySQL 8+ | Charset: utf8mb4 | InnoDB
   ========================================================= */


CREATE DATABASE IF NOT EXISTS sena_control
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;
USE sena_control;

SET sql_safe_updates = 0;

/* =========================================================
   1) Catálogos básicos
   ========================================================= */

CREATE TABLE IF NOT EXISTS cat_persona_tipo (
  id TINYINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  codigo VARCHAR(30) NOT NULL UNIQUE,
  nombre VARCHAR(80) NOT NULL
) ENGINE=InnoDB;

INSERT IGNORE INTO cat_persona_tipo (codigo, nombre) VALUES
('aprendiz','Aprendiz'),
('instructor','Instructor'),
('vigilante','Vigilante'),
('contratista','Contratista'),
('visitante','Visitante'),
('proveedor','Proveedor'),
('aseo','Personal de Aseo'),
('interno','Personal Interno');

CREATE TABLE IF NOT EXISTS cat_rol_sistema (
  id TINYINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  codigo VARCHAR(30) NOT NULL UNIQUE,
  nombre VARCHAR(80) NOT NULL
) ENGINE=InnoDB;

INSERT IGNORE INTO cat_rol_sistema (codigo, nombre) VALUES
('admin','Administrador'),
('instructor','Instructor'),
('vigilante','Vigilante');

CREATE TABLE IF NOT EXISTS cat_motivo (
  id SMALLINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  tipo ENUM('INGRESO_SALIDA','PERMISO_SALIDA','OTRO') NOT NULL DEFAULT 'OTRO',
  nombre VARCHAR(120) NOT NULL,
  activo TINYINT(1) NOT NULL DEFAULT 1,
  UNIQUE KEY uq_motivo (tipo, nombre)
) ENGINE=InnoDB;

INSERT IGNORE INTO cat_motivo (tipo, nombre) VALUES
('INGRESO_SALIDA','Ingreso normal'),
('INGRESO_SALIDA','Salida normal'),
('PERMISO_SALIDA','Cita médica'),
('PERMISO_SALIDA','Diligencia personal'),
('PERMISO_SALIDA','Emergencia familiar'),
('PERMISO_SALIDA','Otro');

/* =========================================================
   2) Personas
   ========================================================= */

CREATE TABLE IF NOT EXISTS personas (
  id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  documento VARCHAR(30) NOT NULL,
  nombres VARCHAR(120) NOT NULL,
  apellidos VARCHAR(120) NULL,
  tipo_persona_id TINYINT UNSIGNED NOT NULL,
  empresa VARCHAR(160) NULL,
  telefono VARCHAR(40) NULL,
  email VARCHAR(160) NULL,
  estado ENUM('ACTIVO','INACTIVO') NOT NULL DEFAULT 'ACTIVO',
  creado_por BIGINT UNSIGNED NULL,
  actualizado_por BIGINT UNSIGNED NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  deleted_at DATETIME NULL,

  UNIQUE KEY uq_persona_documento (documento),
  KEY idx_persona_tipo (tipo_persona_id),
  KEY idx_persona_estado (estado),
  KEY idx_persona_deleted (deleted_at),

  CONSTRAINT fk_persona_tipo
    FOREIGN KEY (tipo_persona_id) REFERENCES cat_persona_tipo(id)
) ENGINE=InnoDB;



/* =========================================================
   3) Usuarios Sistema
   ========================================================= */

CREATE TABLE IF NOT EXISTS usuarios_sistema (
  id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  persona_id BIGINT UNSIGNED NOT NULL,
  rol_id TINYINT UNSIGNED NOT NULL,
  username VARCHAR(80) NOT NULL,
  password_hash VARCHAR(255) NOT NULL,
  estado ENUM('ACTIVO','INACTIVO') NOT NULL DEFAULT 'ACTIVO',
  intentos_fallidos TINYINT UNSIGNED DEFAULT 0,
  bloqueado_hasta DATETIME NULL,
  last_login_at DATETIME NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,

  UNIQUE KEY uq_user_username (username),
  UNIQUE KEY uq_user_persona (persona_id),
  KEY idx_user_rol (rol_id),
  KEY idx_user_estado (estado),

  CONSTRAINT fk_user_persona
    FOREIGN KEY (persona_id) REFERENCES personas(id)
    ON DELETE CASCADE,
  CONSTRAINT fk_user_rol
    FOREIGN KEY (rol_id) REFERENCES cat_rol_sistema(id)
) ENGINE=InnoDB;

/* =========================================================
   4) Dispositivos biométricos
   ========================================================= */

CREATE TABLE IF NOT EXISTS dispositivos (
  id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  nombre VARCHAR(120) NOT NULL,
  tipo ENUM('HUELLA','OTRO') NOT NULL DEFAULT 'HUELLA',
  marca VARCHAR(80) NULL,
  modelo VARCHAR(80) NULL,
  serial VARCHAR(120) NULL,
  ubicacion VARCHAR(160) NULL,
  estado ENUM('ACTIVO','INACTIVO') NOT NULL DEFAULT 'ACTIVO',
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY uq_dispositivo_serial (serial)
) ENGINE=InnoDB;

/* =========================================================
   5) Huellas
   ========================================================= */

CREATE TABLE IF NOT EXISTS huellas (
  id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  persona_id BIGINT UNSIGNED NOT NULL,
  dispositivo_id BIGINT UNSIGNED NULL,
  template LONGBLOB NOT NULL,
  template_version VARCHAR(40) NULL,
  activo TINYINT(1) NOT NULL DEFAULT 1,
  enrolled_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

  UNIQUE KEY uq_huella_persona (persona_id),
  KEY idx_huella_dispositivo (dispositivo_id),

  CONSTRAINT fk_huella_persona
    FOREIGN KEY (persona_id) REFERENCES personas(id)
    ON DELETE CASCADE,
  CONSTRAINT fk_huella_dispositivo
    FOREIGN KEY (dispositivo_id) REFERENCES dispositivos(id)
    ON DELETE SET NULL
) ENGINE=InnoDB;

/* =========================================================
   6) Marcaciones
   ========================================================= */

CREATE TABLE IF NOT EXISTS marcaciones (
  id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  persona_id BIGINT UNSIGNED NULL,
  dispositivo_id BIGINT UNSIGNED NULL,
  tipo_evento ENUM('ENTRADA','SALIDA') NOT NULL,
  metodo ENUM('HUELLA','MANUAL') NOT NULL DEFAULT 'HUELLA',
  motivo_id SMALLINT UNSIGNED NULL,
  empresa VARCHAR(160) NULL,
  documento_capturado VARCHAR(30) NULL,
  nombre_capturado VARCHAR(240) NULL,

  exitoso TINYINT(1) NOT NULL DEFAULT 1,
  mensaje VARCHAR(255) NULL,

  registrado_por BIGINT UNSIGNED NULL,
  fecha_hora DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

  KEY idx_marc_persona_fecha (persona_id, fecha_hora),
  KEY idx_marc_fecha (fecha_hora),
  KEY idx_marc_tipo (tipo_evento),
  KEY idx_marc_exitoso (exitoso),

  CONSTRAINT fk_marc_persona
    FOREIGN KEY (persona_id) REFERENCES personas(id)
    ON DELETE SET NULL,
  CONSTRAINT fk_marc_dispositivo
    FOREIGN KEY (dispositivo_id) REFERENCES dispositivos(id)
    ON DELETE SET NULL,
  CONSTRAINT fk_marc_motivo
    FOREIGN KEY (motivo_id) REFERENCES cat_motivo(id)
    ON DELETE SET NULL,
  CONSTRAINT fk_marc_registrado_por
    FOREIGN KEY (registrado_por) REFERENCES personas(id)
    ON DELETE SET NULL
) ENGINE=InnoDB;

/* =========================================================
   7) Ambientes y Reservas
   ========================================================= */

CREATE TABLE IF NOT EXISTS ambientes (
  id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
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
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS reservas (
  id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  ambiente_id BIGINT UNSIGNED NOT NULL,
  solicitante_id BIGINT UNSIGNED NOT NULL,
  titulo VARCHAR(160) NULL,
  descripcion VARCHAR(255) NULL,
  inicio DATETIME NOT NULL,
  fin DATETIME NOT NULL,
  estado ENUM('CREADA','CANCELADA','FINALIZADA') NOT NULL DEFAULT 'CREADA',
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

  KEY idx_reserva_ambiente (ambiente_id, inicio, fin),
  KEY idx_reserva_solicitante (solicitante_id),
  KEY idx_reserva_estado (estado),

  CONSTRAINT fk_reserva_ambiente
    FOREIGN KEY (ambiente_id) REFERENCES ambientes(id)
    ON DELETE CASCADE,
  CONSTRAINT fk_reserva_solicitante
    FOREIGN KEY (solicitante_id) REFERENCES personas(id)
    ON DELETE RESTRICT,

  CHECK (fin > inicio)
) ENGINE=InnoDB;

/* =========================================================
   8) Llaves y Préstamos
   ========================================================= */

CREATE TABLE IF NOT EXISTS llaves (
  id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  ambiente_id BIGINT UNSIGNED NOT NULL,
  etiqueta VARCHAR(60) NOT NULL,
  estado ENUM('DISPONIBLE','PRESTADA','INACTIVA') NOT NULL DEFAULT 'DISPONIBLE',
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

  UNIQUE KEY uq_llave_etiqueta (etiqueta),
  KEY idx_llave_ambiente (ambiente_id),
  KEY idx_llave_estado (estado),

  CONSTRAINT fk_llave_ambiente
    FOREIGN KEY (ambiente_id) REFERENCES ambientes(id)
    ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS prestamos_llaves (
  id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  llave_id BIGINT UNSIGNED NOT NULL,
  responsable_id BIGINT UNSIGNED NOT NULL,
  prestada_por BIGINT UNSIGNED NULL,
  recibida_por BIGINT UNSIGNED NULL,

  fecha_prestamo DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  fecha_devolucion DATETIME NULL,
  estado ENUM('ABIERTA','DEVUELTA','ATRASADA','CERRADA') NOT NULL DEFAULT 'ABIERTA',
  observaciones VARCHAR(255) NULL,

  KEY idx_prestamo_llave (llave_id, estado),
  KEY idx_prestamo_responsable (responsable_id),
  KEY idx_prestamo_fechas (fecha_prestamo, fecha_devolucion),

  CONSTRAINT fk_prestamo_llave
    FOREIGN KEY (llave_id) REFERENCES llaves(id)
    ON DELETE RESTRICT,
  CONSTRAINT fk_prestamo_responsable
    FOREIGN KEY (responsable_id) REFERENCES personas(id)
    ON DELETE RESTRICT,
  CONSTRAINT fk_prestada_por
    FOREIGN KEY (prestada_por) REFERENCES personas(id)
    ON DELETE SET NULL,
  CONSTRAINT fk_recibida_por
    FOREIGN KEY (recibida_por) REFERENCES personas(id)
    ON DELETE SET NULL
) ENGINE=InnoDB;

/* =========================================================
   9) Permisos de Salida
   ========================================================= */

CREATE TABLE IF NOT EXISTS permisos_salida (
  id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  aprendiz_id BIGINT UNSIGNED NOT NULL,
  instructor_id BIGINT UNSIGNED NOT NULL,

  fecha DATE NOT NULL,
  hora_salida DATETIME NOT NULL,
  hora_regreso_programada DATETIME NULL,
  hora_regreso_real DATETIME NULL,

  motivo_id SMALLINT UNSIGNED NULL,
  motivo_texto VARCHAR(255) NULL,
  estado ENUM('CREADO','USADO','VENCIDO','CERRADO','ANULADO') NOT NULL DEFAULT 'CREADO',

  validado_por BIGINT UNSIGNED NULL,
  validado_en DATETIME NULL,

  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

  KEY idx_perm_aprendiz (aprendiz_id, fecha),
  KEY idx_perm_estado (estado),
  KEY idx_perm_salida (hora_salida),
  KEY idx_perm_instructor (instructor_id),

  CONSTRAINT fk_perm_aprendiz
    FOREIGN KEY (aprendiz_id) REFERENCES personas(id)
    ON DELETE RESTRICT,
  CONSTRAINT fk_perm_instructor
    FOREIGN KEY (instructor_id) REFERENCES personas(id)
    ON DELETE RESTRICT,
  CONSTRAINT fk_perm_motivo
    FOREIGN KEY (motivo_id) REFERENCES cat_motivo(id)
    ON DELETE SET NULL,
  CONSTRAINT fk_perm_validado_por
    FOREIGN KEY (validado_por) REFERENCES personas(id)
    ON DELETE SET NULL
) ENGINE=InnoDB;

/* =========================================================
   10) Auditoría
   ========================================================= */

CREATE TABLE IF NOT EXISTS auditoria (
  id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  actor_persona_id BIGINT UNSIGNED NULL,
  modulo VARCHAR(60) NOT NULL,
  accion VARCHAR(60) NOT NULL,
  entidad VARCHAR(60) NOT NULL,
  entidad_id BIGINT UNSIGNED NULL,
  detalle TEXT NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

  KEY idx_aud_actor (actor_persona_id, created_at),
  KEY idx_aud_modulo (modulo, created_at),

  CONSTRAINT fk_aud_actor
    FOREIGN KEY (actor_persona_id) REFERENCES personas(id)
    ON DELETE SET NULL
) ENGINE=InnoDB;

/* =========================================================
   11) Vistas
   ========================================================= */

CREATE OR REPLACE VIEW vw_marcaciones_diarias AS
SELECT
  DATE(fecha_hora) AS fecha,
  tipo_evento,
  COUNT(*) AS total
FROM marcaciones
WHERE exitoso = 1
GROUP BY DATE(fecha_hora), tipo_evento;

CREATE OR REPLACE VIEW vw_llaves_prestadas AS
SELECT
  pl.id AS prestamo_id,
  l.etiqueta,
  a.codigo AS ambiente_codigo,
  a.nombre AS ambiente_nombre,
  pl.fecha_prestamo,
  pl.estado,
  p.documento,
  CONCAT(p.nombres,' ',IFNULL(p.apellidos,'')) AS responsable
FROM prestamos_llaves pl
JOIN llaves l ON l.id = pl.llave_id
JOIN ambientes a ON a.id = l.ambiente_id
JOIN personas p ON p.id = pl.responsable_id
WHERE pl.estado IN ('ABIERTA','ATRASADA');

SELECT '✅ PASO 2: Schema sena_control creado exitosamente' AS status;


/* =========================================================
   PASO 3: Migrar datos de senaaccses a sena_control
   ========================================================= */

-- LIMPIAR DATOS MIGRADOS ANTERIORES (IMPORTANTE para evitar duplicados)
DELETE FROM sena_control.usuarios_sistema;
DELETE FROM sena_control.personas;
DELETE FROM sena_control.auditoria;

-- Migrar usuarios a personas y usuarios_sistema
INSERT INTO sena_control.personas (documento, nombres, apellidos, tipo_persona_id, email, estado, created_at)
SELECT 
  CONCAT('DOC', LPAD(u.id, 8, '0')) AS documento,
  SUBSTRING_INDEX(u.nombre, ' ', 2) AS nombres,
  SUBSTRING_INDEX(u.nombre, ' ', -2) AS apellidos,
  CASE 
    WHEN u.rol = 'admin' THEN (SELECT id FROM sena_control.cat_persona_tipo WHERE codigo = 'interno')
    WHEN u.rol = 'instructor' THEN (SELECT id FROM sena_control.cat_persona_tipo WHERE codigo = 'instructor')
    WHEN u.rol = 'vigilante' THEN (SELECT id FROM sena_control.cat_persona_tipo WHERE codigo = 'vigilante')
  END AS tipo_persona_id,
  u.email,
  CASE WHEN u.estado = 'activo' THEN 'ACTIVO' ELSE 'INACTIVO' END,
  u.created_at
FROM senaaccses.usuarios u;

-- Crear usuarios_sistema para cada persona migrada
INSERT INTO sena_control.usuarios_sistema (persona_id, rol_id, username, password_hash, estado, intentos_fallidos, bloqueado_hasta, last_login_at, created_at)
SELECT 
  p.id,
  CASE 
    WHEN u.rol = 'admin' THEN (SELECT id FROM sena_control.cat_rol_sistema WHERE codigo = 'admin')
    WHEN u.rol = 'instructor' THEN (SELECT id FROM sena_control.cat_rol_sistema WHERE codigo = 'instructor')
    WHEN u.rol = 'vigilante' THEN (SELECT id FROM sena_control.cat_rol_sistema WHERE codigo = 'vigilante')
  END AS rol_id,
  u.username,
  u.password_hash,
  CASE WHEN u.estado = 'activo' THEN 'ACTIVO' ELSE 'INACTIVO' END,
  u.intentos_fallidos,
  u.bloqueado_hasta,
  u.ultimo_acceso,
  u.created_at
FROM senaaccses.usuarios u
JOIN sena_control.personas p ON p.documento = CONCAT('DOC', LPAD(u.id, 8, '0'));

-- Migrar auditoría de accesos
INSERT INTO sena_control.auditoria (actor_persona_id, modulo, accion, entidad, entidad_id, detalle, created_at)
SELECT 
  p.id,
  'autenticacion',
  CASE 
    WHEN aa.accion = 'login_exitoso' THEN 'login_exitoso'
    WHEN aa.accion = 'login_fallido' THEN 'login_fallido'
    WHEN aa.accion = 'logout' THEN 'logout'
    WHEN aa.accion = 'sesion_expirada' THEN 'sesion_expirada'
  END,
  'usuarios_sistema',
  us.id,
  CONCAT('{"ip_address":"', aa.ip_address, '","user_agent":"', REPLACE(aa.user_agent, '"', '\\"'), '","detalles":"', IFNULL(aa.detalles, ''), '"}'),
  aa.created_at
FROM senaaccses.auditoria_accesos aa
LEFT JOIN senaaccses.usuarios u ON aa.usuario_id = u.id
LEFT JOIN sena_control.personas p ON p.documento = CONCAT('DOC', LPAD(u.id, 8, '0'))
LEFT JOIN sena_control.usuarios_sistema us ON us.persona_id = p.id;

SELECT '✅ PASO 3: Datos migrados exitosamente' AS status;

/* =========================================================
   PASO 4: Verificación
   ========================================================= */

SELECT 'Verificación de Migración:' AS titulo;

SELECT 
    'Personas' AS tabla,
    COUNT(*) AS registros
FROM personas
UNION ALL
SELECT 
    'Usuarios Sistema',
    COUNT(*)
FROM usuarios_sistema
UNION ALL
SELECT 
    'Auditoría',
    COUNT(*)
FROM auditoria;

-- Mostrar usuarios migrados
SELECT 
    p.documento,
    p.nombres,
    p.apellidos,
    cpt.nombre AS tipo_persona,
    us.username,
    crs.nombre AS rol,
    us.estado
FROM personas p
JOIN usuarios_sistema us ON us.persona_id = p.id
JOIN cat_persona_tipo cpt ON p.tipo_persona_id = cpt.id
JOIN cat_rol_sistema crs ON us.rol_id = crs.id;

SELECT '✅ MIGRACIÓN COMPLETA - Usa sena_control para tu aplicación' AS resultado;
