<?php
/**
 * Funciones Helper Globales
 */

/**
 * Redirigir a una URL
 */
function redirect(string $path): void
{
    // Detectar el esquema (http o https)
    $scheme = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
    
    // Obtener el host con el puerto si existe
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    
    // Construir la URL completa
    $baseUrl = $scheme . '://' . $host;
    
    // Redirigir
    header("Location: {$baseUrl}{$path}");
    exit;
}

/**
 * Verificar si el usuario está autenticado
 */
function isAuthenticated(): bool
{
    return Auth::check();
}

/**
 * Obtener el usuario actual
 */
function currentUser(): ?array
{
    return Auth::user();
}

/**
 * Sanitizar entrada de usuario
 */
function sanitize(string $data): string
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    return $data;
}

/**
 * Escapar salida HTML
 */
function e(?string $string): string
{
    return htmlspecialchars($string ?? '', ENT_QUOTES, 'UTF-8');
}

/**
 * Generar token CSRF
 */
function generateCSRFToken(): string
{
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Validar token CSRF
 */
function validateCSRFToken(string $token): bool
{
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * Establecer mensaje flash
 */
function setFlashMessage(string $message, string $type = 'info'): void
{
    $_SESSION['flash_message'] = [
        'message' => $message,
        'type' => $type
    ];
}

/**
 * Obtener y eliminar mensaje flash
 */
function getFlashMessage(): ?array
{
    if (isset($_SESSION['flash_message'])) {
        $message = $_SESSION['flash_message'];
        unset($_SESSION['flash_message']);
        return $message;
    }
    return null;
}

/**
 * Obtener la URL base
 */
function baseUrl(string $path = ''): string
{
    // Detectar el esquema (http o https)
    $scheme = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
    
    // Obtener el host con el puerto si existe
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    
    // Construir la URL base completa
    $baseUrl = $scheme . '://' . $host;
    
    // Limpiar y agregar la ruta
    return rtrim($baseUrl, '/') . '/' . ltrim($path, '/');
}

/**
 * Obtener la URL de un asset
 */
function asset(string $path): string
{
    // Detectar el esquema (http o https)
    $scheme = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
    
    // Obtener el host con el puerto si existe
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    
    // Construir la URL base completa
    $baseUrl = $scheme . '://' . $host;
    
    // Limpiar y agregar la ruta
    return rtrim($baseUrl, '/') . '/' . ltrim($path, '/');
}

/**
 * Validar email
 */
function isValidEmail(string $email): bool
{
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Obtener el tiempo transcurrido desde login
 */
function getSessionElapsedTime(): int
{
    if (!isset($_SESSION['login_time'])) {
        return 0;
    }
    return time() - $_SESSION['login_time'];
}

/**
 * Formatear tiempo de sesión
 */
function formatSessionTime(int $seconds): string
{
    $hours = floor($seconds / 3600);
    $minutes = floor(($seconds % 3600) / 60);
    
    if ($hours > 0) {
        return "{$hours}h {$minutes}m";
    }
    return "{$minutes} minutos";
}

/**
 * Verificar si es una petición AJAX
 */
function isAjax(): bool
{
    return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 
           strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
}

/**
 * Devolver respuesta JSON
 */
function jsonResponse(array $data, int $statusCode = 200): void
{
    http_response_code($statusCode);
    header('Content-Type: application/json');
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}
