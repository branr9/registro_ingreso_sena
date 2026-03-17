<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Sistema Ingreso SENA</title>
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Nunito', sans-serif;
            background-color: #f4f6f9;
            padding: 30px;
            margin: 0;
        }

        .dashboard-container {
            max-width: 1400px;
            margin: 0 auto;
        }

        .dashboard-header {
            margin-bottom: 30px;
        }

        .dashboard-header h1 {
            color: #2c3e50;
            font-weight: 700;
            margin: 0 0 10px 0;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .dashboard-header p {
            color: #7f8c8d;
            margin: 0;
            font-size: 14px;
        }

        .stats-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            border-left: 5px solid #3aa822;
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12);
        }

        .stat-card.primary {
            border-left-color: #3aa822;
        }

        .stat-card.success {
            border-left-color: #1abc9c;
        }

        .stat-card.warning {
            border-left-color: #f39c12;
        }

        .stat-card.danger {
            border-left-color: #e74c3c;
        }

        .stat-icon {
            font-size: 32px;
            margin-bottom: 12px;
        }

        .stat-icon.primary { color: #3aa822; }
        .stat-icon.success { color: #1abc9c; }
        .stat-icon.warning { color: #f39c12; }
        .stat-icon.danger { color: #e74c3c; }

        .stat-value {
            font-size: 32px;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 8px;
        }

        .stat-label {
            color: #7f8c8d;
            font-size: 14px;
            font-weight: 600;
        }

        .charts-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(500px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .chart-card {
            background: white;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        .chart-title {
            color: #2c3e50;
            font-weight: 700;
            margin-bottom: 25px;
            font-size: 18px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .chart-title i {
            color: #3aa822;
        }

        .chart-container {
            position: relative;
            height: 300px;
        }

        @media (max-width: 768px) {
            .charts-row {
                grid-template-columns: 1fr;
            }

            .stat-value {
                font-size: 24px;
            }

            .stats-row {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <!-- Header -->
        <div class="dashboard-header">
            <h1><i class="bi bi-speedometer2"></i> Dashboard</h1>
            <p>Resumen general del sistema de ingreso SENA</p>
        </div>

        <!-- Estadísticas principales -->
        <div class="stats-row">
            <div class="stat-card primary">
                <div class="stat-icon primary"><i class="bi bi-people-fill"></i></div>
                <div class="stat-value">24</div>
                <div class="stat-label">Entradas Hoy</div>
            </div>

            <div class="stat-card success">
                <div class="stat-icon success"><i class="bi bi-door-open"></i></div>
                <div class="stat-value">8</div>
                <div class="stat-label">Salas Disponibles</div>
            </div>

            <div class="stat-card warning">
                <div class="stat-icon warning"><i class="bi bi-calendar2-check"></i></div>
                <div class="stat-value">5</div>
                <div class="stat-label">Préstamos Hoy</div>
            </div>

            <div class="stat-card danger">
                <div class="stat-icon danger"><i class="bi bi-exclamation-circle"></i></div>
                <div class="stat-value">2</div>
                <div class="stat-label">Alertas Activas</div>
            </div>
        </div>

        <!-- Gráficos -->
        <div class="charts-row">
            <!-- Gráfico de Entradas -->
            <div class="chart-card">
                <div class="chart-title">
                    <i class="bi bi-graph-up"></i> Entradas de Personal por Hora
                </div>
                <div class="chart-container">
                    <canvas id="entradasChart"></canvas>
                </div>
            </div>

            <!-- Gráfico de Préstamo de Salas -->
            <div class="chart-card">
                <div class="chart-title">
                    <i class="bi bi-pie-chart"></i> Estado de Préstamo de Salas
                </div>
                <div class="chart-container">
                    <canvas id="salasChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Gráfico adicional -->
        <div class="charts-row">
            <div class="chart-card">
                <div class="chart-title">
                    <i class="bi bi-bar-chart"></i> Distribución de Entradas esta Semana
                </div>
                <div class="chart-container">
                    <canvas id="semanalaChart" style="height: 250px !important;"></canvas>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Gráfico de Entradas por Hora
        const entradasCtx = document.getElementById('entradasChart').getContext('2d');
        new Chart(entradasCtx, {
            type: 'line',
            data: {
                labels: ['06:00', '08:00', '10:00', '12:00', '14:00', '16:00', '18:00', '20:00'],
                datasets: [{
                    label: 'Entradas',
                    data: [2, 5, 8, 12, 9, 7, 4, 2],
                    borderColor: '#3aa822',
                    backgroundColor: 'rgba(58, 168, 34, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointRadius: 5,
                    pointBackgroundColor: '#3aa822',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        labels: {
                            font: { size: 13, weight: '600', family: "'Nunito', sans-serif" },
                            color: '#2c3e50',
                            padding: 15
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 15,
                        ticks: {
                            font: { size: 12, family: "'Nunito', sans-serif" },
                            color: '#7f8c8d'
                        },
                        grid: { color: 'rgba(0, 0, 0, 0.05)' }
                    },
                    x: {
                        ticks: {
                            font: { size: 12, family: "'Nunito', sans-serif" },
                            color: '#7f8c8d'
                        },
                        grid: { display: false }
                    }
                }
            }
        });

        // Gráfico de Estado de Salas
        const salasCtx = document.getElementById('salasChart').getContext('2d');
        new Chart(salasCtx, {
            type: 'doughnut',
            data: {
                labels: ['Disponibles', 'Ocupadas', 'Mantenimiento'],
                datasets: [{
                    data: [8, 10, 2],
                    backgroundColor: ['#3aa822', '#f39c12', '#e74c3c'],
                    borderColor: '#fff',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'bottom',
                        labels: {
                            font: { size: 13, weight: '600', family: "'Nunito', sans-serif" },
                            color: '#2c3e50',
                            padding: 15
                        }
                    }
                }
            }
        });

        // Gráfico de Distribución Semanal
        const semanalaCtx = document.getElementById('semanalaChart').getContext('2d');
        new Chart(semanalaCtx, {
            type: 'bar',
            data: {
                labels: ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'],
                datasets: [{
                    label: 'Total de Entradas',
                    data: [28, 32, 26, 35, 30, 12, 5],
                    backgroundColor: '#3aa822',
                    borderColor: '#2a8619',
                    borderWidth: 1,
                    borderRadius: 6
                }]
            },
            options: {
                indexAxis: undefined,
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        labels: {
                            font: { size: 13, weight: '600', family: "'Nunito', sans-serif" },
                            color: '#2c3e50',
                            padding: 15
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            font: { size: 12, family: "'Nunito', sans-serif" },
                            color: '#7f8c8d'
                        },
                        grid: { color: 'rgba(0, 0, 0, 0.05)' }
                    },
                    x: {
                        ticks: {
                            font: { size: 12, family: "'Nunito', sans-serif" },
                            color: '#7f8c8d'
                        },
                        grid: { display: false }
                    }
                }
            }
        });
    </script>
</body>
</html>