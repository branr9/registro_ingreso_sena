<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Sistema de Control de Ingreso SENA">
    <title><?= e($pageTitle ?? 'Sistema de Ingreso') ?> - SENA</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="<?= asset('css/style-v2.css') ?>?v=<?= time() ?>">
    <link rel="stylesheet" href="<?= asset('css/dark-mode.css') ?>?v=<?= time() ?>">

    <script>
        // Aplicar el tema guardado ANTES de pintar, para evitar parpadeo (FOUC)
        (function() {
            var saved = localStorage.getItem('sena-theme');
            var prefersDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
            if (saved === 'dark' || (!saved && prefersDark)) {
                document.documentElement.classList.add('dark');
            }
        })();
    </script>

    <script>
        // Paleta de marca SENA: morado (#6B46C1) + cian (#00BCD4), igual que la versión original
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50:  '#f5f3fb',
                            100: '#ede7f8',
                            200: '#d9cef1',
                            300: '#beabe6',
                            400: '#9d7ed7',
                            500: '#8257c9',
                            600: '#6B46C1',
                            700: '#5a3ba8',
                            800: '#4a3189',
                            900: '#3d2a70'
                        },
                        accent: {
                            50:  '#eefbfd',
                            100: '#ddf6fc',
                            200: '#b8ecf7',
                            300: '#8adeef',
                            400: '#4dcbe3',
                            500: '#22b8d4',
                            600: '#00BCD4',
                            700: '#0891a8',
                            800: '#0a7488',
                            900: '#0d5f6f'
                        }
                    }
                }
            }
        };
    </script>

    <style>
        body { font-family: 'Inter', sans-serif; }

        /* Sidebar deslizable en móvil, reutiliza el JS existente (toggle de clase .active) */
        #sidebar {
            transform: translateX(-100%);
            transition: transform .3s ease-in-out;
        }
        #sidebar.active { transform: translateX(0); }
        @media (min-width: 1025px) {
            #sidebar { transform: translateX(0); }
        }
    </style>
