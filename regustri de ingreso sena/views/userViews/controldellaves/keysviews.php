<div class="p-8 w-full h-full overflow-y-auto bg-slate-50">
    
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-slate-800 flex items-center gap-3 mb-1">
            <i class="fa-solid fa-key text-[#2c3e50]"></i> Gestión de Préstamos
        </h1>
        <p class="text-slate-500 italic">Panel administrativo de inventario y disponibilidad</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-10">
        <div class="bg-white rounded-xl shadow-sm border p-5 flex items-center gap-5">
            <div class="w-12 h-12 rounded-full bg-cyan-50 flex items-center justify-center text-cyan-500 text-xl"><i class="fa-solid fa-door-closed"></i></div>
            <div><p class="text-2xl font-bold" id="stat-total-aulas">0</p><p class="text-xs text-slate-500 uppercase font-semibold">Aulas</p></div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border p-5 flex items-center gap-5">
            <div class="w-12 h-12 rounded-full bg-blue-50 flex items-center justify-center text-blue-400 text-xl"><i class="fa-solid fa-key"></i></div>
            <div><p class="text-2xl font-bold" id="stat-total-llaves">0</p><p class="text-xs text-slate-500 uppercase font-semibold">Llaves</p></div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border p-5 flex items-center gap-5">
            <div class="w-12 h-12 rounded-full bg-red-500 flex items-center justify-center text-white text-xl"><i class="fa-solid fa-hand-holding"></i></div>
            <div><p class="text-2xl font-bold" id="stat-llaves-prestadas">0</p><p class="text-xs text-slate-500 uppercase font-semibold">Prestadas</p></div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border p-5 flex items-center gap-5">
            <div class="w-12 h-12 rounded-full bg-green-500 flex items-center justify-center text-white text-xl"><i class="fa-solid fa-clock"></i></div>
            <div><p class="text-2xl font-bold" id="stat-prestamos-hoy">0</p><p class="text-xs text-slate-500 uppercase font-semibold">Hoy</p></div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-bold">Listado de Aulas</h3>
            <div class="flex gap-3">
                <button id="btn-nueva-aula" class="bg-[#2e7d32] text-white px-4 py-2 rounded text-sm font-medium hover:bg-green-800 transition">
                    + Nueva Aula
                </button>
                <button id="btn-registrar-prestamo" class="bg-[#10b981] text-white px-4 py-2 rounded text-sm font-medium hover:bg-emerald-600 transition">
                    Tomar/Devolver
                </button>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse" id="tabla-aulas">
                <thead>
                    <tr>
                        <th class="pb-4 font-bold uppercase tracking-widest text-xs text-slate-400 border-b border-slate-100 text-left">Aula / Descripción</th>
                        <th class="pb-4 font-bold uppercase tracking-widest text-xs text-slate-400 border-b border-slate-100 text-center">Capacidad</th>
                        <th class="pb-4 font-bold uppercase tracking-widest text-xs text-slate-400 border-b border-slate-100 text-center">Llaves</th>
                        <th class="pb-4 font-bold uppercase tracking-widest text-xs text-slate-400 border-b border-slate-100 text-center">Estado</th>
                        <th class="pb-4 font-bold uppercase tracking-widest text-xs text-slate-400 border-b border-slate-100 text-center">Acciones</th>
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