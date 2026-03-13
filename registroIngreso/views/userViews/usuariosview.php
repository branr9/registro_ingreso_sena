<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Usuarios - Sistema Ingreso</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/heroicons@^2/24/outline.js" defer></script>
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
                        'btn-gray': '#546e7a',
                        'badge-vigilante': '#8bc34a',
                        'badge-contratista': '#4caf50',
                        'badge-persona': '#607d8b',
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-100 font-sans antialiased text-gray-800">
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
                <a href="#" class="flex items-center gap-3 p-3 rounded-lg bg-active-menu hover:bg-green-600 transition">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" /></svg>
                    Usuarios
                </a>
                <a href="#" class="flex items-center gap-3 p-3 rounded-lg hover:bg-sidebar-hover transition">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" /></svg>
                    Control de Ingreso
                </a>
                <a href="#" class="flex items-center gap-3 p-3 rounded-lg hover:bg-sidebar-hover transition">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 5.25a3 3 0 013 3m3 0a6 6 0 01-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-.9.43-1.563A6 6 0 1121.75 8.25z" /></svg>
                    Control de Llaves
                </a>
                <a href="#" class="flex items-center gap-3 p-3 rounded-lg hover:bg-sidebar-hover transition">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" /></svg>
                    Permisos de Salida
                </a>
                <a href="#" class="flex items-center gap-3 p-3 rounded-lg hover:bg-sidebar-hover transition">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h.75c.621 0 1.125.504 1.125 1.125v6.75C6 20.496 5.496 21 4.875 21h-.75A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h.75c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-.75a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h.75c.621 0 1.125.504 1.125 1.125v15.75c0 .621-.504 1.125-1.125 1.125h-.75a1.125 1.125 0 01-1.125-1.125V4.125z" /></svg>
                    Reportes
                </a>
                <a href="#" class="flex items-center gap-3 p-3 rounded-lg hover:bg-sidebar-hover transition">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" /></svg>
                    Personal Externo
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
                <button class="flex w-full items-center gap-3 p-3 rounded-lg text-red-100 hover:bg-sidebar-hover transition">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9" /></svg>
                    Salir
                </button>
            </div>
        </nav>

        <main class="flex-grow">
            <header class="bg-white p-5 flex items-center justify-between border-b border-gray-200">
                <h2 class="text-2xl font-bold">Gestión de Usuarios</h2>
                <div class="flex items-center gap-3">
                    <p class="text-sm font-medium">Administrador Sistema</p>
                    <div class="bg-active-menu rounded-full w-9 h-9 flex items-center justify-center font-bold text-lg text-white">A</div>
                </div>
            </header>

            <div class="p-8">
                <div class="flex items-center justify-between mb-8">
                    <div class="flex items-center gap-4">
                        <svg class="w-10 h-10 text-gray-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
                        </svg>
                        <h3 class="text-3xl font-bold">Gestión de Usuarios</h3>
                    </div>
                    <div class="flex gap-3">
                        <button class="bg-btn-blue text-white px-5 py-2.5 rounded-lg font-medium flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5" /></svg>
                            Importar CSV
                        </button>
                        <a href="crear_usuario.php" class="bg-btn-green text-white px-5 py-2.5 rounded-lg font-medium flex items-center gap-2 hover:bg-green-600 transition">
                     <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                       Crear Usuario
                        </a>
                    </div>
                </div>

                <div class="grid grid-cols-3 gap-6 mb-8">
                    <div class="bg-card-bg p-6 rounded-2xl border border-gray-100 flex items-center gap-5">
                        <div class="bg-blue-100 p-3 rounded-xl text-blue-600">
                            <svg class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" /></svg>
                        </div>
                        <div>
                            <p class="text-4xl font-bold">13</p>
                            <p class="text-gray-500 font-medium">Total Usuarios</p>
                        </div>
                    </div>
                    <div class="bg-card-bg p-6 rounded-2xl border border-gray-100 flex items-center gap-5">
                        <div class="bg-green-100 p-3 rounded-xl text-btn-green">
                            <svg class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        </div>
                        <div>
                            <p class="text-4xl font-bold text-btn-green">11</p>
                            <p class="text-gray-500 font-medium">Activos</p>
                        </div>
                    </div>
                    <div class="bg-card-bg p-6 rounded-2xl border border-gray-100 flex items-center gap-5">
                        <div class="bg-sky-100 p-3 rounded-xl text-sky-500">
                            <svg class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        </div>
                        <div>
                            <p class="text-4xl font-bold text-sky-500">1</p>
                            <p class="text-gray-500 font-medium">Inactivos</p>
                        </div>
                    </div>
                </div>

                <div class="bg-card-bg p-6 rounded-2xl border border-gray-100 mb-8 grid grid-cols-[1fr,200px,200px,auto,auto] gap-4 items-end">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Buscar</label>
                        <input type="text" placeholder="Documento, nombre, email..." class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-1 focus:ring-sidebar-bg focus:border-sidebar-bg">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tipo</label>
                        <select class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-1 focus:ring-sidebar-bg focus:border-sidebar-bg appearance-none bg-[url('data:image/svg+xml;charset=UTF-8,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%2224%22%20height%3D%2224%22%20viewBox%3D%220%200%2024%2024%22%20fill%3D%22none%22%20stroke%3D%22currentColor%22%20stroke-width%3D%222%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%20class%3D%22feather%22%3E%3Cpolyline%20points%3D%226%209%2012%2015%2018%209%22%3E%3C%2Fpolyline%20%3E%3C%2Fsvg%3E')] bg-[length:20px] bg-[position:calc(100%-12px)_50%] bg-no-repeat">
                            <option>Todos</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Estado</label>
                        <select class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-1 focus:ring-sidebar-bg focus:border-sidebar-bg appearance-none bg-[url('data:image/svg+xml;charset=UTF-8,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%2224%22%20height%3D%2224%22%20viewBox%3D%220%200%2024%2024%22%20fill%3D%22none%22%20stroke%3D%22currentColor%22%20stroke-width%3D%222%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%20class%3D%22feather%22%3E%3Cpolyline%20points%3D%226%209%2012%2015%2018%209%22%3E%3C%2Fpolyline%20%3E%3C%2Fsvg%3E')] bg-[length:20px] bg-[position:calc(100%-12px)_50%] bg-no-repeat">
                            <option>Todos</option>
                        </select>
                    </div>
                    <button class="bg-btn-green text-white px-5 py-2.5 rounded-lg font-medium flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" /></svg>
                        Buscar
                    </button>
                    <button class="bg-btn-gray text-white px-5 py-2.5 rounded-lg font-medium flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" /></svg>
                        Limpiar
                    </button>
                </div>

                <div class="bg-card-bg rounded-2xl border border-gray-100 overflow-hidden shadow-sm">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="text-left p-5 text-sm font-semibold text-gray-700">Documento</th>
                                <th class="text-left p-5 text-sm font-semibold text-gray-700">Nombre</th>
                                <th class="text-left p-5 text-sm font-semibold text-gray-700">Tipo</th>
                                <th class="text-left p-5 text-sm font-semibold text-gray-700">Empresa</th>
                                <th class="text-left p-5 text-sm font-semibold text-gray-700">Email/Usuario</th>
                                <th class="text-left p-5 text-sm font-semibold text-gray-700">Rol</th>
                                <th class="text-left p-5 text-sm font-semibold text-gray-700">Estado</th>
                                <th class="text-left p-5 text-sm font-semibold text-gray-700">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Simulación de datos de usuario (esto vendría de una base de datos)
                            $users = [
                                [
                                    'doc' => '123321456',
                                    'name' => 'alberto cardenas',
                                    'type' => 'Vigilante',
                                    'company' => 'atlas',
                                    'email' => 'alberti@gmail.com',
                                    'username' => '@alberto12',
                                    'rol' => 'Vigilante',
                                    'active' => true,
                                ],
                                [
                                    'doc' => '111533645',
                                    'name' => 'julio',
                                    'type' => 'Contratista',
                                    'company' => 'Sena',
                                    'email' => '',
                                    'username' => '',
                                    'rol' => 'Persona',
                                    'active' => true,
                                ],
                                [
                                    'doc' => '987654321',
                                    'name' => 'maria lopez',
                                    'type' => 'Persona',
                                    'company' => '-',
                                    'email' => 'maria@empresa.com',
                                    'username' => '@maria',
                                    'rol' => 'Persona',
                                    'active' => false,
                                ],
                            ];

                            foreach ($users as $index => $user):
                                $row_class = ($index % 2 == 1) ? 'bg-gray-50' : '';
                                $type_badge_class = '';
                                switch ($user['type']) {
                                    case 'Vigilante': $type_badge_class = 'bg-badge-vigilante'; break;
                                    case 'Contratista': $type_badge_class = 'bg-badge-contratista'; break;
                                    default: $type_badge_class = 'bg-badge-persona';
                                }
                                $rol_badge_class = ($user['rol'] == 'Vigilante') ? 'bg-gray-500 text-white' : 'bg-gray-300 text-gray-800';
                            ?>
                                <tr class="<?php echo $row_class; ?> border-b border-gray-200 text-sm hover:bg-gray-100 transition">
                                    <td class="p-5 font-medium"><?php echo htmlspecialchars($user['doc']); ?></td>
                                    <td class="p-5 text-gray-900"><?php echo htmlspecialchars($user['name']); ?></td>
                                    <td class="p-5">
                                        <span class="<?php echo $type_badge_class; ?> text-white px-3 py-1 rounded-full text-xs font-semibold">
                                            <?php echo htmlspecialchars($user['type']); ?>
                                        </span>
                                    </td>
                                    <td class="p-5 text-gray-600"><?php echo htmlspecialchars($user['company']); ?></td>
                                    <td class="p-5 text-gray-900">
                                        <?php if (!empty($user['email'])): ?>
                                            <?php echo htmlspecialchars($user['email']); ?><br>
                                            <span class="text-xs text-gray-500"><?php echo htmlspecialchars($user['username']); ?></span>
                                        <?php else: ?>
                                            -
                                        <?php endif; ?>
                                    </td>
                                    <td class="p-5">
                                        <span class="<?php echo $rol_badge_class; ?> px-3 py-1 rounded-full text-xs font-semibold">
                                            <?php echo htmlspecialchars($user['rol']); ?>
                                        </span>
                                    </td>
                                    <td class="p-5">
                                        <?php if ($user['active']): ?>
                                            <div class="flex items-center gap-1.5 text-btn-green font-semibold">
                                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                                Activo
                                            </div>
                                        <?php else: ?>
                                            <div class="flex items-center gap-1.5 text-red-500 font-semibold">
                                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                                Inactivo
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td class="p-5">
                                        <div class="flex gap-2.5">
                                            <a href="usuarios_editar.php?id=<?php echo htmlspecialchars($user['doc']); ?>" class="bg-btn-blue text-white p-2 rounded hover:opacity-80 inline-block">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" /></svg>
                                            </a>
                                            <button class="bg-orange-500 text-white p-2 rounded hover:opacity-80">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" /></svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

            </div>
        </main>
    </div>
</body>
</html>