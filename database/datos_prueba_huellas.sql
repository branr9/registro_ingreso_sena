/* =========================================================
   DATOS DE PRUEBA: Huellas para Control de Ingreso
   ========================================================= */

USE sena_control;

-- Verificar que existan personas
SELECT COUNT(*) as personas_count FROM personas WHERE deleted_at IS NULL;

-- Insertar huellas simuladas para personas existentes
-- Template simulado: simplemente un BLOB con identificador "FP_{persona_id}"
-- En producción real: aquí iría el template biométrico real del SDK

-- Limpiar huellas existentes (opcional)
-- DELETE FROM huellas;

-- Insertar huella para cada persona activa
INSERT INTO huellas (persona_id, dispositivo_id, template, template_version, activo, enrolled_at)
SELECT 
    p.id,
    NULL, -- No hay dispositivo registrado aún
    CONCAT('FP_', p.id), -- Template simulado
    'SIMULATOR_v1.0',
    1,
    NOW()
FROM personas p
WHERE p.deleted_at IS NULL
  AND NOT EXISTS (
      SELECT 1 FROM huellas h WHERE h.persona_id = p.id
  );

-- Verificar huellas insertadas
SELECT 
    h.id,
    p.id as persona_id,
    p.documento,
    p.nombres,
    p.apellidos,
    p.estado,
    cpt.nombre as tipo_persona,
    h.template,
    h.activo
FROM huellas h
INNER JOIN personas p ON h.persona_id = p.id
INNER JOIN cat_persona_tipo cpt ON p.tipo_persona_id = cpt.id
ORDER BY p.nombres;

-- Mostrar resumen
SELECT 
    'Huellas Registradas' as concepto,
    COUNT(*) as cantidad
FROM huellas WHERE activo = 1
UNION ALL
SELECT 
    'Personas sin Huella',
    COUNT(*)
FROM personas p
WHERE p.deleted_at IS NULL
  AND NOT EXISTS (SELECT 1 FROM huellas h WHERE h.persona_id = p.id);

SELECT '✅ Datos de prueba insertados - Sistema listo para testing' AS status;

/* =========================================================
   NOTAS PARA TESTING:
   
   1. Acceder al kiosko: http://localhost:8000/control-ingreso
   2. Debe autenticarse como vigilante o admin
   3. Usar el panel "SIMULADOR DE HUELLA" (abajo izquierda)
   4. Seleccionar una persona del dropdown
   5. Click en "Simular Lectura" para probar acceso
   6. También puede probar "Huella Desconocida" para ver rechazo
   
   TEMPLATES SIMULADOS:
   - Formato: "FP_{persona_id}"
   - Ejemplo: "FP_1" para persona con id=1
   - "FP_UNKNOWN_xxxxx" simula huella no registrada
   
   EN PRODUCCIÓN REAL:
   - Reemplazar findByFingerprint() para usar SDK real
   - El SDK retornará el template real capturado
   - Usar algoritmo de matching biométrico real
   - Remover panel simulador de la vista
   ========================================================= */
