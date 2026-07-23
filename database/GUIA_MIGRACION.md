# 🔄 Guía de Migración: senaaccses → sena_control

## 📋 Resumen

Esta migración transforma tu sistema básico de autenticación en un **sistema completo de control de ingreso y ambientes** para SENA.

### ⚡ Cambios Principales

| Antes (senaaccses) | Después (sena_control) |
|-------------------|------------------------|
| 3 tablas simples | 17 tablas profesionales |
| Solo usuarios sistema | Personas + Usuarios Sistema |
| Sin control biométrico | ✅ Huellas + Dispositivos |
| Sin gestión de ambientes | ✅ Reservas + Llaves |
| Sin permisos de salida | ✅ Permisos para aprendices |
| Auditoría básica | ✅ Auditoría completa JSON |

---

## 🚀 Pasos de Migración

### 📦 Paso 1: Ejecutar Script SQL

```bash
# En terminal PowerShell
cd "c:\Users\Brandon\Documents\ingreso sena\desarrollo ingreso"

# Ejecutar migración
mysql -u root -p < database/migracion_schema_completo.sql
```

**Contraseña:** (tu password de MySQL)

### ✅ Paso 2: Verificar Resultados

El script creará:
- ✅ `senaaccses_backup` - Respaldo de tu BD actual
- ✅ `sena_control` - Nueva BD con 17 tablas
- ✅ Migración de 3 usuarios existentes
- ✅ Auditoría migrada con formato JSON

### 🔍 Paso 3: Verificar en phpMyAdmin

1. Abre: http://localhost/phpmyadmin
2. Verifica que existan:
   - Base de datos: **sena_control**
   - Tablas: 17 tablas nuevas
   - Datos migrados en `personas` y `usuarios_sistema`

---

## 📊 Estructura Nueva

### 🔐 Autenticación
- `cat_rol_sistema` → Roles (admin, instructor, vigilante)
- `personas` → TODOS (aprendices, instructores, visitantes, etc.)
- `usuarios_sistema` → Solo usuarios que tienen login

### 👥 Gestión de Personas
- `cat_persona_tipo` → 8 tipos (aprendiz, instructor, vigilante, contratista, visitante, proveedor, aseo, interno)
- `personas` → Documento único, puede o no tener cuenta de sistema

### 🔒 Control de Acceso Biométrico
- `dispositivos` → Lectores de huella
- `huellas` → Plantillas biométricas (BLOB)
- `marcaciones` → Entradas/salidas (HUELLA o MANUAL)

### 🏢 Gestión de Ambientes
- `ambientes` → Salas, laboratorios, auditorios
- `reservas` → Reservar ambientes por fecha/hora
- `llaves` → Llaves físicas por ambiente
- `prestamos_llaves` → Préstamo/devolución

### 📄 Permisos de Salida
- `permisos_salida` → Aprendiz sale con permiso de instructor
- `cat_motivo` → Motivos (cita médica, diligencia, etc.)

### 📝 Auditoría
- `auditoria` → Log de todas las acciones con JSON
- Campos: actor, módulo, acción, entidad, detalle JSON

---

## 🔑 Usuarios Migrados

Tus 3 usuarios actuales se migrarán automáticamente:

| Usuario Original | Documento Generado | Tabla personas | Tabla usuarios_sistema |
|------------------|-------------------|----------------|------------------------|
| admin | DOC00000001 | ✅ | ✅ username: admin |
| instructor | DOC00000002 | ✅ | ✅ username: instructor |
| vigilante | DOC00000003 | ✅ | ✅ username: vigilante |

**🔐 Las contraseñas NO cambian**: `Admin123!` (admin) y `Instructor123!` (instructor/vigilante)

---

## ⚙️ Actualizar Configuración PHP

### 📄 Archivo: `config/database.php`

```php
<?php
class Database {
    private static $instance = null;
    private $pdo;

    // ⚠️ CAMBIA ESTOS VALORES
    private $host = 'localhost';
    private $db_name = 'sena_control'; // 👈 ACTUALIZAR
    private $username = 'root';
    private $password = ''; // tu password

    private function __construct() {
        try {
            $this->pdo = new PDO(
                "mysql:host={$this->host};dbname={$this->db_name};charset=utf8mb4",
                $this->username,
                $this->password,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false
                ]
            );
        } catch(PDOException $e) {
            die("Error de conexión: " . $e->getMessage());
        }
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection() {
        return $this->pdo;
    }
}
```

---

## 🔄 Actualizar Modelos PHP

### 🚨 Cambios Críticos en Consultas SQL

#### ❌ ANTES (senaaccses):
```sql
SELECT * FROM usuarios WHERE id = ?
```

#### ✅ AHORA (sena_control):
```sql
SELECT 
    us.id,
    us.username,
    us.password_hash,
    us.estado,
    us.intentos_fallidos,
    us.bloqueado_hasta,
    us.last_login_at,
    p.id AS persona_id,
    p.documento,
    p.nombres,
    p.apellidos,
    p.email,
    crs.codigo AS rol_codigo,
    crs.nombre AS rol_nombre
FROM usuarios_sistema us
JOIN personas p ON us.persona_id = p.id
JOIN cat_rol_sistema crs ON us.rol_id = crs.id
WHERE us.id = ?
```

