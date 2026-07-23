<?php
$search = $_GET['search'] ?? '';
$estado = $_GET['estado'] ?? 'Todos';

// Estadísticas
$resTotales = mysqli_query($conexion, "SELECT COUNT(*) as total FROM usuarios UNION ALL SELECT COUNT(*) as total FROM personal_externo");
$totalUsuarios = 0;
while($row = mysqli_fetch_assoc($resTotales)) $totalUsuarios += $row['total'];

$resActivos = mysqli_query($conexion, "SELECT COUNT(*) as total FROM usuarios WHERE estado='Activo' UNION ALL SELECT COUNT(*) as total FROM personal_externo WHERE estado='Dentro'");
$totalActivos = 0;
while($row = mysqli_fetch_assoc($resActivos)) $totalActivos += $row['total'];

$totalInactivos = $totalUsuarios - $totalActivos;

// Consulta principal
$search_esc = mysqli_real_escape_string($conexion, $search);
$sql = "SELECT Dni as documento, CONCAT(nombre, ' ', apellido) as nombre, 'Usuario' as tipo, 'N/A' as empresa, correo, IF(estado='Activo',1,0) as estado, 'usuario' as origen FROM usuarios WHERE (Dni LIKE '%$search_esc%' OR nombre LIKE '%$search_esc%' OR apellido LIKE '%$search_esc%') UNION ALL SELECT documento, nombre, 'Externo' as tipo, empresa, 'N/A' as correo, IF(estado='Dentro',1,0) as estado, 'externo' as origen FROM personal_externo WHERE (documento LIKE '%$search_esc%' OR nombre LIKE '%$search_esc%')";

if ($estado !== 'Todos') $sql .= " AND estado = " . ($estado === 'Activos' ? 1 : 0);
$sql .= " ORDER BY documento ASC";

$resultado = mysqli_query($conexion, $sql);
$usuarios = [];
if ($resultado) while($row = mysqli_fetch_assoc($resultado)) $usuarios[] = $row;
?>

