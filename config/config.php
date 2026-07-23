<?php
/**
 * Configuración Principal de la Aplicación
 */

// Cargar variables de entorno
if (file_exists(__DIR__ . '/../.env')) {
    $lines = file(__DIR__ . '/../.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) continue;
        list($name, $value) = explode('=', $line, 2);
        $_ENV[trim($name)] = trim($value);
    }
}

// Definir constantes de la aplicación
define('APP_ENV', $_ENV['APP_ENV'] ?? 'development');
define('APP_DEBUG', filter_var($_ENV['APP_DEBUG'] ?? true, FILTER_VALIDATE_BOOLEAN));
define('APP_URL', $_ENV['APP_URL'] ?? 'http://localhost');

// Rutas del sistema
define('ROOT_PATH', dirname(__DIR__));
define('APP_PATH', ROOT_PATH . '/app');
define('CONFIG_PATH', ROOT_PATH . '/config');
define('PUBLIC_PATH', ROOT_PATH . '/public');
define('STORAGE_PATH', ROOT_PATH . '/storage');
define('SESSION_PATH', STORAGE_PATH . '/sessions');

// Configuración de sesiones
define('SESSION_LIFETIME', (int)($_ENV['SESSION_LIFETIME'] ?? 7200)); // 2 horas
define('SESSION_SECURE', filter_var($_ENV['SESSION_SECURE'] ?? false, FILTER_VALIDATE_BOOLEAN));
define('SESSION_HTTPONLY', filter_var($_ENV['SESSION_HTTPONLY'] ?? true, FILTER_VALIDATE_BOOLEAN));
define('SESSION_SAMESITE', $_ENV['SESSION_SAMESITE'] ?? 'Strict');

// Configuración de seguridad de login
define('MAX_LOGIN_ATTEMPTS', (int)($_ENV['MAX_LOGIN_ATTEMPTS'] ?? 5));
define('LOCKOUT_TIME', (int)($_ENV['LOCKOUT_TIME'] ?? 900)); // 15 minutos

// Zona horaria
date_default_timezone_set('America/Bogota');

// Configuración de errores
if (APP_DEBUG) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
    ini_set('log_errors', 1);
    ini_set('error_log', STORAGE_PATH . '/logs/php_errors.log');
}

// Configuración de sesión segura (ANTES de session_start())
// Guardar las sesiones dentro del proyecto evita depender de la ruta temporal
// configurada globalmente por Laragon, que puede no ser escribible.
if (!is_dir(SESSION_PATH) && !mkdir(SESSION_PATH, 0775, true) && !is_dir(SESSION_PATH)) {
    throw new RuntimeException('No se pudo crear el directorio de sesiones: ' . SESSION_PATH);
}

if (!is_writable(SESSION_PATH)) {
    throw new RuntimeException('El directorio de sesiones no tiene permisos de escritura: ' . SESSION_PATH);
}

ini_set('session.save_path', SESSION_PATH);
ini_set('session.cookie_httponly', SESSION_HTTPONLY ? '1' : '0');
ini_set('session.cookie_secure', SESSION_SECURE ? '1' : '0');
ini_set('session.cookie_samesite', SESSION_SAMESITE);
ini_set('session.use_strict_mode', '1');
ini_set('session.use_only_cookies', '1');
ini_set('session.cookie_lifetime', '0'); // Sesión hasta cerrar navegador
ini_set('session.gc_maxlifetime', SESSION_LIFETIME);

// Iniciar sesión después de configurarla
session_start();
