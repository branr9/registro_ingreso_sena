# Módulo AMBIENTES

Módulo integrado al MVC y a la identidad visual existente para administrar espacios y registrar reservas temporales. No realiza asignaciones permanentes de ambientes a instructores.

## Instalación

La base activa del entorno de desarrollo ya fue actualizada. En otra instalación, ejecute:

```sql
SOURCE database/ambientes_reservas.sql;
```

La migración crea las tablas `ambientes` y `reservas` con sus índices, validación de horario y llaves foráneas hacia `personas`.

## Funcionalidad

- Panel con indicadores y próximas reservas.
- Creación, consulta, edición, activación y desactivación de ambientes.
- Reservas para cualquier persona responsable, sin exigir que tenga una cuenta o un registro previo en el sistema.
- Registro opcional del documento del responsable y trazabilidad interna del usuario que creó la reserva.
- Prevención transaccional de reservas superpuestas.
- Edición, cancelación, consulta e historial de reservas.
- Calendario mensual de ocupación.
- Consulta de disponibilidad por fecha, horario, capacidad y tipo.
- Exportación CSV del historial filtrado.
- Finalización automática de reservas cuyo horario ya terminó.

## Permisos

- `admin`: administra ambientes y todas las reservas.
- `instructor`: consulta ambientes y disponibilidad, registra reservas para cualquier responsable y administra las operaciones que creó.

Todas las operaciones de escritura validan token CSRF. Las reservas se serializan por ambiente dentro de una transacción antes de comprobar cruces de horario.

## Integración visual

Las vistas reutilizan el layout, Tailwind local, Inter, Font Awesome, tarjetas, tablas, formularios, alertas, paginación y modo oscuro existentes. `public/css/ambientes.css` solo contiene reglas encapsuladas para el calendario y la cuadrícula de disponibilidad.

## Pruebas

```bash
php tests/AmbientesModelSmokeTest.php
node --check public/js/ambientes-calendar.js
```

Para verificar las rutas con el servidor integrado de PHP:

```bash
php -S 127.0.0.1:8765 tests/router.php
```