<div style="padding: 20px;">
    <h1 style="margin: 0 0 20px 0; font-size: 20px;">Gestión de Usuarios</h1>

    <!-- Botones -->
    <div style="text-align: right; margin-bottom: 20px;">
        <a href="?seccion=crear-usuario" style="background: #22c55e; color: white; padding: 8px 14px; border-radius: 4px; text-decoration: none; font-weight: 600; font-size: 13px; display: inline-block;">+ Crear Usuario</a>
    </div>

    <!-- Estadísticas -->
    <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 15px; margin-bottom: 20px;">
        <div style="background: white; padding: 15px; border-radius: 4px; border: 1px solid #ddd; text-align: center;">
            <div style="font-size: 24px; font-weight: bold; color: #3b82f6;"><?= $totalUsuarios ?></div>
            <div style="font-size: 11px; color: #666; margin-top: 5px;">Total Usuarios</div>
        </div>
        <div style="background: white; padding: 15px; border-radius: 4px; border: 1px solid #ddd; text-align: center;">
            <div style="font-size: 24px; font-weight: bold; color: #22c55e;"><?= $totalActivos ?></div>
            <div style="font-size: 11px; color: #666; margin-top: 5px;">Activos</div>
        </div>
        <div style="background: white; padding: 15px; border-radius: 4px; border: 1px solid #ddd; text-align: center;">
            <div style="font-size: 24px; font-weight: bold; color: #ef4444;"><?= $totalInactivos ?></div>
            <div style="font-size: 11px; color: #666; margin-top: 5px;">Inactivos</div>
        </div>
    </div>

    <!-- Búsqueda -->
    <form method="GET" action="?seccion=usuarios" style="background: white; padding: 15px; border-radius: 4px; margin-bottom: 20px; border: 1px solid #ddd;">
        <div style="display: grid; grid-template-columns: 2fr 1fr 1fr auto auto; gap: 10px; align-items: flex-end;">
            <div>
                <label style="display: block; font-size: 11px; font-weight: 600; color: #666; margin-bottom: 4px;">Buscar</label>
                <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="Documento, nombre..." style="width: 100%; padding: 6px; border: 1px solid #ddd; border-radius: 3px; font-size: 12px;">
            </div>
            <div>
                <label style="display: block; font-size: 11px; font-weight: 600; color: #666; margin-bottom: 4px;">Tipo</label>
                <select name="tipo" style="width: 100%; padding: 6px; border: 1px solid #ddd; border-radius: 3px; font-size: 12px;">
                    <option>Todos</option>
                </select>
            </div>
            <div>
                <label style="display: block; font-size: 11px; font-weight: 600; color: #666; margin-bottom: 4px;">Estado</label>
                <select name="estado" style="width: 100%; padding: 6px; border: 1px solid #ddd; border-radius: 3px; font-size: 12px;">
                    <option value="Todos" <?= $estado === 'Todos' ? 'selected' : '' ?>>Todos</option>
                    <option value="Activos" <?= $estado === 'Activos' ? 'selected' : '' ?>>Activos</option>
                    <option value="Inactivos" <?= $estado === 'Inactivos' ? 'selected' : '' ?>>Inactivos</option>
                </select>
            </div>
            <button type="submit" style="background: #22c55e; color: white; padding: 6px 12px; border: none; border-radius: 3px; cursor: pointer; font-weight: 600; font-size: 12px;">Buscar</button>
            <a href="?seccion=usuarios" style="background: #666; color: white; padding: 6px 12px; border-radius: 3px; text-decoration: none; font-weight: 600; font-size: 12px;">Limpiar</a>
        </div>
    </form>

    <!-- Tabla -->
    <div style="background: white; border-radius: 4px; border: 1px solid #ddd; overflow: hidden;">
        <table style="width: 100%; border-collapse: collapse; font-size: 12px;">
            <thead style="background: #f5f5f5; border-bottom: 1px solid #ddd;">
                <tr>
                    <th style="padding: 10px; text-align: left; font-weight: 600;">Documento</th>
                    <th style="padding: 10px; text-align: left; font-weight: 600;">Nombre</th>
                    <th style="padding: 10px; text-align: left; font-weight: 600;">Tipo</th>
                    <th style="padding: 10px; text-align: left; font-weight: 600;">Empresa</th>
                    <th style="padding: 10px; text-align: left; font-weight: 600;">Email/Usuario</th>
                    <th style="padding: 10px; text-align: left; font-weight: 600;">Rol</th>
                    <th style="padding: 10px; text-align: center; font-weight: 600;">Estado</th>
                    <th style="padding: 10px; text-align: center; font-weight: 600;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($usuarios)): ?>
                    <tr>
                        <td colspan="8" style="padding: 20px; text-align: center; color: #999;">No hay usuarios</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($usuarios as $i => $user): 
                        $bg = $i % 2 == 0 ? 'white' : '#fafafa';
                        $estado_txt = $user['estado'] == 1 ? 'Activo' : 'Inactivo';
                        $estado_color = $user['estado'] == 1 ? '#22c55e' : '#ef4444';
                    ?>
                        <tr style="background: <?= $bg ?>; border-bottom: 1px solid #eee;">
                            <td style="padding: 10px;"><?= htmlspecialchars($user['documento']) ?></td>
                            <td style="padding: 10px;"><?= htmlspecialchars($user['nombre']) ?></td>
                            <td style="padding: 10px;"><span style="background: #e8f5e9; color: #2e7d32; padding: 2px 6px; border-radius: 3px; font-size: 11px;"><?= htmlspecialchars($user['tipo']) ?></span></td>
                            <td style="padding: 10px; color: #666;"><?= htmlspecialchars($user['empresa']) ?></td>
                            <td style="padding: 10px; color: #666;"><?= htmlspecialchars($user['correo']) ?></td>
                            <td style="padding: 10px;">Usuario</td>
                            <td style="padding: 10px; text-align: center; font-weight: 600; color: <?= $estado_color ?>;"><?= $estado_txt ?></td>
                            <td style="padding: 10px; text-align: center;">
                                <div style="display: flex; gap: 5px; justify-content: center;">
                                    <?php if ($user['origen'] === 'usuario'): ?>
                                        <a href="?seccion=usuarios-editar&id=<?= htmlspecialchars($user['documento']) ?>" style="background: #3b82f6; color: white; padding: 3px 8px; border-radius: 3px; text-decoration: none; font-size: 10px;">✎</a>
                                    <?php endif; ?>
                                    <form method="POST" action="" style="margin: 0; display: inline;" onsubmit="return confirm('¿Eliminar?');">
                                        <input type="hidden" name="accion" value="eliminar-usuario">
                                        <input type="hidden" name="delete_dni" value="<?= htmlspecialchars($user['documento']) ?>">
                                        <input type="hidden" name="origen" value="<?= htmlspecialchars($user['origen']) ?>">
                                        <button type="submit" style="background: #ef4444; color: white; padding: 3px 8px; border: none; border-radius: 3px; cursor: pointer; font-size: 10px;">✕</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php
