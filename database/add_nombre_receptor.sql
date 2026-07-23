-- Agregar campo nombre_receptor a prestamos_llaves
-- Para registrar el nombre de quien recibe la llave

ALTER TABLE prestamos_llaves 
ADD COLUMN nombre_receptor VARCHAR(150) NOT NULL AFTER usuario_id,
ADD INDEX idx_nombre_receptor (nombre_receptor);
