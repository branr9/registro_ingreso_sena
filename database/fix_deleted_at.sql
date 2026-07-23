/* =========================================================
   FIX: Agregar columna deleted_at para soft deletes
   ========================================================= */

USE sena_control;

-- Agregar columna deleted_at a personas si no existe
ALTER TABLE personas 
ADD COLUMN IF NOT EXISTS deleted_at DATETIME NULL,
ADD INDEX IF NOT EXISTS idx_persona_deleted (deleted_at);

SELECT '✅ Columna deleted_at agregada exitosamente' AS resultado;

-- Verificar estructura
DESCRIBE personas;
