<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Usuarios - Sistema Ingreso SENA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Nunito', sans-serif;
            background-color: #f5f5f5;
            padding: 20px;
        }
        .container-fluid {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        }
        h1 {
            color: #333;
            margin-bottom: 30px;
        }
        .table {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <h1><i class="bi bi-people"></i> Gestión de Usuarios</h1>
        <p>Panel de administración y gestión de usuarios del sistema.</p>
        
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Rol</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>Juan Pérez</td>
                    <td>juan@sena.edu.co</td>
                    <td>Administrador</td>
                    <td><span class="badge bg-success">Activo</span></td>
                    <td><button class="btn btn-sm btn-primary">Editar</button></td>
                </tr>
                <tr>
                    <td>2</td>
                    <td>María García</td>
                    <td>maria@sena.edu.co</td>
                    <td>Usuario</td>
                    <td><span class="badge bg-success">Activo</span></td>
                    <td><button class="btn btn-sm btn-primary">Editar</button></td>
                </tr>
            </tbody>
        </table>
    </div>
</body>
</html>