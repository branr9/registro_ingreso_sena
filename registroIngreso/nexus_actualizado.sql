-- phpMyAdmin SQL Dump
-- Actualizado para el sistema de préstamo de llaves

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

-- Base de datos: `nexus`

-- ========== DROPEAR TABLAS EN ORDEN CORRECTO (by dependencies) ==========
DROP TABLE IF EXISTS `prestamos_llaves`;
DROP TABLE IF EXISTS `marcaciones`;
DROP TABLE IF EXISTS `permisos_salida`;
DROP TABLE IF EXISTS `llaves`;
DROP TABLE IF EXISTS `personal_externo`;
DROP TABLE IF EXISTS `usuarios`;
DROP TABLE IF EXISTS `aulas`;

-- ========== CREAR TABLAS EN ORDEN INVERSO ==========

-- ========== TABLA AULAS ==========
CREATE TABLE `aulas` (
  `id_aula` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `nombre` varchar(100) NOT NULL UNIQUE,
  `descripcion` varchar(255) DEFAULT NULL,
  `ubicacion` varchar(100) DEFAULT NULL,
  `capacidad` int(11) NOT NULL,
  `total_llaves` int(11) NOT NULL,
  `disponibles` int(11) NOT NULL DEFAULT 0,
  `responsable` varchar(100) DEFAULT NULL,
  `activa` tinyint(4) DEFAULT 1,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ========== TABLA LLAVES ==========
CREATE TABLE `llaves` (
  `id_llave` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `id_aula` int(11) NOT NULL,
  `numero_llave` varchar(50) NOT NULL,
  `disponible` tinyint(4) DEFAULT 1,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  UNIQUE KEY `unique_llave` (`id_aula`, `numero_llave`),
  FOREIGN KEY (`id_aula`) REFERENCES `aulas`(`id_aula`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ========== TABLA USUARIOS ==========
CREATE TABLE `usuarios` (
  `Id_usuario` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `nombre` varchar(100) NOT NULL,
  `apellido` varchar(100) NOT NULL,
  `fecha` date NOT NULL,
  `Dni` varchar(20) NOT NULL UNIQUE,
  `correo` varchar(100) NOT NULL UNIQUE,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `estado` enum('Activo','Inactivo') DEFAULT 'Activo',
  KEY `idx_usuarios_dni` (`Dni`),
  KEY `idx_usuarios_correo` (`correo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ========== TABLA PRESTAMOS_LLAVES ==========
CREATE TABLE `prestamos_llaves` (
  `id_prestamo` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `id_llave` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_aula` int(11) NOT NULL,
  `usuario_retira` varchar(150) DEFAULT NULL,
  `documento` varchar(20) DEFAULT NULL,
  `estado` enum('Prestada','Devuelto','Perdida') DEFAULT 'Prestada',
  `fecha_prestamo` date DEFAULT NULL,
  `hora_prestamo` time DEFAULT NULL,
  `fecha_devolucion` date DEFAULT NULL,
  `hora_devolucion` time DEFAULT NULL,
  `observaciones` varchar(255) DEFAULT NULL,
  `fecha_registro` timestamp DEFAULT current_timestamp(),
  FOREIGN KEY (`id_llave`) REFERENCES `llaves`(`id_llave`) ON DELETE CASCADE,
  FOREIGN KEY (`id_usuario`) REFERENCES `usuarios`(`Id_usuario`) ON DELETE RESTRICT,
  FOREIGN KEY (`id_aula`) REFERENCES `aulas`(`id_aula`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ========== TABLA MARCACIONES ==========
CREATE TABLE `marcaciones` (
  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `id_usuario` int(11) NOT NULL,
  `estado` enum('ENTRADA','SALIDA') NOT NULL,
  `fecha` date NOT NULL,
  `hora` time NOT NULL,
  `timestamp_marcacion` datetime DEFAULT current_timestamp(),
  KEY `idx_marcaciones_usuario` (`id_usuario`),
  KEY `idx_marcaciones_fecha` (`fecha`),
  KEY `idx_marcaciones_estado` (`estado`),
  FOREIGN KEY (`id_usuario`) REFERENCES `usuarios`(`Id_usuario`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ========== TABLA PERMISOS_SALIDA ==========
CREATE TABLE `permisos_salida` (
  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `id_usuario` int(11) NOT NULL,
  `motivo` varchar(255) NOT NULL,
  `fecha` date NOT NULL,
  `hora_salida` time NOT NULL,
  `hora_retorno_estimada` time DEFAULT NULL,
  `hora_retorno_real` time DEFAULT NULL,
  `estado` enum('Pendiente','Aprobado','Rechazado','Completado') DEFAULT 'Pendiente',
  `observaciones` varchar(500) DEFAULT NULL,
  `aprobado_por` int(11) DEFAULT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  KEY `idx_permisos_usuario` (`id_usuario`),
  KEY `idx_permisos_fecha` (`fecha`),
  KEY `idx_permisos_estado` (`estado`),
  FOREIGN KEY (`id_usuario`) REFERENCES `usuarios`(`Id_usuario`) ON DELETE CASCADE,
  FOREIGN KEY (`aprobado_por`) REFERENCES `usuarios`(`Id_usuario`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ========== TABLA PERSONAL_EXTERNO ==========
CREATE TABLE `personal_externo` (
  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `documento` varchar(20) NOT NULL,
  `tipo_documento` varchar(5) NOT NULL COMMENT 'CC, CE, PA, etc',
  `nombre` varchar(150) NOT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `empresa` varchar(100) DEFAULT NULL,
  `motivo` varchar(255) DEFAULT NULL,
  `fecha` date NOT NULL,
  `hora_ingreso` time NOT NULL,
  `hora_salida` time DEFAULT NULL,
  `estado` enum('Dentro','Salió') DEFAULT 'Dentro',
  `tiempo_estancia` varchar(20) DEFAULT NULL,
  `responsable` varchar(100) DEFAULT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  KEY `idx_personal_externo_documento` (`documento`),
  KEY `idx_personal_externo_fecha` (`fecha`),
  KEY `idx_personal_externo_estado` (`estado`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ========== DATOS DE EJEMPLO ==========

-- Insertar usuarios
INSERT INTO `usuarios` (`nombre`, `apellido`, `fecha`, `Dni`, `correo`, `estado`) VALUES
('Juan Carlos', 'García López', '1985-03-15', '123456789', 'juan.garcia@sena.edu.co', 'Activo'),
('María Fernanda', 'Rodríguez Pérez', '1990-07-22', '987654321', 'maria.rodriguez@sena.edu.co', 'Activo'),
('Carlos Alberto', 'Martínez Sánchez', '1988-11-10', '456789123', 'carlos.martinez@sena.edu.co', 'Activo'),
('Ana Isabel', 'López Gómez', '1992-05-18', '321654987', 'ana.lopez@sena.edu.co', 'Activo');

-- Insertar aulas de ejemplo
INSERT INTO `aulas` (`nombre`, `descripcion`, `ubicacion`, `capacidad`, `total_llaves`, `disponibles`, `responsable`) VALUES
('Aula 101', 'Aula de systems', 'Primer piso', 30, 2, 2, 'Juan García'),
('Aula 102', 'Aula de inglés', 'Primer piso', 25, 1, 1, 'María López'),
('Laboratorio A', 'Laboratorio de electrónica', 'Segundo piso', 20, 2, 2, 'Carlos Sánchez');

-- Insertar llaves ejemplo para aula 101
INSERT INTO `llaves` (`id_aula`, `numero_llave`, `disponible`) VALUES
(1, 'LLV-001-01', 1),
(1, 'LLV-001-02', 1);

-- Insertar llaves ejemplo para aula 102
INSERT INTO `llaves` (`id_aula`, `numero_llave`, `disponible`) VALUES
(2, 'LLV-002-01', 1);

-- Insertar llaves ejemplo para laboratorio A
INSERT INTO `llaves` (`id_aula`, `numero_llave`, `disponible`) VALUES
(3, 'LLV-003-01', 1),
(3, 'LLV-003-02', 0);

-- Insertar marcaciones ejemplo
INSERT INTO `marcaciones` (`id_usuario`, `estado`, `fecha`, `hora`) VALUES
(1, 'ENTRADA', '2026-03-25', '08:00:00'),
(1, 'SALIDA', '2026-03-25', '17:30:00'),
(2, 'ENTRADA', '2026-03-25', '08:15:00');

-- Insertar permisos de salida ejemplo
INSERT INTO `permisos_salida` (`id_usuario`, `motivo`, `fecha`, `hora_salida`, `hora_retorno_estimada`, `estado`, `aprobado_por`) VALUES
(1, 'Reunión con cliente', '2026-03-25', '14:00:00', '16:00:00', 'Aprobado', 2);

-- Insertar personal externo ejemplo
INSERT INTO `personal_externo` (`documento`, `tipo_documento`, `nombre`, `telefono`, `empresa`, `motivo`, `fecha`, `hora_ingreso`, `estado`) VALUES
('1236486', 'CE', 'Yirlene Perez', '3101234567', 'Centro Aguas', 'Revisión acueducto', '2026-03-25', '10:00:00', 'Dentro');

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
