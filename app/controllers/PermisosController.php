    <?php
/**
 * Controlador de Permisos de Salida
 */

class PermisosController
{
    private PermisosModel $permisosModel;

    public function __construct()
    {
        $this->permisosModel = new PermisosModel();
        // Marcar permisos vencidos en cada request
        $this->permisosModel->marcarPermisosVencidos();
    }

    // ========================================
    // INSTRUCTORES Y ADMIN - GESTIÓN DE PERMISOS
    // ========================================

    /**
     * Listar permisos (Instructor solo ve los suyos, Admin ve todos)
     */
    public function index(): void
    {
        $pageTitle = 'Permisos de Salida';
        
        $filters = [
            'fecha' => $_GET['fecha'] ?? date('Y-m-d'),
            'documento' => $_GET['documento'] ?? '',
            'estado' => $_GET['estado'] ?? ''
        ];
        
        // Si es instructor, solo ver sus propios permisos
        if (Auth::hasRole('instructor') && !Auth::hasRole('admin')) {
            $filters['instructor_id'] = $_SESSION['user_id'];
        }
        
        $permisos = $this->permisosModel->getPermisos($filters);
        $stats = $this->permisosModel->getEstadisticas($filters['fecha']);
        
        require_once APP_PATH . '/views/permisos/index.php';
    }

    /**
     * Mostrar formulario de crear permiso
     */
    public function create(): void
    {
        $pageTitle = 'Crear Permiso de Salida';
        require_once APP_PATH . '/views/permisos/create.php';
    }

    /**
     * Guardar nuevo permiso
     */
    public function store(): void
    {
        // Validar token CSRF
        if (!validateCSRFToken($_POST['csrf_token'] ?? '')) {
            setFlashMessage('Token de seguridad inválido', 'error');
            redirect('/permisos/create');
        }

        // Obtener y sanitizar datos
        $documentoAprendiz = sanitize($_POST['documento_aprendiz'] ?? '');
        $fechaPermiso = sanitize($_POST['fecha_permiso'] ?? '');
        $horaSalida = sanitize($_POST['hora_salida'] ?? '');
        $horaRegreso = sanitize($_POST['hora_regreso'] ?? '');
        $motivo = sanitize($_POST['motivo'] ?? '');
        $observaciones = sanitize($_POST['observaciones'] ?? '');

        // Validaciones
        if (empty($documentoAprendiz) || empty($fechaPermiso) || empty($horaSalida) || empty($motivo)) {
            setFlashMessage('Todos los campos marcados con * son obligatorios', 'error');
            redirect('/permisos/create');
        }

        // VALIDAR QUE EL APRENDIZ ESTÉ REGISTRADO EN LA BASE DE DATOS
        $persona = $this->permisosModel->buscarPersonaPorDocumento($documentoAprendiz);
        
        if (!$persona) {
            setFlashMessage('Error: El documento ingresado no corresponde a ningún aprendiz registrado en el sistema', 'error');
            redirect('/permisos/create');
        }

        if ($persona['estado'] !== 'ACTIVO') {
            setFlashMessage('Error: El aprendiz está inactivo en el sistema', 'error');
            redirect('/permisos/create');
        }

        // Usar el nombre completo de la base de datos
        $nombreAprendiz = trim($persona['nombre_completo']);

        // Validar que la fecha no sea pasada
        if (strtotime($fechaPermiso) < strtotime(date('Y-m-d'))) {
            setFlashMessage('No se pueden crear permisos para fechas pasadas', 'error');
            redirect('/permisos/create');
        }

        // Verificar si ya existe un permiso activo para este documento en esta fecha
        if ($this->permisosModel->existePermisoActivo($documentoAprendiz, $fechaPermiso)) {
            setFlashMessage('Ya existe un permiso activo para este aprendiz en la fecha seleccionada', 'warning');
            redirect('/permisos/create');
        }

        // Obtener nombre del instructor desde la base de datos
        $instructor = $this->permisosModel->getInstructorById($_SESSION['user_id']);
        $instructorNombre = $instructor ? $instructor['nombre'] : ($_SESSION['user_name'] ?? 'Instructor');

        // Preparar datos
        $data = [
            'documento_aprendiz' => $documentoAprendiz,
            'nombre_aprendiz' => $nombreAprendiz,
            'fecha_permiso' => $fechaPermiso,
            'hora_salida' => $horaSalida,
            'hora_regreso' => $horaRegreso ?: null,
            'motivo' => $motivo,
            'instructor_id' => $_SESSION['user_id'],
            'instructor_nombre' => $instructorNombre,
            'observaciones' => $observaciones
        ];

        if ($this->permisosModel->createPermiso($data)) {
            setFlashMessage("Permiso creado exitosamente para {$nombreAprendiz}", 'success');
            redirect('/permisos');
        } else {
            setFlashMessage('Error al crear el permiso', 'error');
            redirect('/permisos/create');
        }
    }

