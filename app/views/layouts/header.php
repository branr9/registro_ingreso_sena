<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Sistema de Control de Ingreso SENA">
    <title><?= e($pageTitle ?? 'Sistema de Ingreso') ?> - SENA</title>
    <link rel="stylesheet" href="<?= asset('css/style-v2.css') ?>?v=<?= time() ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <?php if (isAuthenticated()): ?>
    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <div class="logo-container">
                <img src="<?= asset('images/logo.png') ?>" alt="Logo SENA" class="logo-sena">
                <span class="logo-text" style="color: #FFFFFF !important;" +
                >Sistema Ingreso</span>
            </div>
            <button class="sidebar-toggle" id="sidebarToggle">
                <i class="fas fa-bars"></i>
            </button>
        </div>

        <nav class="sidebar-nav">
            <ul class="nav-list">
                <?php if (!Auth::hasRole('vigilante')): ?>
                <li class="nav-item">
                    <a href="<?= baseUrl('/dashboard') ?>" class="nav-link <?= ($_SERVER['REQUEST_URI'] == '/dashboard' || $_SERVER['REQUEST_URI'] == '/') ? 'active' : '' ?>">
                        <i class="fas fa-home"></i>
                        <span class="nav-text">Dashboard</span>
                    </a>
                </li>
                <?php endif; ?>

                <?php if (Auth::hasRole('admin')): ?>
                <li class="nav-item">
                    <a href="<?= baseUrl('/usuarios') ?>" class="nav-link">
                        <i class="fas fa-users"></i>
                        <span class="nav-text">Usuarios</span>
                    </a>
                </li>
                <?php endif; ?>

                <?php if (Auth::hasRole('admin') || Auth::hasRole('vigilante')): ?>
                <li class="nav-item">
                    <a href="<?= baseUrl('/control-ingreso/kiosk') ?>" class="nav-link nav-link-white" style="color: #FFFFFF !important;">
                        <i class="fas fa-qrcode" style="color: #FFFFFF !important;"></i>
                        <span class="nav-text" style="color: #FFFFFF !important;">Control de Ingreso</span>
                       
                    </a>
                </li>
                <?php endif; ?>

                <?php if (Auth::hasRole('admin') || Auth::hasRole('instructor')): ?>
                <li class="nav-item">
                    <a href="<?= baseUrl('/control-llaves') ?>" class="nav-link">
                        <i class="fas fa-key"></i>
                        <span class="nav-text">Control de Llaves</span>
                    </a>
                </li>
                <?php endif; ?>

                <?php if (Auth::hasRole('admin') || Auth::hasRole('instructor')): ?>
                <li class="nav-item">
                    <a href="<?= baseUrl('/permisos') ?>" class="nav-link">
                        <i class="fas fa-file-signature"></i>
                        <span class="nav-text">Permisos de Salida</span>
                    </a>
                </li>
                <?php endif; ?>

                <?php if (Auth::hasRole('vigilante') && !Auth::hasRole('admin')): ?>
                <li class="nav-item">
                    <a href="<?= baseUrl('/permisos/consulta') ?>" class="nav-link">
                        <i class="fas fa-search"></i>
                        <span class="nav-text">Consultar Permiso</span>
                    </a>
                </li>
                <?php endif; ?>

                <?php if (Auth::hasRole('admin')): ?>
                <li class="nav-item">
                    <a href="<?= baseUrl('/reportes') ?>" class="nav-link">
                        <i class="fas fa-chart-bar"></i>
                        <span class="nav-text">Reportes</span>
                    </a>
                </li>
                <?php endif; ?>

                <?php if (Auth::hasRole('admin') || Auth::hasRole('vigilante')): ?>
                <li class="nav-item">
                    <a href="<?= baseUrl('/acceso-externo') ?>" class="nav-link">
                        <i class="fas fa-user-friends"></i>
                        <span class="nav-text">Personal Externo</span>
                    </a>
                </li>
                <?php endif; ?>
            </ul>
        </nav>

        <div class="sidebar-footer">
            <div class="user-profile">
                <div class="user-avatar">
                    <i class="fas fa-user"></i>
                </div>
                <div class="user-details">
                    <span class="user-name"><?= e(currentUser()['nombre']) ?></span>
                    <span class="user-role badge-<?= e(currentUser()['rol']) ?>">
                        <?= ucfirst(e(currentUser()['rol'])) ?>
                    </span>
                </div>
            </div>
            <a href="<?= baseUrl('/logout') ?>" class="logout-btn" title="Cerrar Sesión">
                <i class="fas fa-sign-out-alt"></i>
                <span class="nav-text">Salir</span>
            </a>
        </div>
    </aside>
    <?php endif; ?>

    <!-- Main Content -->
    <div class="main-wrapper <?= isAuthenticated() ? 'has-sidebar' : '' ?>">
        <?php if (isAuthenticated()): ?>
        <!-- Top Header -->
        <header class="top-header">
            <div class="header-left">
                <button class="mobile-toggle" id="mobileToggle">
                    <i class="fas fa-bars"></i>
                </button>
                <h1 class="page-title"><?= e($pageTitle ?? 'Dashboard') ?></h1>
            </div>
            <div class="header-right">
                <div class="header-user">
                    <span class="header-user-name"><?= e(currentUser()['nombre']) ?></span>
                    <div class="header-avatar">
                        <i class="fas fa-user-circle"></i>
                    </div>
                </div>
            </div>
        </header>
        <?php endif; ?>

        <?php 
        // Mostrar mensajes flash
        $flash = getFlashMessage();
        if ($flash): 
        ?>
            <div class="flash-message flash-<?= e($flash['type']) ?>">
                <p><?= e($flash['message']) ?></p>
                <button class="flash-close" onclick="this.parentElement.remove()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        <?php endif; ?>

        <main class="main-content">
