<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Usuario - Sistema Ingreso</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/heroicons@^2/24/outline.js" defer></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

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

        <nav class="w-[230px] flex-shrink-0 bg-gradient-to-b from-[#6b4db8] to-[#5a3d9e] flex flex-col shadow-[2px_0_5px_rgba(0,0,0,0.1)] min-h-screen relative z-10">
            <div class="bg-black/10 py-[20px] px-[15px] flex items-center gap-[10px]">
                <i class="bi bi-flower2 text-[#4ade80] text-[35px]"></i>
                <h5 class="text-white m-0 text-[18px] font-semibold">Sistema Ingreso</h5>
            </div>

            <ul class="list-none p-0 my-[20px] flex-grow flex flex-col gap-1">
                <li>
                    <a href="../../index.php" class="flex items-center px-[20px] py-[15px] text-white/90 no-underline transition-all duration-300 gap-[12px] text-[15px] hover:bg-white/10">
                        <i class="bi bi-house-door text-[20px] w-[25px]"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="usuariosview.php" class="flex items-center px-[20px] py-[15px] bg-[#4ade80] text-white font-semibold no-underline transition-all duration-300 gap-[12px] text-[15px]">
                        <i class="bi bi-people text-[20px] w-[25px]"></i>
                        <span>Usuarios</span>
                    </a>
                </li>
                <li>
                    <a href="#" class="flex items-center px-[20px] py-[15px] text-white/90 no-underline transition-all duration-300 gap-[12px] text-[15px] hover:bg-white/10">
                        <i class="bi bi-grid text-[20px] w-[25px]"></i>
                        <span>Control de Ingreso</span>
                    </a>
                </li>
                <li>
                    <a href="#" class="flex items-center px-[20px] py-[15px] text-white/90 no-underline transition-all duration-300 gap-[12px] text-[15px] hover:bg-white/10">
                        <i class="bi bi-key text-[20px] w-[25px]"></i>
                        <span>Control de Llaves</span>
                    </a>
                </li>
                <li>
                    <a href="#" class="flex items-center px-[20px] py-[15px] text-white/90 no-underline transition-all duration-300 gap-[12px] text-[15px] hover:bg-white/10">
                        <i class="bi bi-door-open text-[20px] w-[25px]"></i>
                        <span>Permisos de Salida</span>
                    </a>
                </li>
                <li>
                    <a href="#" class="flex items-center px-[20px] py-[15px] text-white/90 no-underline transition-all duration-300 gap-[12px] text-[15px] hover:bg-white/10">
                        <i class="bi bi-bar-chart text-[20px] w-[25px]"></i>
                        <span>Reportes</span>
                    </a>
                </li>
                <li>
                    <a href="#" class="flex items-center px-[20px] py-[15px] text-white/90 no-underline transition-all duration-300 gap-[12px] text-[15px] hover:bg-white/10">
                        <i class="bi bi-person-badge text-[20px] w-[25px]"></i>
                        <span>Personal Externo</span>
                    </a>
                </li>
            </ul>

            <div class="mt-auto pt-6 pb-4 border-t border-white/20">
                <div class="flex items-center gap-3 px-5 mb-4">
                    <div class="bg-blue-400 rounded-full w-10 h-10 flex items-center justify-center font-bold text-[22px] text-white">
                        <i class="bi bi-person-circle"></i>
                    </div>
                    <div>
                        <p class="text-white font-semibold text-sm m-0">Administrador Sistema</p>
                        <p class="text-xs text-white/70 m-0">ADMIN</p>
                    </div>
                </div>
                <div class="px-3">
                    <button class="flex w-full items-center gap-[12px] px-[15px] py-[10px] rounded-lg text-red-300 hover:bg-white/10 transition-all duration-300">
                        <i class="bi bi-box-arrow-left text-[20px] w-[25px]"></i>
                        <span class="text-[15px] font-medium">Salir</span>
                    </button>
                </div>
            </div>
        </nav>

        <main class="flex-grow">
            <header class="bg-white p-5 flex items-center justify-between border-b border-gray-200 shadow-sm">
                <h2 class="text-2xl font-bold text-gray-800">Editar Usuario</h2>
                <div class="flex items-center gap-3">
                    <p class="text-sm font-medium">Administrador Sistema</p>
                    <div class="bg-[#4ade80] rounded-full w-9 h-9 flex items-center justify-center font-bold text-lg text-white">
                        <i class="bi bi-person-fill"></i>
                    </div>
                </div>
            </header>

            <div class="p-10 max-w-5xl">
                
                <div class="mb-8">
                    <div class="flex items-center gap-4 mb-2">
                        <a href="usuariosview.php" class="bg-gray-500 text-white px-4 py-2 rounded-lg font-medium flex items-center gap-2 hover:bg-gray-600 transition">
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