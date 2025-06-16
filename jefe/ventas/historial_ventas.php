<?php
session_start();
require('../../db.php');

$busqueda_nombre = $_POST['nombre'] ?? '';
$busqueda_fecha = $_POST['fecha'] ?? '';

// Consulta principal
$sql = "SELECT v.*, u.nombre AS nombre_usuario, p.estado AS estado_pedido 
        FROM ventas v
        INNER JOIN usuarios u ON u.id_usuario = v.id_usuario
        INNER JOIN pedidos p ON p.id_pedido = v.id_pedido
        WHERE 1";

if (!empty($busqueda_nombre)) {
    $nombre_filtrado = $conn->real_escape_string($busqueda_nombre);
    $sql .= " AND u.nombre LIKE '%$nombre_filtrado%'";
}

if (!empty($busqueda_fecha)) {
    $fecha_filtrada = $conn->real_escape_string($busqueda_fecha);
    $sql .= " AND DATE(v.fecha_venta) = '$fecha_filtrada'";
}

$sql .= " ORDER BY v.fecha_venta DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Historial de Ventas</title>
    <link rel="stylesheet" href="/viajes turismo/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #eaf6fb, #d3ecf5);
            min-height: 100vh;
            color: #1d3c73;
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

        .form-busqueda {
            display: flex;
            gap: 15px;
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
            background: #f5fbff;
            padding: 15px;
            border-radius: 18px;
            box-shadow: 0 2px 12px rgba(77, 176, 230, 0.15);
        }

        .form-busqueda input {
            border-radius: 10px;
            border: 1px solid #b3d8f2;
            padding: 10px;
            flex: 1;
        }

        .form-busqueda button {
            background-color: #1d3c73;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 10px;
            transition: background 0.3s ease;
        }

        .form-busqueda button:hover {
            background-color: #15436e;
        }

        table {
            width: 100%;
            margin-top: 1rem;
            background: white;
            border-radius: 12px;
            box-shadow: 0 0 15px rgba(77, 176, 230, 0.2);
            overflow: hidden;
        }

        table thead {
            background-color: #eaf6fb;
            color: #1d3c73;
        }

        table th, table td {
            text-align: center;
            padding: 12px;
        }

        .btn-ver {
            background-color: #0d6efd;
            color: white;
            border-radius: 10px;
            padding: 5px 12px;
            text-decoration: none;
        }

        .btn-volver {
            background-color: #6c757d;
            color: white;
            border-radius: 10px;
            padding: 10px 20px;
            text-decoration: none;
        }

        .btn-volver:hover {
            background-color: #5a6268;
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
    <a href="historial_ventas.php"><i class="bi bi-clock-history"></i> Historial de Ventas</a>
    <a href="../estado_cuenta/estado_cuenta.php"><i class="bi bi-cash-coin"></i> Estado de Cuentas</a>
    <a href="../../cierre_sesion.php"><i class="bi bi-box-arrow-right"></i> Cerrar sesión</a>
</div>


<!-- Main Content -->
<div class="main-content">
    <h2>Historial de Ventas</h2>

    <form method="post" class="form-busqueda">
        <input type="text" name="nombre" placeholder="Buscar por nombre de usuario" value="<?= htmlspecialchars($busqueda_nombre) ?>">
        <input type="date" name="fecha" value="<?= htmlspecialchars($busqueda_fecha) ?>">
        <button type="submit" name="buscar"><i class="bi bi-search"></i> Buscar</button>
        <a href="historial_ventas.php" class="btn-volver"><i class="bi bi-arrow-clockwise"></i> Volver</a>
    </form>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Usuario</th>
                <th>Fecha de Venta</th>
                <th>Total</th>
                <th>Estado del Pedido</th>
                <th>Detalle</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result && $result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['nombre_usuario']) ?></td>
                        <td><?= htmlspecialchars($row['fecha_venta']) ?></td>
                        <td>$<?= number_format($row['total'], 2) ?></td>
                        <td><?= htmlspecialchars($row['estado_pedido']) ?></td>
                        <td>
                            <a href="detalle_venta.php?id_venta=<?= $row['id_venta'] ?>" class="btn-ver">Ver Detalle</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5">No se encontraron ventas.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

</body>
</html>
