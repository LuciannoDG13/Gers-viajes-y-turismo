<?php
include '../../db.php';

$tipo = $_GET['tipo'] ?? null;

function renderTable($title, $headers, $rows) {
    echo "<h3>$title</h3><table><thead><tr>";
    foreach ($headers as $th) {
        echo "<th>$th</th>";
    }
    echo "</tr></thead><tbody>";
    foreach ($rows as $row) {
        echo "<tr>";
        foreach ($row as $td) {
            echo "<td>$td</td>";
        }
        echo "</tr>";
    }
    echo "</tbody></table>";
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <title>Reportes</title>
    <link rel="stylesheet" href="/viajes turismo/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        body {
            background: linear-gradient(135deg, #eaf6fb, #d3ecf5);
            font-family: 'Segoe UI', sans-serif;
            color: #1d3c73;
            margin: 0;
            padding: 0;
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
            z-index: 999;
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
        .sidebar i {
            margin-right: 8px;
        }
.container {
    background: linear-gradient(135deg, #eaf6fb, #d3ecf5);
    width: calc(100% - 270px);
    margin-left: 270px;
    padding: 3rem;
    box-sizing: border-box;
}


        h2 {
            text-align: center;
            margin-bottom: 2rem;
            font-weight: 700;
            color: #1d3c73;
        }
        h3 {
            margin-top: 2rem;
            margin-bottom: 1rem;
            color: #1d3c73;
            font-weight: 600;
        }
.menu {
    text-align: center;
    margin-bottom: 25px;
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 15px;
}


    .menu a {
    background-color: #4db0e6;
    color: white !important;
    padding: 12px 20px;
    border-radius: 10px;
    font-weight: 600;
    text-decoration: none;
    transition: background-color 0.3s ease-in-out;
    font-size: 15px;
    }

    .menu a:hover {
    background-color: #3ca3d4;
    text-decoration: none;
    }

        table {
            width: 100%;
            border-collapse: collapse;
            border-radius: 14px;
            overflow: hidden;
            box-shadow: 0 0 15px rgba(77, 176, 230, 0.15);
            margin-top: 0.5rem;
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
            font-size: 15px;
        }
        tbody tr:nth-child(even) {
            background-color: #f4fbff;
        }
        .pdf-btn, .volver-btn {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }
        .pdf-btn button, .volver-btn a {
            background-color: #1d3c73;
            color: white;
            border: none;
            padding: 10px 20px;
            font-weight: 600;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            text-decoration: none;
        }
        .pdf-btn button:hover, .volver-btn a:hover {
            background-color: #16315e;
            color: white;
        }
        p {
            font-size: 16px;
            font-weight: 600;
            text-align: center;
            color: #1d3c73;
            margin-top: 2rem;
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
    <a href="reportes.php"><i class="bi bi-graph-up"></i> Reportes</a>
    <a href="../ventas/historial_ventas.php"><i class="bi bi-clock-history"></i> Historial de Ventas</a>
    <a href="../estado_cuenta/estado_cuenta.php"><i class="bi bi-cash-coin"></i> Estado de Cuentas</a>
    <a href="../../cierre_sesion.php"><i class="bi bi-box-arrow-right"></i> Cerrar sesión</a>
</div>

<!-- Contenido principal -->
<div class="container">
    <!-- Botón para PDF de TODOS los reportes -->
    <div class="pdf-btn" style="margin-bottom: 50px;">
        <form method="post" action="generar_pdf.php" target="_blank">
            <input type="hidden" name="tipo" value="todos">
            <button type="submit"> Generar PDF de Todos los Reportes</button>
        </form>
    </div>

    <!-- Subtítulo -->
    <h3 style="text-align: center; margin-bottom: 20px;"> Elegí un reporte individual</h3>

    <!-- Menú de selección -->
    <div class="menu">
        <a href="reportes.php?tipo=ventas">Ventas</a>
        <a href="reportes.php?tipo=productos">Productos más vendidos</a>
        <a href="reportes.php?tipo=clientes">Clientes frecuentes</a>
        <a href="reportes.php?tipo=pagos">Pagos recibidos</a>
        <a href="reportes.php?tipo=devoluciones">Devoluciones</a>
    </div>

    <?php
    if ($tipo === 'ventas') {
        $sql = "SELECT DATE(fecha_venta) AS fecha, COUNT(*) AS cantidad_ventas, SUM(total) AS total
                FROM ventas
                GROUP BY DATE(fecha_venta)
                ORDER BY fecha DESC";
        $res = mysqli_query($conn, $sql);
        $rows = mysqli_fetch_all($res, MYSQLI_NUM);
        renderTable("Reporte de Ventas por Fecha", ['Fecha', 'Cantidad de Ventas', 'Total'], $rows);

    } elseif ($tipo === 'productos') {
        $sql = "
        SELECT p.nombre_producto, SUM(dv.cantidad) AS total_vendido
        FROM detalle_ventas dv
        JOIN productos p ON dv.id_producto = p.id_producto
        GROUP BY p.id_producto
        ORDER BY total_vendido DESC
        LIMIT 10";
        $res = mysqli_query($conn, $sql);
        $rows = mysqli_fetch_all($res, MYSQLI_NUM);
        renderTable("Productos Más Vendidos", ['Producto', 'Unidades Vendidas'], $rows);

    } elseif ($tipo === 'clientes') {
        $sql = "
        SELECT u.nombre, COUNT(v.id_venta) AS cantidad_compras, SUM(v.total) AS total_gastado
        FROM ventas v
        JOIN usuarios u ON v.id_usuario = u.id_usuario
        GROUP BY v.id_usuario
        ORDER BY total_gastado DESC
        LIMIT 10";
        $res = mysqli_query($conn, $sql);
        $rows = mysqli_fetch_all($res, MYSQLI_NUM);
        renderTable("Clientes con más compras", ['Cliente', 'Compras', 'Total gastado'], $rows);

    } elseif ($tipo === 'pagos') {
        $sql = "
        SELECT DATE(fecha_pago) AS fecha, COUNT(*) AS pagos, SUM(monto_total_pagado) AS total
        FROM pagos
        WHERE estado_pago = 'confirmado'
        GROUP BY DATE(fecha_pago)
        ORDER BY fecha DESC";
        $res = mysqli_query($conn, $sql);
        $rows = mysqli_fetch_all($res, MYSQLI_NUM);
        renderTable("Pagos Confirmados por Fecha", ['Fecha', 'Cantidad de Pagos', 'Total Pagado'], $rows);

    } elseif ($tipo === 'devoluciones') {
        $sql = "
        SELECT id_cancelacion, id_pago, monto_devoluto, fecha_devolucion, estado_devolucion
        FROM devoluciones
        ORDER BY fecha_devolucion DESC";
        $res = mysqli_query($conn, $sql);
        if (!$res) {
            echo "<p style='color: red;'>Error en la consulta de devoluciones: " . mysqli_error($conn) . "</p>";
        } else {
            $rows = mysqli_fetch_all($res, MYSQLI_NUM);
            renderTable("Reporte de Devoluciones", ['ID Cancelación', 'ID Pago', 'Monto Devuelto', 'Fecha', 'Estado'], $rows);
        }
    }
    mysqli_close($conn);
    ?>

    <?php if ($tipo): ?>
        <!-- Botón para PDF individual -->
        <div class="pdf-btn">
            <form method="post" action="generar_pdf.php" target="_blank">
                <input type="hidden" name="tipo" value="<?= htmlspecialchars($tipo) ?>">
                <button type="submit">Generar PDF de este Reporte</button>
            </form>
        </div>

        <!-- Botón para volver -->
        <div class="volver-btn">
            <a href="reportes.php">Volver al menú de reportes</a>
        </div>
    <?php endif; ?>
</div>

</body>
</html>
