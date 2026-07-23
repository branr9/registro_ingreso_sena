<?php
/**
 * Punto de entrada principal de la aplicación
 */

// Cargar configuración ANTES de iniciar sesión
require_once __DIR__ . '/../config/config.php';

// Autoloader simple
spl_autoload_register(function ($class) {
    $paths = [
        APP_PATH . '/models/',
        APP_PATH . '/controllers/',
        APP_PATH . '/middleware/',
        APP_PATH . '/helpers/',
    ];

    foreach ($paths as $path) {
        $file = $path . $class . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

// Cargar helpers
require_once APP_PATH . '/helpers/functions.php';

// Obtener la URI y método
$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$requestMethod = $_SERVER['REQUEST_METHOD'];

// Remover el directorio base si existe
$scriptName = dirname($_SERVER['SCRIPT_NAME']);
if ($scriptName !== '/') {
    $requestUri = str_replace($scriptName, '', $requestUri);
}
$requestUri = '/' . trim($requestUri, '/');

// Enrutamiento simple
try {
    switch ($requestUri) {
        case '/':
            // Página de inicio/landing
            $controller = new HomeController();
            $controller->index();
            break;

        case '/login':
            if ($requestMethod === 'GET') {
                $controller = new AuthController();
                $controller->showLogin();
            } elseif ($requestMethod === 'POST') {
                $controller = new AuthController();
                $controller->login();
            }
            break;

        case '/logout':
            $controller = new AuthController();
            $controller->logout();
            break;

        case '/dashboard':
            Auth::requireAuth();
            $pageTitle = 'Dashboard';
            require_once APP_PATH . '/views/dashboard/index.php';
            break;

        // ========================================
        // RUTAS DEL MÓDULO DE USUARIOS
        // ========================================
        case '/usuarios':
            Auth::requireAuth();
            $controller = new UsersController();
            $controller->index();
            break;

        case '/usuarios/create':
            Auth::requireRole('admin');
            $controller = new UsersController();
            $controller->create();
            break;

        case '/usuarios/store':
            if ($requestMethod === 'POST') {
                Auth::requireRole('admin');
                $controller = new UsersController();
                $controller->store();
            }
            break;

        case (preg_match('/^\/usuarios\/edit\/(\d+)$/', $requestUri, $matches) ? true : false):
            Auth::requireRole('admin');
            $controller = new UsersController();
            $controller->edit((int)$matches[1]);
            break;

        case (preg_match('/^\/usuarios\/update\/(\d+)$/', $requestUri, $matches) ? true : false):
            if ($requestMethod === 'POST') {
                Auth::requireRole('admin');
                $controller = new UsersController();
                $controller->update((int)$matches[1]);
            }
            break;

        case (preg_match('/^\/usuarios\/toggle\/(\d+)$/', $requestUri, $matches) ? true : false):
            if ($requestMethod === 'POST') {
                Auth::requireRole('admin');
                $controller = new UsersController();
                $controller->toggle((int)$matches[1]);
            }
            break;

        case (preg_match('/^\/usuarios\/delete\/(\d+)$/', $requestUri, $matches) ? true : false):
            if ($requestMethod === 'POST') {
                Auth::requireRole('admin');
                $controller = new UsersController();
                $controller->delete((int)$matches[1]);
            }
            break;

        case '/usuarios/import':
            Auth::requireRole('admin');
            $controller = new UsersController();
            $controller->importForm();
            break;

        case '/usuarios/import-preview':
            if ($requestMethod === 'POST') {
                Auth::requireRole('admin');
                $controller = new UsersController();
                $controller->importPreview();
            }
            break;

        case '/usuarios/import-confirm':
            if ($requestMethod === 'POST') {
                Auth::requireRole('admin');
                $controller = new UsersController();
                $controller->importConfirm();
            }
            break;

        // ========================================
        // RUTAS DEL MÓDULO DE CONTROL DE INGRESO
        // ========================================
        case '/control-ingreso':
        case '/control-ingreso/kiosk':
            Auth::requireAuth();
            Auth::requireRole(['vigilante', 'admin']);
            $controller = new AccessControlController();
            $controller->kiosk();
            break;

        case '/control-ingreso/process':
            if ($requestMethod === 'POST') {
                Auth::requireAuth();
                Auth::requireRole(['vigilante', 'admin']);
                $controller = new AccessControlController();
                $controller->processFingerprint();
            }
            break;

        case '/control-ingreso/stats':
            Auth::requireAuth();
            Auth::requireRole(['vigilante', 'admin']);
            $controller = new AccessControlController();
            $controller->getStats();
            break;

        case '/control-ingreso/recent':
            Auth::requireAuth();
            Auth::requireRole(['vigilante', 'admin']);
            $controller = new AccessControlController();
            $controller->getRecent();
            break;

        // ========================================
        // RUTAS DEL MÓDULO DE CONTROL DE LLAVES
        // ========================================
        case '/control-llaves':
            Auth::requireAuth();
            Auth::requireRole(['admin', 'instructor']);
            $controller = new KeysController();
            $controller->index();
            break;

        case '/control-llaves/create':
            Auth::requireRole('admin');
            $controller = new KeysController();
            $controller->create();
            break;

        case '/control-llaves/store':
            if ($requestMethod === 'POST') {
                Auth::requireRole('admin');
                $controller = new KeysController();
                $controller->store();
            }
            break;

        case (preg_match('/^\/control-llaves\/edit\/(\d+)$/', $requestUri, $matches) ? true : false):
            Auth::requireRole('admin');
            $controller = new KeysController();
            $controller->edit((int)$matches[1]);
            break;

        case (preg_match('/^\/control-llaves\/update\/(\d+)$/', $requestUri, $matches) ? true : false):
            if ($requestMethod === 'POST') {
                Auth::requireRole('admin');
                $controller = new KeysController();
                $controller->update((int)$matches[1]);
            }
            break;

        case (preg_match('/^\/control-llaves\/toggle\/(\d+)$/', $requestUri, $matches) ? true : false):
            if ($requestMethod === 'POST') {
                Auth::requireRole('admin');
                $controller = new KeysController();
                $controller->toggle((int)$matches[1]);
            }
            break;

        case (preg_match('/^\/control-llaves\/delete\/(\d+)$/', $requestUri, $matches) ? true : false):
            if ($requestMethod === 'POST') {
                Auth::requireRole('admin');
                $controller = new KeysController();
                $controller->delete((int)$matches[1]);
            }
            break;

        case '/control-llaves/prestamo':
            Auth::requireAuth();
            Auth::requireRole(['admin', 'instructor']);
            $controller = new KeysController();
            $controller->prestamo();
            break;

        case '/control-llaves/procesar-prestamo':
            if ($requestMethod === 'POST') {
                Auth::requireAuth();
                Auth::requireRole(['admin', 'instructor']);
                $controller = new KeysController();
                $controller->procesarPrestamo();
            }
            break;

        case '/control-llaves/procesar-devolucion':
            if ($requestMethod === 'POST') {
                Auth::requireAuth();
                Auth::requireRole(['admin', 'instructor']);
                $controller = new KeysController();
                $controller->procesarDevolucion();
            }
            break;

        case '/control-llaves/historial':
            Auth::requireAuth();
            Auth::requireRole(['admin', 'instructor']);
            $controller = new KeysController();
            $controller->historial();
            break;

        // ========================================
        // RUTAS DEL MÓDULO DE PERMISOS DE SALIDA
        // ========================================
        case '/permisos':
            Auth::requireAuth();
            Auth::requireRole(['admin', 'instructor']);
            $controller = new PermisosController();
            $controller->index();
            break;

        case '/permisos/create':
            Auth::requireAuth();
            Auth::requireRole(['admin', 'instructor']);
            $controller = new PermisosController();
            $controller->create();
            break;

        case '/permisos/store':
            if ($requestMethod === 'POST') {
                Auth::requireAuth();
                Auth::requireRole(['admin', 'instructor']);
                $controller = new PermisosController();
                $controller->store();
            }
            break;

        case '/permisos/buscar-aprendiz':
            Auth::requireAuth();
            Auth::requireRole(['admin', 'instructor']);
            $controller = new PermisosController();
            $controller->buscarAprendiz();
            break;

        case (preg_match('/^\/permisos\/ver\/(\d+)$/', $requestUri, $matches) ? true : false):
            Auth::requireAuth();
            $controller = new PermisosController();
            $controller->ver((int)$matches[1]);
            break;

        case (preg_match('/^\/permisos\/cancelar\/(\d+)$/', $requestUri, $matches) ? true : false):
            if ($requestMethod === 'POST') {
                Auth::requireAuth();
                Auth::requireRole(['admin', 'instructor']);
                $controller = new PermisosController();
                $controller->cancelar((int)$matches[1]);
            }
            break;

        case '/permisos/consulta':
            Auth::requireAuth();
            Auth::requireRole(['admin', 'vigilante']);
            $controller = new PermisosController();
            $controller->consulta();
            break;

        case '/permisos/validar-salida':
            if ($requestMethod === 'POST') {
                Auth::requireAuth();
                Auth::requireRole(['admin', 'vigilante']);
                $controller = new PermisosController();
                $controller->validarSalida();
            }
            break;

        // ========================================
        // RUTAS DEL MÓDULO DE ACCESO EXTERNO
        // ========================================
        case '/acceso-externo':
            Auth::requireAuth();
            Auth::requireRole(['admin', 'vigilante']);
            $controller = new ExternalAccessController();
            $controller->index();
            break;

        case '/acceso-externo/registro-entrada':
            Auth::requireAuth();
            Auth::requireRole(['admin', 'vigilante']);
            $controller = new ExternalAccessController();
            $controller->registroEntrada();
            break;

        case '/acceso-externo/guardar-entrada':
            if ($requestMethod === 'POST') {
                Auth::requireAuth();
                Auth::requireRole(['admin', 'vigilante']);
                $controller = new ExternalAccessController();
                $controller->guardarEntrada();
            }
            break;

        case (preg_match('/^\/acceso-externo\/registrar-salida\/(\d+)$/', $requestUri, $matches) ? true : false):
            if ($requestMethod === 'POST') {
                Auth::requireAuth();
                Auth::requireRole(['admin', 'vigilante']);
                $controller = new ExternalAccessController();
                $controller->registrarSalida((int)$matches[1]);
            }
            break;

        case '/acceso-externo/personas-dentro':
            Auth::requireAuth();
            Auth::requireRole(['admin', 'vigilante']);
            $controller = new ExternalAccessController();
            $controller->personasDentro();
            break;

        case (preg_match('/^\/acceso-externo\/detalle\/(\d+)$/', $requestUri, $matches) ? true : false):
            Auth::requireAuth();
            Auth::requireRole(['admin', 'vigilante']);
            $controller = new ExternalAccessController();
            $controller->detalle((int)$matches[1]);
            break;

        // ========================================
        // RUTAS DEL MÓDULO DE REPORTES
        // ========================================
        case '/reportes':
            Auth::requireAuth();
            Auth::requireRole('admin');
            $controller = new ReportsController();
            $controller->index();
            break;

        case '/reportes/data':
            Auth::requireAuth();
            Auth::requireRole('admin');
            $controller = new ReportsController();
            $controller->getData();
            break;

        case '/reportes/export-excel':
            Auth::requireAuth();
            Auth::requireRole('admin');
            $controller = new ReportsController();
            $controller->exportExcel();
            break;

        case '/reportes/export-pdf':
            Auth::requireAuth();
            Auth::requireRole('admin');
            $controller = new ReportsController();
            $controller->exportPdf();
            break;

        default:
            // Página no encontrada
            http_response_code(404);
            echo '<h1>404 - Página no encontrada</h1>';
            echo '<p><a href="' . baseUrl('/') . '">Volver al inicio</a></p>';
            break;
    }
} catch (Exception $e) {
    // Manejo de errores
    if (APP_DEBUG) {
        echo '<h1>Error</h1>';
        echo '<pre>' . $e->getMessage() . '</pre>';
        echo '<pre>' . $e->getTraceAsString() . '</pre>';
    } else {
        http_response_code(500);
        echo '<h1>Error del servidor</h1>';
        echo '<p>Ha ocurrido un error. Por favor, contacte al administrador.</p>';
    }
    
    // Log del error
    error_log($e->getMessage());
}
