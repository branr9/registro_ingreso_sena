<?php
// Configuración de datos simulados para recrear la imagen exacta
date_default_timezone_set('America/Bogota'); // Ajustar a la zona horaria adecuada si es necesario

// Para la recreación fiel de la imagen, fijaremos la hora y el contador.
// En un entorno de producción, esto usaría date('g:i a') y datos de base de datos.
$currentTime = "05:38 p. m.";
$totalMarcaciones = 0;

// Lista de personas para el simulador
$personas = ['Juan Carlos García López', 'Carlos Alberto Martínez Sánchez', 'María Fernanda Rodríguez Pérez'];

// Lista de actividad reciente (recreada de la imagen)
$marcaciones = [
    ['nombre' => 'Juan Carlos García López', 'estado' => 'SALIDA', 'hora' => '20:08:10', 'accion' => 'Salió'],
    ['nombre' => 'Carlos Alberto Martínez Sánchez', 'estado' => 'SALIDA', 'hora' => '19:38:10', 'accion' => 'Salió'],
    ['nombre' => 'Juan Carlos García López', 'estado' => 'ENTRADA', 'hora' => '18:38:10', 'accion' => 'Ingresó'],
    ['nombre' => 'María Fernanda Rodríguez Pérez', 'estado' => 'ENTRADA', 'hora' => '17:38:10', 'accion' => 'Ingresó'],
    ['nombre' => 'Carlos Alberto Martínez Sánchez', 'estado' => 'ENTRADA', 'hora' => '16:38:10', 'accion' => 'Ingresó']
];

