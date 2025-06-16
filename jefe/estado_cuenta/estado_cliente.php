<?php
include '../../db.php';

$id_cliente = isset($_GET['id_cliente']) ? intval($_GET['id_cliente']) : 0;

$sql = "
    SELECT 
        c.id_cliente,
        u.nombre AS nombre_cliente,
        SUM(p.monto_total_pagado) AS total_pagado,
        COUNT(p.id_pago) AS cantidad_pagos,
        MAX(p.fecha_pago) AS ultimo_pago
    FROM pagos p
    JOIN pedidos pd ON p.id_pedido = pd.id_pedido
    JOIN clientes c ON pd.id_usuario = c.id_usuario
    JOIN usuarios u ON c.id_usuario = u.id_usuario
    WHERE c.id_cliente = $id_cliente AND p.estado_pago = 'confirmado'
    GROUP BY c.id_cliente
";

$resultado = mysqli_query($conn, $sql);
$cliente = mysqli_fetch_assoc($resultado);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <title>Estado de Cliente</title>
    <link rel="stylesheet" href="/viajes turismo/css/bootstrap.min.css" />
      <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        body {
            background: linear-gradient(135deg, #eaf6fb, #d3ecf5);
            font-family: 'Segoe UI', sans-serif;
            color: #1d3c73;
            padding: 40px;
            min-height: 100vh;
        }
        .container {
            max-width: 600px;
            margin: auto;
            background: #ffffff;
            border-radius: 18px;
            padding: 2.5rem 3rem;
            box-shadow: 0 0 25px rgba(77, 176, 230, 0.25);
        }
        h2 {
            text-align: center;
            margin-bottom: 2rem;
            font-weight: 700;
            color: #1d3c73;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            border-radius: 14px;
            overflow: hidden;
            box-shadow: 0 0 15px rgba(77, 176, 230, 0.15);
            margin-bottom: 2.5rem;
        }
        thead tr {
            background-color: #1d3c73;
            color: white;
            font-weight: 600;
        }
        th, td {
            padding: 14px 18px;
            text-align: center;
            border-bottom: 1px solid #cde5f7;
            font-size: 16px;
        }
        tbody tr:last-child td {
            border-bottom: none;
        }
        .btn-volver {
            display: inline-block;
            background-color: #4db0e6;
            color: white;
            padding: 10px 22px;
            border-radius: 8px;
            font-weight: 600;
            text-decoration: none;
            transition: background-color 0.3s ease-in-out;
            user-select: none;
            margin-bottom: 0;
        }
        .btn-volver:hover {
            background-color: #3ca3d4;
            color: white;
            text-decoration: none;
        }
        p {
            font-size: 16px;
            text-align: center;
            color: #1d3c73;
            font-weight: 600;
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

    <a href="estado_cuenta.php" class="btn-volver mb-4">Volver al listado</a>

    <?php if ($cliente): ?>
        <h2>Estado de Cuenta del Cliente: <?= htmlspecialchars($cliente['nombre_cliente']) ?></h2>
        <table>
            <thead>
                <tr>
                    <th>Total Pagado</th>
                    <th>Cantidad de Pagos</th>
                    <th>Último Pago</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>$<?= number_format($cliente['total_pagado'], 2, ',', '.') ?></td>
                    <td><?= (int)$cliente['cantidad_pagos'] ?></td>
                    <td><?= htmlspecialchars($cliente['ultimo_pago']) ?></td>
                </tr>
            </tbody>
        </table>
    <?php else: ?>
        <p>No se encontraron pagos confirmados para este cliente.</p>
    <?php endif; ?>

</div>

</body>
</html>

<?php mysqli_close($conn); ?>
