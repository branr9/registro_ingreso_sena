<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Sistema de Control de Ingreso SENA">
    <title>Iniciar Sesión - Sistema Ingreso SENA</title>

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
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50:  '#f5f3fb', 100: '#ede7f8', 200: '#d9cef1', 300: '#beabe6',
                            400: '#9d7ed7', 500: '#8257c9', 600: '#6B46C1', 700: '#5a3ba8',
                            800: '#4a3189', 900: '#3d2a70'
                        },
                        accent: {
                            50:  '#eefbfd', 100: '#ddf6fc', 200: '#b8ecf7', 300: '#8adeef',
                            400: '#4dcbe3', 500: '#22b8d4', 600: '#00BCD4', 700: '#0891a8',
                            800: '#0a7488', 900: '#0d5f6f'
                        }
                    }
                }
            }
        };
    </script>

    <style>
        body { font-family: 'Inter', sans-serif; }

        .spiral-pattern {
            position: absolute;
            bottom: 0; left: 0; right: 0;
            height: 150px;
            display: flex;
            align-items: center;
            padding: 0 2rem;
            overflow: hidden;
            pointer-events: none;
        }
        .spiral-container {
            display: flex;
            gap: 0;
            animation: scroll-spiral 30s linear infinite;
        }
        .spiral {
            width: 100px;
            height: 100px;
            border: 8px solid rgba(255, 255, 255, 0.35);
            border-radius: 50%;
            position: relative;
            flex-shrink: 0;
        }
        .spiral::before {
            content: '';
            position: absolute;
            top: 50%; left: 50%;
            transform: translate(-50%, -50%);
            width: 70px; height: 70px;
            border: 8px solid rgba(255, 255, 255, 0.25);
            border-radius: 50%;
        }
        .spiral::after {
            content: '';
            position: absolute;
            top: 50%; left: 50%;
            transform: translate(-50%, -50%);
            width: 40px; height: 40px;
            border: 8px solid rgba(255, 255, 255, 0.15);
            border-radius: 50%;
        }
        @keyframes scroll-spiral {
            0% { transform: translateX(0); }
            100% { transform: translateX(-600px); }
        }
    </style>
</head>
<body class="min-h-screen bg-gradient-to-br from-primary-700 via-primary-600 to-accent-500 relative overflow-x-hidden">

    <!-- Botón de cambio de tema -->
    <button id="themeToggle" type="button"
            class="theme-toggle fixed top-5 right-5 z-20"
            title="Cambiar tema claro/oscuro"
            aria-label="Cambiar tema claro/oscuro">
        <span class="knob"><i id="themeToggleIcon" class="fas fa-moon"></i></span>
    </button>

    <div class="min-h-screen flex items-center justify-center px-4 py-12 relative z-10">
        <div class="w-full max-w-md">

            <!-- Tarjeta de login -->
            <div class="bg-white/95 dark:bg-[#1c1830]/95 backdrop-blur-md rounded-3xl shadow-2xl p-8 sm:p-10">
                <div class="text-center mb-8">
                    <div class="w-20 h-20 mx-auto mb-4 rounded-full bg-gradient-to-br from-primary-700 to-accent-500 flex items-center justify-center text-white text-3xl shadow-lg">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                    <h2 class="text-2xl font-extrabold text-gray-800">Iniciar Sesión</h2>
                    <p class="text-sm text-gray-500 mt-1">Ingrese sus credenciales para acceder al sistema</p>
                </div>

                <?php
                $flash = getFlashMessage();
                if ($flash):
                ?>
                    <div class="mb-6 rounded-2xl px-4 py-3 text-sm font-medium text-center
                                <?= $flash['type'] === 'success' ? 'bg-green-50 border border-green-200 text-green-800' : 'bg-red-50 border border-red-200 text-red-800' ?>">
                        <?= e($flash['message']) ?>
                    </div>
                <?php endif; ?>

                <form method="POST" action="<?= baseUrl('/login') ?>" autocomplete="off" class="space-y-5">
                    <input type="hidden" name="csrf_token" value="<?= generateCSRFToken() ?>">

                    <div>
                        <label for="credential" class="block text-sm font-semibold text-gray-700 mb-1">
                            <i class="fas fa-user text-primary-600"></i> Usuario o Email
                        </label>
                        <input type="text"
                               id="credential"
                               name="credential"
                               class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none"
                               required
                               autofocus
                               autocomplete="username"
                               placeholder="Ingrese su usuario o email">
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-semibold text-gray-700 mb-1">
                            <i class="fas fa-lock text-primary-600"></i> Contraseña
                        </label>
                        <input type="password"
                               id="password"
                               name="password"
                               class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none"
                               required
                               autocomplete="current-password"
                               placeholder="Ingrese su contraseña">
                    </div>

                    <button type="submit"
                            class="w-full rounded-2xl bg-gradient-to-r from-primary-700 to-accent-600 hover:from-primary-800 hover:to-accent-700 transition text-white font-bold text-base py-3 shadow-lg flex items-center justify-center gap-2">
                        <i class="fas fa-sign-in-alt"></i> Iniciar Sesión
                    </button>
                </form>

                <div class="mt-8 pt-6 border-t border-gray-100 text-center">
                    <p class="text-xs text-gray-400 leading-relaxed">
                        <strong class="text-gray-500">Nota de seguridad:</strong>
                        Después de <?= MAX_LOGIN_ATTEMPTS ?> intentos fallidos,
                        su cuenta será bloqueada temporalmente por <?= round(LOCKOUT_TIME / 60) ?> minutos.
                    </p>
                </div>

                <div class="mt-5 text-center">
                    <a href="<?= baseUrl('/') ?>" class="inline-flex items-center gap-2 text-sm font-semibold text-primary-700 dark:text-primary-300 hover:opacity-75 transition">
                        <i class="fas fa-arrow-left"></i> Volver al inicio
                    </a>
                </div>
            </div>

        </div>
    </div>

    <!-- Patrón decorativo -->
    <div class="spiral-pattern">
        <div class="spiral-container">
            <div class="spiral"></div>
            <div class="spiral"></div>
            <div class="spiral"></div>
            <div class="spiral"></div>
            <div class="spiral"></div>
            <div class="spiral"></div>
            <div class="spiral"></div>
            <div class="spiral"></div>
            <div class="spiral"></div>
            <div class="spiral"></div>
        </div>
    </div>

    <script>
        (function() {
            var toggle = document.getElementById('themeToggle');
            var icon = document.getElementById('themeToggleIcon');

            function updateIcon() {
                var isDark = document.documentElement.classList.contains('dark');
                icon.className = isDark ? 'fas fa-sun' : 'fas fa-moon';
            }
            updateIcon();

            toggle.addEventListener('click', function() {
                var isDark = document.documentElement.classList.toggle('dark');
                localStorage.setItem('sena-theme', isDark ? 'dark' : 'light');
                updateIcon();
            });
        })();
    </script>
</body>
</html>
