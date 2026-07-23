<?php
/**
 * Controlador de Usuarios
 * Gestión completa de usuarios del sistema
 */

class UsersController
{
    private UserModel $model;

    public function __construct()
    {
        $this->model = new UserModel();
    }

    /**
     * Listar usuarios con filtros
     */
    public function index(): void
    {
        $page = (int)($_GET['page'] ?? 1);
        $perPage = 20;

        // Filtros
        $filters = [
            'search' => $_GET['search'] ?? '',
            'tipo_persona' => $_GET['tipo_persona'] ?? '',
            'estado' => $_GET['estado'] ?? '',
            'rol' => $_GET['rol'] ?? ''
        ];

        // Obtener datos paginados
        $result = $this->model->paginate($filters, $page, $perPage);
        $usuarios = $result['data'];
        $pagination = [
            'current_page' => $result['page'],
            'last_page' => $result['last_page'],
            'total' => $result['total'],
            'per_page' => $result['per_page']
        ];

        // Estadísticas
        $stats = $this->model->getStats();

        $pageTitle = 'Gestión de Usuarios';
        require_once APP_PATH . '/views/usuarios/index.php';
    }

    /**
     * Mostrar formulario de creación
     */
    public function create(): void
    {
        $pageTitle = 'Crear Usuario';
        $csrfToken = generateCSRFToken();
        require_once APP_PATH . '/views/usuarios/create.php';
    }

    /**
     * Guardar nuevo usuario
     */
    public function store(): void
    {
        // Validar CSRF
        if (!validateCSRFToken($_POST['csrf_token'] ?? '')) {
            setFlashMessage('Token de seguridad inválido', 'error');
            redirect('/usuarios/create');
        }

        // Validar datos
        $validator = new Validator($_POST);
        $rules = [
            'documento' => 'required|document|unique:personas,documento',
            'nombre' => 'required|min:3|max:100',
            'tipo_persona' => 'required|in:admin,instructor,vigilante,aprendiz,contratista,visitante,proveedor',
            'estado' => 'required|in:activo,inactivo'
        ];

        // Validaciones condicionales
        $tipoPersona = $_POST['tipo_persona'] ?? '';
        $esPersonalSistema = in_array($tipoPersona, ['admin', 'instructor', 'vigilante']);

        if ($esPersonalSistema) {
            $rules['username'] = 'required|min:4|max:50|unique:usuarios_sistema,username';
            $rules['password'] = 'required|min:8';
            $rules['email'] = 'required|email|unique:personas,email';
        } elseif (!empty($_POST['email'])) {
            $rules['email'] = 'email|unique:personas,email';
        }

        if (!$validator->validate($rules)) {
            $_SESSION['errors'] = $validator->errors();
            $_SESSION['old'] = $_POST;
            redirect('/usuarios/create');
        }

        // Preparar datos para el nuevo schema
        $data = $_POST;
        
        // Convertir tipo_persona string a tipo_persona_id
        $tipoPersonaCodigo = strtoupper($data['tipo_persona']);
        $db = Database::getInstance();
        $sqlTipo = "SELECT id FROM cat_persona_tipo WHERE codigo = :codigo";
        $stmtTipo = $db->getConnection()->prepare($sqlTipo);
        $stmtTipo->execute(['codigo' => $tipoPersonaCodigo]);
        $tipoPersonaId = $stmtTipo->fetchColumn();
        
        if (!$tipoPersonaId) {
            setFlashMessage('Tipo de persona no válido', 'error');
            $_SESSION['old'] = $_POST;
            redirect('/usuarios/create');
        }
        
        $data['tipo_persona'] = $tipoPersonaId;
        
        // Si es personal del sistema, establecer el rol igual al tipo de persona
        if ($esPersonalSistema) {
            $data['rol'] = strtolower($_POST['tipo_persona']);
        }

        // Crear usuario
        $userId = $this->model->create($data, Auth::user()['id']);

        if ($userId) {
            setFlashMessage('Usuario creado exitosamente', 'success');
            redirect('/usuarios');
        } else {
            setFlashMessage('Error al crear usuario', 'error');
            $_SESSION['old'] = $_POST;
            redirect('/usuarios/create');
        }
    }

