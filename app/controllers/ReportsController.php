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
     * Exportar a Excel
     */
    public function exportExcel(): void
    {
        $fechaInicio = $_GET['fecha_inicio'] ?? date('Y-m-d', strtotime('-30 days'));
        $fechaFin = $_GET['fecha_fin'] ?? date('Y-m-d');
        $documento = $_GET['documento'] ?? null;

        // Obtener datos de marcaciones y préstamos
        $marcaciones = $this->accessModel->getAccessReport($fechaInicio, $fechaFin, $documento);
        $prestamos = $this->keysModel->getReportePrestamos($fechaInicio, $fechaFin, $documento);

        // Configurar headers para descarga de Excel
        header('Content-Type: application/vnd.ms-excel; charset=UTF-8');
        header('Content-Disposition: attachment; filename="reporte_accesos_' . date('Y-m-d_His') . '.xls"');
        header('Cache-Control: max-age=0');

        // Crear tabla HTML que Excel puede interpretar
        echo "\xEF\xBB\xBF"; // UTF-8 BOM
        echo '<html xmlns:x="urn:schemas-microsoft-com:office:excel">';
        echo '<head>';
        echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">';
        echo '<style>';
        echo 'table { border-collapse: collapse; width: 100%; }';
        echo 'th, td { border: 1px solid #000; padding: 8px; text-align: left; }';
        echo 'th { background-color: #39B54A; color: white; font-weight: bold; }';
        echo '</style>';
        echo '</head>';
        echo '<body>';
        echo '<h2>Reporte de Accesos - SENA</h2>';
        echo '<p>Período: ' . date('d/m/Y', strtotime($fechaInicio)) . ' al ' . date('d/m/Y', strtotime($fechaFin)) . '</p>';
        echo '<table>';
        echo '<thead>';
        echo '<tr>';
        echo '<th>Fecha</th>';
        echo '<th>Hora</th>';
        echo '<th>Documento</th>';
        echo '<th>Nombre Completo</th>';
        echo '<th>Tipo Persona</th>';
        echo '<th>Tipo Acceso</th>';
        echo '<th>Método</th>';
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';

        foreach ($marcaciones as $marcacion) {
            echo '<tr>';
            echo '<td>' . date('d/m/Y', strtotime($marcacion['fecha_hora'])) . '</td>';
            echo '<td>' . date('H:i:s', strtotime($marcacion['fecha_hora'])) . '</td>';
            echo '<td>' . htmlspecialchars($marcacion['documento']) . '</td>';
            echo '<td>' . htmlspecialchars($marcacion['nombre_completo']) . '</td>';
            echo '<td>' . htmlspecialchars($marcacion['tipo_persona']) . '</td>';
            echo '<td>' . htmlspecialchars($marcacion['tipo_acceso']) . '</td>';
            echo '<td>' . htmlspecialchars($marcacion['metodo']) . '</td>';
            echo '</tr>';
        }

        echo '</tbody>';
        echo '</table>';
        echo '<p>Total de registros: ' . count($marcaciones) . '</p>';
        
        // Tabla de préstamos de llaves
        echo '<br><br>';
        echo '<h3>Préstamos de Llaves</h3>';
        echo '<table>';
        echo '<thead>';
        echo '<tr>';
        echo '<th>Fecha Préstamo</th>';
        echo '<th>Aula</th>';
        echo '<th>Documento</th>';
        echo '<th>Nombre Completo</th>';
        echo '<th>Tipo Persona</th>';
        echo '<th>Fecha Devolución</th>';
        echo '<th>Estado</th>';
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';

        foreach ($prestamos as $prestamo) {
            echo '<tr>';
            echo '<td>' . date('d/m/Y H:i', strtotime($prestamo['fecha_prestamo'])) . '</td>';
            echo '<td>' . htmlspecialchars($prestamo['aula_nombre']) . '</td>';
            echo '<td>' . htmlspecialchars($prestamo['documento']) . '</td>';
            echo '<td>' . htmlspecialchars($prestamo['nombre_completo']) . '</td>';
            echo '<td>' . htmlspecialchars($prestamo['tipo_persona']) . '</td>';
            echo '<td>' . ($prestamo['fecha_devolucion'] ? date('d/m/Y H:i', strtotime($prestamo['fecha_devolucion'])) : 'Pendiente') . '</td>';
            echo '<td>' . htmlspecialchars($prestamo['estado']) . '</td>';
            echo '</tr>';
        }

        echo '</tbody>';
        echo '</table>';
        echo '<p>Total de préstamos: ' . count($prestamos) . '</p>';
        echo '<p>Generado el ' . date('d/m/Y H:i:s') . '</p>';
        echo '</body>';
        echo '</html>';
        exit;
    }

    /**
     * Exportar a PDF
     */
    public function exportPdf(): void
    {
        $fechaInicio = $_GET['fecha_inicio'] ?? date('Y-m-d', strtotime('-30 days'));
        $fechaFin = $_GET['fecha_fin'] ?? date('Y-m-d');
        $documento = $_GET['documento'] ?? null;

        // Obtener datos de marcaciones
        $marcaciones = $this->accessModel->getAccessReport($fechaInicio, $fechaFin, $documento);
        $prestamos = $this->keysModel->getReportePrestamos($fechaInicio, $fechaFin, $documento);

        // Configurar headers para PDF
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="reporte_accesos_' . date('Y-m-d_His') . '.pdf"');

        // Generar HTML simple que se puede convertir a PDF
        $html = $this->generatePdfHtml($marcaciones, $prestamos, $fechaInicio, $fechaFin);
        
        // Por ahora, redirigir a una vista que muestra el HTML
        // En producción, usar una librería como TCPDF o DomPDF
        echo $html;
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
