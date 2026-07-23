<?php
/**
 * AuthController - Gestión de autenticación
 */

class AuthController
{
    private User $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }

    /**
     * Mostrar formulario de login
     */
    public function showLogin(): void
    {
        // Si ya está autenticado, redirigir al dashboard
        if (isAuthenticated()) {
            redirect('/dashboard');
        }

        $pageTitle = 'Iniciar Sesión';
        require_once APP_PATH . '/views/auth/login.php';
    }

    /**
     * Procesar login
     */
    public function login(): void
    {
        // Verificar que sea POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/login');
        }

        // Verificar token CSRF
        if (!validateCSRFToken($_POST['csrf_token'] ?? '')) {
            setFlashMessage('Token de seguridad inválido', 'error');
            redirect('/login');
        }

        // Validar y sanitizar datos
        $credential = sanitize($_POST['credential'] ?? '');
        $password = $_POST['password'] ?? '';

        // Validaciones básicas
        if (empty($credential) || empty($password)) {
            setFlashMessage('Por favor complete todos los campos', 'error');
            redirect('/login');
        }

        // Obtener información del cliente
        $ipAddress = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
        $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? 'unknown';

        // Intentar autenticar
        $user = $this->userModel->validateCredentials($credential, $password);

        if (!$user) {
            // Login fallido
            $this->userModel->logAccess(
                null,
                'login_fallido',
                $ipAddress,
                $userAgent,
                "Intento con credencial: {$credential}"
            );

            setFlashMessage('Credenciales inválidas o cuenta bloqueada', 'error');
            redirect('/login');
        }

        // Login exitoso - Crear sesión
        $this->createUserSession($user);

        // Registrar auditoría
        $this->userModel->logAccess(
            $user['id'],
            'login_exitoso',
            $ipAddress,
            $userAgent,
            null
        );

        setFlashMessage('Bienvenido, ' . htmlspecialchars($user['nombre']), 'success');
        redirect('/dashboard');
    }

    /**
     * Crear sesión de usuario
     */
    private function createUserSession(array $user): void
    {
        // Regenerar ID de sesión para prevenir session fixation
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_regenerate_id(true);
        }

        // Guardar datos en sesión
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['nombre'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['user_role'] = $user['rol'];
        $_SESSION['login_time'] = time();
        $_SESSION['last_activity'] = time();
        $_SESSION['ip_address'] = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
        $_SESSION['user_agent'] = $_SERVER['HTTP_USER_AGENT'] ?? 'unknown';
    }

    /**
     * Cerrar sesión
     */
    public function logout(): void
    {
        if (isAuthenticated()) {
            // Registrar auditoría
            $this->userModel->logAccess(
                $_SESSION['user_id'],
                'logout',
                $_SERVER['REMOTE_ADDR'] ?? 'unknown',
                $_SERVER['HTTP_USER_AGENT'] ?? 'unknown',
                null
            );
        }

        // Destruir sesión
        session_unset();
        session_destroy();

        // Crear nueva sesión limpia
        session_start();
        session_regenerate_id(true);

        setFlashMessage('Sesión cerrada correctamente', 'info');
        redirect('/login');
    }
}
