<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - Betowa SENA</title>
    <link rel="stylesheet" href="<?= asset('css/landing-new.css') ?>?v=<?= time() ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .auth-container {
            min-height: 100vh;
            background: linear-gradient(135deg, #6B46C1 0%, #00BCD4 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            position: relative;
        }
        
        .auth-card {
            background: white;
            border-radius: 20px;
            padding: 3rem;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            max-width: 450px;
            width: 100%;
            z-index: 2;
        }
        
        .auth-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        
        .auth-logo {
            width: 80px;
            height: 80px;
            margin: 0 auto 1rem;
            background: #39A900;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 2.5rem;
        }
        
        .auth-header h2 {
            color: var(--text-dark);
            font-size: 2rem;
            margin-bottom: 0.5rem;
        }
        
        .auth-header p {
            color: #666;
            font-size: 1rem;
        }
        
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: var(--text-dark);
            font-weight: 600;
        }
        
        .form-control {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            font-size: 1rem;
            transition: border-color 0.3s ease;
        }
        
        .form-control:focus {
            outline: none;
            border-color: #39A900;
        }
        
        .btn-block {
            width: 100%;
            padding: 14px;
            font-size: 1.1rem;
            background: #39A900;
            color: white;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            font-weight: 600;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .btn-block:hover {
            background: #5bbd2a;
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(57, 169, 0, 0.3);
        }
        
        .auth-footer {
            margin-top: 2rem;
            padding-top: 1.5rem;
            border-top: 1px solid #e0e0e0;
            text-align: center;
        }
        
        .text-muted {
            color: #666;
            font-size: 0.875rem;
            line-height: 1.5;
        }
        
        .debug-credentials {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            margin-top: 2rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            max-width: 450px;
            width: 100%;
            z-index: 2;
        }
        
        .debug-credentials h4 {
            color: #6B46C1;
            margin-bottom: 1rem;
        }
        
        .debug-credentials ul {
            list-style: none;
            padding: 0;
        }
        
        .debug-credentials li {
            padding: 0.5rem 0;
            color: var(--text-dark);
        }
        
        .back-link {
            text-align: center;
            margin-top: 1.5rem;
        }
        
        .back-link a {
            color: white;
            text-decoration: none;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: opacity 0.3s ease;
        }
        
        .back-link a:hover {
            opacity: 0.8;
        }
        
        .spiral-pattern {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 150px;
            display: flex;
            align-items: center;
            padding: 0 2rem;
            overflow: hidden;
        }

        .spiral-container {
            display: flex;
            gap: 0;
            animation: scroll-spiral 30s linear infinite;
        }

        .spiral {
            width: 100px;
            height: 100px;
            border: 8px solid rgba(139, 69, 255, 0.6);
            border-radius: 50%;
            position: relative;
            flex-shrink: 0;
        }

        .spiral::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 70px;
            height: 70px;
            border: 8px solid rgba(139, 69, 255, 0.4);
            border-radius: 50%;
        }

        .spiral::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 40px;
            height: 40px;
            border: 8px solid rgba(139, 69, 255, 0.2);
            border-radius: 50%;
        }

        @keyframes scroll-spiral {
            0% {
                transform: translateX(0);
            }
            100% {
                transform: translateX(-600px);
            }
        }
    </style>
</head>
<body>
    <div class="auth-container">
        <div style="max-width: 450px; width: 100%; z-index: 2;">
            <div class="auth-card">
                <div class="auth-header">
                    <div class="auth-logo">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                    <h2>Iniciar Sesión</h2>
                    <p>Ingrese sus credenciales para acceder al sistema</p>
                </div>

                <?php 
                $flash = getFlashMessage();
                if ($flash): 
                ?>
                    <div style="padding: 1rem; background: <?= $flash['type'] === 'success' ? '#d4edda' : '#f8d7da' ?>; color: <?= $flash['type'] === 'success' ? '#155724' : '#721c24' ?>; border-radius: 10px; margin-bottom: 1.5rem; text-align: center;">
                        <?= e($flash['message']) ?>
                    </div>
                <?php endif; ?>

                <form method="POST" action="<?= baseUrl('/login') ?>" class="auth-form" autocomplete="off">
                    <!-- Token CSRF -->
                    <input type="hidden" name="csrf_token" value="<?= generateCSRFToken() ?>">

                    <div class="form-group">
                        <label for="credential">Usuario o Email</label>
                        <input 
                            type="text" 
                            id="credential" 
                            name="credential" 
                            class="form-control" 
                            required 
                            autofocus
                            autocomplete="username"
                            placeholder="Ingrese su usuario o email"
                        >
                    </div>

                    <div class="form-group">
                        <label for="password">Contraseña</label>
                        <input 
                            type="password" 
                            id="password" 
                            name="password" 
                            class="form-control" 
                            required
                            autocomplete="current-password"
                            placeholder="Ingrese su contraseña"
                        >
                    </div>

                    <button type="submit" class="btn-block">
                        <i class="fas fa-sign-in-alt"></i> Iniciar Sesión
                    </button>
                </form>

                <div class="auth-footer">
                    <p class="text-muted">
                        <small>
                            <strong>Nota de seguridad:</strong> Después de <?= MAX_LOGIN_ATTEMPTS ?> intentos fallidos, 
                            su cuenta será bloqueada temporalmente por <?= round(LOCKOUT_TIME / 60) ?> minutos.
                        </small>
                    </p>
                </div>
                
                <div class="back-link">
                    <a href="<?= baseUrl('/') ?>">
                        <i class="fas fa-arrow-left"></i>
                        Volver al inicio
                    </a>
                </div>
            </div>

            <?php if (APP_DEBUG): ?>
            <div class="debug-credentials">
                <h4><i class="fas fa-code"></i> Credenciales de Prueba (Solo en desarrollo)</h4>
                <ul>
                    <li><strong>Admin:</strong> admin / Admin123!</li>
                    <li><strong>Instructor:</strong> instructor / Admin123!</li>
                    <li><strong>Vigilante:</strong> vigilante / Admin123!</li>
                </ul>
            </div>
            <?php endif; ?>
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
    </div>
</body>
</html>
