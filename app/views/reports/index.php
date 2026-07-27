<?php require_once APP_PATH . '/views/layouts/header.php'; ?>

<div>
    <h1 class="text-2xl font-extrabold text-primary-700 flex items-center gap-2 mb-1">
        <i class="fas fa-chart-bar"></i> Reportes de Acceso
    </h1>
    <p class="text-sm text-gray-500 mb-6">Consulta y exporta los registros de entrada y salida</p>

    <!-- Filtros -->
    <div class="bg-white/85 backdrop-blur-md border border-primary-100 rounded-3xl shadow-xl overflow-hidden mb-8">
        <div class="px-6 py-4 border-b border-primary-100 bg-gradient-to-r from-white to-primary-50 dark:from-[#1c1830] dark:to-[#241a42]">
            <h3 class="text-lg font-bold text-primary-700"><i class="fas fa-filter"></i> Filtros de Búsqueda</h3>
        </div>
        <div class="p-6">
            <form id="reportForm" method="GET" class="flex flex-wrap items-end gap-4">
                <div class="min-w-[180px]">
                    <label for="fecha_inicio" class="block text-xs font-semibold text-gray-600 mb-1">
                        <i class="fas fa-calendar-alt"></i> Fecha Inicio
                    </label>
                    <input type="date"
                           id="fecha_inicio"
                           name="fecha_inicio"
                           class="w-full rounded-xl border border-gray-200 px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none"
                           value="<?= $_GET['fecha_inicio'] ?? date('Y-m-d', strtotime('-30 days')) ?>"
                           required>
                </div>

                <div class="min-w-[180px]">
                    <label for="fecha_fin" class="block text-xs font-semibold text-gray-600 mb-1">
                        <i class="fas fa-calendar-alt"></i> Fecha Fin
                    </label>
                    <input type="date"
                           id="fecha_fin"
                           name="fecha_fin"
                           class="w-full rounded-xl border border-gray-200 px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none"
                           value="<?= $_GET['fecha_fin'] ?? date('Y-m-d') ?>"
                           required>
                </div>

                <div class="flex-1 min-w-[200px]">
                    <label for="documento" class="block text-xs font-semibold text-gray-600 mb-1">
                        <i class="fas fa-id-card"></i> Documento (Opcional)
                    </label>
                    <input type="text"
                           id="documento"
                           name="documento"
                           class="w-full rounded-xl border border-gray-200 px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none"
                           placeholder="Buscar por documento"
                           value="<?= $_GET['documento'] ?? '' ?>">
                </div>

                <div>
                    <button type="button" onclick="loadReport()"
                            class="inline-flex items-center gap-2 rounded-xl bg-primary-700 hover:bg-primary-800 transition text-white font-semibold px-5 py-2 text-sm">
                        <i class="fas fa-search"></i> Consultar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Resultados -->
    <div class="bg-white/85 backdrop-blur-md border border-primary-100 rounded-3xl shadow-xl overflow-hidden">
        <div class="px-6 py-4 border-b border-primary-100 bg-gradient-to-r from-white to-primary-50 dark:from-[#1c1830] dark:to-[#241a42] flex items-center justify-between flex-wrap gap-3">
            <h3 class="text-lg font-bold text-primary-700"><i class="fas fa-list"></i> Resultados</h3>
            <div class="flex items-center gap-2">
                <button onclick="exportExcel()"
                        class="inline-flex items-center gap-2 rounded-xl bg-green-600 hover:bg-green-700 transition text-white font-semibold px-4 py-2 text-sm">
                    <i class="fas fa-file-excel"></i> Exportar Excel
                </button>
                <button onclick="exportPdf()"
                        class="inline-flex items-center gap-2 rounded-xl bg-red-600 hover:bg-red-700 transition text-white font-semibold px-4 py-2 text-sm">
                    <i class="fas fa-file-pdf"></i> Exportar PDF
                </button>
            </div>
        </div>

        <div class="p-6">
            <!-- Tabs -->
            <div class="flex items-center gap-2 border-b border-gray-200 mb-6">
                <button id="tabBtn-accesos" class="tab-button px-5 py-3 font-semibold text-sm border-b-2 border-primary-700 text-primary-700" onclick="showTab('accesos')">
                    <i class="fas fa-door-open"></i> Accesos
                </button>
                <button id="tabBtn-llaves" class="tab-button px-5 py-3 font-semibold text-sm border-b-2 border-transparent text-gray-500 hover:text-primary-700" onclick="showTab('llaves')">
                    <i class="fas fa-key"></i> Préstamos de Llaves
                </button>
            </div>

            <div id="tab-accesos" class="tab-content">
                <div id="reportResults">
                    <div class="text-center py-16">
                        <i class="fas fa-chart-line text-5xl text-gray-300"></i>
                        <p class="text-gray-500 mt-5">
                            Seleccione un rango de fechas y haga clic en Consultar
                        </p>
                    </div>
                </div>
            </div>

            <div id="tab-llaves" class="tab-content hidden">
                <div id="prestamosResults">
                    <div class="text-center py-16">
                        <i class="fas fa-chart-line text-5xl text-gray-300"></i>
                        <p class="text-gray-500 mt-5">
                            Seleccione un rango de fechas y haga clic en Consultar
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let currentData = null;

