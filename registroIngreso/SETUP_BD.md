# Instrucciones para configurar la BD

## PASO 1: Crear la base de datos NEXUS

Abre phpMyAdmin (http://localhost/phpmyadmin) y en la consola SQL ejecuta:

```sql
CREATE DATABASE nexus CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE nexus;
```

## PASO 2: Ejecutar el script SQL

Copia TODO el contenido del archivo **nexus_actualizado.sql** y pégalo en la consola SQL de phpMyAdmin.

O en la terminal MySQL:
```bash
mysql -u root -D nexus < nexus_actualizado.sql
```

## PASO 3: Verificar las tablas

En phpMyAdmin, deberías ver estas tablas en la BD nexus:
- ✅ aulas (con columnas: id_aula, nombre, descripcion, ubicacion, capacidad, total_llaves, disponibles, responsable, activa)
- ✅ llaves (con columnas: id_llave, id_aula, numero_llave, disponible)
- ✅ prestamos_llaves (con columnas: id_prestamo, id_llave, id_usuario, id_aula, usuario_retira, documento, estado, fecha_prestamo, hora_prestamo, fecha_devolucion, hora_devolucion)
- ✅ usuarios (con datos de ejemplo)
- ✅ marcaciones, permisos_salida, personal_externo (tablas de otros módulos)

## PASO 4: Reiniciar el servidor PHP

En la terminal donde está corriendo el servidor:
1. Presiona CTRL+C para detener
2. Ejecuta de nuevo: `php -S localhost:8080`

## PASO 5: Probar

1. Abre http://localhost:8080/?seccion=prestamo-devolucion&tab=tomar
2. Deberías ver 3 aulas de ejemplo con sus llaves
3. Intenta crear una nueva aula en la tab "nueva-aula"
4. Comprueba que aparezca en el grid

## ¿Problemas?

Si ves errores, verifica en consola del navegador (F12 > Console):
- Revisa que la URL de la API sea correcta: `../../api/keyAPI.php`
- Comprueba que la BD "nexus" existe y tiene las tablas
- Verifica que el servidor PHP esté corriendo
