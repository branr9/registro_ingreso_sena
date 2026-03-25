<?php
// ==============================================================================
// 1. CONEXIÓN A LA BASE DE DATOS
// ==============================================================================
// Ajusta la ruta dependiendo de dónde esté exactamente tu conexion.php
// Basado en tu VS Code, si está en la raíz de registroIngreso, sería así:
require_once 'C:\Users\Aprendiz\Documents\GitHub\registro_ingreso_sena\registroIngreso\models\conexion.php'; 

$mensaje = '';
$tipo_mensaje = '';

// ==============================================================================
// 2. LÓGICA DE IMPORTACIÓN CSV
// ==============================================================================
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['csv_file'])) {
    $archivo = $_FILES['csv_file']['tmp_name'];
    
    if (!empty($archivo)) {
        // Abrir el archivo CSV en modo lectura
        $handle = fopen($archivo, "r");
        $row = 0;
        $importados = 0;
        $errores = 0;

        // Leer fila por fila
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            $row++;
            
            // Saltar la primera fila si contiene los encabezados (si la col 0 no es número)
            if ($row == 1 && !is_numeric($data[0])) {
                continue;
            }

            // Mapear las columnas del CSV (Asegúrate de que el CSV tenga este orden)
            // 0: documento, 1: nombre, 2: tipo_persona, 3: empresa, 4: email, 5: username
            $documento = mysqli_real_escape_string($conexion, $data[0] ?? '');
            $nombre = mysqli_real_escape_string($conexion, $data[1] ?? '');
            $tipo = mysqli_real_escape_string($conexion, $data[2] ?? '');
            $empresa = mysqli_real_escape_string($conexion, $data[3] ?? '');
            $email = mysqli_real_escape_string($conexion, $data[4] ?? '');
            $username = mysqli_real_escape_string($conexion, $data[5] ?? '');
            
            // Usamos 'tipo' como 'rol' también por defecto
            $rol = $tipo; 
            $estado = 1; // 1 para Activo

            // Validar campos obligatorios
            if (!empty($documento) && !empty($nombre) && !empty($tipo)) {
                
                // Verificar si el usuario ya existe en la BD
                $check = mysqli_query($conexion, "SELECT documento FROM usuarios WHERE documento = '$documento'");
                
                if (mysqli_num_rows($check) == 0) {
                    // Insertar nuevo usuario
                    // IMPORTANTE: Ajusta los nombres de las columnas ('tipo', 'rol') según tu tabla 'usuarios' real en Heidisql
                    $query = "INSERT INTO usuarios (documento, nombre, tipo, empresa, email, username, rol, estado) 
                              VALUES ('$documento', '$nombre', '$tipo', '$empresa', '$email', '$username', '$rol', '$estado')";
                    
                    if (mysqli_query($conexion, $query)) {
                        $importados++;
                    } else {
                        $errores++; // Error de SQL
                    }
                } else {
                    $errores++; // El documento ya existe
                }
            } else {
                $errores++; // Faltan datos obligatorios en la fila
            }
        }
        fclose($handle);
        
        // Generar mensaje de resultado
        if ($importados > 0) {
            $mensaje = "¡Éxito! Se han importado $importados usuarios correctamente. (Filas ignoradas/duplicadas: $errores)";
            $tipo_mensaje = "success";
        } else {
            $mensaje = "No se importó ningún usuario. Revisa el formato del CSV. (Errores: $errores)";
            $tipo_mensaje = "error";
        }
    } else {
        $mensaje = "Por favor, selecciona un archivo CSV válido.";
        $tipo_mensaje = "error";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Importar Usuarios - Sistema Ingreso</title>
    <script src="https://cdn.tailwindcss.com"></script>
    
    <style>
        /* CSS Personalizado para la tarjeta Glassmorphism */
        .gradient-card {
            background: linear-gradient(135deg, #5c4cd4, #0dcaf0);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }
        .glass-item {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        .item-warning {
            background: rgba(0, 0, 0, 0.1); /* Ligeramente más oscuro para advertencia */
        }
    </style>
</head>
<body class="bg-gray-100 font-sans antialiased text-gray-800 p-8">

    <div class="max-w-4xl mx-auto">
        
        <div class="flex items-center gap-4 mb-8">
            <a href="usuariosview.php" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg font-medium transition flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" /></svg>
                Volver
            </a>
            <h1 class="text-3xl font-bold flex items-center gap-2 text-gray-800">
                <svg class="w-8 h-8 text-gray-700" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m6.75 12l-3-3m0 0l-3 3m3-3v6m-1.5-15H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" /></svg>
                Importar Usuarios desde CSV
            </h1>
        </div>

        <?php if (!empty($mensaje)): ?>
            <div class="mb-6 p-4 rounded-lg font-medium <?php echo $tipo_mensaje == 'success' ? 'bg-green-100 text-green-800 border border-green-200' : 'bg-red-100 text-red-800 border border-red-200'; ?>">
                <?php echo $mensaje; ?>
            </div>
        <?php endif; ?>

        <div class="gradient-card rounded-2xl p-8 text-white mb-8">
            <div class="flex items-center gap-3 mb-6 text-xl font-bold">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" /></svg>
                <h2>Instrucciones de Importación</h2>
            </div>

            <div class="glass-item rounded-xl p-5 mb-4 flex gap-4">
                <svg class="w-7 h-7 mt-1 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" /></svg>
                <div>
                    <h3 class="font-bold text-lg mb-1">Formato del archivo:</h3>
                    <p class="opacity-90 text-sm">El archivo debe ser CSV (separado por comas). Puede incluir encabezados en la primera fila.</p>
                </div>
            </div>

            <div class="glass-item rounded-xl p-5 mb-4 flex gap-4">
                <svg class="w-7 h-7 mt-1 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 4.5v15m6-15v15m-10.5-6h15m-15-6h15m-3-4.5h3c.621 0 1.125.504 1.125 1.125v3c0 .621-.504 1.125-1.125 1.125h-3c-.621 0-1.125-.504-1.125-1.125v-3c0-.621.504-1.125 1.125-1.125z" /></svg>
                <div>
                    <h3 class="font-bold text-lg mb-1">Columnas requeridas (en orden):</h3>
                    <p class="opacity-90 text-sm">documento, nombre, tipo_persona, empresa (opcional), email (opcional), username (opcional)</p>
                </div>
            </div>

            <div class="glass-item rounded-xl p-5 mb-4 flex gap-4">
                <svg class="w-7 h-7 mt-1 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>
                <div>
                    <h3 class="font-bold text-lg mb-1">Tipos de persona válidos:</h3>
                    <p class="opacity-90 text-sm">aprendiz, instructor, admin, vigilante, contratista, visitante, proveedor</p>
                </div>
            </div>

            <div class="glass-item item-warning rounded-xl p-5 flex gap-4 border border-white/30">
                <svg class="w-7 h-7 mt-1 flex-shrink-0 text-white" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                <div>
                    <h3 class="font-bold text-lg mb-1">Importante:</h3>
                    <p class="opacity-90 text-sm">El sistema validará cada fila antes de importar. Podrás revisar un resumen al finalizar.</p>
                </div>
            </div>
        </div>

        <form action="" method="POST" enctype="multipart/form-data" class="bg-white p-8 rounded-2xl shadow-sm border border-gray-200">
            <h3 class="text-xl font-bold mb-4 text-gray-800">Cargar archivo</h3>
            
            <div class="flex items-center justify-center w-full mb-6">
                <label for="dropzone-file" class="flex flex-col items-center justify-center w-full h-40 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 transition">
                    <div class="flex flex-col items-center justify-center pt-5 pb-6">
                        <svg class="w-10 h-10 mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                        <p class="mb-2 text-sm text-gray-500"><span class="font-semibold">Haz clic para seleccionar</span> o arrastra y suelta aquí</p>
                        <p class="text-xs text-gray-500">Solo archivos .CSV</p>
                    </div>
                    <input id="dropzone-file" type="file" name="csv_file" accept=".csv" class="hidden" required />
                </label>
            </div>

            <div class="flex justify-end">
                <button type="submit" class="bg-[#4caf50] hover:bg-green-600 text-white font-bold py-3 px-6 rounded-lg shadow-md transition flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5" /></svg>
                    Iniciar Importación
                </button>
            </div>
        </form>

    </div>

    <script>
        document.getElementById('dropzone-file').addEventListener('change', function(e) {
            if(e.target.files.length > 0) {
                const fileName = e.target.files[0].name;
                const labelText = e.target.previousElementSibling.querySelector('p.text-sm');
                labelText.innerHTML = `<span class="font-semibold text-blue-600">Archivo seleccionado:</span> ${fileName}`;
            }
        });
    </script>
</body>
</html>