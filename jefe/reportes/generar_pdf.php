<?php
require_once '../../dompdf/vendor/autoload.php';
include '../../db.php';

use Dompdf\Dompdf;

$tipo = $_POST['tipo'] ?? null;

function renderTableRows($rows) {
    $html = "";
    foreach ($rows as $row) {
        $html .= "<tr>";
        foreach ($row as $td) {
            $html .= "<td>" . htmlspecialchars($td) . "</td>";
        }
        $html .= "</tr>";
    }
    return $html;
}

function renderReport($title, $headers, $rows) {
    $html = "<h3>$title</h3>";
    $html .= "<table>";
    $html .= "<thead><tr>";
    foreach ($headers as $th) {
        $html .= "<th>$th</th>";
    }
    $html .= "</tr></thead>";
    $html .= "<tbody>";
    $html .= renderTableRows($rows);
    $html .= "</tbody></table><br>";
    return $html;
}

$dompdf = new Dompdf();

$html = "<h2>Reporte Completo</h2>";

// Si no hay tipo o es "todos", mostramos todos los reportes
if (!$tipo || $tipo === 'todos') {

    // Ventas
    $sql = "SELECT DATE(fecha_venta) AS fecha, COUNT(*) AS cantidad_ventas, SUM(total) AS total
            FROM ventas
            GROUP BY DATE(fecha_venta)
            ORDER BY fecha DESC";
    $res = mysqli_query($conn, $sql);
    $ventas = mysqli_fetch_all($res, MYSQLI_NUM);
    $html .= renderReport("Reporte de Ventas por Fecha", ['Fecha', 'Cantidad de Ventas', 'Total'], $ventas);

    // Productos más vendidos
    $sql = "
    SELECT p.nombre_producto, SUM(dv.cantidad) AS total_vendido
    FROM detalle_ventas dv
    JOIN productos p ON dv.id_producto = p.id_producto
    GROUP BY p.id_producto
    ORDER BY total_vendido DESC
    LIMIT 10";
    $res = mysqli_query($conn, $sql);
    $productos = mysqli_fetch_all($res, MYSQLI_NUM);
    $html .= renderReport("Productos Más Vendidos", ['Producto', 'Unidades Vendidas'], $productos);

    // Clientes frecuentes
    $sql = "
    SELECT u.nombre, COUNT(v.id_venta) AS cantidad_compras, SUM(v.total) AS total_gastado
    FROM ventas v
    JOIN usuarios u ON v.id_usuario = u.id_usuario
    GROUP BY v.id_usuario
    ORDER BY total_gastado DESC
    LIMIT 10";
    $res = mysqli_query($conn, $sql);
    $clientes = mysqli_fetch_all($res, MYSQLI_NUM);
    $html .= renderReport("Clientes con más compras", ['Cliente', 'Compras', 'Total gastado'], $clientes);

    // Pagos recibidos
    $sql = "
    SELECT DATE(fecha_pago) AS fecha, COUNT(*) AS pagos, SUM(monto_total_pagado) AS total
    FROM pagos
    WHERE estado_pago = 'confirmado'
    GROUP BY DATE(fecha_pago)
    ORDER BY fecha DESC";
    $res = mysqli_query($conn, $sql);
    $pagos = mysqli_fetch_all($res, MYSQLI_NUM);
    $html .= renderReport("Pagos Confirmados por Fecha", ['Fecha', 'Cantidad de Pagos', 'Total Pagado'], $pagos);

    // Devoluciones
    $sql = "
    SELECT id_cancelacion, id_pago, monto_devoluto, fecha_devolucion, estado_devolucion
    FROM devoluciones
    ORDER BY fecha_devolucion DESC";
    $res = mysqli_query($conn, $sql);
    $devoluciones = mysqli_fetch_all($res, MYSQLI_NUM);
    $html .= renderReport("Reporte de Devoluciones", ['ID Cancelación', 'ID Pago', 'Monto Devuelto', 'Fecha', 'Estado'], $devoluciones);

} else {

    // Reporte individual según el tipo solicitado
    if ($tipo === 'ventas') {
        $sql = "SELECT DATE(fecha_venta) AS fecha, COUNT(*) AS cantidad_ventas, SUM(total) AS total
                FROM ventas
                GROUP BY DATE(fecha_venta)
                ORDER BY fecha DESC";
        $res = mysqli_query($conn, $sql);
        $rows = mysqli_fetch_all($res, MYSQLI_NUM);
        $html .= renderReport("Reporte de Ventas por Fecha", ['Fecha', 'Cantidad de Ventas', 'Total'], $rows);

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
        $html .= renderReport("Productos Más Vendidos", ['Producto', 'Unidades Vendidas'], $rows);

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
        $html .= renderReport("Clientes con más compras", ['Cliente', 'Compras', 'Total gastado'], $rows);

    } elseif ($tipo === 'pagos') {
        $sql = "
        SELECT DATE(fecha_pago) AS fecha, COUNT(*) AS pagos, SUM(monto_total_pagado) AS total
        FROM pagos
        WHERE estado_pago = 'confirmado'
        GROUP BY DATE(fecha_pago)
        ORDER BY fecha DESC";
        $res = mysqli_query($conn, $sql);
        $rows = mysqli_fetch_all($res, MYSQLI_NUM);
        $html .= renderReport("Pagos Confirmados por Fecha", ['Fecha', 'Cantidad de Pagos', 'Total Pagado'], $rows);

    } elseif ($tipo === 'devoluciones') {
        $sql = "
        SELECT id_cancelacion, id_pago, monto_devoluto, fecha_devolucion, estado_devolucion
        FROM devoluciones
        ORDER BY fecha_devolucion DESC";
        $res = mysqli_query($conn, $sql);
        $rows = mysqli_fetch_all($res, MYSQLI_NUM);
        $html .= renderReport("Reporte de Devoluciones", ['ID Cancelación', 'ID Pago', 'Monto Devuelto', 'Fecha', 'Estado'], $rows);

    } else {
        $html .= "<p>No se encontró el tipo de reporte solicitado.</p>";
    }
}

mysqli_close($conn);

$style = '
<style>
    body { font-family: Arial, sans-serif; font-size: 12px; color: #1d3c73; }
    h2, h3 { font-family: Arial, sans-serif; color: #1d3c73; }
    table { font-family: Arial, sans-serif; border-collapse: collapse; width: 100%; margin-bottom: 30px; }
    th, td { border: 1px solid #aaa; padding: 8px; text-align: center; font-size: 12px; }
    th { background-color: #1d3c73; color: white; }
    tr:nth-child(even) { background-color: #f2f2f2; }
    p { text-align: center; }
</style>
';

$dompdf->loadHtml($style . $html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

// Nombre de archivo dinámico según tipo
$filename = "reporte_" . ($tipo ?? "completo") . ".pdf";

$dompdf->stream($filename, ['Attachment' => 0]); // 0 = abre en navegador, 1 = fuerza descarga
exit;

https://myaccount.google.com apreta ese