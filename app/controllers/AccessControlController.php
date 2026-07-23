<?php

require_once __DIR__ . '/../models/AccessControlModel.php';

/**
 * Controlador de Control de Ingreso
 * 
 * Modo Kiosko para vigilantes: validación de acceso mediante código de barras.
 */
class AccessControlController
{
    private AccessControlModel $model;

    public function __construct()
    {
        $this->model = new AccessControlModel();
    }

    /**
     * Vista principal del kiosko (pantalla completa)
     * Solo accesible por vigilante o admin
     */
    public function kiosk(): void
    {
        // Verificar autenticación y rol
        Auth::requireAuth();
        Auth::requireRole(['vigilante', 'admin']);

        // Obtener datos para el kiosko
        $data = [
            'stats' => $this->model->getTodayStats(),
            'recent' => $this->model->getRecentAccess(5),
            'personas_test' => $this->model->getPersonasActivas(), // Para simulador
            'page_title' => 'Control de Ingreso - Modo Kiosko'
        ];

        $this->loadView('access_control/kiosk', $data);
    }

    /**
     * Procesar lectura de código de barras (AJAX)
     * 
     * Flujo:
     * 1. Recibir código de barras
     * 2. Buscar coincidencia en BD por documento
     * 3. Validar estado de persona
     * 4. Determinar ENTRADA/SALIDA
     * 5. Registrar marcación
     * 6. Retornar respuesta JSON
     */
    public function processFingerprint(): void
    {
        try {
            // Establecer headers JSON primero
            header('Content-Type: application/json; charset=utf-8');
            
            // Verificar autenticación manualmente sin redirigir
            if (!Auth::check()) {
                $this->jsonResponse([
                    'success' => false,
                    'message' => 'Sesión expirada. Por favor inicie sesión nuevamente.',
                    'type' => 'error'
                ], 401);
                return;
            }

            // Verificar rol manualmente
            $allowedRoles = ['vigilante', 'admin'];
            $userRole = $_SESSION['user_role'] ?? null;
            $hasRole = false;
            foreach ($allowedRoles as $role) {
                if (strcasecmp($userRole, $role) === 0) {
                    $hasRole = true;
                    break;
                }
            }

            if (!$hasRole) {
                $this->jsonResponse([
                    'success' => false,
                    'message' => 'No tiene permisos para esta acción',
                    'type' => 'error'
                ], 403);
                return;
            }

            // Solo aceptar POST
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                $this->jsonResponse([
                    'success' => false,
                    'message' => 'Método no permitido'
                ], 405);
                return;
            }

            // Obtener datos JSON
            $input = json_decode(file_get_contents('php://input'), true);
            $barcode = $input['barcode'] ?? null;
            $metodo = 'BARCODE'; // Método de acceso

            // Validar que se reciba código de barras
            if (empty($barcode)) {
                $this->jsonResponse([
                    'success' => false,
                    'type' => 'error',
                    'message' => 'Debe proporcionar un código de barras',
                    'allowed' => false
                ]);
                return;
            }

            // PASO 1: Buscar persona por código de barras (documento)
            $persona = $this->model->findByDocumento($barcode);
            $metodo = 'BARCODE';

            if ($persona === null) {
                // NO REGISTRADO - Denegar acceso y registrar marcación fallida
                $this->model->recordAccess([
                    'persona_id' => null,
                    'dispositivo_id' => null,
                    'tipo_evento' => null,
                    'exitoso' => false,
                    'mensaje' => 'Código de barras no encontrado',
                    'documento' => $barcode ?? null,
                    'nombre' => 'Desconocido',
                    'metodo' => $metodo
                ]);

                $this->jsonResponse([
                    'success' => true,
                    'type' => 'denied',
                    'allowed' => false,
                    'message' => 'ACCESO DENEGADO',
                    'reason' => 'Código de barras no registrado en el sistema',
                    'icon' => '🚫',
                    'sound' => 'denied'
                ]);
                return;
            }

            // PASO 2: Validar estado y determinar tipo de evento
            $validation = $this->model->validateAccess($persona);

            if (!$validation['allowed']) {
                // INACTIVO - Denegar acceso y registrar marcación fallida
                $nombreCompleto = trim($persona['nombres'] . ' ' . ($persona['apellidos'] ?? ''));
                
                $this->model->recordAccess([
                    'persona_id' => $persona['persona_id'],
                    'dispositivo_id' => null,
                    'tipo_evento' => null,
                    'exitoso' => false,
                    'mensaje' => $validation['reason'],
                    'documento' => $persona['documento'],
                    'nombre' => $nombreCompleto,
                    'metodo' => $metodo
                ]);

                $this->jsonResponse([
                    'success' => true,
                    'type' => 'denied',
                    'allowed' => false,
                    'message' => 'ACCESO DENEGADO',
                    'reason' => $validation['reason'],
                    'persona' => [
                        'documento' => $persona['documento'],
                        'nombre' => $nombreCompleto,
                        'tipo' => $persona['tipo_persona_nombre']
                    ],
                    'icon' => '❌',
                    'sound' => 'denied'
                ]);
                return;
            }

            // PASO 3: ACCESO PERMITIDO - Registrar marcación exitosa
            $nombreCompleto = trim($persona['nombres'] . ' ' . ($persona['apellidos'] ?? ''));
            $tipoEvento = $validation['tipo_evento'];

            $registered = $this->model->recordAccess([
                'persona_id' => $persona['persona_id'],
                'dispositivo_id' => null,
                'tipo_evento' => $tipoEvento,
                'metodo' => $metodo,
                'exitoso' => true,
                'mensaje' => 'Acceso autorizado - ' . $tipoEvento,
                'documento' => $persona['documento'],
                'nombre' => $nombreCompleto
            ]);

            if (!$registered) {
                $this->jsonResponse([
                    'success' => false,
                    'type' => 'error',
                    'message' => 'Error al registrar marcación',
                    'allowed' => false
                ], 500);
                return;
            }

            // Respuesta exitosa
            $this->jsonResponse([
                'success' => true,
                'type' => 'allowed',
                'allowed' => true,
                'message' => 'ACCESO PERMITIDO',
                'reason' => $validation['reason'],
                'evento' => $tipoEvento,
                'persona' => [
                    'documento' => $persona['documento'],
                    'nombre' => $nombreCompleto,
                    'tipo' => $persona['tipo_persona_nombre']
                ],
                'icon' => $tipoEvento === 'ENTRADA' ? '✅' : '👋',
                'sound' => 'allowed',
                'timestamp' => date('Y-m-d H:i:s')
            ]);
        } catch (Exception $e) {
            error_log("Error en processFingerprint (código de barras): " . $e->getMessage());
            $this->jsonResponse([
                'success' => false,
                'type' => 'error',
                'message' => 'Error al procesar: ' . $e->getMessage(),
                'allowed' => false
            ], 500);
            return;
        }
    }

    /**
     * Obtener estadísticas actualizadas (AJAX)
     */
    public function getStats(): void
    {
        header('Content-Type: application/json; charset=utf-8');
        
        // Verificar autenticación manualmente
        if (!Auth::check()) {
            $this->jsonResponse([
                'success' => false,
                'message' => 'Sesión expirada'
            ], 401);
            return;
        }

        // Verificar rol
        $allowedRoles = ['vigilante', 'admin'];
        $userRole = $_SESSION['user_role'] ?? null;
        $hasRole = false;
        foreach ($allowedRoles as $role) {
            if (strcasecmp($userRole, $role) === 0) {
                $hasRole = true;
                break;
            }
        }

        if (!$hasRole) {
            $this->jsonResponse([
                'success' => false,
                'message' => 'Acceso denegado'
            ], 403);
            return;
        }

        $stats = $this->model->getTodayStats();
        $this->jsonResponse([
            'success' => true,
            'stats' => $stats
        ]);
    }

    /**
     * Obtener marcaciones recientes (AJAX)
     */
    public function getRecent(): void
    {
        header('Content-Type: application/json; charset=utf-8');
        
        // Verificar autenticación manualmente
        if (!Auth::check()) {
            $this->jsonResponse([
                'success' => false,
                'message' => 'Sesión expirada'
            ], 401);
            return;
        }

        // Verificar rol
        $allowedRoles = ['vigilante', 'admin'];
        $userRole = $_SESSION['user_role'] ?? null;
        $hasRole = false;
        foreach ($allowedRoles as $role) {
            if (strcasecmp($userRole, $role) === 0) {
                $hasRole = true;
                break;
            }
        }

        if (!$hasRole) {
            $this->jsonResponse([
                'success' => false,
                'message' => 'Acceso denegado'
            ], 403);
            return;
        }

        $recent = $this->model->getRecentAccess(10);
        $this->jsonResponse([
            'success' => true,
            'recent' => $recent
        ]);
    }

    /**
     * Helper: Enviar respuesta JSON
     */
    private function jsonResponse(array $data, int $status = 200): void
    {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }

    /**
     * Helper: Cargar vista
     */
    private function loadView(string $view, array $data = []): void
    {
        // Extraer variables para la vista
        extract($data);
        
        // Cargar la vista
        $viewPath = APP_PATH . '/views/' . $view . '.php';
        
        if (!file_exists($viewPath)) {
            throw new Exception("Vista no encontrada: {$view}");
        }
        
        require_once $viewPath;
    }
}
