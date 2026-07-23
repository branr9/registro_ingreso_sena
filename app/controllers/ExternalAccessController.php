<?php
/**
 * Controlador: Acceso Personal Externo
 * Gestiona el registro de entrada y salida de personal sin carnet
 */

class ExternalAccessController
{
    private ExternalAccessModel $model;

    public function __construct()
    {
        $this->model = new ExternalAccessModel();
    }

    /**
     * Listar registros
     */
    public function index(): void
    {
        $page = (int)($_GET['page'] ?? 1);
        $search = $_GET['search'] ?? '';
        $estado = $_GET['estado'] ?? '';
        $fecha_desde = $_GET['fecha_desde'] ?? '';
        $fecha_hasta = $_GET['fecha_hasta'] ?? '';

        $filters = [
            'search' => $search,
            'estado' => $estado,
            'fecha_desde' => $fecha_desde,
            'fecha_hasta' => $fecha_hasta
        ];

        $result = $this->model->paginate($page, 20, $filters);

        $pageTitle = 'Registro de Personal Externo';
        require_once APP_PATH . '/views/external_access/index.php';
    }

    /**
     * Mostrar formulario de registro de entrada
     */
    public function registroEntrada(): void
    {
        $pageTitle = 'Registrar Entrada - Personal Externo';
        require_once APP_PATH . '/views/external_access/registro_entrada.php';
    }

    /**
     * Procesar registro de entrada
     */
    public function guardarEntrada(): void
    {
        // Validar CSRF
        if (!validateCSRFToken($_POST['csrf_token'] ?? '')) {
            setFlashMessage('Token de seguridad inválido', 'error');
            redirect('/acceso-externo/registro-entrada');
        }

        // Validar datos
        $validator = new Validator($_POST);
        $rules = [
            'documento' => 'required|document',
            'nombres' => 'required|min:2|max:100',
            'motivo_visita' => 'required|min:5|max:255'
        ];

        if (!empty($_POST['email'])) {
            $rules['email'] = 'email';
        }

        if (!$validator->validate($rules)) {
            $_SESSION['errors'] = $validator->errors();
            $_SESSION['old'] = $_POST;
            redirect('/acceso-externo/registro-entrada');
        }

        // Registrar entrada
        $result = $this->model->registrarEntrada($_POST, Auth::user()['id']);

        if ($result) {
            setFlashMessage('Entrada registrada exitosamente', 'success');
            redirect('/acceso-externo');
        } else {
            setFlashMessage('Error al registrar entrada', 'error');
            $_SESSION['old'] = $_POST;
            redirect('/acceso-externo/registro-entrada');
        }
    }

    /**
     * Registrar salida
     */
    public function registrarSalida(int $id): void
    {
        // Validar CSRF
        if (!validateCSRFToken($_POST['csrf_token'] ?? '')) {
            setFlashMessage('Token de seguridad inválido', 'error');
            redirect('/acceso-externo');
        }

        $observaciones = $_POST['observaciones'] ?? null;
        $result = $this->model->registrarSalida($id, Auth::user()['id'], $observaciones);

        if ($result) {
            setFlashMessage('Salida registrada exitosamente', 'success');
        } else {
            setFlashMessage('Error al registrar salida', 'error');
        }

        redirect('/acceso-externo');
    }

    /**
     * Ver personas actualmente dentro
     */
    public function personasDentro(): void
    {
        $personas = $this->model->getPersonasDentro();
        $pageTitle = 'Personal Externo Dentro';
        require_once APP_PATH . '/views/external_access/personas_dentro.php';
    }

    /**
     * Ver detalle de registro
     */
    public function detalle(int $id): void
    {
        $registro = $this->model->findById($id);

        if (!$registro) {
            setFlashMessage('Registro no encontrado', 'error');
            redirect('/acceso-externo');
        }

        $pageTitle = 'Detalle de Registro';
        require_once APP_PATH . '/views/external_access/detalle.php';
    }
}