function showTab(tabName) {
    // Ocultar todas las pestañas
    document.querySelectorAll('.tab-content').forEach(tab => {
        tab.classList.add('hidden');
    });
    document.querySelectorAll('.tab-button').forEach(btn => {
        btn.classList.remove('border-primary-700', 'text-primary-700');
        btn.classList.add('border-transparent', 'text-gray-500');
    });

    // Mostrar la pestaña seleccionada
    document.getElementById('tab-' + tabName).classList.remove('hidden');
    const activeBtn = document.getElementById('tabBtn-' + tabName);
    activeBtn.classList.remove('border-transparent', 'text-gray-500');
    activeBtn.classList.add('border-primary-700', 'text-primary-700');
}

function loadReport() {
    const fechaInicio = document.getElementById('fecha_inicio').value;
    const fechaFin = document.getElementById('fecha_fin').value;
    const documento = document.getElementById('documento').value;

    if (!fechaInicio || !fechaFin) {
        alert('Por favor seleccione ambas fechas');
        return;
    }

    // Mostrar loading
    document.getElementById('reportResults').innerHTML = `
        <div class="text-center py-16">
            <i class="fas fa-spinner fa-spin text-5xl text-primary-600"></i>
            <p class="text-gray-500 mt-5">Cargando reporte...</p>
        </div>
    `;

    // Construir URL con parámetros
    let url = `<?= baseUrl('/reportes/data') ?>?fecha_inicio=${fechaInicio}&fecha_fin=${fechaFin}`;
    if (documento) {
        url += `&documento=${documento}`;
    }

    // Hacer petición AJAX
    fetch(url)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                currentData = data; // Guardar datos globalmente
                displayReport(data.marcaciones, data.stats);
                displayPrestamos(data.prestamos);
            } else {
                document.getElementById('reportResults').innerHTML = `
                    <div class="text-center py-16">
                        <i class="fas fa-exclamation-triangle text-5xl text-amber-400"></i>
                        <p class="text-gray-500 mt-5">${data.message}</p>
                    </div>
                `;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('reportResults').innerHTML = `
                <div class="text-center py-16">
                    <i class="fas fa-times-circle text-5xl text-red-500"></i>
                    <p class="text-gray-500 mt-5">Error al cargar el reporte</p>
                </div>
            `;
        });
}

function displayReport(marcaciones, stats) {
    let html = `
        <div class="mb-6 grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="bg-white rounded-2xl shadow-sm border border-primary-100 p-4 flex items-center gap-3">
                <div class="w-11 h-11 rounded-xl bg-primary-100 text-primary-700 flex items-center justify-center text-lg flex-shrink-0"><i class="fas fa-list"></i></div>
                <div>
                    <h3 class="text-xl font-extrabold text-gray-800">${stats.total}</h3>
                    <p class="text-xs text-gray-500">Total Registros</p>
                </div>
            </div>
            <div class="bg-white rounded-2xl shadow-sm border border-primary-100 p-4 flex items-center gap-3">
                <div class="w-11 h-11 rounded-xl bg-green-100 text-green-700 flex items-center justify-center text-lg flex-shrink-0"><i class="fas fa-sign-in-alt"></i></div>
                <div>
                    <h3 class="text-xl font-extrabold text-green-700">${stats.entradas}</h3>
                    <p class="text-xs text-gray-500">Entradas</p>
                </div>
            </div>
            <div class="bg-white rounded-2xl shadow-sm border border-primary-100 p-4 flex items-center gap-3">
                <div class="w-11 h-11 rounded-xl bg-red-100 text-red-700 flex items-center justify-center text-lg flex-shrink-0"><i class="fas fa-sign-out-alt"></i></div>
                <div>
                    <h3 class="text-xl font-extrabold text-red-700">${stats.salidas}</h3>
                    <p class="text-xs text-gray-500">Salidas</p>
                </div>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead>
                    <tr class="bg-primary-50 text-primary-800 text-xs uppercase">
                        <th class="px-4 py-3 text-left font-bold">Fecha</th>
                        <th class="px-4 py-3 text-left font-bold">Hora</th>
                        <th class="px-4 py-3 text-left font-bold">Documento</th>
                        <th class="px-4 py-3 text-left font-bold">Nombre</th>
                        <th class="px-4 py-3 text-left font-bold">Tipo Persona</th>
                        <th class="px-4 py-3 text-left font-bold">Tipo Acceso</th>
                        <th class="px-4 py-3 text-left font-bold">Método</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
    `;

    if (marcaciones.length === 0) {
        html += `
            <tr>
                <td colspan="7" class="px-4 py-16 text-center text-gray-400">
                    No se encontraron registros para el período seleccionado
                </td>
            </tr>
        `;
    } else {
        marcaciones.forEach(m => {
            const fecha = new Date(m.fecha_hora);
            const badgeClass = m.tipo_acceso === 'ENTRADA' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700';

            html += `
                <tr class="hover:bg-primary-50/50 transition">
                    <td class="px-4 py-3 text-gray-600 whitespace-nowrap">${fecha.toLocaleDateString('es-CO')}</td>
                    <td class="px-4 py-3 text-gray-600 whitespace-nowrap">${fecha.toLocaleTimeString('es-CO')}</td>
                    <td class="px-4 py-3 font-medium text-gray-700">${m.documento}</td>
                    <td class="px-4 py-3 text-gray-700">${m.nombre_completo}</td>
                    <td class="px-4 py-3 text-gray-600">${m.tipo_persona}</td>
                    <td class="px-4 py-3"><span class="${badgeClass} px-2 py-0.5 rounded-xl text-xs font-semibold">${m.tipo_acceso}</span></td>
                    <td class="px-4 py-3 text-gray-600">${m.metodo}</td>
                </tr>
            `;
        });
    }

    html += `
                </tbody>
            </table>
        </div>
    `;

    document.getElementById('reportResults').innerHTML = html;
}

