<?php  
include '../db.php';  

// 1. Producto más vendido
$sql_producto_mas_vendido = "
    SELECT p.nombre_producto, SUM(dv.cantidad) AS total_vendido
    FROM detalle_ventas dv
    INNER JOIN productos p ON dv.id_producto = p.id_producto
    GROUP BY dv.id_producto
    ORDER BY total_vendido DESC
    LIMIT 1
";
$result = $conn->query($sql_producto_mas_vendido);
$producto_mas_vendido = $result && $result->num_rows > 0 ? $result->fetch_assoc() : ['nombre_producto' => 'No hay datos', 'total_vendido' => 0];

// 2. Usuario que más compra
$sql_usuario_mas_comprador = "
    SELECT u.nombre, SUM(v.total) AS total_comprado
    FROM ventas v
    INNER JOIN usuarios u ON v.id_usuario = u.id_usuario
    GROUP BY v.id_usuario
    ORDER BY total_comprado DESC
    LIMIT 1
";
$result = $conn->query($sql_usuario_mas_comprador);
$usuario_mas_comprador = $result && $result->num_rows > 0 ? $result->fetch_assoc() : ['nombre' => 'No hay datos', 'total_comprado' => 0];

// 3. Ventas de esta semana
$sql_ventas_semana = "
    SELECT IFNULL(SUM(total),0) AS total_semana
    FROM ventas
    WHERE YEARWEEK(fecha_venta, 1) = YEARWEEK(CURDATE(), 1)
";
$result = $conn->query($sql_ventas_semana);
$ventas_semana = $result && $result->num_rows > 0 ? $result->fetch_assoc()['total_semana'] : 0;

// 4. Ventas del mes
$sql_ventas_mes = "
    SELECT IFNULL(SUM(total),0) AS total_mes
    FROM ventas
    WHERE YEAR(fecha_venta) = YEAR(CURDATE()) AND MONTH(fecha_venta) = MONTH(CURDATE())
";
$result = $conn->query($sql_ventas_mes);
$ventas_mes = $result && $result->num_rows > 0 ? $result->fetch_assoc()['total_mes'] : 0;

// 5. Ventas del año
$sql_ventas_anio = "
    SELECT IFNULL(SUM(total),0) AS total_anio
    FROM ventas
    WHERE YEAR(fecha_venta) = YEAR(CURDATE())
";
$result = $conn->query($sql_ventas_anio);
$ventas_anio = $result && $result->num_rows > 0 ? $result->fetch_assoc()['total_anio'] : 0;

$conn->close();
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Panel de Estadísticas</title>
<link rel="stylesheet" href="/viajes turismo/css/bootstrap.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        body {
            background: #f0f8ff;
            font-family: 'Segoe UI', sans-serif;
        }
        .card {
            border-radius: 12px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.05);
        }
        .sidebar {
            height: 100vh;
            width: 250px;
            position: fixed;
            left: 0;
            top: 0;
            background-color: rgb(248, 245, 245);
            box-shadow: 2px 0 15px rgba(77, 176, 230, 0.2);
            padding: 1rem;
            border-radius: 0 18px 18px 0;
        }
        .sidebar a {
            display: block;
            color: #1d3c73;
            padding: 10px 15px;
            text-decoration: none;
            border-radius: 8px;
            transition: background 0.3s ease;
            margin-bottom: 10px;
        }
        .sidebar a:hover {
            background-color: #e6f3fa;
        }
        .main-content {
            margin-left: 270px;
            padding: 2rem;
        }
        .chart-container {
            background: white;
            padding: 1rem;
            border-radius: 12px;
            box-shadow: 0 0 15px rgba(0,0,0,0.05);
        }
.logo-link {
    padding: 0;
    margin: 0;
    display: block;
    background: transparent !important;
    text-decoration: none !important;
    color: inherit !important;
    cursor: pointer;
}

.logo-link:hover,
.logo-link:focus,
.logo-link:active {
    background: transparent !important;
    text-decoration: none !important;
    color: inherit !important;
}


    </style>
</head>
<body>
<div class="sidebar">
    <a href="principal.php" class="logo-link">
    <img style="width: 200px; margin-left:-20px; margin-bottom: 15px;" src="../img/logoo.png" alt="Logo">
</a>

    <a href="pedidos/pedidos.php"><i class="bi bi-basket-fill"></i> Pedidos</a>
    <a href="productos/productos.php"><i class="bi bi-box-seam"></i> Productos</a>
    <a href="clientes/cliente.php"><i class="bi bi-people-fill"></i> Clientes</a>
    <a href="usuarios/usuarios.php"><i class="bi bi-people-fill"></i> Usuarios</a>
    <a href="reportes/reportes.php"><i class="bi bi-graph-up"></i> Reportes</a>
    <a href="ventas/historial_ventas.php"><i class="bi bi-clock-history"></i> Historial de Ventas</a>
    <a href="estado_cuenta/estado_cuenta.php"><i class="bi bi-cash-coin"></i> Estado de Cuentas</a>
    <a href="../cierre_sesion.php"><i class="bi bi-box-arrow-right"></i> Cerrar sesión</a>
</div>


<div class="main-content">
    <h2 class="text-primary mb-4">Panel de Estadísticas</h2>

    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card text-white bg-info p-3">
                <div class="card-body text-center">
                    <i class="bi bi-box-seam fs-1"></i>
                    <h5 class="card-title mt-2">Producto más vendido</h5>
                    <p class="card-text mb-0"><?= htmlspecialchars($producto_mas_vendido['nombre_producto']) ?></p>
                    <small><?= intval($producto_mas_vendido['total_vendido']) ?> unidades</small>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card text-white bg-info p-3">
                <div class="card-body text-center">
                    <i class="bi bi-people-fill fs-1"></i>
                    <h5 class="card-title mt-2">Usuario que más compra</h5>
                    <p class="card-text mb-0"><?= htmlspecialchars($usuario_mas_comprador['nombre']) ?></p>
                    <small>Total: $<?= number_format($usuario_mas_comprador['total_comprado'], 2) ?></small>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card text-white bg-info p-3">
                <div class="card-body text-center">
                    <i class="bi bi-bar-chart-line fs-1"></i>
                    <h5 class="card-title mt-2">Resumen de Ventas</h5>
                    <p class="card-text mb-0">Semana: $<?= number_format($ventas_semana, 2) ?></p>
                    <p class="card-text mb-0">Mes: $<?= number_format($ventas_mes, 2) ?></p>
                    <p class="card-text">Año: $<?= number_format($ventas_anio, 2) ?></p>
                </div>
            </div>
        </div>
    </div>

    <div class="chart-container">
        <canvas id="ventasChart"></canvas>
    </div>
</div>

<script>
    const ctx = document.getElementById('ventasChart').getContext('2d');
    const ventasChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Semana', 'Mes', 'Año'],
            datasets: [{
                label: 'Ventas ($)',
                data: [<?= $ventas_semana ?>, <?= $ventas_mes ?>, <?= $ventas_anio ?>],
                backgroundColor: [
                    'rgba(75, 192, 192, 0.7)',
                    'rgba(54, 162, 235, 0.7)',
                    'rgba(153, 102, 255, 0.7)'
                ],
                borderRadius: 8
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return '$' + context.raw.toLocaleString();
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return '$' + value;
                        }
                    }
                }
            }
        }
    });
</script>
</body>
</html>
