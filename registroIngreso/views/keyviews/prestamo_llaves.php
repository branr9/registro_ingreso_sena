<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Préstamo de Llaves</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-slate-50 text-slate-800 h-screen flex flex-col overflow-hidden relative">

    <header class="h-16 bg-white border-b flex items-center justify-between px-8 shrink-0">
        <h2 class="text-lg font-medium tracking-tight">Préstamo de Llaves</h2>
        <div class="flex items-center gap-3">
            <span class="text-sm font-medium">Administrador Sistema</span>
            <div class="w-8 h-8 rounded-full bg-green-600 text-white flex items-center justify-center font-bold text-sm"><i class="fa-regular fa-user"></i></div>
        </div>
    </header>

    <main class="flex-1 overflow-y-auto p-8">
        
        <div class="mb-8 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-[#2c3e50] flex items-center gap-3 mb-1">
                    <i class="fa-solid fa-key"></i> Préstamo de Llaves
                </h1>
                <p class="text-slate-500">Seleccione el aula para tomar o devolver la llave</p>
            </div>
            <a href="keyviews.php" class="text-slate-600 hover:text-slate-900 font-medium text-sm flex items-center gap-2 border border-slate-300 px-4 py-2 rounded-lg bg-white shadow-sm transition">
                <i class="fa-solid fa-arrow-left"></i> Volver a la Tabla
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-8 flex flex-col h-full hover:shadow-lg transition">
                <div class="flex justify-between items-center border-b border-slate-100 pb-4 mb-4">
                    <h3 class="text-xl font-bold text-slate-800 flex items-center gap-2">
                        <i class="fa-solid fa-door-closed text-slate-600"></i> Aula 101
                    </h3>
                    <span class="text-[10px] font-bold tracking-wider text-slate-600 uppercase">ACTIVO</span>
                </div>
                <div class="space-y-4 flex-1">
                    <div class="p-3 bg-slate-50 rounded-lg border border-slate-100">
                        <p class="text-sm text-slate-500 mb-1">Llaves totales:</p>
                        <p class="text-lg font-bold text-slate-800">2</p>
                    </div>
                    <div class="p-3 bg-green-50 rounded-lg border border-green-100">
                        <p class="text-sm text-green-700 font-medium flex items-center gap-2 mb-1">
                            <i class="fa-solid fa-circle-check text-green-500 text-lg"></i> Disponibles:
                        </p>
                        <p class="text-lg font-bold text-green-900">2</p>
                    </div>
                    <div class="p-3 bg-red-50 rounded-lg border border-red-100">
                        <p class="text-sm text-red-700 font-medium flex items-center gap-2 mb-1">
                            <i class="fa-solid fa-hand-holding text-red-500 text-lg"></i> Prestadas:
                        </p>
                        <p class="text-lg font-bold text-red-900">0</p>
                    </div>
                </div>
                <button onclick="abrirModalLlave('Aula 101')" class="w-full mt-8 bg-[#20c997] hover:bg-emerald-500 text-white font-bold py-4 rounded-xl flex justify-center items-center gap-2 transition shadow-sm uppercase tracking-wider text-sm">
                    <i class="fa-solid fa-hand-holding-hand"></i> Tomar Llave
                </button>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-8 flex flex-col h-full hover:shadow-lg transition">
                <div class="flex justify-between items-center border-b border-slate-100 pb-4 mb-4">
                    <h3 class="text-xl font-bold text-slate-800 flex items-center gap-2">
                        <i class="fa-solid fa-door-closed text-slate-600"></i> Aula 102
                    </h3>
                    <span class="text-[10px] font-bold tracking-wider text-slate-600 uppercase">ACTIVO</span>
                </div>
                <div class="space-y-4 flex-1">
                    <div class="p-3 bg-slate-50 rounded-lg border border-slate-100">
                        <p class="text-sm text-slate-500 mb-1">Llaves totales:</p>
                        <p class="text-lg font-bold text-slate-800">1</p>
                    </div>
                    <div class="p-3 bg-green-50 rounded-lg border border-green-100">
                        <p class="text-sm text-green-700 font-medium flex items-center gap-2 mb-1">
                            <i class="fa-solid fa-circle-check text-green-500 text-lg"></i> Disponibles:
                        </p>
                        <p class="text-lg font-bold text-green-900">1</p>
                    </div>
                    <div class="p-3 bg-red-50 rounded-lg border border-red-100">
                        <p class="text-sm text-red-700 font-medium flex items-center gap-2 mb-1">
                            <i class="fa-solid fa-hand-holding text-red-500 text-lg"></i> Prestadas:
                        </p>
                        <p class="text-lg font-bold text-red-900">0</p>
                    </div>
                </div>
                <button onclick="abrirModalLlave('Aula 102')" class="w-full mt-8 bg-[#20c997] hover:bg-emerald-500 text-white font-bold py-4 rounded-xl flex justify-center items-center gap-2 transition shadow-sm uppercase tracking-wider text-sm">
                    <i class="fa-solid fa-hand-holding-hand"></i> Tomar Llave
                </button>
            </div>

        </div>
    </main>

    <div id="modal-tomar-llave" class="fixed inset-0 bg-slate-900/60 hidden items-center justify-center z-50 backdrop-blur-sm p-4">
        
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-md flex flex-col max-h-[90vh]">
            
            <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center shrink-0">
                <h3 class="font-bold text-lg text-[#2c3e50] flex items-center gap-2">
                    <i class="fa-solid fa-hand-holding-hand"></i> Tomar Llave
                </h3>
                <button onclick="cerrarModalLlave()" class="text-slate-400 hover:text-red-500 transition text-xl">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <div class="p-6 overflow-y-auto">
                
                <p class="text-sm text-slate-500 mb-5">Aula: <span id="modal-aula-nombre" class="font-bold text-slate-800">Cargando...</span></p>

                <form id="form-tomar-llave" class="space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1 flex items-center gap-2">
                            <i class="fa-solid fa-user text-slate-500"></i> Nombre Completo <span class="text-red-500">*</span>
                        </label>
                        <input type="text" placeholder="Nombre completo" required class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#52b202] focus:border-transparent transition">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1 flex items-center gap-2">
                            <i class="fa-solid fa-id-card text-slate-500"></i> Documento <span class="text-red-500">*</span>
                        </label>
                        <input type="text" placeholder="Número de documento" required class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#52b202] focus:border-transparent transition">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1 flex items-center gap-2">
                            <i class="fa-solid fa-building text-slate-500"></i> Departamento
                        </label>
                        <input type="text" placeholder="Departamento al que pertenece" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#52b202] focus:border-transparent transition">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1 flex items-center gap-2">
                            <i class="fa-solid fa-phone text-slate-500"></i> Teléfono
                        </label>
                        <input type="tel" placeholder="Número de teléfono" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#52b202] focus:border-transparent transition">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1 flex items-center gap-2">
                            <i class="fa-solid fa-comment-dots text-slate-500"></i> Observaciones
                        </label>
                        <textarea placeholder="Observaciones..." rows="2" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#52b202] focus:border-transparent transition resize-none"></textarea>
                    </div>
                </form>
            </div>

            <div class="px-6 py-4 border-t border-slate-100 flex justify-end gap-3 shrink-0 bg-slate-50">
                <button type="button" onclick="cerrarModalLlave()" class="px-4 py-2 text-slate-600 font-medium hover:bg-slate-200 rounded-lg transition text-sm">Cancelar</button>
                <button type="submit" form="form-tomar-llave" class="px-5 py-2 bg-[#20c997] text-white font-bold rounded-lg hover:bg-emerald-500 transition shadow-sm text-sm uppercase tracking-wide">
                    Registrar
                </button>
            </div>

        </div>
    </div>

    <script>
        const modalLlave = document.getElementById('modal-tomar-llave');
        const spanAulaNombre = document.getElementById('modal-aula-nombre');

        function abrirModalLlave(nombreAula) {
            spanAulaNombre.textContent = nombreAula;
            modalLlave.classList.remove('hidden');
            modalLlave.classList.add('flex');
        }

        function cerrarModalLlave() {
            modalLlave.classList.add('hidden');
            modalLlave.classList.remove('flex');
        }
    </script>

</body>
</html>