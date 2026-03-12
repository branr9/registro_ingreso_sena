<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Usuario - Sistema Ingreso</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'sidebar-bg': '#5c4cd4',
                        'sidebar-hover': '#7165de',
                        'active-menu': '#4caf50',
                        'card-bg': '#ffffff',
                        'btn-green': '#4caf50',
                        'btn-blue': '#1fb6ff',
                        'btn-gray': '#78909c',
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-50 font-sans antialiased text-gray-800">
    <div class="min-h-screen flex">

        <nav class="w-64 bg-sidebar-bg text-white flex flex-col p-4">
            <div class="flex items-center gap-3 mb-10 pb-4 border-b border-white/20">
                <div class="bg-active-menu p-2 rounded-lg">
                    <svg class="w-8 h-8 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                    </svg>
                </div>
                <h1 class="text-xl font-bold">Sistema Ingreso</h1>
            </div>

            <div class="flex flex-col gap-2 flex-grow">
                <a href="../../index.php" class="flex items-center gap-3 p-3 rounded-lg hover:bg-sidebar-hover transition">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21.75h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21.75h7.5" /></svg>
                    Dashboard
                </a>
                <a href="usuarios.php" class="flex items-center gap-3 p-3 rounded-lg bg-active-menu hover:bg-green-600 transition">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" /></svg>
                    Usuarios
                </a>
            </div>

            <div class="mt-auto pt-6 border-t border-white/20">
                <div class="flex items-center gap-3 mb-4">
                    <div class="bg-sky-500 rounded-full w-10 h-10 flex items-center justify-center font-bold text-lg text-white">A</div>
                    <div>
                        <p class="font-semibold text-sm">Administrador Sistema</p>
                        <p class="text-xs text-white/70">ADMIN</p>
                    </div>
                </div>
            </div>
        </nav>

        <main class="flex-grow">
            <header class="bg-white p-5 flex items-center justify-between border-b border-gray-200 shadow-sm">
                <h2 class="text-2xl font-bold text-gray-800">Editar Usuario</h2>
                <div class="flex items-center gap-3">
                    <p class="text-sm font-medium">Administrador Sistema</p>
                    <div class="bg-active-menu rounded-full w-9 h-9 flex items-center justify-center font-bold text-lg text-white">A</div>
                </div>
            </header>

            <div class="p-10 max-w-5xl">
                
                <div class="mb-8">
                    <div class="flex items-center gap-4 mb-2">
                        <<a href="usuariosview.php" class="bg-gray-500 text-white px-4 py-2 rounded-lg font-medium flex items-center gap-2 hover:bg-gray-600 transition">
    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" /></svg>
    Volver
</a>
                        <div class="flex items-center gap-2 text-gray-700">
                            <h3 class="text-3xl font-bold">Editar Usuario</h3>
                        </div>
                    </div>
                    <p class="text-gray-500 ml-[115px]">Editando: <span class="font-semibold text-gray-700">alberto cardenas</span> (123321456)</p>
                </div>

                <form class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100">
                    
                    <div class="mb-10">
                        <div class="flex items-center gap-2 text-btn-green border-b-2 border-btn-green pb-2 mb-6">
                            <h4 class="text-lg font-bold">Datos Personales</h4>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-x-8 gap-y-6">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-1">Documento <span class="text-red-500">*</span></label>
                                <input type="text" value="123321456" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg bg-gray-50 text-gray-600 focus:outline-none" readonly>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-1">Nombre Completo <span class="text-red-500">*</span></label>
                                <input type="text" value="alberto cardenas" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-1 focus:ring-btn-green outline-none">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-1">Tipo de Persona <span class="text-red-500">*</span></label>
                                <select class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-1 focus:ring-btn-green outline-none bg-white">
                                    <option selected>Vigilante</option>
                                    <option>Contratista</option>
                                    <option>Persona</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-1">Empresa/Institución</label>
                                <input type="text" value="atlas" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-1 focus:ring-btn-green outline-none">
                            </div>
                        </div>
                    </div>

                    <div>
                        <div class="flex items-center gap-2 text-btn-green border-b-2 border-btn-green pb-2 mb-6">
                            <h4 class="text-lg font-bold">Datos de Acceso al Sistema</h4>
                        </div>

                        <div class="grid grid-cols-2 gap-x-8 gap-y-6">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-1">Usuario</label>
                                <input type="text" value="@alberto12" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-1 focus:ring-btn-green outline-none">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-1">Email</label>
                                <input type="email" value="alberti@gmail.com" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-1 focus:ring-btn-green outline-none">
                            </div>
                        </div>
                    </div>

                    <div class="mt-10 flex justify-end">
                        <button type="submit" class="bg-btn-green text-white font-bold py-3 px-8 rounded-lg shadow-md hover:bg-green-600 transition">
                            Guardar Cambios
                        </button>
                    </div>

                </form>
            </div>
        </main>
    </div>
</body>
</html>