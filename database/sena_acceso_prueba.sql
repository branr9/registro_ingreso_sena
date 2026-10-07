-- =====================================================================
-- Base de datos de PRUEBA - Sistema de Control de Ingreso SENA
-- Estructura completa + catálogos + datos ficticios mínimos.
--
-- Usuarios para iniciar sesión (contraseña de todos: Admin123!)
--   admin       -> rol ADMIN
--   instructor  -> rol INSTRUCTOR
--   vigilante   -> rol VIGILANTE
--   acastro     -> rol INSTRUCTOR
--   dmorales    -> rol INSTRUCTOR
--
-- Importar en una base vacía llamada sena_acceso:
--   docker exec -i mysql_api mysql -uroot -proot_password sena_acceso < database/sena_acceso_prueba.sql
-- =====================================================================

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `sena_acceso`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `auditoria_accesos`
--

CREATE TABLE `auditoria_accesos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `usuario_id` bigint(20) UNSIGNED DEFAULT NULL,
  `accion` enum('login_exitoso','login_fallido','logout','sesion_expirada','cambio_password') NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `user_agent` varchar(255) NOT NULL,
  `detalles` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `aulas`
--

CREATE TABLE `aulas` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `capacidad` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT 'Capacidad de personas',
  `cantidad_llaves` int(10) UNSIGNED NOT NULL DEFAULT 1 COMMENT 'Número de juegos de llaves disponibles',
  `estado` enum('ACTIVO','INACTIVO') DEFAULT 'ACTIVO',
  `observaciones` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cat_persona_tipo`
--

