<?php
require('../../db.php');

$id_venta = $_GET['id_venta'] ?? null;

if ($id_venta) {
    $sql_detalle = "SELECT dv.*, p.nombre_producto 
                    FROM detalle_ventas dv
                    INNER JOIN productos p ON p.id_producto = dv.id_producto
                    WHERE dv.id_venta = $id_venta";
    $detalle_venta = $conn->query($sql_detalle);
} else {
    header("Location: historial_ventas.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Detalle de Venta</title>
      <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/viajes turismo/css/bootstrap.min.css">
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #eaf6fb, #d3ecf5);
            padding: 2rem;
            color: #1d3c73;
        }
     .logo-link {
    padding: 0;
    margin: 0;
    display: block;
}

.logo-link:hover {
    background-color: transparent;
}

        .detalle-venta {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 25px;
            border-radius: 18px;
            box-shadow: 0 0 15px rgba(77, 176, 230, 0.2);
        }

        h4 {
            margin-bottom: 1.5rem;
        }

        table {
            width: 100%;
        }

        th, td {
            text-align: center;
            padding: 10px;
        }

        thead {
            background: #eaf6fb;
        }

        .btn-volver {
            margin-top: 20px;
            background-color: #1d3c73;
            color: white;
            padding: 10px 20px;
            border-radius: 10px;
            text-decoration: none;
            display: inline-block;
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
    <div class="detalle-venta">

     <div class="sidebar">
    <a href="principal.php" class="logo-link">
    <img style="width: 200px; margin-left:-20px; margin-bottom: 15px;" src="../../img/logoo.png" alt="Logo">
</a>

    <a href="../pedidos/pedidos.php"><i class="bi bi-basket-fill"></i> Pedidos</a>
    <a href="../productos/productos.php"><i class="bi bi-box-seam"></i> Productos</a>
    <a href="../clientes/cliente.php"><i class="bi bi-people-fill"></i> Clientes</a>
    <a href="../usuarios/usuarios.php"><i class="bi bi-people-fill"></i> Usuarios</a>
    <a href="../reportes/reportes.php"><i class="bi bi-graph-up"></i> Reportes</a>
    <a href="historial_ventas.php"><i class="bi bi-clock-history"></i> Historial de Ventas</a>
    <a href="../estado_cuenta/estado_cuenta.php"><i class="bi bi-cash-coin"></i> Estado de Cuentas</a>
    <a href="../../cierre_sesion.php"><i class="bi bi-box-arrow-right"></i> Cerrar sesión</a>
</div>



        <h4>Detalle de Venta</h4>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th>Precio Unitario</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($detalle = $detalle_venta->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($detalle['nombre_producto']) ?></td>
                        <td><?= $detalle['cantidad'] ?></td>
                        <td>$<?= number_format($detalle['precio_unitario'], 2) ?></td>
                        <td>$<?= number_format($detalle['subtotal'], 2) ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <a href="historial_ventas.php" class="btn-volver">← Volver al Historial</a>
    </div>
</body>
</html>
