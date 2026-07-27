-- Sincroniza los tipos usados por el módulo de usuarios con el catálogo real.
-- Puede ejecutarse varias veces sin duplicar registros.
INSERT INTO cat_persona_tipo (codigo, nombre, descripcion, activo)
SELECT 'VISITANTE', 'Visitante', 'Persona visitante', 1
WHERE NOT EXISTS (
    SELECT 1 FROM cat_persona_tipo WHERE UPPER(codigo) = 'VISITANTE'
);
