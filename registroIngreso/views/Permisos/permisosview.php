<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Permisos de Salida - Sistema Ingreso SENA</title>
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --sidebar-bg: #6a3fa5;
            --sidebar-hover: #7b4db8;
            --sidebar-active: #28b463;
            --accent-green: #28b463;
            --accent-teal: #1abc9c;
            --accent-blue: #3498db;
            --accent-orange: #e67e22;
            --text-light: #ffffff;
            --body-bg: #f4f6f9;
            --card-shadow: 0 2px 10px rgba(0,0,0,0.08);
        }

        * { box-sizing: border-box; }

        body {
            font-family: 'Nunito', sans-serif;
            background: var(--body-bg);
            margin: 0;
            display: flex;
            min-height: 100vh;
        }



        /* ===== MAIN CONTENT ===== */
        .main-content {
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .topbar {
            background: white;
            padding: 14px 30px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid #e8ecf0;
            box-shadow: 0 1px 4px rgba(0,0,0,0.05);
        }

        .topbar-title {
            font-size: 1.05rem;
            font-weight: 700;
            color: #333;
        }

        .topbar-user {
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 700;
            color: #333;
            font-size: 0.95rem;
        }

        .topbar-avatar {
            width: 36px;
            height: 36px;
            background: var(--accent-green);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1rem;
        }

        .page-body {
            padding: 30px;
            flex: 1;
        }

        /* ===== PAGE HEADER ===== */
        .page-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 28px;
        }

        .page-title {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 1.7rem;
            font-weight: 800;
            color: #222;
        }

        .page-title i { color: #555; }

        .btn-crear {
            background: var(--accent-green);
            color: white;
            border: none;
            padding: 10px 22px;
            border-radius: 8px;
            font-size: 0.95rem;
            font-weight: 700;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
            transition: background 0.2s, transform 0.15s;
        }
        .btn-crear:hover { background: #219a52; color: white; transform: translateY(-1px); }

        /* ===== STATS CARDS ===== */
        .stats-section {
            background: white;
            border-radius: 14px;
            padding: 24px 28px;
            margin-bottom: 24px;
            box-shadow: var(--card-shadow);
        }

        .stat-row {
            display: flex;
            align-items: center;
            padding: 14px 0;
            border-bottom: 1px solid #f0f2f5;
            gap: 18px;
        }

        .stat-row:last-child { border-bottom: none; }

        .stat-icon {
            width: 52px;
            height: 52px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
            flex-shrink: 0;
        }

        .stat-icon.teal   { background: #e0f7f4; color: #1abc9c; }
        .stat-icon.green  { background: #e8f8f0; color: #28b463; }
        .stat-icon.blue   { background: #e8f4fd; color: #3498db; }
        .stat-icon.orange { background: #fef5e7; color: #e67e22; }

        .stat-info .stat-value {
            font-size: 1.5rem;
            font-weight: 800;
            color: #222;
            line-height: 1;
        }

        .stat-info .stat-label {
            font-size: 0.88rem;
            color: #888;
            font-weight: 600;
            margin-top: 2px;
        }

        /* ===== FILTERS SECTION ===== */
        .filters-section {
            background: white;
            border-radius: 14px;
            padding: 22px 28px;
            margin-bottom: 24px;
            box-shadow: var(--card-shadow);
        }

        .section-title {
            font-size: 1rem;
            font-weight: 800;
            color: #333;
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 16px;
        }

        .filter-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 14px;
        }

        .filter-label {
            font-size: 0.82rem;
            font-weight: 700;
            color: #555;
            margin-bottom: 5px;
            display: block;
        }

        .filter-input {
            width: 100%;
            padding: 8px 12px;
            border: 1.5px solid #e0e4ea;
            border-radius: 8px;
            font-size: 0.88rem;
            font-family: 'Nunito', sans-serif;
            color: #333;
            background: #fafbfc;
            transition: border-color 0.2s;
        }

        .filter-input:focus {
            outline: none;
            border-color: var(--sidebar-bg);
            background: white;
        }

        .btn-filter {
            background: var(--sidebar-bg);
            color: white;
            border: none;
            padding: 9px 20px;
            border-radius: 8px;
            font-size: 0.88rem;
            font-weight: 700;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 6px;
            transition: background 0.2s;
            align-self: flex-end;
        }
        .btn-filter:hover { background: #7b4db8; }

        /* ===== TABLE SECTION ===== */
        .table-section {
            background: white;
            border-radius: 14px;
            padding: 22px 28px;
            box-shadow: var(--card-shadow);
        }

        .table-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 16px;
        }

        .permisos-table {
            width: 100%;
            border-collapse: collapse;
        }

        .permisos-table thead th {
            background: #f8f9fb;
            padding: 10px 14px;
            font-size: 0.8rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #666;
            border-bottom: 2px solid #eee;
            white-space: nowrap;
        }

        .permisos-table tbody td {
            padding: 12px 14px;
            font-size: 0.9rem;
            color: #333;
            border-bottom: 1px solid #f0f2f5;
        }

        .permisos-table tbody tr:hover { background: #fafbff; }

        .badge-status {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.78rem;
            font-weight: 700;
        }
        .badge-activo   { background: #e8f8f0; color: #28b463; }
        .badge-usado    { background: #e8f4fd; color: #3498db; }
        .badge-vencido  { background: #fdecea; color: #e74c3c; }

        .btn-table-action {
            padding: 5px 12px;
            border-radius: 6px;
            font-size: 0.78rem;
            font-weight: 700;
            border: none;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            transition: opacity 0.2s;
        }
        .btn-table-action:hover { opacity: 0.8; }
        .btn-view   { background: #e8f4fd; color: #3498db; }
        .btn-edit   { background: #e8f8f0; color: #28b463; }
        .btn-delete { background: #fdecea; color: #e74c3c; }

        .empty-state {
            text-align: center;
            padding: 48px 20px;
            color: #aaa;
        }
        .empty-state i { font-size: 3rem; margin-bottom: 12px; display: block; }
        .empty-state p { font-size: 0.95rem; font-weight: 600; }

        /* ===== MODAL / FORM OVERLAY ===== */
        .modal-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.45);
            z-index: 200;
            justify-content: center;
            align-items: flex-start;
            padding: 30px 20px;
            overflow-y: auto;
        }
        .modal-overlay.show { display: flex; }

        .modal-card {
            background: white;
            border-radius: 16px;
            width: 100%;
            max-width: 720px;
            padding: 32px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.18);
            margin: auto;
        }

        .modal-header-title {
            font-size: 1.5rem;
            font-weight: 800;
            color: #222;
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 4px;
        }

        .modal-back {
            color: var(--sidebar-bg);
            text-decoration: none;
            font-size: 0.88rem;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            margin-bottom: 22px;
            cursor: pointer;
        }
        .modal-back:hover { text-decoration: underline; }

        .form-section-title {
            font-size: 1rem;
            font-weight: 800;
            color: #333;
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 6px;
        }

        .form-hint {
            font-size: 0.82rem;
            color: #888;
            margin-bottom: 20px;
        }

        .form-field {
            margin-bottom: 20px;
        }

        .form-field-label {
            font-size: 0.88rem;
            font-weight: 700;
            color: #444;
            display: flex;
            align-items: center;
            gap: 6px;
            margin-bottom: 6px;
        }

        .form-field-label .required { color: #e74c3c; }

        .form-control-custom {
            width: 100%;
            padding: 11px 14px;
            border: 1.5px solid #dde2ea;
            border-radius: 9px;
            font-size: 0.92rem;
            font-family: 'Nunito', sans-serif;
            color: #333;
            background: white;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .form-control-custom:focus {
            outline: none;
            border-color: var(--sidebar-bg);
            box-shadow: 0 0 0 3px rgba(106,63,165,0.1);
        }

        .form-control-custom::placeholder { color: #bbb; }
        .form-control-custom:disabled { background: #f4f6f9; color: #999; }

        .form-field-hint {
            font-size: 0.78rem;
            color: #aaa;
            margin-top: 4px;
        }

        .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 18px; }

        .btn-submit {
            background: var(--accent-green);
            color: white;
            border: none;
            padding: 12px 32px;
            border-radius: 9px;
            font-size: 0.95rem;
            font-weight: 700;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: background 0.2s, transform 0.15s;
        }
        .btn-submit:hover { background: #219a52; transform: translateY(-1px); }

        .btn-cancel-form {
            background: #f0f2f5;
            color: #555;
            border: none;
            padding: 12px 24px;
            border-radius: 9px;
            font-size: 0.95rem;
            font-weight: 700;
            cursor: pointer;
            transition: background 0.2s;
        }
        .btn-cancel-form:hover { background: #e2e5ea; }

        .divider { border: none; border-top: 1px solid #f0f2f5; margin: 22px 0; }

        /* Textarea */
        textarea.form-control-custom { resize: vertical; min-height: 90px; }

        /* ===== ALERT ===== */
        .alert-success {
            background: #e8f8f0;
            color: #1a7a40;
            border-radius: 10px;
            padding: 12px 18px;
            font-weight: 600;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 20px;
        }

        @media (max-width: 768px) {
            .form-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>

<!-- ===== MAIN CONTENT ===== -->
<main class="main-content">

    <!-- TOPBAR -->
    <div class="topbar" id="topbar-title">
        <span class="topbar-title">Permisos de Salida</span>
        <div class="topbar-user">
            Administrador Sistema
            <div class="topbar-avatar"><i class="bi bi-person-fill"></i></div>
        </div>
    </div>

    <!-- PAGE BODY — LIST VIEW -->
    <div class="page-body" id="view-list">

        <div class="page-header">
            <h1 class="page-title">
                <i class="bi bi-clipboard-check-fill"></i>
                Permisos de Salida
            </h1>
            <button class="btn-crear" onclick="showCreateForm()">
                <i class="bi bi-plus-lg"></i> Crear Permiso
            </button>
        </div>

        <!-- STATS -->
        <div class="stats-section">
            <div class="stat-row">
                <div class="stat-icon teal"><i class="bi bi-clipboard-data-fill"></i></div>
                <div class="stat-info">
                    <div class="stat-value" id="stat-total">0</div>
                    <div class="stat-label">Total Permisos</div>
                </div>
            </div>
            <div class="stat-row">
                <div class="stat-icon green"><i class="bi bi-check-circle-fill"></i></div>
                <div class="stat-info">
                    <div class="stat-value" id="stat-activos">—</div>
                    <div class="stat-label">Activos</div>
                </div>
            </div>
            <div class="stat-row">
                <div class="stat-icon blue"><i class="bi bi-door-open-fill"></i></div>
                <div class="stat-info">
                    <div class="stat-value" id="stat-usados">—</div>
                    <div class="stat-label">Usados</div>
                </div>
            </div>
            <div class="stat-row">
                <div class="stat-icon orange"><i class="bi bi-clock-fill"></i></div>
                <div class="stat-info">
                    <div class="stat-value" id="stat-vencidos">—</div>
                    <div class="stat-label">Vencidos</div>
                </div>
            </div>
        </div>

        <!-- FILTERS -->
        <div class="filters-section">
            <div class="section-title">
                <i class="bi bi-funnel-fill"></i> Filtros
            </div>
            <div class="filter-grid">
                <div>
                    <span class="filter-label">Documento</span>
                    <input type="text" class="filter-input" id="filter-doc" placeholder="Nro. documento...">
                </div>
                <div>
                    <span class="filter-label">Estado</span>
                    <select class="filter-input" id="filter-estado">
                        <option value="">Todos</option>
                        <option value="Activo">Activo</option>
                        <option value="Usado">Usado</option>
                        <option value="Vencido">Vencido</option>
                    </select>
                </div>
                <div>
                    <span class="filter-label">Fecha Desde</span>
                    <input type="date" class="filter-input" id="filter-desde">
                </div>
                <div>
                    <span class="filter-label">Fecha Hasta</span>
                    <input type="date" class="filter-input" id="filter-hasta">
                </div>
                <div style="display:flex; align-items:flex-end;">
                    <button class="btn-filter" onclick="applyFilters()">
                        <i class="bi bi-search"></i> Buscar
                    </button>
                </div>
            </div>
        </div>

        <!-- TABLE -->
        <div class="table-section">
            <div class="table-header">
                <div class="section-title" style="margin-bottom:0">
                    <i class="bi bi-table"></i> Lista de Permisos
                </div>
            </div>

            <div id="table-container">
                <div class="empty-state">
                    <i class="bi bi-clipboard-x"></i>
                    <p>No hay permisos registrados aún.<br>Haz clic en <strong>+ Crear Permiso</strong> para agregar uno.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- PAGE BODY — CREATE/EDIT FORM -->
    <div class="page-body" id="view-form" style="display:none;">

        <div class="modal-card" style="max-width:100%; border-radius:14px; box-shadow:none;">

            <h1 class="modal-header-title">
                <i class="bi bi-plus-circle-fill"></i> Crear Permiso de Salida
            </h1>

            <a class="modal-back" onclick="showList()">
                <i class="bi bi-arrow-left"></i> Volver
            </a>

            <div id="form-alert" style="display:none;"></div>

            <div class="form-section-title">
                <i class="bi bi-pencil-square"></i> Información del Permiso
            </div>
            <p class="form-hint">
                <i class="bi bi-info-circle"></i>
                Complete la información del aprendiz que requiere permiso de salida. Los campos marcados con <span style="color:#e74c3c">*</span> son obligatorios.
            </p>

            <div class="form-grid">
                <div class="form-field">
                    <label class="form-field-label">
                        <i class="bi bi-card-text"></i>
                        Documento del Aprendiz <span class="required">*</span>
                    </label>
                    <input
                        type="text"
                        class="form-control-custom"
                        id="field-documento"
                        placeholder="Ingrese el documento y presione Tab"
                        onblur="buscarAprendiz()"
                    >
                    <div class="form-field-hint">Ingrese el documento y presione Tab para buscar</div>
                </div>

                <div class="form-field">
                    <label class="form-field-label">
                        <i class="bi bi-person-fill"></i>
                        Nombre Completo del Aprendiz
                    </label>
                    <input
                        type="text"
                        class="form-control-custom"
                        id="field-nombre"
                        placeholder="Se mostrará automáticamente"
                        disabled
                    >
                    <div class="form-field-hint">El nombre se obtiene automáticamente del sistema</div>
                </div>
            </div>

            <div class="form-grid">
                <div class="form-field">
                    <label class="form-field-label">
                        <i class="bi bi-calendar-event-fill"></i>
                        Fecha del Permiso <span class="required">*</span>
                    </label>
                    <input
                        type="date"
                        class="form-control-custom"
                        id="field-fecha"
                    >
                </div>

                <div class="form-field">
                    <label class="form-field-label">
                        <i class="bi bi-clock-fill"></i>
                        Hora de Salida <span class="required">*</span>
                    </label>
                    <input
                        type="time"
                        class="form-control-custom"
                        id="field-hora-salida"
                    >
                </div>
            </div>

            <div class="form-grid">
                <div class="form-field">
                    <label class="form-field-label">
                        <i class="bi bi-clock-history"></i>
                        Hora de Regreso Estimada
                    </label>
                    <input
                        type="time"
                        class="form-control-custom"
                        id="field-hora-regreso"
                    >
                </div>

                <div class="form-field">
                    <label class="form-field-label">
                        <i class="bi bi-person-badge-fill"></i>
                        Instructor Autoriza <span class="required">*</span>
                    </label>
                    <input
                        type="text"
                        class="form-control-custom"
                        id="field-instructor"
                        placeholder="Nombre del instructor"
                    >
                </div>
            </div>

            <div class="form-field">
                <label class="form-field-label">
                    <i class="bi bi-chat-left-text-fill"></i>
                    Motivo del Permiso <span class="required">*</span>
                </label>
                <textarea
                    class="form-control-custom"
                    id="field-motivo"
                    placeholder="Describa el motivo del permiso de salida..."
                ></textarea>
            </div>

            <hr class="divider">

            <div style="display:flex; gap:12px; justify-content:flex-end;">
                <button class="btn-cancel-form" onclick="showList()">
                    <i class="bi bi-x-circle"></i> Cancelar
                </button>
                <button class="btn-submit" onclick="guardarPermiso()">
                    <i class="bi bi-check-circle-fill"></i> Guardar Permiso
                </button>
            </div>
        </div>
    </div>

</main>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // ======= DATA STORE (reemplazar con llamadas PHP/AJAX reales) =======
    let permisos = [];
    let editingId = null;

    // ======= NAVIGATION =======
    function showCreateForm() {
        document.getElementById('view-list').style.display = 'none';
        document.getElementById('view-form').style.display = 'block';
        document.getElementById('topbar-title').querySelector('.topbar-title').textContent = 'Crear Permiso de Salida';
        resetForm();
        setDefaultDateTime();
    }

    function showList() {
        document.getElementById('view-list').style.display = 'block';
        document.getElementById('view-form').style.display = 'none';
        document.getElementById('topbar-title').querySelector('.topbar-title').textContent = 'Permisos de Salida';
        editingId = null;
    }

    // ======= FORM UTILITIES =======
    function setDefaultDateTime() {
        const now = new Date();
        const fecha = now.toISOString().split('T')[0];
        const hora  = now.toTimeString().slice(0, 5);
        document.getElementById('field-fecha').value = fecha;
        document.getElementById('field-hora-salida').value = hora;
    }

    function resetForm() {
        ['field-documento','field-nombre','field-hora-regreso','field-instructor','field-motivo']
            .forEach(id => document.getElementById(id).value = '');
        document.getElementById('form-alert').style.display = 'none';
    }

    function buscarAprendiz() {
        const doc = document.getElementById('field-documento').value.trim();
        // Aquí se haría fetch('/controller/buscar_aprendiz.php?doc='+doc)
        // Simulación demo:
        if (doc.length >= 6) {
            document.getElementById('field-nombre').value = 'Aprendiz Ejemplo ' + doc.slice(-3);
        } else {
            document.getElementById('field-nombre').value = '';
        }
    }

    // ======= CRUD =======
    function guardarPermiso() {
        const doc      = document.getElementById('field-documento').value.trim();
        const nombre   = document.getElementById('field-nombre').value.trim();
        const fecha    = document.getElementById('field-fecha').value;
        const horaSal  = document.getElementById('field-hora-salida').value;
        const motivo   = document.getElementById('field-motivo').value.trim();
        const instructor = document.getElementById('field-instructor').value.trim();

        if (!doc || !fecha || !horaSal || !motivo || !instructor) {
            mostrarAlerta('Por favor complete todos los campos obligatorios (*).', 'error');
            return;
        }

        const permiso = {
            id: editingId || Date.now(),
            documento: doc,
            nombre: nombre || 'Sin nombre',
            fecha,
            horaSalida: horaSal,
            horaRegreso: document.getElementById('field-hora-regreso').value || '--:--',
            instructor,
            motivo,
            estado: 'Activo'
        };

        if (editingId) {
            permisos = permisos.map(p => p.id === editingId ? permiso : p);
        } else {
            permisos.push(permiso);
        }

        actualizarEstadisticas();
        renderTabla();
        showList();
    }

    function editarPermiso(id) {
        const p = permisos.find(x => x.id === id);
        if (!p) return;
        editingId = id;
        document.getElementById('field-documento').value   = p.documento;
        document.getElementById('field-nombre').value      = p.nombre;
        document.getElementById('field-fecha').value       = p.fecha;
        document.getElementById('field-hora-salida').value = p.horaSalida;
        document.getElementById('field-hora-regreso').value = p.horaRegreso !== '--:--' ? p.horaRegreso : '';
        document.getElementById('field-instructor').value  = p.instructor;
        document.getElementById('field-motivo').value      = p.motivo;
        document.querySelector('.modal-header-title').innerHTML =
            '<i class="bi bi-pencil-fill"></i> Editar Permiso de Salida';
        showCreateForm();
        document.getElementById('field-nombre').value = p.nombre;
    }

    function eliminarPermiso(id) {
        if (!confirm('¿Desea eliminar este permiso?')) return;
        permisos = permisos.filter(p => p.id !== id);
        actualizarEstadisticas();
        renderTabla();
    }

    // ======= STATS =======
    function actualizarEstadisticas() {
        const total    = permisos.length;
        const activos  = permisos.filter(p => p.estado === 'Activo').length;
        const usados   = permisos.filter(p => p.estado === 'Usado').length;
        const vencidos = permisos.filter(p => p.estado === 'Vencido').length;
        document.getElementById('stat-total').textContent   = total;
        document.getElementById('stat-activos').textContent = activos || '—';
        document.getElementById('stat-usados').textContent  = usados  || '—';
        document.getElementById('stat-vencidos').textContent = vencidos || '—';
    }

    // ======= RENDER TABLE =======
    function renderTabla(lista) {
        const data = lista !== undefined ? lista : permisos;
        const container = document.getElementById('table-container');

        if (data.length === 0) {
            container.innerHTML = `
                <div class="empty-state">
                    <i class="bi bi-clipboard-x"></i>
                    <p>No hay permisos registrados aún.<br>Haz clic en <strong>+ Crear Permiso</strong> para agregar uno.</p>
                </div>`;
            return;
        }

        const rows = data.map(p => `
            <tr>
                <td><strong>${p.documento}</strong></td>
                <td>${p.nombre}</td>
                <td>${formatFecha(p.fecha)}</td>
                <td>${p.horaSalida}</td>
                <td>${p.horaRegreso}</td>
                <td>${p.instructor}</td>
                <td>
                    <span class="badge-status badge-${p.estado.toLowerCase()}">${p.estado}</span>
                </td>
                <td>
                    <button class="btn-table-action btn-view" onclick="verPermiso(${p.id})">
                        <i class="bi bi-eye-fill"></i> Ver
                    </button>
                    <button class="btn-table-action btn-edit" onclick="editarPermiso(${p.id})">
                        <i class="bi bi-pencil-fill"></i> Editar
                    </button>
                    <button class="btn-table-action btn-delete" onclick="eliminarPermiso(${p.id})">
                        <i class="bi bi-trash-fill"></i>
                    </button>
                </td>
            </tr>
        `).join('');

        container.innerHTML = `
            <table class="permisos-table">
                <thead>
                    <tr>
                        <th>Documento</th>
                        <th>Nombre</th>
                        <th>Fecha</th>
                        <th>Hora Salida</th>
                        <th>Hora Regreso</th>
                        <th>Instructor</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>${rows}</tbody>
            </table>`;
    }

    // ======= FILTERS =======
    function applyFilters() {
        const doc    = document.getElementById('filter-doc').value.trim().toLowerCase();
        const estado = document.getElementById('filter-estado').value;
        const desde  = document.getElementById('filter-desde').value;
        const hasta  = document.getElementById('filter-hasta').value;

        const filtered = permisos.filter(p => {
            if (doc    && !p.documento.toLowerCase().includes(doc)) return false;
            if (estado && p.estado !== estado) return false;
            if (desde  && p.fecha < desde) return false;
            if (hasta  && p.fecha > hasta)  return false;
            return true;
        });

        renderTabla(filtered);
    }

    // ======= HELPERS =======
    function formatFecha(f) {
        if (!f) return '—';
        const [y,m,d] = f.split('-');
        return `${d}/${m}/${y}`;
    }

    function mostrarAlerta(msg, tipo) {
        const el = document.getElementById('form-alert');
        el.className = tipo === 'error' ? 'alert-success' : 'alert-success';
        el.style.background = tipo === 'error' ? '#fdecea' : '#e8f8f0';
        el.style.color      = tipo === 'error' ? '#c0392b' : '#1a7a40';
        el.innerHTML = `<i class="bi bi-${tipo === 'error' ? 'exclamation-circle-fill' : 'check-circle-fill'}"></i> ${msg}`;
        el.style.display = 'flex';
        if (tipo !== 'error') setTimeout(() => el.style.display = 'none', 3000);
    }

    function verPermiso(id) {
        const p = permisos.find(x => x.id === id);
        if (!p) return;
        alert(`Permiso #${p.id}\nAprendiz: ${p.nombre}\nDocumento: ${p.documento}\nFecha: ${formatFecha(p.fecha)}\nHora Salida: ${p.horaSalida}\nHora Regreso: ${p.horaRegreso}\nInstructor: ${p.instructor}\nMotivo: ${p.motivo}\nEstado: ${p.estado}`);
    }

    // Init
    actualizarEstadisticas();
    renderTabla();
</script>

</body>
</html>