<?php
// ==============================================================================
// 1. CONEXIÓN A LA BASE DE DATOS
// ==============================================================================
require_once __DIR__ . "/../../models/conexion.php";

// ==============================================================================
// 2. CAPTURA DE FILTROS (Búsqueda, Tipo, Estado)
// ==============================================================================
$search = $_GET['search'] ?? '';
$tipo = $_GET['tipo'] ?? 'Todos';
$estado = $_GET['estado'] ?? 'Todos';

// ==============================================================================
// 3. CONSULTAS DE ESTADÍSTICAS
// ==============================================================================
/* Aquí debes ejecutar tus consultas SQL para obtener los totales reales. */
// ESTOS VALORES SON SIMULADOS PARA QUE PRUEBES LA INTERFAZ
$totalUsuarios = 1; 
$totalActivos = 1;  
$totalInactivos = 0; 

// ==============================================================================
// 4. CONSULTA PRINCIPAL DE USUARIOS (Con Filtros)
// ==============================================================================
/*
// Lógica real de base de datos...
*/

// UN USUARIO DE PRUEBA PARA ENSAYAR LOS BOTONES Y LA TABLA
$usuarios_db = [
    [
        'documento' => '123321456',
        'nombre' => 'Alberto Cárdenas',
        'tipo' => 'Vigilante',
        'empresa' => 'Atlas',
        'email' => 'alberti@gmail.com',
        'username' => '@alberto12',
        'rol' => 'Vigilante',
        'estado' => 1 // 1 para Activo, 0 para Inactivo
    ]
]; 
?>

