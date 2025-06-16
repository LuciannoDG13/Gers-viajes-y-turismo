<?php
include '../../db.php';

// Estado general de la empresa
$sql_empresa = "
    SELECT 
        SUM(p.monto_total_pagado) AS total_ingresado,
        COUNT(p.id_pago) AS total_pagos,
        MAX(p.fecha_pago) AS ultimo_pago
    FROM pagos p
    WHERE p.estado_pago = 'confirmado'
";
$result_empresa = mysqli_query($conn, $sql_empresa);
$empresa = mysqli_fetch_assoc($result_empresa);

// Lista de clientes
$sql_clientes = "
    SELECT c.id_cliente, u.nombre
    FROM clientes c
    JOIN usuarios u ON c.id_usuario = u.id_usuario
    ORDER BY u.nombre ASC
";
$result_clientes = mysqli_query($conn, $sql_clientes);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <title>Estado de Cuenta Financiero</title>
    <link rel="stylesheet" href="/viajes turismo/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        body {
            background: linear-gradient(135deg, #eaf6fb, #d3ecf5);
            font-family: 'Segoe UI', sans-serif;
            color: #1d3c73;
            min-height: 100vh;
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

        h2 {
            text-align: center;
            margin-bottom: 2rem;
            font-weight: 700;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 0 25px rgba(77, 176, 230, 0.25);
            overflow: hidden;
            margin-bottom: 3rem;
        }

        thead tr {
            background-color: #1d3c73;
            color: white;
            font-weight: 600;
        }

        th, td {
            padding: 12px 18px;
            text-align: center;
            border-bottom: 1px solid #cde5f7;
            font-size: 15px;
        }

        tbody tr:last-child td {
            border-bottom: none;
        }

        .btn-ver-estado {
            background-color: #4db0e6;
            border: none;
            padding: 7px 16px;
            font-weight: 600;
            color: white;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s ease-in-out;
            text-decoration: none;
            display: inline-block;
            user-select: none;
        }

        .btn-ver-estado:hover {
            background-color: #3ca3d4;
            text-decoration: none;
            color: white;
        }

        @media (max-width: 600px) {
            .main-content {
                margin-left: 0;
                padding: 1rem;
            }
            th, td {
                padding: 10px 8px;
                font-size: 13px;
            }
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

<!-- Sidebar -->
   <div class="sidebar">
    <a href="../principal.php" class="logo-link">
    <img style="width: 200px; margin-left:-20px; margin-bottom: 15px;" src="../../img/logoo.png" alt="Logo">
</a>

    <a href="../pedidos/pedidos.php"><i class="bi bi-basket-fill"></i> Pedidos</a>
    <a href="../productos/productos.php"><i class="bi bi-box-seam"></i> Productos</a>
    <a href="../clientes/cliente.php"><i class="bi bi-people-fill"></i> Clientes</a>
    <a href="../usuarios/usuarios.php"><i class="bi bi-people-fill"></i> Usuarios</a>
    <a href="../reportes/reportes.php"><i class="bi bi-graph-up"></i> Reportes</a>
    <a href="../ventas/historial_ventas.php"><i class="bi bi-clock-history"></i> Historial de Ventas</a>
    <a href="estado_cuenta.php"><i class="bi bi-cash-coin"></i> Estado de Cuentas</a>
    <a href="../../cierre_sesion.php"><i class="bi bi-box-arrow-right"></i> Cerrar sesión</a>
</div>
<!-- Main content -->
<div class="main-content">
    <h2>Estado Financiero General</h2>
    <table>
        <thead>
            <tr>
                <th>Total Ingresado</th>
                <th>Total de Pagos Confirmados</th>
                <th>Fecha del Último Pago</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>$<?= number_format($empresa['total_ingresado'], 2, ',', '.') ?></td>
                <td><?= (int)$empresa['total_pagos'] ?></td>
                <td><?= htmlspecialchars($empresa['ultimo_pago']) ?></td>
            </tr>
        </tbody>
    </table>

    <h2>Listado de Clientes</h2>
    <table>
        <thead>
            <tr>
                <th>Nombre del Cliente</th>
                <th>Acción</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($cliente = mysqli_fetch_assoc($result_clientes)): ?>
                <tr>
                    <td><?= htmlspecialchars($cliente['nombre']) ?></td>
                    <td>
                        <a class="btn-ver-estado" href="estado_cliente.php?id_cliente=<?= (int)$cliente['id_cliente'] ?>">
                            Ver Estado
                        </a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

</body>
</html>

<?php mysqli_close($conn); ?>