CREATE TABLE `cat_persona_tipo` (
  `id` int(10) UNSIGNED NOT NULL,
  `codigo` varchar(20) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `descripcion` varchar(200) DEFAULT NULL,
  `activo` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `cat_persona_tipo`
--

INSERT INTO `cat_persona_tipo` (`id`, `codigo`, `nombre`, `descripcion`, `activo`, `created_at`) VALUES
(1, 'ADMIN', 'Administrador', 'Usuario administrador del sistema', 1, '2026-02-06 01:38:10'),
(2, 'INSTRUCTOR', 'Instructor', 'Personal docente', 1, '2026-02-06 01:38:10'),
(3, 'VIGILANTE', 'Vigilante', 'Personal de seguridad', 1, '2026-02-06 01:38:10'),
(4, 'APRENDIZ', 'Aprendiz', 'Estudiante del SENA', 1, '2026-02-06 01:38:10'),
(5, 'PLANTA', 'Personal de Planta', 'Empleado de planta', 1, '2026-02-06 01:38:10'),
(6, 'EXTERNO', 'Externo', 'Visitante o personal externo', 1, '2026-02-06 01:38:10'),
(7, 'CONTRATISTA', 'Contratista', 'Personal contratista', 1, '2026-02-06 01:38:10'),
(8, 'PROVEEDOR', 'Proveedor', 'Proveedor de servicios', 1, '2026-02-06 01:38:10');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cat_roles`
--

CREATE TABLE `cat_roles` (
  `id` int(10) UNSIGNED NOT NULL,
  `codigo` varchar(20) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `descripcion` varchar(200) DEFAULT NULL,
  `activo` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `cat_roles`
--

INSERT INTO `cat_roles` (`id`, `codigo`, `nombre`, `descripcion`, `activo`, `created_at`) VALUES
(1, 'ADMIN', 'Administrador', 'Acceso total al sistema', 1, '2026-02-06 01:38:10'),
(2, 'INSTRUCTOR', 'Instructor', 'Gestión de aprendices y reportes', 1, '2026-02-06 01:38:10'),
(3, 'VIGILANTE', 'Vigilante', 'Control de acceso en kiosko', 1, '2026-02-06 01:38:10');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `marcaciones`
--

CREATE TABLE `marcaciones` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `persona_id` bigint(20) UNSIGNED DEFAULT NULL,
  `dispositivo_id` varchar(50) DEFAULT NULL,
  `tipo_evento` enum('ENTRADA','SALIDA') DEFAULT NULL,
  `metodo` enum('BARCODE','MANUAL','HUELLA','QR','FACIAL') NOT NULL DEFAULT 'BARCODE',
  `motivo_id` int(10) UNSIGNED DEFAULT NULL,
  `documento_capturado` varchar(20) DEFAULT NULL,
  `nombre_capturado` varchar(200) DEFAULT NULL,
  `exitoso` tinyint(1) NOT NULL DEFAULT 1,
  `mensaje` varchar(500) DEFAULT NULL,
  `ubicacion` varchar(100) DEFAULT NULL,
  `registrado_por` bigint(20) UNSIGNED DEFAULT NULL,
  `fecha_hora` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `permisos_salida`
--

CREATE TABLE `permisos_salida` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `documento_aprendiz` varchar(30) NOT NULL,
  `nombre_aprendiz` varchar(255) NOT NULL,
  `fecha_permiso` date NOT NULL,
  `hora_salida` time NOT NULL,
  `hora_regreso` time DEFAULT NULL,
  `motivo` text NOT NULL,
  `instructor_id` bigint(20) UNSIGNED NOT NULL,
  `instructor_nombre` varchar(100) NOT NULL,
  `estado` enum('ACTIVO','USADO','VENCIDO','CANCELADO') NOT NULL DEFAULT 'ACTIVO',
  `usado_por` bigint(20) UNSIGNED DEFAULT NULL,
  `fecha_uso` datetime DEFAULT NULL,
  `observaciones` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `personas`
--

CREATE TABLE `personas` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `documento` varchar(20) NOT NULL,
  `tipo_documento` enum('CC','TI','CE','PASAPORTE','NIT') DEFAULT 'CC',
  `nombres` varchar(100) NOT NULL,
  `apellidos` varchar(100) DEFAULT NULL,
  `tipo_persona_id` int(10) UNSIGNED NOT NULL DEFAULT 4,
  `estado` enum('ACTIVO','INACTIVO','SUSPENDIDO') NOT NULL DEFAULT 'ACTIVO',
  `telefono` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `empresa` varchar(150) DEFAULT NULL,
  `observaciones` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `prestamos_llaves`
--

CREATE TABLE `prestamos_llaves` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `aula_id` bigint(20) UNSIGNED NOT NULL,
  `usuario_id` bigint(20) UNSIGNED NOT NULL COMMENT 'ID del usuario (persona_id)',
  `nombre_receptor` varchar(150) NOT NULL,
  `documento_receptor` varchar(20) NOT NULL,
  `departamento` varchar(100) DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `fecha_prestamo` timestamp NOT NULL DEFAULT current_timestamp(),
  `fecha_devolucion` timestamp NULL DEFAULT NULL,
  `estado` enum('PRESTADO','DEVUELTO','VENCIDO') DEFAULT 'PRESTADO',
  `observaciones_prestamo` text DEFAULT NULL,
  `observaciones_devolucion` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `registros_acceso_externo`
--

CREATE TABLE `registros_acceso_externo` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `documento` varchar(20) NOT NULL,
  `tipo_documento` enum('CC','CE','TI','PAS','NIT') DEFAULT 'CC',
  `nombres` varchar(100) NOT NULL,
  `apellidos` varchar(100) DEFAULT NULL,
  `empresa` varchar(150) DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `motivo_visita` varchar(255) NOT NULL,
  `persona_visitada` varchar(150) DEFAULT NULL,
  `area_destino` varchar(100) DEFAULT NULL,
  `fecha_entrada` datetime NOT NULL,
  `fecha_salida` datetime DEFAULT NULL,
  `tiempo_permanencia` int(11) DEFAULT NULL COMMENT 'Minutos de permanencia',
  `vigilante_entrada_id` bigint(20) UNSIGNED DEFAULT NULL,
  `vigilante_salida_id` bigint(20) UNSIGNED DEFAULT NULL,
  `observaciones` text DEFAULT NULL,
  `estado` enum('DENTRO','SALIO') DEFAULT 'DENTRO',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sesiones`
--

CREATE TABLE `sesiones` (
  `id` varchar(128) NOT NULL,
  `usuario_id` bigint(20) UNSIGNED NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `user_agent` varchar(255) NOT NULL,
  `ultima_actividad` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios_sistema`
--

CREATE TABLE `usuarios_sistema` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `persona_id` bigint(20) UNSIGNED NOT NULL,
  `rol_id` int(10) UNSIGNED NOT NULL DEFAULT 3,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `estado` enum('ACTIVO','INACTIVO','BLOQUEADO') NOT NULL DEFAULT 'ACTIVO',
  `intentos_fallidos` tinyint(3) UNSIGNED DEFAULT 0,
  `bloqueado_hasta` timestamp NULL DEFAULT NULL,
  `last_login_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vista_acceso_externo`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `vista_acceso_externo` (
`id` bigint(20) unsigned
,`documento` varchar(20)
,`tipo_documento` enum('CC','CE','TI','PAS','NIT')
,`nombres` varchar(100)
,`apellidos` varchar(100)
,`empresa` varchar(150)
,`telefono` varchar(20)
,`email` varchar(150)
,`motivo_visita` varchar(255)
,`persona_visitada` varchar(150)
,`area_destino` varchar(100)
,`fecha_entrada` datetime
,`fecha_salida` datetime
,`tiempo_permanencia` int(11)
,`vigilante_entrada_id` bigint(20) unsigned
,`vigilante_salida_id` bigint(20) unsigned
,`observaciones` text
,`estado` enum('DENTRO','SALIO')
,`created_at` timestamp
,`updated_at` timestamp
,`nombre_completo` varchar(201)
,`vigilante_entrada_nombre` varchar(201)
,`vigilante_salida_nombre` varchar(201)
,`minutos_transcurridos` bigint(21)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `v_marcaciones_hoy`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `v_marcaciones_hoy` (
`id` bigint(20) unsigned
,`persona_id` bigint(20) unsigned
,`documento` varchar(20)
,`nombre_completo` varchar(201)
,`tipo_persona` varchar(50)
,`tipo_evento` enum('ENTRADA','SALIDA')
,`metodo` enum('BARCODE','MANUAL','HUELLA','QR','FACIAL')
,`exitoso` tinyint(1)
,`mensaje` varchar(500)
,`ubicacion` varchar(100)
,`fecha_hora` timestamp
,`hora` time
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `v_personas_activas`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `v_personas_activas` (
`id` bigint(20) unsigned
,`documento` varchar(20)
,`tipo_documento` enum('CC','TI','CE','PASAPORTE','NIT')
,`nombres` varchar(100)
,`apellidos` varchar(100)
,`nombre_completo` varchar(201)
,`tipo_persona_codigo` varchar(20)
,`tipo_persona_nombre` varchar(50)
,`estado` enum('ACTIVO','INACTIVO','SUSPENDIDO')
,`telefono` varchar(20)
,`email` varchar(100)
,`empresa` varchar(150)
,`usuario_sistema_id` bigint(20) unsigned
,`username` varchar(50)
,`rol_id` int(10) unsigned
,`rol_nombre` varchar(50)
,`created_at` timestamp
,`updated_at` timestamp
);

-- --------------------------------------------------------

--
-- Estructura para la vista `vista_acceso_externo`
--
DROP TABLE IF EXISTS `vista_acceso_externo`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vista_acceso_externo`  AS SELECT `rae`.`id` AS `id`, `rae`.`documento` AS `documento`, `rae`.`tipo_documento` AS `tipo_documento`, `rae`.`nombres` AS `nombres`, `rae`.`apellidos` AS `apellidos`, `rae`.`empresa` AS `empresa`, `rae`.`telefono` AS `telefono`, `rae`.`email` AS `email`, `rae`.`motivo_visita` AS `motivo_visita`, `rae`.`persona_visitada` AS `persona_visitada`, `rae`.`area_destino` AS `area_destino`, `rae`.`fecha_entrada` AS `fecha_entrada`, `rae`.`fecha_salida` AS `fecha_salida`, `rae`.`tiempo_permanencia` AS `tiempo_permanencia`, `rae`.`vigilante_entrada_id` AS `vigilante_entrada_id`, `rae`.`vigilante_salida_id` AS `vigilante_salida_id`, `rae`.`observaciones` AS `observaciones`, `rae`.`estado` AS `estado`, `rae`.`created_at` AS `created_at`, `rae`.`updated_at` AS `updated_at`, concat(`rae`.`nombres`,' ',coalesce(`rae`.`apellidos`,'')) AS `nombre_completo`, concat(`pe`.`nombres`,' ',coalesce(`pe`.`apellidos`,'')) AS `vigilante_entrada_nombre`, concat(`ps`.`nombres`,' ',coalesce(`ps`.`apellidos`,'')) AS `vigilante_salida_nombre`, timestampdiff(MINUTE,`rae`.`fecha_entrada`,coalesce(`rae`.`fecha_salida`,current_timestamp())) AS `minutos_transcurridos` FROM ((((`registros_acceso_externo` `rae` left join `usuarios_sistema` `use1` on(`rae`.`vigilante_entrada_id` = `use1`.`id`)) left join `personas` `pe` on(`use1`.`persona_id` = `pe`.`id`)) left join `usuarios_sistema` `use2` on(`rae`.`vigilante_salida_id` = `use2`.`id`)) left join `personas` `ps` on(`use2`.`persona_id` = `ps`.`id`)) ORDER BY `rae`.`fecha_entrada` DESC ;

-- --------------------------------------------------------

--
-- Estructura para la vista `v_marcaciones_hoy`
--
DROP TABLE IF EXISTS `v_marcaciones_hoy`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_marcaciones_hoy`  AS SELECT `m`.`id` AS `id`, `m`.`persona_id` AS `persona_id`, `p`.`documento` AS `documento`, concat(`p`.`nombres`,' ',coalesce(`p`.`apellidos`,'')) AS `nombre_completo`, `cpt`.`nombre` AS `tipo_persona`, `m`.`tipo_evento` AS `tipo_evento`, `m`.`metodo` AS `metodo`, `m`.`exitoso` AS `exitoso`, `m`.`mensaje` AS `mensaje`, `m`.`ubicacion` AS `ubicacion`, `m`.`fecha_hora` AS `fecha_hora`, cast(`m`.`fecha_hora` as time) AS `hora` FROM ((`marcaciones` `m` left join `personas` `p` on(`m`.`persona_id` = `p`.`id`)) left join `cat_persona_tipo` `cpt` on(`p`.`tipo_persona_id` = `cpt`.`id`)) WHERE cast(`m`.`fecha_hora` as date) = curdate() ORDER BY `m`.`fecha_hora` DESC ;

-- --------------------------------------------------------

--
-- Estructura para la vista `v_personas_activas`
--
DROP TABLE IF EXISTS `v_personas_activas`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_personas_activas`  AS SELECT `p`.`id` AS `id`, `p`.`documento` AS `documento`, `p`.`tipo_documento` AS `tipo_documento`, `p`.`nombres` AS `nombres`, `p`.`apellidos` AS `apellidos`, concat(`p`.`nombres`,' ',coalesce(`p`.`apellidos`,'')) AS `nombre_completo`, `cpt`.`codigo` AS `tipo_persona_codigo`, `cpt`.`nombre` AS `tipo_persona_nombre`, `p`.`estado` AS `estado`, `p`.`telefono` AS `telefono`, `p`.`email` AS `email`, `p`.`empresa` AS `empresa`, `us`.`id` AS `usuario_sistema_id`, `us`.`username` AS `username`, `us`.`rol_id` AS `rol_id`, `cr`.`nombre` AS `rol_nombre`, `p`.`created_at` AS `created_at`, `p`.`updated_at` AS `updated_at` FROM (((`personas` `p` join `cat_persona_tipo` `cpt` on(`p`.`tipo_persona_id` = `cpt`.`id`)) left join `usuarios_sistema` `us` on(`us`.`persona_id` = `p`.`id`)) left join `cat_roles` `cr` on(`us`.`rol_id` = `cr`.`id`)) WHERE `p`.`deleted_at` is null AND `p`.`estado` = 'ACTIVO' ;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `auditoria_accesos`
--
ALTER TABLE `auditoria_accesos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_usuario` (`usuario_id`),
  ADD KEY `idx_accion` (`accion`),
  ADD KEY `idx_fecha` (`created_at`),
  ADD KEY `idx_ip` (`ip_address`);

--
-- Indices de la tabla `aulas`
--
ALTER TABLE `aulas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombre` (`nombre`),
  ADD KEY `idx_estado` (`estado`),
  ADD KEY `idx_nombre` (`nombre`);

--
-- Indices de la tabla `cat_persona_tipo`
--
ALTER TABLE `cat_persona_tipo`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `codigo` (`codigo`);

--
-- Indices de la tabla `cat_roles`
--
ALTER TABLE `cat_roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `codigo` (`codigo`);

--
-- Indices de la tabla `marcaciones`
--
ALTER TABLE `marcaciones`
  ADD PRIMARY KEY (`id`),
  ADD KEY `registrado_por` (`registrado_por`),
  ADD KEY `idx_persona` (`persona_id`),
  ADD KEY `idx_fecha` (`fecha_hora`),
  ADD KEY `idx_tipo_evento` (`tipo_evento`),
  ADD KEY `idx_exitoso` (`exitoso`),
  ADD KEY `idx_metodo` (`metodo`),
  ADD KEY `idx_documento` (`documento_capturado`),
  ADD KEY `idx_fecha_persona` (`fecha_hora`,`persona_id`);

--
-- Indices de la tabla `permisos_salida`
--
ALTER TABLE `permisos_salida`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_documento` (`documento_aprendiz`),
  ADD KEY `idx_fecha` (`fecha_permiso`),
  ADD KEY `idx_estado` (`estado`),
  ADD KEY `idx_instructor` (`instructor_id`),
  ADD KEY `fk_permisos_validado_por` (`usado_por`);

--
-- Indices de la tabla `personas`
--
ALTER TABLE `personas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `documento` (`documento`),
  ADD KEY `idx_documento` (`documento`),
  ADD KEY `idx_tipo_persona` (`tipo_persona_id`),
  ADD KEY `idx_estado` (`estado`),
  ADD KEY `idx_deleted` (`deleted_at`),
  ADD KEY `idx_nombres` (`nombres`),
  ADD KEY `idx_apellidos` (`apellidos`);

--
-- Indices de la tabla `prestamos_llaves`
--
ALTER TABLE `prestamos_llaves`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_aula` (`aula_id`),
  ADD KEY `idx_usuario` (`usuario_id`),
  ADD KEY `idx_estado` (`estado`),
  ADD KEY `idx_fecha_prestamo` (`fecha_prestamo`),
  ADD KEY `idx_fecha_devolucion` (`fecha_devolucion`),
  ADD KEY `idx_nombre_receptor` (`nombre_receptor`),
  ADD KEY `idx_documento_receptor` (`documento_receptor`);

--
-- Indices de la tabla `registros_acceso_externo`
--
ALTER TABLE `registros_acceso_externo`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_documento` (`documento`),
  ADD KEY `idx_fecha_entrada` (`fecha_entrada`),
  ADD KEY `idx_estado` (`estado`),
  ADD KEY `idx_empresa` (`empresa`),
  ADD KEY `vigilante_entrada_id` (`vigilante_entrada_id`),
  ADD KEY `vigilante_salida_id` (`vigilante_salida_id`);

--
-- Indices de la tabla `sesiones`
--
ALTER TABLE `sesiones`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_usuario` (`usuario_id`),
  ADD KEY `idx_actividad` (`ultima_actividad`);

--
-- Indices de la tabla `usuarios_sistema`
--
ALTER TABLE `usuarios_sistema`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `idx_username` (`username`),
  ADD KEY `idx_email` (`email`),
  ADD KEY `idx_rol` (`rol_id`),
  ADD KEY `idx_estado` (`estado`),
  ADD KEY `idx_persona` (`persona_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `auditoria_accesos`
--
ALTER TABLE `auditoria_accesos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `aulas`
--
ALTER TABLE `aulas`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `cat_persona_tipo`
--
ALTER TABLE `cat_persona_tipo`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `cat_roles`
--
ALTER TABLE `cat_roles`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `marcaciones`
--
ALTER TABLE `marcaciones`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `permisos_salida`
--
ALTER TABLE `permisos_salida`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `personas`
--
ALTER TABLE `personas`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `prestamos_llaves`
--
ALTER TABLE `prestamos_llaves`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `registros_acceso_externo`
--
ALTER TABLE `registros_acceso_externo`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `usuarios_sistema`
--
ALTER TABLE `usuarios_sistema`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `auditoria_accesos`
--
ALTER TABLE `auditoria_accesos`
  ADD CONSTRAINT `auditoria_accesos_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios_sistema` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `marcaciones`
--
ALTER TABLE `marcaciones`
  ADD CONSTRAINT `marcaciones_ibfk_1` FOREIGN KEY (`persona_id`) REFERENCES `personas` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `marcaciones_ibfk_2` FOREIGN KEY (`registrado_por`) REFERENCES `usuarios_sistema` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `permisos_salida`
--
ALTER TABLE `permisos_salida`
  ADD CONSTRAINT `fk_permisos_instructor` FOREIGN KEY (`instructor_id`) REFERENCES `usuarios_sistema` (`id`),
  ADD CONSTRAINT `fk_permisos_validado_por` FOREIGN KEY (`usado_por`) REFERENCES `usuarios_sistema` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `personas`
--
ALTER TABLE `personas`
  ADD CONSTRAINT `personas_ibfk_1` FOREIGN KEY (`tipo_persona_id`) REFERENCES `cat_persona_tipo` (`id`);

--
-- Filtros para la tabla `prestamos_llaves`
--
ALTER TABLE `prestamos_llaves`
  ADD CONSTRAINT `prestamos_llaves_ibfk_1` FOREIGN KEY (`aula_id`) REFERENCES `aulas` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `prestamos_llaves_ibfk_2` FOREIGN KEY (`usuario_id`) REFERENCES `personas` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `registros_acceso_externo`
--
ALTER TABLE `registros_acceso_externo`
  ADD CONSTRAINT `registros_acceso_externo_ibfk_1` FOREIGN KEY (`vigilante_entrada_id`) REFERENCES `usuarios_sistema` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `registros_acceso_externo_ibfk_2` FOREIGN KEY (`vigilante_salida_id`) REFERENCES `usuarios_sistema` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `sesiones`
--
ALTER TABLE `sesiones`
  ADD CONSTRAINT `sesiones_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios_sistema` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `usuarios_sistema`
--
ALTER TABLE `usuarios_sistema`
  ADD CONSTRAINT `usuarios_sistema_ibfk_1` FOREIGN KEY (`persona_id`) REFERENCES `personas` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `usuarios_sistema_ibfk_2` FOREIGN KEY (`rol_id`) REFERENCES `cat_roles` (`id`);
-- =====================================================================
-- Datos ficticios de prueba
-- =====================================================================

INSERT INTO `personas` (`id`, `documento`, `tipo_documento`, `nombres`, `apellidos`, `tipo_persona_id`, `estado`, `telefono`, `email`, `empresa`, `observaciones`) VALUES
-- Personal del sistema
(1, '1000000001', 'CC', 'Administrador', 'Sistema', 1, 'ACTIVO', '3000000001', 'admin@sena.edu.co', NULL, 'Usuario de prueba'),
(2, '1000000002', 'CC', 'Laura', 'Gómez Prueba', 2, 'ACTIVO', '3000000002', 'instructor@sena.edu.co', NULL, 'Instructora de prueba'),
(3, '1000000003', 'CC', 'Pedro', 'Ramírez Prueba', 3, 'ACTIVO', '3000000003', 'vigilante@sena.edu.co', NULL, 'Vigilante de prueba'),
-- Más instructores (también con usuario de sistema)
(4, '1000000004', 'CC', 'Andrés', 'Castro Prueba', 2, 'ACTIVO', '3000000004', 'andres.castro@prueba.test', NULL, NULL),
(5, '1000000005', 'CC', 'Diana', 'Morales Prueba', 2, 'ACTIVO', '3000000005', 'diana.morales@prueba.test', NULL, NULL),
-- Aprendices
(6, '1100000001', 'TI', 'Santiago', 'López Prueba', 4, 'ACTIVO', '3100000001', 'santiago.lopez@prueba.test', NULL, NULL),
(7, '1100000002', 'CC', 'Valentina', 'Torres Prueba', 4, 'ACTIVO', '3100000002', 'valentina.torres@prueba.test', NULL, NULL),
(8, '1100000003', 'CC', 'Mateo', 'Herrera Prueba', 4, 'ACTIVO', '3100000003', 'mateo.herrera@prueba.test', NULL, NULL),
(9, '1100000004', 'TI', 'Camila', 'Rojas Prueba', 4, 'ACTIVO', '3100000004', 'camila.rojas@prueba.test', NULL, NULL),
(10, '1100000005', 'CC', 'Sebastián', 'Vargas Prueba', 4, 'ACTIVO', '3100000005', 'sebastian.vargas@prueba.test', NULL, NULL),
(11, '1100000006', 'CC', 'Isabella', 'Mendoza Prueba', 4, 'INACTIVO', '3100000006', 'isabella.mendoza@prueba.test', NULL, 'Aprendiz inactivo para probar accesos denegados');

INSERT INTO `usuarios_sistema` (`id`, `persona_id`, `rol_id`, `username`, `email`, `password_hash`, `estado`, `intentos_fallidos`) VALUES
(1, 1, 1, 'admin', 'admin@sena.edu.co', '$2y$10$I9TrUN3QRcMwymTOzdEfcOmUaYWSjUtyhYFPRpzsWRU63J4Sfcqiu', 'ACTIVO', 0),
(2, 2, 2, 'instructor', 'instructor@sena.edu.co', '$2y$10$q0rWL6STkHRVicBkKppMQ.co7Km5HyDExQB8iazaEsqMJ2inel8t.', 'ACTIVO', 0),
(3, 3, 3, 'vigilante', 'vigilante@sena.edu.co', '$2y$10$KZmw.e6zKjkv6jM54b57.OhrgBVPPbM27DDYen0T4OckWJETwiaKS', 'ACTIVO', 0),
(4, 4, 2, 'acastro', 'andres.castro@prueba.test', '$2y$10$sr4taSRnhEsQE77DlXpW9OVjqRuT5KV6gBM1TXHHTIs6Xjjvqe2PG', 'ACTIVO', 0),
(5, 5, 2, 'dmorales', 'diana.morales@prueba.test', '$2y$10$CotbXyJ/sm7DpSg8xguJq.lS/z6DcvGjN7THuF5ADBKlQulIoOhQ.', 'ACTIVO', 0);

INSERT INTO `aulas` (`id`, `nombre`, `capacidad`, `cantidad_llaves`, `estado`, `observaciones`) VALUES
(1, 'Aula 101', 30, 2, 'ACTIVO', 'Aula de sistemas'),
(2, 'Aula 102', 25, 1, 'ACTIVO', 'Aula de inglés'),
(3, 'Laboratorio A', 20, 2, 'ACTIVO', 'Laboratorio de electrónica');

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