    /**
     * Mostrar formulario de edición
     */
    public function edit(int $id): void
    {
        $usuario = $this->model->findById($id);

        if (!$usuario) {
            setFlashMessage('Usuario no encontrado', 'error');
            redirect('/usuarios');
        }

        $pageTitle = 'Editar Usuario';
        $csrfToken = generateCSRFToken();
        require_once APP_PATH . '/views/usuarios/edit.php';
    }

    /**
     * Actualizar usuario
     */
    public function update(int $id): void
    {
        // Validar CSRF
        if (!validateCSRFToken($_POST['csrf_token'] ?? '')) {
            setFlashMessage('Token de seguridad inválido', 'error');
            redirect('/usuarios/edit/' . $id);
        }

        // Validar que existe
        $usuario = $this->model->findById($id);
        if (!$usuario) {
            setFlashMessage('Usuario no encontrado', 'error');
            redirect('/usuarios');
        }

        // Validar datos
        $validator = new Validator($_POST);
        $rules = [
            'documento' => 'required|document|unique:personas,documento,' . $id,
            'nombre' => 'required|min:3|max:100',
            'tipo_persona' => 'required|in:admin,instructor,vigilante,aprendiz,contratista,visitante,proveedor',
            'estado' => 'required|in:activo,inactivo'
        ];

        // Validaciones condicionales
        $tipoPersona = $_POST['tipo_persona'] ?? '';
        $esPersonalSistema = in_array($tipoPersona, ['admin', 'instructor', 'vigilante']);

        if ($esPersonalSistema) {
            $rules['rol'] = 'required|in:admin,instructor,vigilante';
            $rules['username'] = 'required|min:4|max:50|unique:usuarios_sistema,username,' . $id;
        }

        if (!empty($_POST['email'])) {
            $rules['email'] = 'email|unique:personas,email,' . $id;
        }

        // Password solo si se proporciona
        if (!empty($_POST['password'])) {
            $rules['password'] = 'min:8';
        }

        if (!$validator->validate($rules)) {
            $_SESSION['errors'] = $validator->errors();
            $_SESSION['old'] = $_POST;
            redirect('/usuarios/edit/' . $id);
        }

        // Preparar datos para el nuevo schema
        $data = $_POST;
        
        // Convertir tipo_persona string a tipo_persona_id
        $tipoPersonaCodigo = strtoupper($data['tipo_persona']);
        $db = Database::getInstance();
        $sqlTipo = "SELECT id FROM cat_persona_tipo WHERE codigo = :codigo";
        $stmtTipo = $db->getConnection()->prepare($sqlTipo);
        $stmtTipo->execute(['codigo' => $tipoPersonaCodigo]);
        $tipoPersonaId = $stmtTipo->fetchColumn();
        
        if (!$tipoPersonaId) {
            setFlashMessage('Tipo de persona no válido', 'error');
            $_SESSION['old'] = $_POST;
            redirect('/usuarios/edit/' . $id);
        }
        
        $data['tipo_persona'] = $tipoPersonaId;

        // Actualizar
        $result = $this->model->update($id, $data, Auth::user()['id']);

        if ($result) {
            setFlashMessage('Usuario actualizado exitosamente', 'success');
            redirect('/usuarios');
        } else {
            setFlashMessage('Error al actualizar usuario', 'error');
            $_SESSION['old'] = $_POST;
            redirect('/usuarios/edit/' . $id);
        }
    }

    /**
     * Activar/Desactivar usuario
     */
    public function toggle(int $id): void
    {
        // Validar CSRF
        if (!validateCSRFToken($_POST['csrf_token'] ?? '')) {
            setFlashMessage('Token de seguridad inválido', 'error');
            redirect('/usuarios');
        }

        $result = $this->model->toggleStatus($id, Auth::user()['id']);

        if ($result) {
            setFlashMessage('Estado cambiado exitosamente', 'success');
        } else {
            setFlashMessage('Error al cambiar estado', 'error');
        }

        redirect('/usuarios');
    }

    /**
     * Eliminar usuario (borrado lógico)
     */
    public function delete(int $id): void
    {
        // Validar CSRF
        if (!validateCSRFToken($_POST['csrf_token'] ?? '')) {
            setFlashMessage('Token de seguridad inválido', 'error');
            redirect('/usuarios');
        }

        // No permitir eliminar el propio usuario
        if ($id === Auth::user()['id']) {
            setFlashMessage('No puede eliminar su propio usuario', 'error');
            redirect('/usuarios');
        }

        $result = $this->model->delete($id, Auth::user()['id']);

        if ($result) {
            setFlashMessage('Usuario eliminado exitosamente', 'success');
        } else {
            setFlashMessage('Error al eliminar usuario', 'error');
        }

        redirect('/usuarios');
    }

