-- Actualizar tabla prestamos_llaves para sistema de control completo
-- Agregar campos adicionales para información del receptor

ALTER TABLE prestamos_llaves 
ADD COLUMN documento_receptor VARCHAR(20) NOT NULL AFTER nombre_receptor,
ADD COLUMN departamento VARCHAR(100) NULL AFTER documento_receptor,
ADD COLUMN telefono VARCHAR(20) NULL AFTER departamento,
ADD INDEX idx_documento_receptor (documento_receptor);
