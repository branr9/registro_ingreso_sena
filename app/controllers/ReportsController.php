<?php
/**
 * Controlador de Reportes
 */

class ReportsController
{
    private AccessControlModel $accessModel;
    private KeysModel $keysModel;

    public function __construct()
    {
        $this->accessModel = new AccessControlModel();
        $this->keysModel = new KeysModel();
    }

    /**
     * Mostrar página principal de reportes
     */
    public function index(): void
    {
        $pageTitle = 'Reportes de Acceso';
        require_once APP_PATH . '/views/reports/index.php';
    }

    /**
     * Obtener datos del reporte en JSON
     */
    public function getData(): void
    {
        header('Content-Type: application/json');
        
        $fechaInicio = $_GET['fecha_inicio'] ?? date('Y-m-d', strtotime('-30 days'));
        $fechaFin = $_GET['fecha_fin'] ?? date('Y-m-d');
        $documento = $_GET['documento'] ?? null;

        try {
            $marcaciones = $this->accessModel->getAccessReport($fechaInicio, $fechaFin, $documento);
            $prestamos = $this->keysModel->getReportePrestamos($fechaInicio, $fechaFin, $documento);
            
            // Calcular estadísticas
            $stats = [
                'total' => count($marcaciones),
                'entradas' => 0,
                'salidas' => 0,
                'prestamos' => count($prestamos),
                'devoluciones' => 0
            ];

            foreach ($marcaciones as $m) {
                if ($m['tipo_acceso'] === 'ENTRADA') {
                    $stats['entradas']++;
                } elseif ($m['tipo_acceso'] === 'SALIDA') {
                    $stats['salidas']++;
                }
            }

            foreach ($prestamos as $p) {
                if ($p['estado'] === 'DEVUELTO') {
                    $stats['devoluciones']++;
                }
            }

            echo json_encode([
                'success' => true,
                'marcaciones' => $marcaciones,
                'prestamos' => $prestamos,
                'stats' => $stats
            ]);
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => 'Error al obtener los datos: ' . $e->getMessage()
            ]);
        }
        exit;
    }

    /**
     * Exportar a CSV (compatible con Excel)
     */
    public function exportExcel(): void
    {
        $fechaInicio = $_GET['fecha_inicio'] ?? date('Y-m-d', strtotime('-30 days'));
        $fechaFin    = $_GET['fecha_fin']    ?? date('Y-m-d');
        $documento   = $_GET['documento']    ?? null;

        $marcaciones = $this->accessModel->getAccessReport($fechaInicio, $fechaFin, $documento);
        $prestamos   = $this->keysModel->getReportePrestamos($fechaInicio, $fechaFin, $documento);

        // Limpiar cualquier output previo
        if (ob_get_level()) ob_end_clean();

        $filename = 'reporte_accesos_' . date('Y-m-d_His') . '.csv';
        header('Content-Type: text/csv; charset=UTF-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        header('Pragma: no-cache');

        $out = fopen('php://output', 'w');

        // BOM para que Excel reconozca UTF-8
        fprintf($out, chr(0xEF) . chr(0xBB) . chr(0xBF));

        // === MARCACIONES DE ACCESO ===
        fputcsv($out, ['REPORTE DE CONTROL DE ACCESOS - SENA'], ';');
        fputcsv($out, ['Periodo:', date('d/m/Y', strtotime($fechaInicio)) . ' al ' . date('d/m/Y', strtotime($fechaFin))], ';');
        fputcsv($out, [], ';');
        fputcsv($out, ['MARCACIONES DE ACCESO'], ';');
        fputcsv($out, ['Fecha', 'Hora', 'Documento', 'Nombre Completo', 'Tipo Persona', 'Tipo Acceso', 'Metodo'], ';');

        foreach ($marcaciones as $m) {
            fputcsv($out, [
                date('d/m/Y', strtotime($m['fecha_hora'])),
                date('H:i:s', strtotime($m['fecha_hora'])),
                $m['documento'],
                $m['nombre_completo'],
                $m['tipo_persona'],
                $m['tipo_acceso'],
                $m['metodo'],
            ], ';');
        }

        fputcsv($out, ['Total de registros:', count($marcaciones)], ';');
        fputcsv($out, [], ';');

        // === PRESTAMOS DE LLAVES ===
        fputcsv($out, ['PRESTAMOS DE LLAVES'], ';');
        fputcsv($out, ['Fecha Prestamo', 'Aula', 'Documento', 'Nombre Completo', 'Tipo Persona', 'Fecha Devolucion', 'Estado'], ';');

        foreach ($prestamos as $p) {
            fputcsv($out, [
                date('d/m/Y H:i', strtotime($p['fecha_prestamo'])),
                $p['aula_nombre'],
                $p['documento'],
                $p['nombre_completo'],
                $p['tipo_persona'],
                $p['fecha_devolucion'] ? date('d/m/Y H:i', strtotime($p['fecha_devolucion'])) : 'Pendiente',
                $p['estado'],
            ], ';');
        }

        fputcsv($out, ['Total de prestamos:', count($prestamos)], ';');
        fputcsv($out, ['Generado el:', date('d/m/Y H:i:s')], ';');

        fclose($out);
        exit;
    }



    /**
     * Exportar a PDF (vista HTML de impresión)
     */
    public function exportPdf(): void
    {
        $fechaInicio = $_GET['fecha_inicio'] ?? date('Y-m-d', strtotime('-30 days'));
        $fechaFin    = $_GET['fecha_fin']    ?? date('Y-m-d');
        $documento   = $_GET['documento']    ?? null;

        $marcaciones = $this->accessModel->getAccessReport($fechaInicio, $fechaFin, $documento);
        $prestamos   = $this->keysModel->getReportePrestamos($fechaInicio, $fechaFin, $documento);

        // Limpiar cualquier output previo y servir HTML limpio (sin layout del sistema)
        if (ob_get_level()) ob_end_clean();

        header('Content-Type: text/html; charset=UTF-8');
        header('Cache-Control: no-cache');

        echo $this->generatePdfHtml($marcaciones, $prestamos, $fechaInicio, $fechaFin);
        exit;
    }

    /**
     * Generar HTML para PDF
     */
    private function generatePdfHtml(array $marcaciones, array $prestamos, string $fechaInicio, string $fechaFin): string
    {
        ob_start();
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>Reporte de Accesos</title>
            <style>
                body { font-family: Arial, sans-serif; font-size: 10pt; }
                h2 { color: #39B54A; text-align: center; }
                table { width: 100%; border-collapse: collapse; margin-top: 20px; }
                th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
                th { background-color: #39B54A; color: white; }
                .header { text-align: center; margin-bottom: 20px; }
                .footer { margin-top: 20px; font-size: 9pt; color: #666; }
            </style>
        </head>
        <body>
            <div class="header">
                <h2>SERVICIO NACIONAL DE APRENDIZAJE - SENA</h2>
                <h3>Reporte de Control de Accesos</h3>
                <p>Período: <?= date('d/m/Y', strtotime($fechaInicio)) ?> al <?= date('d/m/Y', strtotime($fechaFin)) ?></p>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Hora</th>
                        <th>Documento</th>
                        <th>Nombre</th>
                        <th>Tipo</th>
                        <th>Acceso</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($marcaciones as $marcacion): ?>
                    <tr>
                        <td><?= date('d/m/Y', strtotime($marcacion['fecha_hora'])) ?></td>
                        <td><?= date('H:i:s', strtotime($marcacion['fecha_hora'])) ?></td>
                        <td><?= htmlspecialchars($marcacion['documento']) ?></td>
                        <td><?= htmlspecialchars($marcacion['nombre_completo']) ?></td>
                        <td><?= htmlspecialchars($marcacion['tipo_persona']) ?></td>
                        <td><?= htmlspecialchars($marcacion['tipo_acceso']) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <div class="footer">
                <p>Total de registros: <?= count($marcaciones) ?></p>
            </div>

            <h3 style="margin-top: 30px;">Préstamos de Llaves</h3>
            <table>
                <thead>
                    <tr>
                        <th>Fecha Préstamo</th>
                        <th>Aula</th>
                        <th>Documento</th>
                        <th>Nombre</th>
                        <th>Devolución</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($prestamos as $prestamo): ?>
                    <tr>
                        <td><?= date('d/m/Y H:i', strtotime($prestamo['fecha_prestamo'])) ?></td>
                        <td><?= htmlspecialchars($prestamo['aula_nombre']) ?></td>
                        <td><?= htmlspecialchars($prestamo['documento']) ?></td>
                        <td><?= htmlspecialchars($prestamo['nombre_completo']) ?></td>
                        <td><?= $prestamo['fecha_devolucion'] ? date('d/m/Y H:i', strtotime($prestamo['fecha_devolucion'])) : 'Pendiente' ?></td>
                        <td><?= htmlspecialchars($prestamo['estado']) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <div class="footer">
                <p>Total de préstamos: <?= count($prestamos) ?></p>
                <p>Generado el <?= date('d/m/Y H:i:s') ?></p>
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