### 📂 Archivo: `app/models/User.php`

**Actualizar método `findByCredential()`:**

```php
public function findByCredential($credential) {
    $sql = "SELECT 
                us.id,
                us.username,
                us.password_hash,
                us.estado,
                us.intentos_fallidos,
                us.bloqueado_hasta,
                us.last_login_at,
                p.id AS persona_id,
                p.documento,
                p.nombres,
                p.apellidos,
                p.email,
                p.telefono,
                crs.codigo AS rol_codigo,
                crs.nombre AS rol_nombre,
                cpt.codigo AS tipo_persona_codigo,
                cpt.nombre AS tipo_persona_nombre
            FROM usuarios_sistema us
            JOIN personas p ON us.persona_id = p.id
            JOIN cat_rol_sistema crs ON us.rol_id = crs.id
            JOIN cat_persona_tipo cpt ON p.tipo_persona_id = cpt.id
            WHERE (us.username = :credential OR p.email = :credential)
              AND us.estado = 'ACTIVO'
              AND p.estado = 'ACTIVO'";
    
    $stmt = $this->db->prepare($sql);
    $stmt->execute(['credential' => $credential]);
    return $stmt->fetch();
}
```

**Actualizar método `updateLastLogin()`:**

```php
public function updateLastLogin($userId) {
    $sql = "UPDATE usuarios_sistema 
            SET last_login_at = NOW(), 
                intentos_fallidos = 0, 
                bloqueado_hasta = NULL 
            WHERE id = :id";
    $stmt = $this->db->prepare($sql);
    return $stmt->execute(['id' => $userId]);
}
```

**Actualizar método `incrementFailedAttempts()`:**

```php
public function incrementFailedAttempts($userId) {
    $sql = "UPDATE usuarios_sistema 
            SET intentos_fallidos = intentos_fallidos + 1,
                bloqueado_hasta = CASE 
                    WHEN intentos_fallidos + 1 >= 3 
                    THEN DATE_ADD(NOW(), INTERVAL 15 MINUTE)
                    ELSE bloqueado_hasta
                END
            WHERE id = :id";
    $stmt = $this->db->prepare($sql);
    return $stmt->execute(['id' => $userId]);
}
```

---

## 🧪 Testing

### 1️⃣ Verificar Login

```bash
# Servidor corriendo en localhost:8000
http://localhost:8000/login

# Probar con:
Usuario: admin
Password: Admin123!
```

### 2️⃣ Verificar Dashboard

- ✅ Debe cargar sin errores
- ✅ Debe mostrar nombre completo del usuario
- ✅ Debe mostrar rol correcto
- ✅ Menú lateral debe funcionar

### 3️⃣ Verificar Sesiones

```sql
-- En phpMyAdmin o terminal MySQL
USE sena_control;

-- Ver personas
SELECT * FROM personas;

-- Ver usuarios sistema
SELECT 
    us.username,
    CONCAT(p.nombres, ' ', p.apellidos) AS nombre_completo,
    crs.nombre AS rol,
    us.estado
FROM usuarios_sistema us
JOIN personas p ON us.persona_id = p.id
JOIN cat_rol_sistema crs ON us.rol_id = crs.id;

-- Ver auditoría
SELECT * FROM auditoria ORDER BY created_at DESC LIMIT 10;
```

---

## 🛠️ Solución de Problemas

### ❌ Error: "Table 'sena_control.usuarios' doesn't exist"

**Causa:** El código PHP sigue usando tabla `usuarios` antigua

**Solución:** Actualizar modelos PHP para usar `usuarios_sistema`

### ❌ Error: "Column 'rol' not found"

**Causa:** La columna `rol` ya no está en `usuarios_sistema`, ahora es `rol_id` (FK)

**Solución:** Usar JOINs con `cat_rol_sistema` y acceder `crs.codigo` o `crs.nombre`

### ❌ Error: Login no funciona

**Causa:** Las columnas cambiaron de nombre

**Solución:** 
- `estado = 'activo'` → `estado = 'ACTIVO'`
- `ultimo_acceso` → `last_login_at`
- Verificar que consulta SQL use JOINs correctos

### ⚠️ Backup Falló

**Solución:**
```bash
# Hacer backup manual primero
mysqldump -u root -p senaaccses > senaaccses_manual_backup.sql
```

---

## 📈 Próximos Pasos

Después de la migración exitosa, puedes implementar:

1. **Control de Ingreso Biométrico**
   - Registrar huellas
   - Marcar entradas/salidas
   - Reportes de asistencia

2. **Gestión de Ambientes**
   - Crear salas/laboratorios
   - Sistema de reservas
   - Control de llaves

3. **Permisos de Salida**
   - Instructor crea permiso
   - Vigilante valida permiso
   - Registro de salida/regreso

4. **Reportes y Dashboards**
   - Asistencia por día/semana/mes
   - Ocupación de ambientes
   - Llaves en préstamo
   - Permisos activos

---

## 📞 Soporte

Si encuentras problemas:
1. Revisa la tabla `senaaccses_backup` (respaldo automático)
2. Verifica logs de MySQL
3. Consulta este documento nuevamente
4. Revisa los errores en navegador (F12 → Console)

---

**✅ ¡Migración lista! Ahora tienes una base sólida para tu sistema completo.**