</head>
<body class="bg-gradient-to-br from-primary-50 via-white to-accent-100 dark:from-[#1b1330] dark:via-[#0f0f14] dark:to-[#0a2530] text-gray-800 dark:text-gray-100 min-h-screen">
    <?php if (isAuthenticated()): ?>
    <div class="flex min-h-screen">

    <!-- Sidebar -->
    <aside id="sidebar" class="fixed z-30 top-0 left-0 h-screen w-72 bg-gradient-to-b from-primary-800 via-primary-700 to-accent-600 text-white shadow-2xl overflow-y-auto">

        <div class="relative px-6 py-5 border-b border-white/20 flex items-center gap-3">
            <img src="<?= asset('images/logo.png') ?>" alt="Logo SENA" class="w-10 h-10 rounded-xl bg-white/10 object-contain p-1">
            <div>
                <h2 class="text-lg font-extrabold leading-tight">Sistema Ingreso</h2>
                <p class="text-xs text-primary-100">Panel de gestión SENA</p>
            </div>
            <button id="sidebarToggle" class="absolute top-4 right-4 text-white text-xl hover:scale-110 transition lg:hidden">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <nav class="p-4 space-y-1">
            <?php
                $currentPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
                function navClass($active) {
                    return $active
                        ? 'flex items-center gap-3 rounded-2xl px-4 py-3 bg-white text-primary-700 shadow-md font-bold transition'
                        : 'flex items-center gap-3 rounded-2xl px-4 py-3 text-white hover:bg-white/20 transition font-medium';
                }
            ?>

            <?php if (!Auth::hasRole('vigilante')): ?>
            <a href="<?= baseUrl('/dashboard') ?>" class="<?= navClass($currentPath === '/dashboard' || $currentPath === '/') ?>">
                <i class="fas fa-home w-5 text-center"></i>
                <span>Dashboard</span>
            </a>
            <?php endif; ?>

            <?php if (Auth::hasRole('admin')): ?>
            <a href="<?= baseUrl('/usuarios') ?>" class="<?= navClass(str_starts_with($currentPath, '/usuarios')) ?>">
                <i class="fas fa-users w-5 text-center"></i>
                <span>Usuarios</span>
            </a>
            <?php endif; ?>

            <?php if (Auth::hasRole('admin') || Auth::hasRole('vigilante')): ?>
            <a href="<?= baseUrl('/control-ingreso/kiosk') ?>" class="<?= navClass(str_starts_with($currentPath, '/control-ingreso')) ?>">
                <i class="fas fa-qrcode w-5 text-center"></i>
                <span>Control de Ingreso</span>
            </a>
            <?php endif; ?>

            <?php if (Auth::hasRole('admin') || Auth::hasRole('instructor')): ?>
            <a href="<?= baseUrl('/control-llaves') ?>" class="<?= navClass(str_starts_with($currentPath, '/control-llaves')) ?>">
                <i class="fas fa-key w-5 text-center"></i>
                <span>Control de Llaves</span>
            </a>
            <?php endif; ?>

            <?php if (Auth::hasRole('admin') || Auth::hasRole('instructor')): ?>
            <a href="<?= baseUrl('/permisos') ?>" class="<?= navClass($currentPath === '/permisos') ?>">
                <i class="fas fa-file-signature w-5 text-center"></i>
                <span>Permisos de Salida</span>
            </a>
            <?php endif; ?>

            <?php if (Auth::hasRole('vigilante') && !Auth::hasRole('admin')): ?>
            <a href="<?= baseUrl('/permisos/consulta') ?>" class="<?= navClass(str_starts_with($currentPath, '/permisos/consulta')) ?>">
                <i class="fas fa-search w-5 text-center"></i>
                <span>Consultar Permiso</span>
            </a>
            <?php endif; ?>

            <?php if (Auth::hasRole('admin')): ?>
            <a href="<?= baseUrl('/reportes') ?>" class="<?= navClass(str_starts_with($currentPath, '/reportes')) ?>">
                <i class="fas fa-chart-bar w-5 text-center"></i>
                <span>Reportes</span>
            </a>
            <?php endif; ?>

            <?php if (Auth::hasRole('admin') || Auth::hasRole('vigilante')): ?>
            <a href="<?= baseUrl('/acceso-externo') ?>" class="<?= navClass(str_starts_with($currentPath, '/acceso-externo')) ?>">
                <i class="fas fa-user-friends w-5 text-center"></i>
                <span>Personal Externo</span>
            </a>
            <?php endif; ?>
        </nav>

        <div class="p-4 border-t border-white/20 mt-4">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center text-white">
                    <i class="fas fa-user"></i>
                </div>
                <div class="min-w-0">
                    <p class="text-sm font-bold text-white truncate"><?= e(currentUser()['nombre']) ?></p>
                    <p class="text-xs text-primary-200"><?= ucfirst(e(currentUser()['rol'])) ?></p>
                </div>
            </div>
            <a href="<?= baseUrl('/logout') ?>" class="logout-btn w-full rounded-2xl bg-white/10 hover:bg-white/25 transition px-4 py-2 text-sm font-semibold text-white flex items-center justify-center gap-2">
                <i class="fas fa-sign-out-alt"></i>
                <span>Cerrar Sesión</span>
            </a>
        </div>
    </aside>

    <div id="sidebarOverlay" class="fixed inset-0 bg-black/40 z-20 hidden lg:hidden"></div>

    <!-- Main Content -->
    <div class="main-wrapper flex-1 min-w-0 lg:ml-72">
        <!-- Top Header -->
        <header class="sticky top-0 z-10 bg-white/80 dark:bg-[#1c162e]/90 backdrop-blur-md border-b border-primary-100 dark:border-[#2c2645] shadow-sm">
            <div class="flex items-center justify-between px-4 md:px-8 py-4">
                <div class="flex items-center gap-4">
                    <button id="mobileToggle" class="rounded-xl bg-primary-700 text-white px-3 py-2 shadow hover:bg-primary-800 transition lg:hidden">
                        <i class="fas fa-bars"></i>
                    </button>
                    <h1 class="text-xl md:text-2xl font-extrabold text-primary-700"><?= e($pageTitle ?? 'Dashboard') ?></h1>
                </div>
                <div class="flex items-center gap-3">
                    <button id="themeToggle" type="button"
                            class="theme-toggle theme-toggle-light"
                            title="Cambiar tema claro/oscuro"
                            aria-label="Cambiar tema claro/oscuro">
                        <span class="knob"><i id="themeToggleIcon" class="fas fa-moon"></i></span>
                    </button>
                    <div class="hidden sm:flex items-center gap-3">
                        <div class="text-right">
                            <p class="text-sm font-semibold text-gray-700"><?= e(currentUser()['nombre']) ?></p>
                            <p class="text-xs text-gray-400"><?= ucfirst(e(currentUser()['rol'])) ?></p>
                        </div>
                        <div class="w-11 h-11 rounded-2xl bg-gradient-to-br from-primary-700 to-accent-500 flex items-center justify-center text-white font-bold shadow-lg">
                            <i class="fas fa-user"></i>
                        </div>
                    </div>
                </div>
            </div>
        </header>
    <?php else: ?>
    <div class="main-wrapper">
    <?php endif; ?>

        <?php
        $flash = getFlashMessage();
        if ($flash):
        ?>
            <div class="flash-message mx-4 md:mx-8 mt-4 flex items-center justify-between gap-3 rounded-2xl px-5 py-4 shadow-md
                        <?= $flash['type'] === 'success' ? 'bg-green-50 border border-green-200 text-green-800' : '' ?>
                        <?= $flash['type'] === 'error' ? 'bg-red-50 border border-red-200 text-red-800' : '' ?>
                        <?= !in_array($flash['type'], ['success','error']) ? 'bg-blue-50 border border-blue-200 text-blue-800' : '' ?>">
                <p class="text-sm font-medium"><?= e($flash['message']) ?></p>
                <button class="text-current opacity-60 hover:opacity-100" onclick="this.parentElement.remove()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        <?php endif; ?>

        <main class="main-content p-4 md:p-8">