// Opcional: Lógica básica de simulación de POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'simular' && !empty($_POST['persona_simulada'])) {
        // En un entorno real, aquí se añadiría la marcación a la base de datos
        $currentTime = date('g:i a'); // Actualizar la hora si se simula
        echo "<script>alert('Simulación exitosa para: " . htmlspecialchars($_POST['persona_simulada']) . "');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SENA Control de Ingreso</title>
    <style>
        /* Estilos Globales */
        body {
            font-family: Arial, sans-serif;
            background-color: #1e603b; /* Fondo verde oscuro de la página */
            color: white;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            height: 100vh;
            overflow: hidden; /* Evitar desplazamiento para un kiosko */
        }

        /* Encabezado (Header) */
        header {
            background-color: #1a5334; /* Fondo header más oscuro */
            padding: 20px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid #ffcc00; /* Línea amarilla divisoria */
        }

        .header-logo {
            display: flex;
            align-items: center;
        }

        /* Simulación del logo para que parezca SENA */
        .sena-logo-simulated {
            width: 60px;
            height: 60px;
            background-color: #fff;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            color: #1e603b;
            font-weight: bold;
            font-size: 16px;
            border: 2px solid #fff;
            margin-right: 15px;
        }

        .header-title-wrapper { }
        .header-title { font-size: 28px; font-weight: bold; margin: 0; }
        .header-subtitle { font-size: 16px; margin: 0; opacity: 0.8; }

        .header-info {
            display: flex;
            gap: 20px;
        }

        .info-panel {
            background-color: #1a5334; /* Mismo color del header */
            padding: 15px 25px;
            border-radius: 8px;
            text-align: center;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .info-main { font-size: 32px; font-weight: bold; margin: 0; }
        .info-label { font-size: 14px; margin: 5px 0 0 0; opacity: 0.8; }

        /* Área de contenido principal */
        main {
            display: flex;
            flex: 1;
            padding: 40px;
            box-sizing: border-box;
            background-color: #1e603b; /* Fondo verde principal */
        }

        /* Área de escaneo central */
        .scanner-area {
            flex: 1;
            background-color: #26794a; /* Panel central más claro */
            border-radius: 12px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            margin-right: 40px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
            text-align: center;
        }

        /* Símbolo de interrogación central (rombo) */
        .scanner-icon {
            width: 150px;
            height: 150px;
            background-color: white;
            border-radius: 15px;
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 30px;
            transform: rotate(45deg); /* Rombo */
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
        .scanner-icon-content {
            font-size: 80px;
            color: #1e603b;
            font-weight: bold;
            transform: rotate(-45deg); /* Desrotar el contenido */
        }

        .scanner-area h2 { font-size: 36px; margin: 0; color: white; }
        .scanner-area p { font-size: 18px; margin: 15px 0 0 0; opacity: 0.9; color: #d4fce1;}

        /* Panel de Actividad Reciente */
        .activity-panel {
            width: 350px;
            background-color: #1a5334; /* Fondo de actividad */
            border-radius: 12px;
            padding: 25px;
            display: flex;
            flex-direction: column;
        }

        .activity-panel h3 {
            font-size: 20px;
            margin: 0 0 20px 0;
            border-bottom: 2px solid #ffcc00;
            padding-bottom: 10px;
            color: white;
        }

        .activity-list {
            flex: 1;
            overflow-y: auto; /* Permitir scroll si hay muchas */
            padding-right: 10px;
        }
        
        /* Personalización de scrollbar */
        .activity-list::-webkit-scrollbar { width: 6px; }
        .activity-list::-webkit-scrollbar-track { background: transparent; }
        .activity-list::-webkit-scrollbar-thumb { background: rgba(255, 255, 255, 0.2); border-radius: 3px; }

        .activity-item {
            background-color: #26794a;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 10px;
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        .activity-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .activity-name { font-weight: bold; color: white; }
        .status-tag {
            font-size: 11px;
            font-weight: bold;
            padding: 3px 8px;
            border-radius: 4px;
            color: white;
        }
        .status-tag.salida { background-color: #3bbfb8; } /* Verde azulado */
        .status-tag.entrada { background-color: #63d179; } /* Verde */

        .activity-details {
            font-size: 12px;
            opacity: 0.8;
            color: white;
            display: flex;
            gap: 10px;
        }

        /* Simulador de Código de Barras (Overlay) */
        .simulator-overlay {
            position: fixed;
            left: 40px;
            bottom: 40px;
            width: 320px;
            background-color: #121212; /* Fondo casi negro */
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.5);
            border: 2px solid #ffcc00; /* Borde amarillo para resaltar */
            z-index: 1000;
        }

        .simulator-header {
            display: flex;
            align-items: center;
            color: #ffcc00;
            margin-bottom: 15px;
            font-size: 14px;
            font-weight: bold;
        }
        .simulator-header::before {
            content: '✎'; /* Icono de lápiz simulado */
            margin-right: 8px;
        }

        /* Estilo para los elementos del simulador */
        .simulator-overlay form {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .simulator-overlay select {
            width: 100%;
            padding: 12px;
            background-color: white;
            color: black;
            border-radius: 5px;
            border: none;
            box-sizing: border-box;
            appearance: none; /* Quitar flecha predeterminada */
            font-size: 14px;
        }

        .simulator-overlay .btn {
            width: 100%;
            padding: 12px;
            border-radius: 5px;
            border: none;
            cursor: pointer;
            font-weight: bold;
            text-align: center;
            font-size: 14px;
            display: block;
            box-sizing: border-box;
            text-decoration: none; /* Para el enlace 'Salir' */
        }

        .simulator-overlay .btn-simular {
            background-color: #ffcc00;
            color: black;
        }

        .simulator-overlay .btn-desconocido {
            background-color: #555;
            color: white;
        }

        .simulator-overlay .btn-salir {
            background-color: #e74c3c; /* Rojo */
            color: white;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <header>
        <div class="header-logo">
            <div class="sena-logo-simulated">
                SENA
            </div>
            <div class="header-title-wrapper">
                <h1 class="header-title">Control de Ingreso</h1>
                <p class="header-subtitle">Sistema de Código de Barras</p>
            </div>
        </div>
        <div class="header-info">
            <div class="info-panel">
                <p class="info-main"><?php echo $currentTime; ?></p>
                <p class="info-label">Hora Actual</p>
            </div>
            <div class="info-panel">
                <p class="info-main"><?php echo $totalMarcaciones; ?></p>
                <p class="info-label">Marcaciones Hoy</p>
            </div>
        </div>
    </header>

    <main>
        <div class="scanner-area">
            <div class="scanner-icon">
                <div class="scanner-icon-content">?</div>
            </div>
            <h2>Escanea el código de barras</h2>
            <p>El lector escaneará automáticamente cuando pase el código</p>
        </div>

        <div class="activity-panel">
            <h3>Actividad Reciente</h3>
            <div class="activity-list">
                <?php foreach ($marcaciones as $marcacion): ?>
                    <div class="activity-item">
                        <div class="activity-header">
                            <span class="activity-name"><?php echo htmlspecialchars($marcacion['nombre']); ?></span>
                            <span class="status-tag <?php echo strtolower($marcacion['estado']); ?>">
                                <?php echo htmlspecialchars($marcacion['estado']); ?>
                            </span>
                        </div>
                        <div class="activity-details">
                            <span><?php echo htmlspecialchars($marcacion['hora']); ?> - </span>
                            <span><?php echo htmlspecialchars($marcacion['accion']); ?></span>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </main>

    <div class="simulator-overlay">
        <div class="simulator-header">SIMULADOR DE CÓDIGO DE BARRAS</div>
        <form action="" method="post">
            <select name="persona_simulada">
                <option value="" disabled selected>Seleccione persona...</option>
                <?php foreach ($personas as $persona): ?>
                    <option value="<?php echo htmlspecialchars($persona); ?>"><?php echo htmlspecialchars($persona); ?></option>
                <?php endforeach; ?>
            </select>
            <button type="submit" class="btn btn-simular" name="action" value="simular">Simular Escaneo</button>
            <button type="submit" class="btn btn-desconocido" name="action" value="desconocido">Código Desconocido</button>
            <a href="#" class="btn btn-salir">Salir del Kiosko</a>
        </form>
    </div>

</body>
</html>