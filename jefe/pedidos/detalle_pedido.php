<?php
require('../../db.php');

if (!isset($_POST['id_pedido'])) {
    echo "Pedido no especificado.";
    exit();
}

$id_pedido = $_POST['id_pedido'];

// Consulta para obtener el detalle con nombre del producto
$sql = "SELECT detalle_pedidos.cantidad, productos.nombre_producto, productos.precio_unitario
        FROM detalle_pedidos
        INNER JOIN productos ON productos.id_producto = detalle_pedidos.id_producto
        WHERE detalle_pedidos.id_pedido = $id_pedido";

$result = mysqli_query($conn, $sql);
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Detalle del Pedido</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="/viajes turismo/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    <style>
    body {
        margin: 0;
        font-family: 'Segoe UI', sans-serif;
        background: linear-gradient(135deg, #eaf6fb, #d3ecf5);
        min-height: 100vh;
        color: #1d3c73;
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

    .sidebar h4 {
        text-align: center;
        margin-bottom: 2rem;
        color: #1d3c73;
        font-weight: bold;
    }

    .sidebar a {
        display: flex;
        align-items: center;
        gap: 10px;
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
        margin-left: 300px;
        padding: 2rem;
    }

    .card {
        background: #ffffff;
        border-radius: 18px;
        box-shadow: 0 0 25px rgba(77, 176, 230, 0.25);
        padding: 2rem;
        margin-top: 60px;
    }

    .table {
        width: 100%;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 0 15px rgba(77, 176, 230, 0.2);
        margin-top: 20px;
        font-size: 1.1rem;
    }

    .table th {
        background-color: #eaf6fb;
        color: #1d3c73;
        font-weight: bold;
        text-align: center;
        padding: 16px;
    }

    .table td {
        text-align: center;
        padding: 14px;
    }

    .table tbody tr:hover {
        background-color: #f0f9ff;
    }

    .btn-volver {
        background-color: #1d3c73;
        color: white;
        border: none;
        padding: 8px 16px;
        border-radius: 10px;
        margin-bottom: 20px;
    }

    .btn-volver:hover {
        background-color: #163055;
    }

    @media screen and (max-width: 768px) {
        .sidebar {
            width: 100%;
            height: auto;
            position: relative;
            border-radius: 0;
        }

        .main-content {
            margin-left: 0;
            margin-top: 20px;
        }
    }
    </style>
</head>
<body>

   <div class="sidebar">
    <a href="../principal.php" class="logo-link">
    <img style="width: 200px; margin-left:-20px; margin-bottom: 15px;" src="../../img/logoo.png" alt="Logo">
</a>

    <a href="pedidos.php"><i class="bi bi-basket-fill"></i> Pedidos</a>
    <a href="../productos/productos.php"><i class="bi bi-box-seam"></i> Productos</a>
    <a href="../clientes/cliente.php"><i class="bi bi-people-fill"></i> Clientes</a>
    <a href="../usuarios/usuarios.php"><i class="bi bi-people-fill"></i> Usuarios</a>
    <a href="../reportes/reportes.php"><i class="bi bi-graph-up"></i> Reportes</a>
    <a href="../ventas/historial_ventas.php"><i class="bi bi-clock-history"></i> Historial de Ventas</a>
    <a href="../estado_cuenta/estado_cuenta.php"><i class="bi bi-cash-coin"></i> Estado de Cuentas</a>
    <a href="../../cierre_sesion.php"><i class="bi bi-box-arrow-right"></i> Cerrar sesión</a>
</div>

<div class="main-content">
    <div class="card">
        <h2 class="mb-4">Detalle del Pedido</h2>

        <form action="pedidos.php" method="post">
            <button type="submit" class="btn-volver"><i class="bi bi-arrow-left-circle"></i> Volver</button>
        </form>

        <table class="table table-hover table-bordered">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th>Precio Unitario</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['nombre_producto']); ?></td>
                        <td><?php echo htmlspecialchars($row['cantidad']); ?></td>
                        <td><?php echo '$' . number_format($row['precio_unitario'], 2); ?></td>
                        <td><?php echo '$' . number_format($row['cantidad'] * $row['precio_unitario'], 2); ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>
