<?php
/**
 * Controlador de Control de Llaves
 */

class KeysController
{
    private KeysModel $keysModel;
    private User $userModel;

    public function __construct()
    {
        $this->keysModel = new KeysModel();
        $this->userModel = new User();
    }

    // ========================================
    // GESTIÓN DE AULAS (ADMIN)
    // ========================================

    /**
     * Listar aulas
     */
    public function index(): void
    {
        $pageTitle = 'Control de Llaves';
        $aulas = $this->keysModel->getAllAulas();
        $stats = $this->keysModel->getEstadisticas();
        
        require_once APP_PATH . '/views/keys/index.php';
    }

    /**
     * Mostrar formulario de crear aula
     */
    public function create(): void
    {
        $pageTitle = 'Crear Aula';
        require_once APP_PATH . '/views/keys/create.php';
    }

    /**
     * Guardar nueva aula
     */
    public function store(): void
    {
        $nombre = trim($_POST['nombre'] ?? '');
        $capacidad = (int)($_POST['capacidad'] ?? 0);
        $cantidadLlaves = (int)($_POST['cantidad_llaves'] ?? 1);
        $observaciones = trim($_POST['observaciones'] ?? '');

        // Validaciones
        if (empty($nombre)) {
            setFlashMessage('El nombre del aula es requerido', 'error');
            redirect('/control-llaves/create');
        }

        if ($capacidad < 1) {
            setFlashMessage('La capacidad debe ser mayor a 0', 'error');
            redirect('/control-llaves/create');
        }

        if ($cantidadLlaves < 1) {
            setFlashMessage('La cantidad de llaves debe ser mayor a 0', 'error');
            redirect('/control-llaves/create');
        }

        $data = [
            'nombre' => $nombre,
            'capacidad' => $capacidad,
            'cantidad_llaves' => $cantidadLlaves,
            'observaciones' => $observaciones
        ];

        if ($this->keysModel->createAula($data)) {
            setFlashMessage('Aula creada exitosamente', 'success');
            redirect('/control-llaves');
        } else {
            setFlashMessage('Error al crear el aula', 'error');
            redirect('/control-llaves/create');
        }
    }

    /**
     * Mostrar formulario de editar aula
     */
    public function edit(int $id): void
    {
        $aula = $this->keysModel->getAulaById($id);
        
        if (!$aula) {
            setFlashMessage('Aula no encontrada', 'error');
            redirect('/control-llaves');
        }

        $pageTitle = 'Editar Aula';
        require_once APP_PATH . '/views/keys/edit.php';
    }

    /**
     * Actualizar aula
     */
    public function update(int $id): void
    {
        $nombre = trim($_POST['nombre'] ?? '');
        $capacidad = (int)($_POST['capacidad'] ?? 0);
        $cantidadLlaves = (int)($_POST['cantidad_llaves'] ?? 1);
        $observaciones = trim($_POST['observaciones'] ?? '');

        if (empty($nombre) || $capacidad < 1 || $cantidadLlaves < 1) {
            setFlashMessage('Datos inválidos', 'error');
            redirect('/control-llaves/edit/' . $id);
        }

        $data = [
            'nombre' => $nombre,
            'capacidad' => $capacidad,
            'cantidad_llaves' => $cantidadLlaves,
            'observaciones' => $observaciones
        ];

        if ($this->keysModel->updateAula($id, $data)) {
            setFlashMessage('Aula actualizada exitosamente', 'success');
            redirect('/control-llaves');
        } else {
            setFlashMessage('Error al actualizar el aula', 'error');
            redirect('/control-llaves/edit/' . $id);
        }
    }

    /**
     * Cambiar estado del aula
     */
    public function toggle(int $id): void
    {
        if ($this->keysModel->toggleAulaEstado($id)) {
            setFlashMessage('Estado del aula actualizado', 'success');
        } else {
            setFlashMessage('Error al cambiar el estado', 'error');
        }
        redirect('/control-llaves');
    }

    /**
     * Eliminar aula
     */
    public function delete(int $id): void
    {
        if ($this->keysModel->deleteAula($id)) {
            setFlashMessage('Aula eliminada exitosamente', 'success');
        } else {
            setFlashMessage('Error al eliminar el aula', 'error');
        }
        redirect('/control-llaves');
    }

    // ========================================
    // PRÉSTAMO Y DEVOLUCIÓN (INSTRUCTOR)
    // ========================================

    /**
     * Vista de préstamo de llaves para instructores
     */
    public function prestamo(): void
    {
        $pageTitle = 'Préstamo de Llaves';
        
        // Obtener todas las aulas con su información de disponibilidad y préstamos activos
        $aulas = $this->keysModel->getAulasConPrestamos();
        
        require_once APP_PATH . '/views/keys/prestamo.php';
    }

    /**
     * Procesar préstamo de llave
     */
    public function procesarPrestamo(): void
    {
        $aulaId = (int)($_POST['aula_id'] ?? 0);
        $nombreReceptor = trim($_POST['nombre_receptor'] ?? '');
        $documentoReceptor = trim($_POST['documento_receptor'] ?? '');
        $departamento = trim($_POST['departamento'] ?? '');
        $telefono = trim($_POST['telefono'] ?? '');
        $observaciones = trim($_POST['observaciones'] ?? '');
        $usuarioId = $_SESSION['user_id'] ?? null;

        if (!$aulaId || !$usuarioId || empty($nombreReceptor) || empty($documentoReceptor)) {
            setFlashMessage('Debe completar todos los campos requeridos (Nombre y Documento)', 'error');
            redirect('/control-llaves/prestamo');
        }

        if ($this->keysModel->prestarLlave(
            $aulaId, 
            $usuarioId, 
            $nombreReceptor, 
            $documentoReceptor,
            $departamento,
            $telefono,
            $observaciones
        )) {
            setFlashMessage('Llave registrada exitosamente para ' . $nombreReceptor, 'success');
        } else {
            setFlashMessage('Error al registrar el préstamo', 'error');
        }
        
        redirect('/control-llaves/prestamo');
    }

    /**
     * Procesar devolución de llave
     */
    public function procesarDevolucion(): void
    {
        $prestamoId = (int)($_POST['prestamo_id'] ?? 0);
        $observaciones = trim($_POST['observaciones'] ?? '');

        if (!$prestamoId) {
            setFlashMessage('Datos inválidos', 'error');
            redirect('/control-llaves/prestamo');
        }

        if ($this->keysModel->devolverLlave($prestamoId, $observaciones)) {
            setFlashMessage('Llave devuelta exitosamente', 'success');
        } else {
            setFlashMessage('Error al registrar la devolución', 'error');
        }
        
        redirect('/control-llaves/prestamo');
    }

    /**
     * Ver historial de préstamos
     */
    public function historial(): void
    {
        $pageTitle = 'Historial de Préstamos';
        $prestamos = $this->keysModel->getHistorialPrestamos(100);
        
        require_once APP_PATH . '/views/keys/historial.php';
    }
}
