<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Sistema de Control de Ingreso SENA">
    <title>Ingreso SENA - SENA</title>

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
        // Paleta de marca SENA: morado (#6B46C1) + cian (#00BCD4), igual que el resto del sistema
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
<body class="min-h-screen bg-white dark:bg-[#0f0f14] text-gray-800 dark:text-gray-100">

    <!-- Navbar -->
    <nav class="sticky top-0 z-20 bg-gradient-to-r from-primary-700 via-primary-600 to-accent-500 shadow-md">
        <div class="max-w-7xl mx-auto px-4 md:px-8 py-4 flex items-center justify-between">
            <a href="#inicio" class="inline-flex items-center gap-2 bg-white rounded-2xl px-4 py-2 shadow">
                <i class="fas fa-landmark text-primary-700 text-lg"></i>
                <span class="font-extrabold text-primary-700 tracking-wide">GOV.CO</span>
            </a>

            <ul class="hidden sm:flex items-center gap-8">
                <li><a href="#inicio" class="text-white font-semibold hover:opacity-80 transition">Inicio</a></li>
            </ul>

            <div class="flex items-center gap-3">
                <button id="themeToggle" type="button"
                        class="theme-toggle theme-toggle-light"
                        title="Cambiar tema claro/oscuro"
                        aria-label="Cambiar tema claro/oscuro">
                    <span class="knob"><i id="themeToggleIcon" class="fas fa-moon"></i></span>
                </button>
                <a href="<?= baseUrl('/login') ?>"
                   class="rounded-full bg-green-600 hover:bg-green-700 transition text-white font-bold text-sm px-5 py-2 shadow">
                    Ingresar
                </a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="inicio" class="relative overflow-hidden bg-gradient-to-br from-primary-700 via-primary-600 to-accent-500">
        <div class="relative z-10 max-w-4xl mx-auto px-4 py-28 md:py-36 text-center">
            <h1 class="text-4xl md:text-6xl font-extrabold text-white mb-4">Bienvenido a</h1>
            <p class="text-lg md:text-2xl text-white/90 mb-10">Sistema de Control de Ingreso del SENA</p>
            <div class="flex flex-wrap items-center justify-center gap-4">
                <a href="<?= baseUrl('/login') ?>"
                   class="inline-flex items-center gap-2 rounded-full bg-green-600 hover:bg-green-700 transition text-white font-bold text-base px-7 py-3.5 shadow-lg">
                    <i class="fas fa-sign-in-alt"></i> Iniciar Sesión
                </a>
                <a href="#informacion"
                   class="inline-flex items-center gap-2 rounded-full border-2 border-white/80 hover:bg-white/10 transition text-white font-bold text-base px-7 py-3.5">
                    <i class="fas fa-info-circle"></i> Más Información
                </a>
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
    </section>

    <!-- Features Section -->
    <section id="informacion" class="max-w-7xl mx-auto px-4 md:px-8 py-20">
        <h2 class="text-3xl md:text-4xl font-extrabold text-center text-gray-800 dark:text-gray-100 mb-12">
            Características del Sistema
        </h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

            <div class="rounded-3xl border border-gray-100 dark:border-primary-100/20 bg-white dark:bg-[#1c1830] shadow-sm hover:shadow-lg transition p-8 text-center">
                <div class="w-16 h-16 mx-auto mb-5 rounded-full bg-gradient-to-br from-primary-700 to-accent-500 flex items-center justify-center text-white text-2xl">
                    <i class="fas fa-qrcode"></i>
                </div>
                <h3 class="text-lg font-bold text-gray-800 dark:text-gray-100 mb-2">Control de Ingreso</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">Sistema de control de acceso con códigos QR y lectura de huellas digitales para mayor seguridad.</p>
            </div>

            <div class="rounded-3xl border border-gray-100 dark:border-primary-100/20 bg-white dark:bg-[#1c1830] shadow-sm hover:shadow-lg transition p-8 text-center">
                <div class="w-16 h-16 mx-auto mb-5 rounded-full bg-gradient-to-br from-primary-700 to-accent-500 flex items-center justify-center text-white text-2xl">
                    <i class="fas fa-key"></i>
                </div>
                <h3 class="text-lg font-bold text-gray-800 dark:text-gray-100 mb-2">Control de Llaves</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">Gestione el préstamo y devolución de llaves de aulas y espacios del centro de formación.</p>
            </div>

            <div class="rounded-3xl border border-gray-100 dark:border-primary-100/20 bg-white dark:bg-[#1c1830] shadow-sm hover:shadow-lg transition p-8 text-center">
                <div class="w-16 h-16 mx-auto mb-5 rounded-full bg-gradient-to-br from-primary-700 to-accent-500 flex items-center justify-center text-white text-2xl">
                    <i class="fas fa-file-signature"></i>
                </div>
                <h3 class="text-lg font-bold text-gray-800 dark:text-gray-100 mb-2">Permisos de Salida</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">Solicite y gestione permisos de salida para aprendices de manera digital y eficiente.</p>
            </div>

            <div class="rounded-3xl border border-gray-100 dark:border-primary-100/20 bg-white dark:bg-[#1c1830] shadow-sm hover:shadow-lg transition p-8 text-center">
                <div class="w-16 h-16 mx-auto mb-5 rounded-full bg-gradient-to-br from-primary-700 to-accent-500 flex items-center justify-center text-white text-2xl">
                    <i class="fas fa-user-friends"></i>
                </div>
                <h3 class="text-lg font-bold text-gray-800 dark:text-gray-100 mb-2">Personal Externo</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">Registre y controle el ingreso de visitantes y personal externo a las instalaciones.</p>
            </div>

            <div class="rounded-3xl border border-gray-100 dark:border-primary-100/20 bg-white dark:bg-[#1c1830] shadow-sm hover:shadow-lg transition p-8 text-center">
                <div class="w-16 h-16 mx-auto mb-5 rounded-full bg-gradient-to-br from-primary-700 to-accent-500 flex items-center justify-center text-white text-2xl">
                    <i class="fas fa-chart-bar"></i>
                </div>
                <h3 class="text-lg font-bold text-gray-800 dark:text-gray-100 mb-2">Reportes</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">Genere reportes detallados sobre ingresos, préstamos de llaves y permisos de salida.</p>
            </div>

            <div class="rounded-3xl border border-gray-100 dark:border-primary-100/20 bg-white dark:bg-[#1c1830] shadow-sm hover:shadow-lg transition p-8 text-center">
                <div class="w-16 h-16 mx-auto mb-5 rounded-full bg-gradient-to-br from-primary-700 to-accent-500 flex items-center justify-center text-white text-2xl">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <h3 class="text-lg font-bold text-gray-800 dark:text-gray-100 mb-2">Seguridad</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">Sistema seguro con autenticación de usuarios y registro de todas las actividades.</p>
            </div>

        </div>
    </section>

    <footer class="text-center text-xs text-gray-400 dark:text-gray-500 py-6 px-4">
        <p>&copy; <?= date('Y') ?> SENA - Servicio Nacional de Aprendizaje. Todos los derechos reservados.</p>
    </footer>

    <script>
        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Toggle de tema claro/oscuro
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
