<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Control de Llaves - Solo Vista</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style type="text/tailwindcss">
        .stat-card { @apply bg-white rounded-xl shadow-sm border p-5 flex items-center gap-5; }
        .btn-primario { @apply bg-[#2e7d32] text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-green-800 transition shadow-sm; }
        .btn-secundario { @apply bg-[#10b981] text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-emerald-600 transition shadow-sm; }
        .input-form { @apply w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#673ab7] focus:border-transparent transition; }
    </style>
</head>

<body class="bg-slate-50 text-slate-800 h-screen flex flex-col overflow-hidden relative">

    <header class="h-16 bg-white border-b flex items-center justify-between px-8 shrink-0">
        <h2 class="text-lg font-medium tracking-tight">Control de Llaves</h2>
        <div class="flex items-center gap-3">
            <span id="usuario-logeado-nombre" class="text-sm font-medium">Administrador</span>
            <div class="w-8 h-8 rounded-full bg-green-600 text-white flex items-center justify-center"><i class="fa-regular fa-user"></i></div>
        </div>
    </header>

    <main class="flex-1 overflow-y-auto p-8">
        
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-slate-800 flex items-center gap-3 mb-1">
                <i class="fa-solid fa-key text-[#2c3e50]"></i> Gestión de Préstamos
            </h1>
            <p class="text-slate-500 italic">Panel administrativo de inventario y disponibilidad</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-10">
            <div class="stat-card">
                <div class="w-12 h-12 rounded-full bg-cyan-50 flex items-center justify-center text-cyan-500 text-xl"><i class="fa-solid fa-door-closed"></i></div>
                <div><p class="text-2xl font-bold" id="stat-total-aulas">0</p><p class="text-xs text-slate-500 uppercase font-semibold">Aulas</p></div>
            </div>
            <div class="stat-card">
                <div class="w-12 h-12 rounded-full bg-blue-50 flex items-center justify-center text-blue-400 text-xl"><i class="fa-solid fa-key"></i></div>
                <div><p class="text-2xl font-bold" id="stat-total-llaves">0</p><p class="text-xs text-slate-500 uppercase font-semibold">Llaves</p></div>
            </div>
            <div class="stat-card">
                <div class="w-12 h-12 rounded-full bg-red-500 flex items-center justify-center text-white text-xl"><i class="fa-solid fa-hand-holding"></i></div>
                <div><p class="text-2xl font-bold" id="stat-llaves-prestadas">0</p><p class="text-xs text-slate-500 uppercase font-semibold">Prestadas</p></div>
            </div>
            <div class="stat-card">
                <div class="w-12 h-12 rounded-full bg-green-500 flex items-center justify-center text-white text-xl"><i class="fa-solid fa-clock"></i></div>
                <div><p class="text-2xl font-bold" id="stat-prestamos-hoy">0</p><p class="text-xs text-slate-500 uppercase font-semibold">Hoy</p></div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-bold">Listado de Aulas</h3>
                <div class="flex gap-3">
                    <button id="btn-nueva-aula" class="btn-primario"><i class="fa-solid fa-plus mr-1"></i> Nueva Aula</button>
                    <a href="prestamo_llaves.php" id="btn-registrar-prestamo" class="btn-secundario inline-block">
                        <i class="fa-solid fa-hand-holding-hand mr-1"></i> Tomar/Devolver
                    </a>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse" id="tabla-aulas">
                    <thead>
                        <tr>
                            <th class="pb-4 font-bold uppercase tracking-widest text-xs text-slate-400 border-b border-slate-100">Aula / Descripción</th>
                            <th class="pb-4 font-bold uppercase tracking-widest text-xs text-slate-400 border-b border-slate-100 text-center">Capacidad</th>
                            <th class="pb-4 font-bold uppercase tracking-widest text-xs text-slate-400 border-b border-slate-100 text-center">Llaves</th>
                            <th class="pb-4 font-bold uppercase tracking-widest text-xs text-slate-400 border-b border-slate-100 text-center">Estado</th>
                            <th class="pb-4 font-bold uppercase tracking-widest text-xs text-slate-400 border-b border-slate-100 text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="tbody-aulas">
                        </tbody>
                </table>
                <div id="mensaje-sin-datos" class="py-16 text-center text-slate-400 border-2 border-dashed border-slate-50 mt-4 rounded-lg">
                    <i class="fa-solid fa-inbox text-4xl mb-3 block"></i> No hay aulas registradas en el sistema.
                </div>
            </div>
        </div>
    </main>

    <div id="modal-nueva-aula" class="fixed inset-0 bg-slate-900/60 hidden items-center justify-center z-50 backdrop-blur-sm transition-all">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg overflow-hidden transform transition-all">
            
            <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50">
                <h3 class="font-bold text-lg text-slate-800 flex items-center gap-2">
                    <i class="fa-solid fa-door-open text-[#673ab7]"></i> Registrar Nueva Aula
                </h3>
                <button id="btn-cerrar-modal" class="text-slate-400 hover:text-red-500 transition text-xl">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <div class="p-6">
                <form id="form-nueva-aula" class="space-y-5">
                    <div class="grid grid-cols-2 gap-4">
                        <div class="col-span-2">
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Nombre del Aula <span class="text-red-500">*</span></label>
                            <input type="text" id="input-nombre" class="input-form" placeholder="Ej. Aula 101, Laboratorio A..." required>
                        </div>
                        <div class="col-span-2">
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Descripción breve</label>
                            <input type="text" id="input-desc" class="input-form" placeholder="Ej. Aula de sistemas piso 1">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Capacidad (Personas)</label>
                            <input type="number" id="input-capacidad" class="input-form" placeholder="Ej. 30" min="1">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Total de Llaves <span class="text-red-500">*</span></label>
                            <input type="number" id="input-llaves" class="input-form" placeholder="Ej. 2" min="1" required>
                        </div>
                    </div>
                </form>
            </div>

            <div class="px-6 py-4 border-t border-slate-100 flex justify-end gap-3 bg-slate-50">
                <button id="btn-cancelar-modal" type="button" class="px-5 py-2 text-slate-600 font-medium hover:bg-slate-200 rounded-lg transition">Cancelar</button>
                <button type="submit" form="form-nueva-aula" class="px-5 py-2 bg-[#673ab7] text-white font-medium rounded-lg hover:bg-[#5e35b1] transition shadow-md">Guardar Aula</button>
            </div>

        </div>
    </div>

    <script>
        const modal = document.getElementById('modal-nueva-aula');
        const btnAbrir = document.getElementById('btn-nueva-aula');
        const btnCerrar = document.getElementById('btn-cerrar-modal');
        const btnCancelar = document.getElementById('btn-cancelar-modal');

        btnAbrir.addEventListener('click', () => {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        });

        const cerrarModal = () => {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        };

        btnCerrar.addEventListener('click', cerrarModal);
        btnCancelar.addEventListener('click', cerrarModal);
    </script>

</body>
</html>