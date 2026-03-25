<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
    .keys-container { width: 100%; padding: 0; }
    .keys-header { background: white; border-bottom: 1px solid #e5e7eb; padding: 16px 32px; display: flex; align-items: center; justify-content: space-between; }
    .keys-header h2 { margin: 0; font-size: 18px; font-weight: 500; color: #333; }
    .keys-main { flex: 1; overflow-y: auto; padding: 32px; }
    .keys-title { font-size: 30px; font-weight: bold; color: #111827; display: flex; align-items: center; gap: 12px; margin-bottom: 8px; }
    .keys-subtitle { font-size: 14px; color: #6b7280; font-style: italic; margin-bottom: 32px; }
    .keys-stats { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 24px; margin-bottom: 40px; }
    .keys-stat-card { background: white; border-radius: 12px; border: 1px solid #e5e7eb; padding: 20px; display: flex; align-items: center; gap: 16px; box-shadow: 0 1px 3px rgba(0,0,0,0.08); }
    .keys-stat-icon { width: 48px; height: 48px; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 24px; }
    .keys-stat-icon.cyan { background: #cffafe; color: #06b6d4; }
    .keys-stat-icon.blue { background: #bfdbfe; color: #3b82f6; }
    .keys-stat-icon.red { background: #fecaca; color: #ef4444; }
    .keys-stat-icon.green { background: #bbf7d0; color: #10b981; }
    .keys-stat-info { flex: 1; }
    .keys-stat-value { font-size: 24px; font-weight: bold; color: #111827; margin: 0; }
    .keys-stat-label { font-size: 12px; color: #6b7280; font-weight: 600; margin: 4px 0 0 0; text-transform: uppercase; }
    .keys-section { background: white; border-radius: 12px; border: 1px solid #e5e7eb; padding: 24px; }
    .keys-section-title { font-size: 16px; font-weight: bold; color: #333; display: flex; align-items: center; gap: 8px; margin-bottom: 16px; }
    .keys-button-group { display: flex; gap: 12px; margin-bottom: 20px; }
    .keys-btn { padding: 10px 20px; border-radius: 8px; font-size: 14px; font-weight: 600; border: none; cursor: pointer; display: flex; align-items: center; gap: 6px; transition: all 0.2s; }
    .keys-btn-primary { background: #2d7c32; color: white; }
    .keys-btn-primary:hover { background: #1f5623; }
    .keys-btn-secondary { background: #10b981; color: white; }
    .keys-btn-secondary:hover { background: #059669; }
    .keys-table { width: 100%; border-collapse: collapse; }
    .keys-table thead th { background: #f9fafb; padding: 12px; text-align: left; font-size: 12px; font-weight: 800; color: #666; text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 2px solid #e5e7eb; }
    .keys-table tbody td { padding: 12px; border-bottom: 1px solid #f3f4f6; font-size: 14px; color: #333; }
    .keys-table tbody tr:hover { background: #fafbff; }
    .keys-badge { padding: 6px 12px; border-radius: 20px; font-size: 12px; font-weight: 700; }
    .keys-badge.disponible { background: #e8f8f0; color: #28b463; }
    .keys-badge.prestada { background: #e8f4fd; color: #3498db; }
    .keys-empty-state { text-align: center; padding: 48px 20px; color: #aaa; }
    .keys-empty-state i { font-size: 48px; margin-bottom: 12px; display: block; }
</style>

<!-- Control de Llaves Content -->
<div class="keys-container">
    <header class="keys-header">
        <h2>Control de Llaves</h2>
    </header>

    <main class="keys-main">
        <div style="margin-bottom: 32px;">
            <h1 class="keys-title">
                <i class="fa-solid fa-key" style="color: #2c3e50;"></i> Gestión de Préstamos
            </h1>
            <p class="keys-subtitle">Panel administrativo de inventario y disponibilidad</p>
        </div>

        <!-- Stats Cards -->
        <div class="keys-stats">
            <div class="keys-stat-card">
                <div class="keys-stat-icon cyan"><i class="fa-solid fa-door-closed"></i></div>
                <div class="keys-stat-info">
                    <p class="keys-stat-value" id="stat-total-aulas">0</p>
                    <p class="keys-stat-label">Aulas</p>
                </div>
            </div>
            <div class="keys-stat-card">
                <div class="keys-stat-icon blue"><i class="fa-solid fa-key"></i></div>
                <div class="keys-stat-info">
                    <p class="keys-stat-value" id="stat-total-llaves">0</p>
                    <p class="keys-stat-label">Llaves</p>
                </div>
            </div>
            <div class="keys-stat-card">
                <div class="keys-stat-icon red"><i class="fa-solid fa-hand-holding"></i></div>
                <div class="keys-stat-info">
                    <p class="keys-stat-value" id="stat-llaves-prestadas">0</p>
                    <p class="keys-stat-label">Prestadas</p>
                </div>
            </div>
            <div class="keys-stat-card">
                <div class="keys-stat-icon green"><i class="fa-solid fa-clock"></i></div>
                <div class="keys-stat-info">
                    <p class="keys-stat-value" id="stat-prestamos-hoy">0</p>
                    <p class="keys-stat-label">Hoy</p>
                </div>
            </div>
        </div>

        <!-- Aulas Section -->
        <div class="keys-section" style="margin-bottom: 24px;">
            <div class="keys-section-title">
                <i class="fa-solid fa-door-closed"></i>Listado de Aulas
            </div>

            <div class="keys-button-group">
                <a href="?seccion=prestamo-devolucion&tab=nueva-aula" class="keys-btn keys-btn-primary">
                    <i class="fa-solid fa-plus"></i> Nueva Aula
                </a>
                <a href="?seccion=prestamo-devolucion&tab=tomar" class="keys-btn keys-btn-secondary">
                    <i class="fa-solid fa-hand-holding-hand"></i> Tomar/Devolver
                </a>
            </div>

            <table class="keys-table" id="tabla-aulas">
                <thead>
                    <tr>
                        <th>Aula / Descripción</th>
                        <th>Capacidad</th>
                        <th>Llaves</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="5" style="text-align: center; padding: 32px; color: #aaa;">
                            <i class="fa-solid fa-inbox" style="font-size: 32px; display: block; margin-bottom: 12px;"></i>
                            No hay aulas registradas en el sistema.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Registro Nueva Aula -->
        <div class="keys-section">
            <div class="keys-section-title">
                <i class="fa-solid fa-folder-plus"></i>Registrar Nueva Aula
            </div>

            <form style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-top: 16px;">
                <div>
                    <label style="display: block; font-size: 13px; font-weight: 700; color: #555; margin-bottom: 6px;">Nombre Aula *</label>
                    <input type="text" placeholder="Ej: Aula 101" style="width: 100%; padding: 10px; border: 1.5px solid #d1d5db; border-radius: 8px; font-size: 14px;">
                </div>
                <div>
                    <label style="display: block; font-size: 13px; font-weight: 700; color: #555; margin-bottom: 6px;">Capacidad *</label>
                    <input type="number" placeholder="30" style="width: 100%; padding: 10px; border: 1.5px solid #d1d5db; border-radius: 8px; font-size: 14px;">
                </div>
                <div>
                    <label style="display: block; font-size: 13px; font-weight: 700; color: #555; margin-bottom: 6px;">Total de Llaves *</label>
                    <input type="number" placeholder="5" style="width: 100%; padding: 10px; border: 1.5px solid #d1d5db; border-radius: 8px; font-size: 14px;">
                </div>
                <div>
                    <label style="display: block; font-size: 13px; font-weight: 700; color: #555; margin-bottom: 6px;">Descripción</label>
                    <input type="text" placeholder="Descripción opcional" style="width: 100%; padding: 10px; border: 1.5px solid #d1d5db; border-radius: 8px; font-size: 14px;">
                </div>
                <div style="grid-column: 1 / -1;">
                    <button type="submit" class="keys-btn keys-btn-primary">
                        <i class="fa-solid fa-check"></i> Guardar Aula
                    </button>
                </div>
            </form>
        </div>
    </main>
</div>

<script>
    // Placeholder para funcionalidad
    console.log('Control de Llaves cargado');
</script>