$search = $_GET['search'] ?? '';
$estado = $_GET['estado'] ?? 'Todos';

// Estadísticas
$resTotales = mysqli_query($conexion, "
    SELECT COUNT(*) as total FROM usuarios
    UNION ALL
    SELECT COUNT(*) as total FROM personal_externo
");
$totalUsuarios = 0;
while($row = mysqli_fetch_assoc($resTotales)) {
    $totalUsuarios += $row['total'];
}

$resActivos = mysqli_query($conexion, "
    SELECT COUNT(*) as total FROM usuarios WHERE estado='Activo'
    UNION ALL
    SELECT COUNT(*) as total FROM personal_externo WHERE estado='Dentro'
");
$totalActivos = 0;
while($row = mysqli_fetch_assoc($resActivos)) {
    $totalActivos += $row['total'];
}

$totalInactivos = $totalUsuarios - $totalActivos;

// Consulta principal
$search_esc = mysqli_real_escape_string($conexion, $search);
$sql = "
    SELECT Dni as documento, CONCAT(nombre, ' ', apellido) as nombre, 'Usuario' as tipo, 
           'N/A' as empresa, correo, IF(estado='Activo',1,0) as estado, 'usuario' as origen
    FROM usuarios
    WHERE (Dni LIKE '%$search_esc%' OR nombre LIKE '%$search_esc%' OR apellido LIKE '%$search_esc%')
    UNION ALL
    SELECT documento, nombre, 'Externo' as tipo, empresa, 'N/A' as correo, 
           IF(estado='Dentro',1,0) as estado, 'externo' as origen
    FROM personal_externo
    WHERE (documento LIKE '%$search_esc%' OR nombre LIKE '%$search_esc%')
";

if ($estado !== 'Todos') {
    $est = ($estado === 'Activos') ? 1 : 0;
    $sql .= " AND estado = $est";
}

$sql .= " ORDER BY documento ASC";
$resultado = mysqli_query($conexion, $sql);
$usuarios = [];
if ($resultado) {
    while($row = mysqli_fetch_assoc($resultado)) {
        $usuarios[] = $row;
    }
}
?>

<div style="padding: 30px;">
    <!-- Encabezado -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
        <h2 style="margin: 0; font-size: 24px; font-weight: bold;">Gestión de Usuarios</h2>
        <a href="?seccion=crear-usuario" style="background: #22c55e; color: white; padding: 10px 16px; border-radius: 6px; text-decoration: none; font-weight: 600; display: inline-flex; align-items: center; gap: 6px;">
            ➕ Crear Usuario
        </a>
    </div>

    <!-- Estadísticas -->
    <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-bottom: 30px;">
        <div style="background: #f5f5f5; padding: 20px; border-radius: 8px; border-left: 4px solid #3b82f6;">
            <div style="font-size: 28px; font-weight: bold; color: #1e40af;"><?= $totalUsuarios ?></div>
            <div style="font-size: 12px; color: #666; margin-top: 5px;">Total Usuarios</div>
        </div>
        <div style="background: #f5f5f5; padding: 20px; border-radius: 8px; border-left: 4px solid #22c55e;">
            <div style="font-size: 28px; font-weight: bold; color: #15803d;"><?= $totalActivos ?></div>
            <div style="font-size: 12px; color: #666; margin-top: 5px;">Activos/Dentro</div>
        </div>
        <div style="background: #f5f5f5; padding: 20px; border-radius: 8px; border-left: 4px solid #ef4444;">
            <div style="font-size: 28px; font-weight: bold; color: #991b1b;"><?= $totalInactivos ?></div>
            <div style="font-size: 12px; color: #666; margin-top: 5px;">Inactivos/Salió</div>
        </div>
    </div>

    <!-- Filtros -->
    <form method="GET" action="?seccion=usuarios" style="background: white; padding: 20px; border-radius: 8px; margin-bottom: 20px; display: flex; gap: 10px; align-items: flex-end;">
        <div style="flex: 1;">
            <label style="display: block; font-size: 12px; font-weight: 600; color: #666; margin-bottom: 5px;">Buscar</label>
            <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="Nombre, documento, empresa..." style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; font-size: 13px;">
        </div>
        <div>
            <label style="display: block; font-size: 12px; font-weight: 600; color: #666; margin-bottom: 5px;">Estado</label>
            <select name="estado" style="padding: 8px; border: 1px solid #ddd; border-radius: 4px; font-size: 13px;">
                <option value="Todos" <?= $estado === 'Todos' ? 'selected' : '' ?>>Todos</option>
                <option value="Activos" <?= $estado === 'Activos' ? 'selected' : '' ?>>Activos</option>
                <option value="Inactivos" <?= $estado === 'Inactivos' ? 'selected' : '' ?>>Inactivos</option>
            </select>
        </div>
        <button type="submit" style="background: #22c55e; color: white; padding: 8px 16px; border: none; border-radius: 4px; cursor: pointer; font-weight: 600;">🔍 Buscar</button>
        <a href="?seccion=usuarios" style="background: #666; color: white; padding: 8px 16px; border-radius: 4px; text-decoration: none; font-weight: 600;">↻ Limpiar</a>
    </form>

    <!-- Tabla -->
    <div style="background: white; border-radius: 8px; overflow: hidden; border: 1px solid #ddd;">
        <table style="width: 100%; border-collapse: collapse;">
            <thead style="background: #f0f0f0; border-bottom: 2px solid #ddd;">
                <tr>
                    <th style="padding: 15px; text-align: left; font-weight: 600; font-size: 13px; color: #333;">Documento</th>
                    <th style="padding: 15px; text-align: left; font-weight: 600; font-size: 13px; color: #333;">Nombre</th>
                    <th style="padding: 15px; text-align: left; font-weight: 600; font-size: 13px; color: #333;">Tipo</th>
                    <th style="padding: 15px; text-align: left; font-weight: 600; font-size: 13px; color: #333;">Empresa</th>
                    <th style="padding: 15px; text-align: left; font-weight: 600; font-size: 13px; color: #333;">Email</th>
                    <th style="padding: 15px; text-align: left; font-weight: 600; font-size: 13px; color: #333;">Estado</th>
                    <th style="padding: 15px; text-align: center; font-weight: 600; font-size: 13px; color: #333;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($usuarios)): ?>
                    <tr>
                        <td colspan="7" style="padding: 40px; text-align: center; color: #999;">No se encontraron usuarios.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($usuarios as $i => $user): 
                        $bg = $i % 2 == 0 ? 'white' : '#fafafa';
                        $estado_texto = $user['estado'] == 1 ? 'Activo' : 'Inactivo';
                        $estado_color = $user['estado'] == 1 ? '#22c55e' : '#ef4444';
                    ?>
                        <tr style="background: <?= $bg ?>; border-bottom: 1px solid #eee;">
                            <td style="padding: 12px 15px; font-size: 13px;"><?= htmlspecialchars($user['documento']) ?></td>
                            <td style="padding: 12px 15px; font-size: 13px;"><?= htmlspecialchars($user['nombre']) ?></td>
                            <td style="padding: 12px 15px; font-size: 13px;"><?= htmlspecialchars($user['tipo']) ?></td>
                            <td style="padding: 12px 15px; font-size: 13px; color: #666;"><?= htmlspecialchars($user['empresa']) ?></td>
                            <td style="padding: 12px 15px; font-size: 13px; color: #666;"><?= htmlspecialchars($user['correo']) ?></td>
                            <td style="padding: 12px 15px; font-size: 13px; color: <?= $estado_color ?>; font-weight: 600;"><?= $estado_texto ?></td>
                            <td style="padding: 12px 15px; text-align: center;">
                                <div style="display: flex; gap: 8px; justify-content: center;">
                                    <?php if ($user['origen'] === 'usuario'): ?>
                                        <a href="?seccion=usuarios-editar&id=<?= htmlspecialchars($user['documento']) ?>" style="background: #3b82f6; color: white; padding: 6px 12px; border-radius: 4px; text-decoration: none; font-size: 12px; font-weight: 600;">Editar</a>
                                    <?php endif; ?>
                                    <form method="POST" action="" style="margin: 0; display: inline;" onsubmit="return confirm('¿Eliminar este registro?');">
                                        <input type="hidden" name="accion" value="eliminar-usuario">
                                        <input type="hidden" name="delete_dni" value="<?= htmlspecialchars($user['documento']) ?>">
                                        <input type="hidden" name="origen" value="<?= htmlspecialchars($user['origen']) ?>">
                                        <button type="submit" style="background: #ef4444; color: white; padding: 6px 12px; border: none; border-radius: 4px; cursor: pointer; font-size: 12px; font-weight: 600;">Eliminar</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php
// ==============================================================================
// LA LÓGICA SE PROCESA EN index.php - SOLO MOSTRAMOS LOS DATOS
// ==============================================================================
$search = $_GET['search'] ?? '';
$tipo = $_GET['tipo'] ?? 'Todos';
$estado = $_GET['estado'] ?? 'Todos';

// ==============================================================================
// CONSULTAS DE ESTADÍSTICAS REALES (Combinadas)
// ==============================================================================
// Total Usuarios (Usuarios + Externos)
$resTotales = mysqli_query($conexion, "
    SELECT SUM(total) as gran_total FROM (
        SELECT COUNT(*) as total FROM usuarios
        UNION ALL
        SELECT COUNT(*) as total FROM personal_externo
    ) as conteos
");
$totalUsuarios = mysqli_fetch_assoc($resTotales)['gran_total'] ?? 0;

// Total Activos
$resActivos = mysqli_query($conexion, "
    SELECT SUM(total) as gran_total FROM (
        SELECT COUNT(*) as total FROM usuarios WHERE estado = 'Activo'
        UNION ALL
        SELECT COUNT(*) as total FROM personal_externo WHERE estado = 'Dentro'
    ) as conteos
");
$totalActivos = mysqli_fetch_assoc($resActivos)['gran_total'] ?? 0;

// Total Inactivos
$resInactivos = mysqli_query($conexion, "
    SELECT SUM(total) as gran_total FROM (
        SELECT COUNT(*) as total FROM usuarios WHERE estado = 'Inactivo'
        UNION ALL
        SELECT COUNT(*) as total FROM personal_externo WHERE estado = 'Salió'
    ) as conteos
");
$totalInactivos = mysqli_fetch_assoc($resInactivos)['gran_total'] ?? 0;

// ==============================================================================
// CONSULTA PRINCIPAL COMBINADA
// ==============================================================================
$search_esc = mysqli_real_escape_string($conexion, $search);

$query_usuarios = "
    SELECT 
        Dni as documento, 
        CONCAT(nombre, ' ', apellido) as nombre_completo,
        'Persona' as tipo,
        '-' as empresa,
        correo as email,
        '' as username,
        'Usuario' as rol,
        IF(estado = 'Activo', 1, 0) as estado_num,
        'usuario' as origen,
        fecha_creacion
    FROM usuarios 
    WHERE 1=1
";

$query_externos = "
    SELECT 
        documento, 
        nombre as nombre_completo,
        'Externo' as tipo,
        IFNULL(empresa, '-') as empresa,
        '-' as email,
        '' as username,
        'Personal Externo' as rol,
        IF(estado = 'Dentro', 1, 0) as estado_num,
        'externo' as origen,
        fecha_creacion
    FROM personal_externo
    WHERE 1=1
";

// Filtros de búsqueda
if (!empty($search)) {
    $cond_search_u = " AND (Dni LIKE '%$search_esc%' OR nombre LIKE '%$search_esc%' OR apellido LIKE '%$search_esc%' OR correo LIKE '%$search_esc%')";
    $cond_search_e = " AND (documento LIKE '%$search_esc%' OR nombre LIKE '%$search_esc%' OR empresa LIKE '%$search_esc%')";
    
    $query_usuarios .= $cond_search_u;
    $query_externos .= $cond_search_e;
}

// Filtros de estado
if ($estado === 'Activos') {
    $query_usuarios .= " AND estado = 'Activo'";
    $query_externos .= " AND estado = 'Dentro'";
} elseif ($estado === 'Inactivos') {
    $query_usuarios .= " AND estado = 'Inactivo'";
    $query_externos .= " AND estado = 'Salió'";
}

// Unir ambas consultas
$query_final = "($query_usuarios) UNION ALL ($query_externos) ORDER BY fecha_creacion DESC";
$resultado = mysqli_query($conexion, $query_final);

$usuarios_db = [];
if ($resultado && mysqli_num_rows($resultado) > 0) {
    while ($row = mysqli_fetch_assoc($resultado)) {
        $usuarios_db[] = [
            'documento' => $row['documento'],
            'nombre' => $row['nombre_completo'],
            'tipo' => $row['tipo'],
            'empresa' => $row['empresa'],
            'email' => $row['email'],
            'username' => $row['username'],
            'rol' => $row['rol'],
            'estado' => $row['estado_num'],
            'origen' => $row['origen']
        ];
    }
}
?>
