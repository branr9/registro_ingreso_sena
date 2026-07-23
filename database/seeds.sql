USE sistema_ingreso;

-- Usuario administrador inicial
-- Password: Admin123! (CAMBIAR EN PRODUCCIÓN)
INSERT INTO usuarios (nombre, email, username, password_hash, rol, estado) VALUES
('Administrador del Sistema', 'admin@sena.edu.co', 'admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 'activo'),
('Instructor Demo', 'instructor@sena.edu.co', 'instructor', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'instructor', 'activo'),
('Vigilante Demo', 'vigilante@sena.edu.co', 'vigilante', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'vigilante', 'activo');

-- Nota: Todos los usuarios de prueba tienen password: Admin123!
-- Para generar un nuevo hash en PHP: password_hash('Admin123!', PASSWORD_DEFAULT)
