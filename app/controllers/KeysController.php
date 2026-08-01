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
        $usuarioActualPrestamo = null;

        if (!empty($_SESSION['user_id'])) {
            $usuarioActualPrestamo = $this->keysModel->getDatosUsuarioActual(
                (int)$_SESSION['user_id'],
                $_SESSION['user_email'] ?? null
            );
        }
        
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

    /**
     * Exportar historial de préstamos a CSV (compatible con Excel)
     */
    public function exportHistorial(): void
    {
        $prestamos = $this->keysModel->getHistorialPrestamos(1000);

        if (ob_get_level()) {
            ob_end_clean();
        }

        $filename = 'historial_prestamos_llaves_' . date('Y-m-d_His') . '.csv';
        header('Content-Type: text/csv; charset=UTF-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        header('Pragma: no-cache');

        $out = fopen('php://output', 'w');

        // BOM para UTF-8 en Microsoft Excel
        fprintf($out, chr(0xEF) . chr(0xBB) . chr(0xBF));

        // Encabezado del documento
        fputcsv($out, ['HISTORIAL DE PRESTAMOS DE LLAVES - SENA'], ';');
        fputcsv($out, ['Fecha de Generación:', date('d/m/Y H:i:s')], ';');
        fputcsv($out, [], ';');

        // Columnas
        fputcsv($out, [
            'FECHA PRESTAMO',
            'AULA',
            'RECEPTOR DE LLAVE',
            'DOC RECEPTOR',
            'DEPARTAMENTO / AREA',
            'TELEFONO',
            'REGISTRADO POR',
            'DOC REGISTRADOR',
            'TIPO PERSONA',
            'FECHA DEVOLUCION',
            'ESTADO',
            'OBSERVACIONES PRESTAMO',
            'OBSERVACIONES DEVOLUCION'
        ], ';');

        foreach ($prestamos as $p) {
            fputcsv($out, [
                date('d/m/Y H:i', strtotime($p['fecha_prestamo'])),
                $p['aula_nombre'] ?? '',
                $p['nombre_receptor'] ?? '',
                $p['documento_receptor'] ?? '',
                $p['departamento'] ?? '-',
                $p['telefono'] ?? '-',
                trim(($p['nombres'] ?? '') . ' ' . ($p['apellidos'] ?? '')),
                $p['documento'] ?? '',
                $p['tipo_persona'] ?? '',
                !empty($p['fecha_devolucion']) ? date('d/m/Y H:i', strtotime($p['fecha_devolucion'])) : 'Pendiente',
                $p['estado'] ?? '',
                $p['observaciones_prestamo'] ?? '',
                $p['observaciones_devolucion'] ?? ''
            ], ';');
        }

        fputcsv($out, [], ';');
        fputcsv($out, ['Total de movimientos:', count($prestamos)], ';');

        fclose($out);
        exit;
    }

    /**
     * Exportar historial de préstamos a PDF (vista de impresión)
     */
    public function exportPdf(): void
    {
        $prestamos = $this->keysModel->getHistorialPrestamos(1000);

        if (ob_get_level()) {
            ob_end_clean();
        }

        header('Content-Type: text/html; charset=UTF-8');
        header('Cache-Control: no-cache');

        echo $this->generatePdfHtml($prestamos);
        exit;
    }

    /**
     * Generar vista imprimible / PDF del historial de préstamos de llaves
     */
    private function generatePdfHtml(array $prestamos): string
    {
        ob_start();
        ?>
        <!DOCTYPE html>
        <html lang="es">
        <head>
            <meta charset="UTF-8">
            <title>Reporte de Historial de Préstamos de Llaves - SENA</title>
            <style>
                @page { size: A4 landscape; margin: 15mm; }
                body { font-family: Arial, sans-serif; font-size: 9pt; color: #333; margin: 0; padding: 20px; }
                .header { text-align: center; border-bottom: 2px solid #39B54A; padding-bottom: 12px; margin-bottom: 20px; }
                .header h1 { color: #39B54A; margin: 0 0 5px 0; font-size: 16pt; font-weight: bold; }
                .header h2 { color: #555; margin: 0 0 5px 0; font-size: 12pt; }
                .header p { margin: 0; font-size: 9pt; color: #777; }
                table { width: 100%; border-collapse: collapse; margin-top: 15px; }
                th, td { border: 1px solid #ddd; padding: 6px 8px; text-align: left; vertical-align: top; }
                th { background-color: #39B54A; color: white; font-weight: bold; font-size: 8.5pt; text-transform: uppercase; }
                tr:nth-child(even) { background-color: #f9f9f9; }
                .badge { display: inline-block; padding: 2px 6px; border-radius: 4px; font-weight: bold; font-size: 8pt; }
                .badge-prestado { background-color: #fff3cd; color: #856404; }
                .badge-devuelto { background-color: #d4edda; color: #155724; }
                .footer { margin-top: 25px; font-size: 8.5pt; color: #666; border-top: 1px solid #ddd; padding-top: 10px; display: flex; justify-content: space-between; }
                @media print {
                    body { padding: 0; }
                    .no-print { display: none; }
                }
            </style>
        </head>
        <body>
            <div class="no-print" style="margin-bottom: 15px; text-align: right;">
                <button onclick="window.print()" style="background-color: #39B54A; color: white; border: none; padding: 8px 16px; border-radius: 6px; font-weight: bold; cursor: pointer;">
                    🖨️ Imprimir / Guardar como PDF
                </button>
            </div>

            <div class="header">
                <h1>SERVICIO NACIONAL DE APRENDIZAJE - SENA</h1>
                <h2>Control de Llaves - Reporte de Historial de Préstamos</h2>
                <p>Generado el: <?= date('d/m/Y H:i:s') ?></p>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Fecha Préstamo</th>
                        <th>Aula</th>
                        <th>Receptor de la Llave</th>
                        <th>Doc. Receptor</th>
                        <th>Registrado Por</th>
                        <th>Fecha Devolución</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($prestamos)): ?>
                    <tr>
                        <td colspan="8" style="text-align: center; padding: 20px; color: #999;">No hay préstamos registrados</td>
                    </tr>
                    <?php else: ?>
                    <?php foreach ($prestamos as $index => $p): ?>
                    <tr>
                        <td><?= $index + 1 ?></td>
                        <td><?= date('d/m/Y H:i', strtotime($p['fecha_prestamo'])) ?></td>
                        <td><strong><?= htmlspecialchars($p['aula_nombre']) ?></strong></td>
                        <td>
                            <strong><?= htmlspecialchars($p['nombre_receptor']) ?></strong>
                            <?php if (!empty($p['departamento'])): ?>
                                <br><small style="color: #666;">Dpto: <?= htmlspecialchars($p['departamento']) ?></small>
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($p['documento_receptor']) ?></td>
                        <td><?= htmlspecialchars(trim($p['nombres'] . ' ' . $p['apellidos'])) ?></td>
                        <td><?= !empty($p['fecha_devolucion']) ? date('d/m/Y H:i', strtotime($p['fecha_devolucion'])) : 'Pendiente' ?></td>
                        <td>
                            <?php if ($p['estado'] === 'PRESTADO'): ?>
                                <span class="badge badge-prestado">PRESTADO</span>
                            <?php else: ?>
                                <span class="badge badge-devuelto">DEVUELTO</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>

            <div class="footer">
                <span>Total de registros: <?= count($prestamos) ?></span>
                <span>Sistema Registro de Ingreso SENA</span>
            </div>

            <script>
                window.onload = function() {
                    window.print();
                };
            </script>
        </body>
        </html>
        <?php
        return ob_get_clean();
    }
}
