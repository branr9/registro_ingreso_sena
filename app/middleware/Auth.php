<?php
/**
 * Middleware de Autenticación
 */

class Auth
{
    /**
     * Verificar si el usuario está autenticado
     */
    public static function check(): bool
    {
        if (!isset($_SESSION['user_id'])) {
            return false;
        }

        // Verificar timeout de sesión
        if (isset($_SESSION['last_activity'])) {
            $elapsed = time() - $_SESSION['last_activity'];
            
            if ($elapsed > SESSION_LIFETIME) {
                self::expireSession();
                return false;
            }
        }

        // Verificar que IP y User Agent no hayan cambiado (protección contra session hijacking)
        if (isset($_SESSION['ip_address']) && $_SESSION['ip_address'] !== ($_SERVER['REMOTE_ADDR'] ?? '')) {
            self::expireSession('Cambio de dirección IP detectado');
            return false;
        }

        if (isset($_SESSION['user_agent']) && $_SESSION['user_agent'] !== ($_SERVER['HTTP_USER_AGENT'] ?? '')) {
            self::expireSession('Cambio de navegador detectado');
            return false;
        }

        // Actualizar última actividad
        $_SESSION['last_activity'] = time();
        
        return true;
    }

    /**
     * Requerir autenticación (middleware)
     */
    public static function requireAuth(): void
    {
        if (!self::check()) {
            setFlashMessage('Debe iniciar sesión para acceder', 'warning');
            redirect('/login');
        }
    }

    /**
     * Requerir rol específico
     * 
     * @param string|array $roles Rol o array de roles permitidos
     */
    public static function requireRole($roles): void
    {
        self::requireAuth();

        // Convertir a array si es string
        $allowedRoles = is_array($roles) ? $roles : [$roles];

        $userRole = $_SESSION['user_role'] ?? null;

        // Comparación case-insensitive
        $hasRole = false;
        foreach ($allowedRoles as $role) {
            if (strcasecmp($userRole, $role) === 0) {
                $hasRole = true;
                break;
            }
        }

        if (!$hasRole) {
            setFlashMessage('No tiene permisos para acceder a esta sección', 'error');
            redirect('/dashboard');
        }
    }

    /**
     * Expirar sesión
     */
    private static function expireSession(string $reason = 'Sesión expirada'): void
    {
        // Registrar auditoría si hay usuario en sesión
        if (isset($_SESSION['user_id'])) {
            $userModel = new User();
            $userModel->logAccess(
                $_SESSION['user_id'],
                'sesion_expirada',
                $_SERVER['REMOTE_ADDR'] ?? 'unknown',
                $_SERVER['HTTP_USER_AGENT'] ?? 'unknown',
                $reason
            );
        }

        session_unset();
        session_destroy();
        session_start();
        session_regenerate_id(true);

        setFlashMessage($reason . '. Por favor, inicie sesión nuevamente', 'warning');
    }

    /**
     * Obtener usuario actual
     */
    public static function user(): ?array
    {
        if (!self::check()) {
            return null;
        }

        return [
            'id' => $_SESSION['user_id'] ?? null,
            'nombre' => $_SESSION['user_name'] ?? null,
            'email' => $_SESSION['user_email'] ?? null,
            'rol' => $_SESSION['user_role'] ?? null,
        ];
    }

    /**
     * Verificar si el usuario tiene un rol específico
     */
    public static function hasRole(string $role): bool
    {
        return isset($_SESSION['user_role']) && strcasecmp($_SESSION['user_role'], $role) === 0;
    }
}