<style>
    .stat-card {
        background: white;
        border-radius: 16px;
        padding: 24px;
        border: 1px solid #e5e7eb;
        display: flex;
        align-items: center;
        gap: 20px;
        transition: all 0.3s;
    }
    .stat-card:hover {
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    .stat-icon {
        width: 60px;
        height: 60px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        font-size: 28px;
    }
    .stat-value {
        font-size: 32px;
        font-weight: bold;
        margin: 0;
    }
    .stat-label {
        color: #6b7280;
        font-weight: 500;
        margin: 0;
    }
</style>

<!-- Gestión de Usuarios Content -->
<div style="padding: 32px;">
    <!-- Header Section -->
    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 32px;">
        <div style="display: flex; align-items: center; gap: 16px;">
            <svg style="width: 40px; height: 40px; color: #6b7280;" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
            </svg>
            <h3 style="font-size: 30px; font-weight: bold; margin: 0;">Gestión de Usuarios</h3>
        </div>
        <div style="display: flex; gap: 12px;">
            <button style="background-color: #1fb6ff; color: white; padding: 10px 20px; border-radius: 8px; font-weight: 500; display: flex; align-items: center; gap: 8px; border: none; cursor: pointer;">
                <svg style="width: 20px; height: 20px;" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5" /></svg>
                Importar CSV
            </button>
            <a href="?seccion=usuarios" style="background-color: #4caf50; color: white; padding: 10px 20px; border-radius: 8px; font-weight: 500; display: flex; align-items: center; gap: 8px; text-decoration: none;">
                <svg style="width: 20px; height: 20px;" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                Crear Usuario
            </a>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 24px; margin-bottom: 32px;">
        <!-- Total Usuarios -->
        <div class="stat-card">
            <div class="stat-icon" style="background-color: #dbeafe;">
                <svg style="color: #3b82f6;" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" /></svg>
            </div>
            <div>
                <p class="stat-value"><?php echo htmlspecialchars($totalUsuarios); ?></p>
                <p class="stat-label">Total Usuarios</p>
            </div>
        </div>

        <!-- Activos -->
        <div class="stat-card">
            <div class="stat-icon" style="background-color: #dcfce7;">
                <svg style="color: #4caf50;" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            </div>
            <div>
                <p class="stat-value" style="color: #4caf50;"><?php echo htmlspecialchars($totalActivos); ?></p>
                <p class="stat-label">Activos</p>
            </div>
        </div>

        <!-- Inactivos -->
        <div class="stat-card">
            <div class="stat-icon" style="background-color: #e0f2fe;">
                <svg style="color: #0ea5e9;" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            </div>
            <div>
                <p class="stat-value" style="color: #0ea5e9;"><?php echo htmlspecialchars($totalInactivos); ?></p>
                <p class="stat-label">Inactivos</p>
            </div>
        </div>
    </div>

    <!-- Search and Filters Form -->
    <form method="GET" style="background: white; padding: 24px; border-radius: 16px; border: 1px solid #e5e7eb; margin-bottom: 32px; display: grid; grid-template-columns: 1fr 200px 200px auto auto; gap: 16px; align-items: flex-end;">
        <div>
            <label style="display: block; font-size: 14px; font-weight: 500; color: #374151; margin-bottom: 8px;">Buscar</label>
            <input type="hidden" name="seccion" value="usuarios">
            <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>" placeholder="Documento, nombre, email..." style="width: 100%; padding: 10px 16px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 14px;">
        </div>
        <div>
            <label style="display: block; font-size: 14px; font-weight: 500; color: #374151; margin-bottom: 8px;">Tipo</label>
            <select name="tipo" style="width: 100%; padding: 10px 16px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 14px;">
                <option value="Todos" <?php echo $tipo == 'Todos' ? 'selected' : ''; ?>>Todos</option>
                <option value="Vigilante" <?php echo $tipo == 'Vigilante' ? 'selected' : ''; ?>>Vigilante</option>
                <option value="Contratista" <?php echo $tipo == 'Contratista' ? 'selected' : ''; ?>>Contratista</option>
                <option value="Persona" <?php echo $tipo == 'Persona' ? 'selected' : ''; ?>>Persona</option>
            </select>
        </div>
        <div>
            <label style="display: block; font-size: 14px; font-weight: 500; color: #374151; margin-bottom: 8px;">Estado</label>
            <select name="estado" style="width: 100%; padding: 10px 16px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 14px;">
                <option value="Todos" <?php echo $estado == 'Todos' ? 'selected' : ''; ?>>Todos</option>
                <option value="Activos" <?php echo $estado == 'Activos' ? 'selected' : ''; ?>>Activos</option>
                <option value="Inactivos" <?php echo $estado == 'Inactivos' ? 'selected' : ''; ?>>Inactivos</option>
            </select>
        </div>
        <button type="submit" style="background-color: #4caf50; color: white; padding: 10px 20px; border-radius: 8px; font-weight: 500; display: flex; align-items: center; gap: 8px; border: none; cursor: pointer;">
            <svg style="width: 20px; height: 20px;" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" /></svg>
            Buscar
        </button>
        <a href="?seccion=usuarios" style="background-color: #546e7a; color: white; padding: 10px 20px; border-radius: 8px; font-weight: 500; display: flex; align-items: center; gap: 8px; text-decoration: none;">
            <svg style="width: 20px; height: 20px;" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" /></svg>
            Limpiar
        </a>
    </form>

    <!-- Users Table -->
    <div style="background: white; border-radius: 16px; border: 1px solid #e5e7eb; overflow: hidden;">
        <table style="width: 100%; border-collapse: collapse;">
            <thead style="background-color: #f9fafb; border-bottom: 1px solid #e5e7eb;">
                <tr>
                    <th style="text-align: left; padding: 20px; font-size: 14px; font-weight: 600; color: #374151;">Documento</th>
                    <th style="text-align: left; padding: 20px; font-size: 14px; font-weight: 600; color: #374151;">Nombre</th>
                    <th style="text-align: left; padding: 20px; font-size: 14px; font-weight: 600; color: #374151;">Tipo</th>
                    <th style="text-align: left; padding: 20px; font-size: 14px; font-weight: 600; color: #374151;">Empresa</th>
                    <th style="text-align: left; padding: 20px; font-size: 14px; font-weight: 600; color: #374151;">Email/Usuario</th>
                    <th style="text-align: left; padding: 20px; font-size: 14px; font-weight: 600; color: #374151;">Rol</th>
                    <th style="text-align: left; padding: 20px; font-size: 14px; font-weight: 600; color: #374151;">Estado</th>
                    <th style="text-align: left; padding: 20px; font-size: 14px; font-weight: 600; color: #374151;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($usuarios_db)): ?>
                    <tr>
                        <td colspan="8" style="padding: 32px; text-align: center; color: #6b7280;">
                            No se encontraron usuarios o la base de datos está vacía.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($usuarios_db as $index => $user): 
                        $row_bg = ($index % 2 == 1) ? '#f9fafb' : 'white';
                    ?>
                        <tr style="border-bottom: 1px solid #e5e7eb; background-color: <?php echo $row_bg; ?>; transition: background-color 0.3s;">
                            <td style="padding: 20px; font-weight: 500;"><?php echo htmlspecialchars($user['documento']); ?></td>
                            <td style="padding: 20px; color: #111827;"><?php echo htmlspecialchars($user['nombre']); ?></td>
                            <td style="padding: 20px;">
                                <?php 
                                    $badge_color = match($user['tipo']) {
                                        'Vigilante' => '#8bc34a',
                                        'Contratista' => '#4caf50',
                                        default => '#607d8b'
                                    };
                                ?>
                                <span style="background-color: <?php echo $badge_color; ?>; color: white; padding: 6px 12px; border-radius: 24px; font-size: 12px; font-weight: 600;">
                                    <?php echo htmlspecialchars($user['tipo']); ?>
                                </span>
                            </td>
                            <td style="padding: 20px; color: #4b5563;"><?php echo htmlspecialchars($user['empresa']); ?></td>
                            <td style="padding: 20px; color: #111827;">
                                <?php if (!empty($user['email'])): ?>
                                    <?php echo htmlspecialchars($user['email']); ?><br>
                                    <span style="font-size: 12px; color: #9ca3af;"><?php echo htmlspecialchars($user['username']); ?></span>
                                <?php else: ?>
                                    -
                                <?php endif; ?>
                            </td>
                            <td style="padding: 20px;">
                                <span style="background-color: #6b7280; color: white; padding: 6px 12px; border-radius: 24px; font-size: 12px; font-weight: 600;">
                                    <?php echo htmlspecialchars($user['rol']); ?>
                                </span>
                            </td>
                            <td style="padding: 20px;">
                                <?php if ($user['estado'] == 1): ?>
                                    <div style="display: flex; align-items: center; gap: 6px; color: #4caf50; font-weight: 600;">
                                        <svg style="width: 20px; height: 20px;" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                        Activo
                                    </div>
                                <?php else: ?>
                                    <div style="display: flex; align-items: center; gap: 6px; color: #ef4444; font-weight: 600;">
                                        <svg style="width: 20px; height: 20px;" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                        Inactivo
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td style="padding: 20px;">
                                <div style="display: flex; gap: 10px;">
                                    <a href="?seccion=usuarios&action=edit&id=<?php echo htmlspecialchars($user['documento']); ?>" style="background-color: #1fb6ff; color: white; padding: 8px; border-radius: 6px; display: inline-block; text-decoration: none;">
                                        <svg style="width: 16px; height: 16px;" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" /></svg>
                                    </a>
                                    <button style="background-color: #f97316; color: white; padding: 8px; border-radius: 6px; border: none; cursor: pointer;">
                                        <svg style="width: 16px; height: 16px;" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" /></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