function displayPrestamos(prestamos) {
    let html = `
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead>
                    <tr class="bg-primary-50 text-primary-800 text-xs uppercase">
                        <th class="px-4 py-3 text-left font-bold">Fecha Préstamo</th>
                        <th class="px-4 py-3 text-left font-bold">Aula</th>
                        <th class="px-4 py-3 text-left font-bold">Documento</th>
                        <th class="px-4 py-3 text-left font-bold">Nombre</th>
                        <th class="px-4 py-3 text-left font-bold">Tipo Persona</th>
                        <th class="px-4 py-3 text-left font-bold">Fecha Devolución</th>
                        <th class="px-4 py-3 text-left font-bold">Estado</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
    `;

    if (prestamos.length === 0) {
        html += `
            <tr>
                <td colspan="7" class="px-4 py-16 text-center text-gray-400">
                    No se encontraron préstamos para el período seleccionado
                </td>
            </tr>
        `;
    } else {
        prestamos.forEach(p => {
            const fechaPrestamo = new Date(p.fecha_prestamo);
            const fechaDevolucion = p.fecha_devolucion ? new Date(p.fecha_devolucion) : null;
            const badgeClass = p.estado === 'PRESTADO' ? 'bg-amber-100 text-amber-700' : p.estado === 'DEVUELTO' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700';

            html += `
                <tr class="hover:bg-primary-50/50 transition">
                    <td class="px-4 py-3 text-gray-600 whitespace-nowrap">${fechaPrestamo.toLocaleDateString('es-CO')} ${fechaPrestamo.toLocaleTimeString('es-CO')}</td>
                    <td class="px-4 py-3"><strong class="text-gray-800">${p.aula_nombre}</strong></td>
                    <td class="px-4 py-3 font-medium text-gray-700">${p.documento}</td>
                    <td class="px-4 py-3 text-gray-700">${p.nombre_completo}</td>
                    <td class="px-4 py-3 text-gray-600">${p.tipo_persona}</td>
                    <td class="px-4 py-3 text-gray-600 whitespace-nowrap">${fechaDevolucion ? fechaDevolucion.toLocaleDateString('es-CO') + ' ' + fechaDevolucion.toLocaleTimeString('es-CO') : '<span class="text-gray-400">Pendiente</span>'}</td>
                    <td class="px-4 py-3"><span class="${badgeClass} px-2 py-0.5 rounded-xl text-xs font-semibold">${p.estado}</span></td>
                </tr>
            `;
        });
    }

    html += `
                </tbody>
            </table>
        </div>
    `;

    document.getElementById('prestamosResults').innerHTML = html;
}

function exportExcel() {
    const fechaInicio = document.getElementById('fecha_inicio').value;
    const fechaFin = document.getElementById('fecha_fin').value;
    const documento = document.getElementById('documento').value;

    if (!fechaInicio || !fechaFin) {
        alert('Por favor seleccione ambas fechas');
        return;
    }

    let url = `<?= baseUrl('/reportes/export-excel') ?>?fecha_inicio=${fechaInicio}&fecha_fin=${fechaFin}`;
    if (documento) {
        url += `&documento=${documento}`;
    }
    window.location.href = url;
}

function exportPdf() {
    const fechaInicio = document.getElementById('fecha_inicio').value;
    const fechaFin = document.getElementById('fecha_fin').value;
    const documento = document.getElementById('documento').value;

    if (!fechaInicio || !fechaFin) {
        alert('Por favor seleccione ambas fechas');
        return;
    }

    let url = `<?= baseUrl('/reportes/export-pdf') ?>?fecha_inicio=${fechaInicio}&fecha_fin=${fechaFin}`;
    if (documento) {
        url += `&documento=${documento}`;
    }
    window.open(url, '_blank');
}
</script>

<?php require_once APP_PATH . '/views/layouts/footer.php'; ?>
