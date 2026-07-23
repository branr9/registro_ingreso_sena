<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Betowa - Sistema de Control de Ingreso SENA">
    <title>Ingreso sena  - SENA</title>
    <link rel="stylesheet" href="<?= asset('css/landing-new.css') ?>?v=<?= time() ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <!-- Navbar -->
    <nav class="gov-navbar">
        <div class="navbar-container">
            <div class="navbar-left">
                <a href="#" class="gov-logo">
                    <div class="gov-logo-badge">
                        <i class="gov-icon fas fa-landmark"></i>
                        <span class="gov-text">GOV.CO</span>
                    </div>
                </a>
                
            </div>

            <div class="navbar-center">
                <ul class="nav-menu">
                    <li class="nav-item">
                        <a href="#inicio" class="nav-link">Inicio</a>
                    </li>
                
                </ul>
            </div>

            <div class="navbar-right">
                <a href="<?= baseUrl('/login') ?>" class="btn btn-ingresar">
                    Ingresar
                </a>
               
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="landing-hero" id="inicio">
        <div class="hero-content">
            <h1>Bienvenido a</h1>
            <p>Sistema de Control de Ingreso del SENA</p>
            <div class="hero-buttons">
                <a href="<?= baseUrl('/login') ?>" class="btn btn-ingresar btn-large">
                    <i class="fas fa-sign-in-alt"></i>
                    Iniciar Sesión
                </a>
                <a href="#informacion" class="btn btn-registrar btn-large">
                    <i class="fas fa-info-circle"></i>
                    Más Información
                </a>
            </div>
        </div>

        <!-- Decorative pattern -->
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
    <section class="features-section" id="informacion">
        <div class="features-container">
            <h2 class="features-title">Características del Sistema</h2>
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-qrcode"></i>
                    </div>
                    <h3>Control de Ingreso</h3>
                    <p>Sistema de control de acceso con códigos QR y lectura de huellas digitales para mayor seguridad.</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-key"></i>
                    </div>
                    <h3>Control de Llaves</h3>
                    <p>Gestione el préstamo y devolución de llaves de aulas y espacios del centro de formación.</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-file-signature"></i>
                    </div>
                    <h3>Permisos de Salida</h3>
                    <p>Solicite y gestione permisos de salida para aprendices de manera digital y eficiente.</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-user-friends"></i>
                    </div>
                    <h3>Personal Externo</h3>
                    <p>Registre y controle el ingreso de visitantes y personal externo a las instalaciones.</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-chart-bar"></i>
                    </div>
                    <h3>Reportes</h3>
                    <p>Genere reportes detallados sobre ingresos, préstamos de llaves y permisos de salida.</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h3>Seguridad</h3>
                    <p>Sistema seguro con autenticación de usuarios y registro de todas las actividades.</p>
                </div>
            </div>
        </div>
    </section>

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
    </script>
</body>
</html>
