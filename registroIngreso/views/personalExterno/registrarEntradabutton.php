<?php
// ==========================================
// 1. CONEXIÓN A LA BASE DE DATOS NEXUS
// ==========================================
$host = '127.0.0.1';
$dbname = 'nexus';
$username = 'root'; 
$password = 'root';     // Cambia esto si en HeidiSQL le tienes contraseña a 'root'

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("<div class='alert alert-danger m-3'>Error de conexión: " . $e->getMessage() . "</div>");
}

$mensaje = '';

// ==========================================
// 2. PROCESAR EL FORMULARIO AL PRESIONAR "GUARDAR"
// ==========================================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recibir los datos del formulario
    $tipo_documento = $_POST['tipo_documento'] ?? '';
    $numero_documento = $_POST['numero_documento'] ?? '';
    $nombres = $_POST['nombres'] ?? '';
    $apellidos = $_POST['apellidos'] ?? '';
    $empresa = $_POST['empresa'] ?? '';
    $telefono = $_POST['telefono'] ?? '';
    
    // Unir nombres y apellidos para la columna 'nombre' de tu tabla
    $nombre_completo = trim($nombres . ' ' . $apellidos);

    // Configurar la zona horaria a Colombia para que la hora sea exacta
    date_default_timezone_set('America/Bogota');
    $fecha_actual = date('Y-m-d');
    $hora_actual = date('H:i:s');

    try {
        // Preparar la consulta para insertar los datos
        $sql = "INSERT INTO personal_externo 
                (documento, tipo_documento, nombre, telefono, empresa, fecha, hora_ingreso, estado) 
                VALUES 
                (:documento, :tipo_documento, :nombre, :telefono, :empresa, :fecha, :hora_ingreso, 'Dentro')";
        
        $stmt = $pdo->prepare($sql);
        
        // Ejecutar la inserción
        $stmt->execute([
            ':documento' => $numero_documento,
            ':tipo_documento' => $tipo_documento,
            ':nombre' => $nombre_completo,
            ':telefono' => $telefono,
            ':empresa' => $empresa,
            ':fecha' => $fecha_actual,
            ':hora_ingreso' => $hora_actual
        ]);

        $mensaje = "<div class='alert alert-success mt-3 mb-3'><i class='bi bi-check-circle-fill'></i> Registro de entrada guardado exitosamente para <strong>" . htmlspecialchars($nombre_completo) . "</strong>.</div>";
    } catch (PDOException $e) {
        $mensaje = "<div class='alert alert-danger mt-3 mb-3'><i class='bi bi-x-circle-fill'></i> Error al guardar en la base de datos: " . $e->getMessage() . "</div>";
    }
}
?>

<style>
    /* Estilos del encabezado (Botón Volver y Título) */
    .header-registrar {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-bottom: 5px;
    }

    .btn-volver {
        background-color: #6c757d; /* Gris azulado similar al de la imagen */
        color: white;
        padding: 6px 14px;
        border-radius: 4px;
        text-decoration: none;
        font-weight: 500;
        font-size: 14px;
        display: flex;
        align-items: center;
        gap: 6px;
        border: none;
        transition: 0.2s ease;
    }

    .btn-volver:hover {
        background-color: #5a6268;
        color: white;
    }

    .title-main {
        margin: 0;
        font-size: 22px;
        color: #2c3e50;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    /* Subtítulo alineado correctamente con el texto, saltando el botón Volver */
    .subtitle-main {
        margin: 0 0 25px 95px; 
        color: #6c757d;
        font-size: 13.5px;
    }

    /* Tarjeta blanca del formulario */
    .form-card {
        background-color: #ffffff;
        border-radius: 8px;
        padding: 30px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        border: 1px solid #eaeaea;
    }

    /* Título verde de la sección con línea separadora */
    .section-title {
        color: #4ca146; /* Verde SENA */
        font-size: 16px;
        font-weight: 600;
        margin-bottom: 25px;
        padding-bottom: 10px;
        border-bottom: 2px solid #4ca146;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    /* Estilos de las etiquetas y campos de entrada */
    .custom-label {
        font-size: 13px;
        font-weight: 600;
        color: #4a5568;
        margin-bottom: 8px;
        display: block;
    }

    .custom-input {
        width: 100%;
        padding: 10px 14px;
        border: 1px solid #cbd5e0;
        border-radius: 5px;
        font-size: 14px;
        color: #2d3748;
        outline: none;
        transition: border-color 0.2s;
        background-color: #ffffff;
    }

    .custom-input:focus {
        border-color: #4ca146;
    }

    .custom-input::placeholder {
        color: #a0aec0;
    }

    .required-asterisk {
        color: #e53e3e; /* Asterisco rojo */
    }
</style>

<div class="header-registrar">
    <a href="index.php?vista=personalExterno" class="btn-volver">
        <i class="bi bi-arrow-left"></i> Volver
    </a>
    <h2 class="title-main">
        <i class="bi bi-person-plus-fill"></i> Registrar Entrada - Personal Externo
    </h2>
</div>
<p class="subtitle-main">Complete el formulario para registrar el ingreso de personal sin carnet (visitantes, contratistas, proveedores)</p>

<?php echo $mensaje; ?>

<div class="form-card">
    <div class="section-title">
        <i class="bi bi-person-vcard"></i> Datos del Visitante
    </div>
    
    <form action="" method="POST">
        <div class="row mb-4">
            <div class="col-md-6">
                <label class="custom-label">Tipo de Documento <span class="required-asterisk">*</span></label>
                <select class="custom-input" name="tipo_documento" required>
                    <option value="CC" selected>Cédula de Ciudadanía</option>
                    <option value="TI">Tarjeta de Identidad</option>
                    <option value="CE">Cédula de Extranjería</option>
                    <option value="PA">Pasaporte</option>
                </select>
            </div>
            <div class="col-md-6">
                <label class="custom-label">Número de Documento <span class="required-asterisk">*</span></label>
                <input type="text" class="custom-input" name="numero_documento" placeholder="Ej: 1234567890" required>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-6">
                <label class="custom-label">Nombres <span class="required-asterisk">*</span></label>
                <input type="text" class="custom-input" name="nombres" placeholder="Ej: Juan Carlos" required>
            </div>
            <div class="col-md-6">
                <label class="custom-label">Apellidos</label>
                <input type="text" class="custom-input" name="apellidos" placeholder="Ej: Pérez González">
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-4">
                <label class="custom-label">Empresa/Institución</label>
                <input type="text" class="custom-input" name="empresa" placeholder="Ej: Tech Solutions S.A.S">
            </div>
            <div class="col-md-4">
                <label class="custom-label">Teléfono</label>
                <input type="text" class="custom-input" name="telefono" placeholder="Ej: 3001234567">
            </div>
            <div class="col-md-4">
                <label class="custom-label">Email</label>
                <input type="email" class="custom-input" name="email" placeholder="ejemplo@empresa.com">
            </div>
        </div>
        
        <div class="text-end mt-2">
            <button type="submit" class="btn-volver" style="background-color: #4ca146; display: inline-flex; margin-left: auto;">
                <i class="bi bi-save"></i> Guardar Registro
            </button>
        </div>
    </form>
</div>