    /**
     * Cancelar permiso
     */
    public function cancelar(int $id): void
    {
        $permiso = $this->permisosModel->getPermisoById($id);
        
        if (!$permiso) {
            setFlashMessage('Permiso no encontrado', 'error');
            redirect('/permisos');
        }

        // Solo el instructor que creó el permiso o el admin pueden cancelar
        if (!Auth::hasRole('admin') && $permiso['instructor_id'] != $_SESSION['user_id']) {
            setFlashMessage('No tiene permisos para cancelar este permiso', 'error');
            redirect('/permisos');
        }

        if ($permiso['estado'] !== 'ACTIVO') {
            setFlashMessage('Solo se pueden cancelar permisos activos', 'warning');
            redirect('/permisos');
        }

        $motivo = $_POST['motivo'] ?? 'Cancelado por el instructor';
        
        if ($this->permisosModel->cancelarPermiso($id, $motivo)) {
            setFlashMessage('Permiso cancelado exitosamente', 'success');
        } else {
            setFlashMessage('Error al cancelar el permiso', 'error');
        }
        
        redirect('/permisos');
    }

    // ========================================
    // VIGILANTES - CONSULTA DE PERMISOS
    // ========================================

    /**
     * Vista de consulta para vigilantes
     */
    public function consulta(): void
    {
        $pageTitle = 'Consultar Permiso de Salida';
        $permiso = null;
        $documento = '';

        // Si hay búsqueda por documento
        if (isset($_GET['documento'])) {
            $documento = sanitize($_GET['documento']);
            if (!empty($documento)) {
                $permiso = $this->permisosModel->getPermisoActivoByDocumento($documento);
            }
        }

        require_once APP_PATH . '/views/permisos/consulta.php';
    }

    /**
     * Procesar validación de salida (AJAX)
     */
    public function validarSalida(): void
    {
        header('Content-Type: application/json');
        
        // Verificar que sea POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode([
                'success' => false,
                'message' => 'Método no permitido'
            ]);
            exit;
        }

        $input = json_decode(file_get_contents('php://input'), true);
        $documento = $input['documento'] ?? '';
        
        if (empty($documento)) {
            echo json_encode([
                'success' => false,
                'message' => 'Documento requerido'
            ]);
            exit;
        }

        // Buscar permiso activo
        $permiso = $this->permisosModel->getPermisoActivoByDocumento($documento);
        
        if (!$permiso) {
            echo json_encode([
                'success' => true,
                'permitido' => false,
                'message' => 'NO AUTORIZADO',
                'detalle' => 'No existe permiso de salida activo para este documento',
                'icon' => '❌',
                'color' => 'danger'
            ]);
            exit;
        }

        // Marcar permiso como usado
        $vigilanteId = $_SESSION['user_id'] ?? null;
        if ($vigilanteId) {
            $this->permisosModel->marcarPermisoUsado($permiso['id'], $vigilanteId);
        }

        echo json_encode([
            'success' => true,
            'permitido' => true,
            'message' => 'SALIDA AUTORIZADA',
            'permiso' => [
                'nombre' => $permiso['nombre_aprendiz'],
                'documento' => $permiso['documento_aprendiz'],
                'motivo' => $permiso['motivo'],
                'hora_salida' => substr($permiso['hora_salida'], 0, 5),
                'hora_regreso' => $permiso['hora_regreso'] ? substr($permiso['hora_regreso'], 0, 5) : 'No especificada',
                'instructor' => $permiso['instructor_nombre'],
                'fecha' => date('d/m/Y', strtotime($permiso['fecha_permiso']))
            ],
            'icon' => '✅',
            'color' => 'success'
        ]);
        exit;
    }

    /**
     * Ver detalle de permiso (todos los roles)
     */
    public function ver(int $id): void
    {
        $permiso = $this->permisosModel->getPermisoById($id);
        
        if (!$permiso) {
            setFlashMessage('Permiso no encontrado', 'error');
            redirect('/permisos');
        }

        // Instructores solo pueden ver sus propios permisos
        if (Auth::hasRole('instructor') && !Auth::hasRole('admin')) {
            if ($permiso['instructor_id'] != $_SESSION['user_id']) {
                setFlashMessage('No tiene permisos para ver este permiso', 'error');
                redirect('/permisos');
            }
        }

        $pageTitle = 'Detalle del Permiso';
        require_once APP_PATH . '/views/permisos/detalle.php';
    }

    /**
     * API: Buscar aprendiz por documento (AJAX)
     */
    public function buscarAprendiz(): void
    {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            echo json_encode([
                'success' => false,
                'message' => 'Método no permitido'
            ]);
            exit;
        }

        $documento = sanitize($_GET['documento'] ?? '');
        
        if (empty($documento)) {
            echo json_encode([
                'success' => false,
                'message' => 'Documento requerido'
            ]);
            exit;
        }

        $persona = $this->permisosModel->buscarPersonaPorDocumento($documento);
        
        if (!$persona) {
            echo json_encode([
                'success' => false,
                'message' => 'Aprendiz no encontrado en el sistema'
            ]);
            exit;
        }

        if ($persona['estado'] !== 'ACTIVO') {
            echo json_encode([
                'success' => false,
                'message' => 'El aprendiz está inactivo'
            ]);
            exit;
        }

        echo json_encode([
            'success' => true,
            'persona' => [
                'documento' => $persona['documento'],
                'nombre_completo' => $persona['nombre_completo'],
                'estado' => $persona['estado']
            ]
        ]);
        exit;
    }
}
