<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Control de Llaves - Plantilla Limpia</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-slate-50 text-slate-800 flex h-screen overflow-hidden">

    <aside class="w-64 bg-[#673ab7] text-white flex flex-col justify-between hidden md:flex">
        <div>
            <div class="p-6 flex items-center gap-3">
                <i class="fa-solid fa-person-rays text-2xl text-green-400"></i>
                <h1 class="text-xl font-bold tracking-wide">Sistema Ingreso</h1>
            </div>
            <nav class="mt-2 text-sm" id="menu-navegacion">
                <a href="#" class="flex items-center gap-3 px-6 py-3 hover:bg-white/10 transition"><i class="fa-solid fa-house w-5"></i> Dashboard</a>
                <a href="#" class="flex items-center gap-3 px-6 py-3 bg-[#52b202] text-white font-medium shadow-md"><i class="fa-solid fa-key w-5"></i> Control de Llaves</a>
                <a href="#" class="flex items-center gap-3 px-6 py-3 hover:bg-white/10 transition"><i class="fa-solid fa-file-lines w-5"></i> Reportes</a>
            </nav>
        </div>
    </aside>

    <main class="flex-1 flex flex-col h-full">
        <header class="h-16 bg-white border-b flex items-center justify-between px-8 shrink-0">
            <h2 class="text-lg font-medium tracking-tight">Control de Llaves</h2>
            <div class="flex items-center gap-3">
                <span class="text-sm font-medium" id="usuario-logeado-nombre">Administrador</span>
                <div class="w-8 h-8 rounded-full bg-green-600 text-white flex items-center justify-center"><i class="fa-regular fa-user"></i></div>
            </div>
        </header>

        <div class="flex-1 overflow-y-auto p-8">
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-slate-800 flex items-center gap-3 mb-1">
                    <i class="fa-solid fa-key text-[#2c3e50]"></i> Gestión de Préstamos
                </h1>
                <p class="text-slate-500 italic">Panel administrativo de inventario y disponibilidad</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-10">
                <div class="bg-white rounded-xl shadow-sm border p-5 flex items-center gap-5">
                    <div class="w-12 h-12 rounded-full bg-cyan-50 flex items-center justify-center text-cyan-500 text-xl"><i class="fa-solid fa-door-closed"></i></div>
                    <div><p class="text-2xl font-bold" id="stat-total-aulas">0</p><p class="text-xs text-slate-500 uppercase font-semibold tracking-wider">Aulas</p></div>
                </div>
                <div class="bg-white rounded-xl shadow-sm border p-5 flex items-center gap-5">
                    <div class="w-12 h-12 rounded-full bg-blue-50 flex items-center justify-center text-blue-400 text-xl"><i class="fa-solid fa-key"></i></div>
                    <div><p class="text-2xl font-bold" id="stat-total-llaves">0</p><p class="text-xs text-slate-500 uppercase font-semibold tracking-wider">Llaves</p></div>
                </div>
                <div class="bg-white rounded-xl shadow-sm border p-5 flex items-center gap-5">
                    <div class="w-12 h-12 rounded-full bg-red-500 flex items-center justify-center text-white text-xl"><i class="fa-solid fa-hand-holding"></i></div>
                    <div><p class="text-2xl font-bold" id="stat-llaves-prestadas">0</p><p class="text-xs text-slate-500 uppercase font-semibold tracking-wider">Prestadas</p></div>
                </div>
                <div class="bg-white rounded-xl shadow-sm border p-5 flex items-center gap-5">
                    <div class="w-12 h-12 rounded-full bg-green-500 flex items-center justify-center text-white text-xl"><i class="fa-solid fa-clock"></i></div>
                    <div><p class="text-2xl font-bold" id="stat-prestamos-hoy">0</p><p class="text-xs text-slate-500 uppercase font-semibold tracking-wider">Hoy</p></div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-bold">Listado de Aulas</h3>
                    <div class="flex items-center gap-3">
                        <button id="btn-nueva-aula" class="bg-[#2e7d32] text-white px-4 py-2 rounded text-sm font-medium hover:bg-green-800 transition"> + Nueva Aula </button>
                        <button id="btn-registrar-prestamo" class="bg-[#10b981] text-white px-4 py-2 rounded text-sm font-medium hover:bg-emerald-600 transition"> Tomar/Devolver </button>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse" id="tabla-aulas">
                        <thead>
                            <tr class="text-slate-400 text-xs uppercase tracking-widest border-b border-slate-100">
                                <th class="pb-4 font-bold">Aula / Descripción</th>
                                <th class="pb-4 font-bold text-center">Capacidad</th>
                                <th class="pb-4 font-bold text-center">Llaves</th>
                                <th class="pb-4 font-bold text-center">Estado</th>
                                <th class="pb-4 font-bold text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="tbody-aulas">
                            
                        </tbody>
                    </table>

                    <div id="mensaje-sin-datos" class="py-20 text-center text-slate-400 border-2 border-dashed border-slate-50 mt-4 rounded-lg">
                        <i class="fa-solid fa-inbox text-4xl mb-3 block"></i>
                        No hay aulas registradas en el sistema.
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>
</html>