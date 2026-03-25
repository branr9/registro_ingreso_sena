-- ========== TABLA AULAS ==========
CREATE TABLE aulas (
    id_aula INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL UNIQUE,
    descripcion VARCHAR(255),
    capacidad INT NOT NULL,
    total_llaves INT NOT NULL,
    disponibles INT NOT NULL DEFAULT 0,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    activa TINYINT DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ========== TABLA LLAVES ==========
CREATE TABLE llaves (
    id_llave INT AUTO_INCREMENT PRIMARY KEY,
    id_aula INT NOT NULL,
    numero_llave VARCHAR(50) NOT NULL,
    disponible TINYINT DEFAULT 1,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_aula) REFERENCES aulas(id_aula) ON DELETE CASCADE,
    UNIQUE KEY unique_llave (id_aula, numero_llave)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ========== TABLA PRESTAMOS_LLAVES ==========
CREATE TABLE prestamos_llaves (
    id_prestamo INT AUTO_INCREMENT PRIMARY KEY,
    id_llave INT NOT NULL,
    id_usuario INT NOT NULL,
    id_aula INT NOT NULL,
    estado ENUM('Prestada', 'Devuelto', 'Perdida') DEFAULT 'Prestada',
    fecha_prestamo DATE NOT NULL,
    hora_prestamo TIME NOT NULL,
    fecha_devolucion DATE,
    hora_devolucion TIME,
    observaciones VARCHAR(255),
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_llave) REFERENCES llaves(id_llave) ON DELETE CASCADE,
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario) ON DELETE RESTRICT,
    FOREIGN KEY (id_aula) REFERENCES aulas(id_aula) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ========== EJEMPLOS DE INSERCIÓN ==========

-- Insertar aulas
INSERT INTO aulas (nombre, descripcion, capacidad, total_llaves, disponibles) VALUES
('Aula 101', 'Aula de programación', 30, 2, 2),
('Aula 102', 'Aula de diseño gráfico', 25, 2, 2),
('Aula 103', 'Laboratorio de sistemas', 20, 3, 3),
('Aula 104', 'Aula de redes', 18, 2, 2),
('Aula 105', 'Aula de soporte técnico', 22, 2, 2);

-- Insertar llaves
INSERT INTO llaves (id_aula, numero_llave, disponible) VALUES
(1, 'LLAVE-101-001', 1),
(1, 'LLAVE-101-002', 1),
(2, 'LLAVE-102-001', 1),
(2, 'LLAVE-102-002', 1),
(3, 'LLAVE-103-001', 1),
(3, 'LLAVE-103-002', 1),
(3, 'LLAVE-103-003', 1),
(4, 'LLAVE-104-001', 1),
(4, 'LLAVE-104-002', 1),
(5, 'LLAVE-105-001', 1),
(5, 'LLAVE-105-002', 1);

-- Insertar un préstamo de ejemplo
INSERT INTO prestamos_llaves (id_llave, id_usuario, id_aula, estado, fecha_prestamo, hora_prestamo) VALUES
(1, 1, 1, 'Prestada', CURDATE(), CURTIME());

-- ========== VISTAS ÚTILES ==========

-- Vista: Resumen de aulas con disponibilidad
CREATE VIEW vista_aulas_disponibilidad AS
SELECT 
    a.id_aula,
    a.nombre,
    a.descripcion,
    a.capacidad,
    a.total_llaves,
    COUNT(CASE WHEN l.disponible = 1 THEN 1 END) as llaves_disponibles,
    COUNT(CASE WHEN l.disponible = 0 THEN 1 END) as llaves_prestadas,
    a.activa
FROM aulas a
LEFT JOIN llaves l ON a.id_aula = l.id_aula
GROUP BY a.id_aula, a.nombre, a.descripcion, a.capacidad, a.total_llaves, a.activa
ORDER BY a.nombre;

-- Vista: Préstamos activos
CREATE VIEW vista_prestamos_activos AS
SELECT 
    pl.id_prestamo,
    a.nombre as aula,
    l.numero_llave,
    u.nombre as usuario,
    pl.fecha_prestamo,
    pl.hora_prestamo,
    DATEDIFF(CURDATE(), pl.fecha_prestamo) as dias_prestamo,
    pl.estado
FROM prestamos_llaves pl
JOIN aulas a ON pl.id_aula = a.id_aula
JOIN llaves l ON pl.id_llave = l.id_llave
JOIN usuarios u ON pl.id_usuario = u.id_usuario
WHERE pl.estado = 'Prestada'
ORDER BY pl.fecha_prestamo DESC;

-- ========== CONSULTAS ÚTILES ==========

-- Obtener todas las aulas con disponibilidad
SELECT * FROM vista_aulas_disponibilidad;

-- Obtener aula específica con sus llaves
SELECT 
    a.id_aula,
    a.nombre,
    a.descripcion,
    a.capacidad,
    COUNT(l.id_llave) as total_llaves,
    SUM(CASE WHEN l.disponible = 1 THEN 1 ELSE 0 END) as llaves_disponibles
FROM aulas a
LEFT JOIN llaves l ON a.id_aula = l.id_aula
WHERE a.id_aula = 1
GROUP BY a.id_aula, a.nombre, a.descripcion, a.capacidad;

-- Obtener préstamos activos
SELECT * FROM vista_prestamos_activos;

-- Obtener historial de préstamos de un aula
SELECT 
    a.nombre as aula,
    l.numero_llave,
    u.nombre as usuario,
    pl.fecha_prestamo,
    pl.hora_prestamo,
    pl.fecha_devolucion,
    pl.hora_devolucion,
    pl.estado
FROM prestamos_llaves pl
JOIN aulas a ON pl.id_aula = a.id_aula
JOIN llaves l ON pl.id_llave = l.id_llave
JOIN usuarios u ON pl.id_usuario = u.id_usuario
WHERE a.id_aula = 1
ORDER BY pl.fecha_prestamo DESC;
