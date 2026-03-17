<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reportes de Acceso - Sistema Ingreso SENA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background: #f5f5f5;
            padding: 20px;
        }
        .container {
            background: white;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #f0f0f0;
            padding-bottom: 15px;
        }
        .page-title {
            font-size: 2rem;
            font-weight: 700;
            color: #333;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        .stat-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
        }
        .stat-value {
            font-size: 2.5rem;
            font-weight: bold;
            line-height: 1;
        }
        .stat-label {
            font-size: 0.9rem;
            opacity: 0.9;
            margin-top: 10px;
        }
        .table-container {
            margin-top: 30px;
        }
        .btn-primary-custom {
            background: #28b463;
            border: none;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
        }
        .btn-primary-custom:hover {
            background: #219a52;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="page-header">
            <h1 class="page-title">
                <i class="bi bi-bar-chart"></i> Reportes de Acceso
            </h1>
            <button class="btn-primary-custom">
                <i class="bi bi-download"></i> Descargar
            </button>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-value">24</div>
                <div class="stat-label">Ingresos Hoy</div>
            </div>
            <div class="stat-card" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                <div class="stat-value">12</div>
                <div class="stat-label">Salidas Hoy</div>
            </div>
            <div class="stat-card" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                <div class="stat-value">156</div>
                <div class="stat-label">Total Este Mes</div>
            </div>
            <div class="stat-card" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);">
                <div class="stat-value">8</div>
                <div class="stat-label">Pendientes</div>
            </div>
        </div>

        <div class="table-container">
            <h3 style="margin-bottom: 20px;">Últimos Registros</h3>
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Documento</th>
                        <th>Nombre</th>
                        <th>Tipo</th>
                        <th>Fecha</th>
                        <th>Hora</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1023456789</td>
                        <td>Juan David Pérez</td>
                        <td><span class="badge bg-primary">Ingreso</span></td>
                        <td>2024-03-16</td>
                        <td>08:30</td>
                        <td><span class="badge bg-success">Registrado</span></td>
                        <td>
                            <button class="btn btn-sm btn-info"><i class="bi bi-eye"></i></button>
                            <button class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></button>
                        </td>
                    </tr>
                    <tr>
                        <td>1078945612</td>
                        <td>María García López</td>
                        <td><span class="badge bg-warning">Salida</span></td>
                        <td>2024-03-16</td>
                        <td>12:15</td>
                        <td><span class="badge bg-success">Registrado</span></td>
                        <td>
                            <button class="btn btn-sm btn-info"><i class="bi bi-eye"></i></button>
                            <button class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></button>
                        </td>
                    </tr>
                    <tr>
                        <td>1089765432</td>
                        <td>Carlos Rodríguez Martinez</td>
                        <td><span class="badge bg-primary">Ingreso</span></td>
                        <td>2024-03-15</td>
                        <td>14:45</td>
                        <td><span class="badge bg-warning">Pendiente</span></td>
                        <td>
                            <button class="btn btn-sm btn-info"><i class="bi bi-eye"></i></button>
                            <button class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #f0f0f0;">
            <h3>Filtros</h3>
            <div class="row mt-4">
                <div class="col-md-3">
                    <label class="form-label">Desde</label>
                    <input type="date" class="form-control">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Hasta</label>
                    <input type="date" class="form-control">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Estado</label>
                    <select class="form-select">
                        <option>Todos</option>
                        <option>Registrado</option>
                        <option>Pendiente</option>
                    </select>
                </div>
                <div class="col-md-3" style="display: flex; align-items: flex-end;">
                    <button class="btn btn-primary w-100">
                        <i class="bi bi-search"></i> Buscar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