    // ========================================
    // IMPORTACIÓN MASIVA
    // ========================================

    /**
     * Mostrar formulario de importación
     */
    public function importForm(): void
    {
        $pageTitle = 'Importar Usuarios';
        $csrfToken = generateCSRFToken();
        require_once APP_PATH . '/views/usuarios/import.php';
    }

    /**
     * Procesar y mostrar vista previa de importación
     */
    public function importPreview(): void
    {
        // Validar CSRF
        if (!validateCSRFToken($_POST['csrf_token'] ?? '')) {
            setFlashMessage('Token de seguridad inválido', 'error');
            redirect('/usuarios/import');
        }

        // Validar archivo
        if (!isset($_FILES['archivo']) || $_FILES['archivo']['error'] !== UPLOAD_ERR_OK) {
            setFlashMessage('Error al subir archivo', 'error');
            redirect('/usuarios/import');
        }

        $file = $_FILES['archivo'];
        $allowedExts = ['csv'];
        $fileExt = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

        if (!in_array($fileExt, $allowedExts)) {
            setFlashMessage('Solo se permiten archivos CSV', 'error');
            redirect('/usuarios/import');
        }

        // Validar tamaño (máx 5MB)
        if ($file['size'] > 5 * 1024 * 1024) {
            setFlashMessage('El archivo no debe superar 5MB', 'error');
            redirect('/usuarios/import');
        }

        // Mover a temporal
        $tempPath = STORAGE_PATH . '/temp/' . uniqid('import_') . '.csv';
        if (!is_dir(STORAGE_PATH . '/temp')) {
            mkdir(STORAGE_PATH . '/temp', 0755, true);
        }

        if (!move_uploaded_file($file['tmp_name'], $tempPath)) {
            setFlashMessage('Error al procesar archivo', 'error');
            redirect('/usuarios/import');
        }

        // Opciones de importación
        $options = [
            'has_header' => isset($_POST['has_header']) && $_POST['has_header'] === '1',
            'delimiter' => $_POST['delimiter'] ?? ',',
            'mode' => $_POST['mode'] ?? 'upsert'
        ];

        // Generar vista previa
        $preview = $this->model->previewImport($tempPath, $options);

        if (isset($preview['error'])) {
            unlink($tempPath);
            setFlashMessage($preview['error'], 'error');
            redirect('/usuarios/import');
        }

        // Guardar en sesión para confirmar
        $_SESSION['import_data'] = [
            'file_path' => $tempPath,
            'options' => $options,
            'preview' => $preview
        ];

        $pageTitle = 'Vista Previa de Importación';
        $csrfToken = generateCSRFToken();
        require_once APP_PATH . '/views/usuarios/import_preview.php';
    }

    /**
     * Confirmar y ejecutar importación
     */
    public function importConfirm(): void
    {
        // Validar CSRF
        if (!validateCSRFToken($_POST['csrf_token'] ?? '')) {
            setFlashMessage('Token de seguridad inválido', 'error');
            redirect('/usuarios/import');
        }

        // Validar datos de sesión
        if (!isset($_SESSION['import_data'])) {
            setFlashMessage('No hay datos de importación pendientes', 'error');
            redirect('/usuarios/import');
        }

        $importData = $_SESSION['import_data'];
        $filePath = $importData['file_path'];
        $options = $importData['options'];

        if (!file_exists($filePath)) {
            setFlashMessage('Archivo temporal no encontrado', 'error');
            unset($_SESSION['import_data']);
            redirect('/usuarios/import');
        }

        // Ejecutar importación
        $result = $this->model->executeImport($filePath, $options, Auth::user()['id']);

        // Limpiar
        unlink($filePath);
        unset($_SESSION['import_data']);

        // Mensaje de resultado
        $mensaje = sprintf(
            'Importación completada: %d insertados, %d actualizados, %d omitidos, %d errores',
            $result['insertados'],
            $result['actualizados'],
            $result['omitidos'],
            count($result['errores'])
        );

        if (count($result['errores']) > 0) {
            $_SESSION['import_errors'] = $result['errores'];
            setFlashMessage($mensaje, 'warning');
        } else {
            setFlashMessage($mensaje, 'success');
        }

        redirect('/usuarios');
    }
}
