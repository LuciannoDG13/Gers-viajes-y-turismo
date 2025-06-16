<?php
session_start();
include '../../db.php';

if (!isset($_SESSION['id_usuario'])) {
    header('Location: login.php');
    exit();
}

$id_usuario = $_SESSION['id_usuario'];

$sql = "SELECT 
            p.id_pedido,
            p.fecha_pedido,
            p.estado,
            v.fecha_venta,
            v.total AS total_venta,
            pr.nombre_producto AS nombre_producto,
            dv.cantidad,
            dv.precio_unitario,
            dv.subtotal
        FROM pedidos p
        LEFT JOIN ventas v ON p.id_pedido = v.id_pedido
        LEFT JOIN detalle_ventas dv ON v.id_venta = dv.id_venta
        LEFT JOIN productos pr ON dv.id_producto = pr.id_producto
        WHERE p.id_usuario = ? AND p.estado = 'pendiente'
        ORDER BY p.fecha_pedido DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$resultado = $stmt->get_result();

$pedidos = [];
while ($row = $resultado->fetch_assoc()) {
    $id = $row['id_pedido'];
    if (!isset($pedidos[$id])) {
        $pedidos[$id] = [
            'fecha_pedido' => $row['fecha_pedido'],
            'estado' => $row['estado'],
            'fecha_venta' => $row['fecha_venta'],
            'total_venta' => $row['total_venta'],
            'productos' => []
        ];
    }

    if ($row['nombre_producto']) {
        $pedidos[$id]['productos'][] = [
            'nombre' => $row['nombre_producto'],
            'cantidad' => $row['cantidad'],
            'precio' => $row['precio_unitario'],
            'subtotal' => $row['subtotal']
        ];
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mis Pedidos Pendientes</title>
    <link href="/viajes turismo/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f0f8fc;
            font-family: 'Segoe UI', sans-serif;
            padding: 30px;
        }
        .container { max-width: 800px; margin: auto; }
        h2 {
            text-align: center;
            margin-bottom: 30px;
            color: #1d3c73;
            font-weight: bold;
        }
        .pedido-card {
            background-color: #ffffff;
            border-left: 6px solid #1d3c73;
            border-radius: 10px;
            padding: 20px 25px;
            margin-bottom: 20px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }
        .pedido-info p, .productos p {
            margin: 5px 0;
            font-size: 16px;
            color: #333;
        }
        .estado {
            background-color: #e0f3fc;
            color: #1d3c73;
            padding: 5px 12px;
            border-radius: 12px;
            font-weight: 500;
            display: inline-block;
            font-size: 14px;
        }
        .btn-cancelar {
            background-color: #1d3c73;
            color: white;
            border: none;
            padding: 8px 18px;
            border-radius: 8px;
            font-size: 14px;
            transition: 0.3s ease;
        }
        .btn-cancelar:hover { background-color: #4db0e6; }
        .volver {
            text-align: center;
            margin-top: 30px;
        }
        .volver a {
            text-decoration: none;
            padding: 10px 25px;
            background-color: #1d3c73;
            color: white;
            border-radius: 10px;
            font-weight: 500;
            transition: background-color 0.3s ease;
        }
        .volver a:hover { background-color: #4db0e6; }
        .alert-info {
            text-align: center;
            background-color: #eaf4fb;
            color: #1d3c73;
            padding: 15px;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 500;
        }
        .productos {
            margin-top: 10px;
            padding-left: 15px;
        }
        .productos p span {
            display: inline-block;
            margin-left: 10px;
            font-size: 14px;
            color: #555;
        }
        .fechas-flex {
            display: flex;
            justify-content: space-between;
            align-items: start;
            margin-bottom: 10px;
            flex-wrap: wrap;
        }
        .fecha-pedido, .fecha-venta {
            width: 48%;
        }
        .text-end {
            text-align: right;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>📦 Mis Pedidos Pendientes</h2>

    <?php if (!empty($pedidos)): ?>
        <?php foreach ($pedidos as $id_pedido => $pedido): ?>
            <div class="pedido-card">
                <div class="pedido-info">
                    <div class="fechas-flex">
                        <div class="fecha-pedido">
                            <p><strong>Fecha del pedido:</strong><br><?= date('d/m/Y H:i', strtotime($pedido['fecha_pedido'])) ?></p>
                        </div>
                        <div class="fecha-venta text-end">
                            <?php if ($pedido['fecha_venta']): ?>
                                <p><strong>Fecha de venta:</strong><br><?= date('d/m/Y H:i', strtotime($pedido['fecha_venta'])) ?></p>
                            <?php else: ?>
                                <p><strong>Fecha de venta:</strong><br><em>No registrada</em></p>
                            <?php endif; ?>
                        </div>
                    </div>
                    <p><span class="estado"><?= ucfirst($pedido['estado']) ?></span></p>
                    <?php if ($pedido['fecha_venta']): ?>
                        <p><strong>Total:</strong> $<?= number_format($pedido['total_venta'], 2, ',', '.') ?></p>
                    <?php else: ?>
                        <p><strong>Total:</strong> No registrado aún</p>
                    <?php endif; ?>
                </div>

                <?php if (!empty($pedido['productos'])): ?>
                    <div class="productos">
                        <strong>Productos:</strong>
                        <?php foreach ($pedido['productos'] as $producto): ?>
                            <p>🛒 <?= htmlspecialchars($producto['nombre']) ?>
                                <span>x<?= $producto['cantidad'] ?></span>
                                <span>$<?= number_format($producto['precio'], 2, ',', '.') ?> c/u</span>
                            </p>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <form action="cancelar_pedido.php" method="post" onsubmit="return confirm('¿Estás seguro de cancelar este pedido?');" style="margin-top: 15px;">
                    <input type="hidden" name="id_pedido" value="<?= $id_pedido ?>">
                    <button type="submit" class="btn btn-cancelar">Cancelar</button>
                </form>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="alert-info">📭 No tenés pedidos pendientes.</div>
    <?php endif; ?>

    <div class="volver">
        <a href="javascript:history.back()">← Volver</a>
    </div>
</div>

</body>
</html>
