<?php
session_start();
require '../../db.php';

if (!isset($_SESSION['id_usuario'])) {
    header('Location: ../../login.php');
    exit();
}

$id_usuario = $_SESSION['id_usuario'];

$usar_credito = isset($_POST['usar_credito']) && $_POST['usar_credito'] == '1';
$monto_a_pagar = isset($_POST['monto_a_pagar']) ? floatval($_POST['monto_a_pagar']) : 0;
$external_reference = $_POST['external_reference'] ?? null;
$carrito = $_POST['carrito'] ?? null;

if (!$carrito || !is_array($carrito) || count($carrito) === 0) {
    die("Carrito vacío o inválido.");
}

// Obtener crédito actual
$sql_credito = "SELECT credito FROM usuarios WHERE id_usuario = $id_usuario";
$result_credito = mysqli_query($conn, $sql_credito);
if (!$result_credito || mysqli_num_rows($result_credito) === 0) {
    die("Error al obtener crédito.");
}
$row = mysqli_fetch_assoc($result_credito);
$credito_actual = floatval($row['credito']);

// Calcular total
$total_original = 0;
foreach ($carrito as $item) {
    $precio = floatval($item['precio']);
    $cantidad = intval($item['cantidad']);
    $total_original += $precio * $cantidad;
}

// Determinar cuánto crédito se usará
$credito_usado = 0;
if ($usar_credito) {
    $credito_usado = min($credito_actual, $total_original);
}

// Verificar suma
if (round($monto_a_pagar + $credito_usado, 2) != round($total_original, 2)) {
    die("Error en el cálculo de montos.");
}

// Validar pago externo (simulado)
if ($monto_a_pagar > 0) {
    $pago_aprobado = true;

    if (!$pago_aprobado) {
        die("Pago externo no aprobado.");
    }
}

$fecha_compra = date("Y-m-d H:i:s");
$estado_pedido = "pendiente";

// Insertar pedido
$sql_pedido = "INSERT INTO pedidos (id_usuario, fecha_pedido, estado, total)
               VALUES ($id_usuario, '$fecha_compra', '$estado_pedido', $total_original)";
if (!mysqli_query($conn, $sql_pedido)) {
    die("Error al insertar pedido: " . mysqli_error($conn));
}
$id_pedido = mysqli_insert_id($conn);

// Insertar venta
$sql_venta = "INSERT INTO ventas (id_pedido, id_usuario, fecha_venta, total)
              VALUES ($id_pedido, $id_usuario, '$fecha_compra', $total_original)";
if (!mysqli_query($conn, $sql_venta)) {
    die("Error al insertar venta: " . mysqli_error($conn));
}
$id_venta = mysqli_insert_id($conn);

// Insertar detalles
foreach ($carrito as $item) {
    $id_producto = intval($item['id']);
    $cantidad = intval($item['cantidad']);
    $precio_unitario = floatval($item['precio']);
    $subtotal = $cantidad * $precio_unitario;

    $sql_detalle = "INSERT INTO detalle_ventas (id_venta, id_producto, cantidad, precio_unitario, subtotal)
                    VALUES ($id_venta, $id_producto, $cantidad, $precio_unitario, $subtotal)";
    
    if (!mysqli_query($conn, $sql_detalle)) {
        die("Error al insertar detalle: " . mysqli_error($conn));
    }
}


// Actualizar crédito si se usó
if ($credito_usado > 0) {
    $nuevo_credito = $credito_actual - $credito_usado;
    $sql_update_credito = "UPDATE usuarios SET credito = $nuevo_credito WHERE id_usuario = $id_usuario";
    mysqli_query($conn, $sql_update_credito);
}

// Registrar pago
$metodo_pago = $monto_a_pagar > 0 && $credito_usado > 0 ? 'mixto' :
               ($monto_a_pagar > 0 ? 'externo' : 'credito');
$estado_pago = 'confirmado';
$cuotas = 1;
$monto_cuota = $monto_a_pagar > 0 ? $monto_a_pagar : $credito_usado;
$monto_total_pagado = $monto_a_pagar + $credito_usado;

$sql_pago = "INSERT INTO pagos (id_pedido, metodo_pago, estado_pago, fecha_pago, cuotas, monto_cuota, monto_total_pagado)
             VALUES ($id_pedido, '$metodo_pago', '$estado_pago', '$fecha_compra', $cuotas, $monto_cuota, $monto_total_pagado)";
if (!mysqli_query($conn, $sql_pago)) {
    die("Error al registrar pago: " . mysqli_error($conn));
}
$id_pago = mysqli_insert_id($conn); // <- Esto debe ir después del insert


// Limpiar sesión
unset($_SESSION['carrito']);
unset($_SESSION['usar_credito']);
unset($_SESSION['external_reference']);
unset($_SESSION['total_original']);
unset($_SESSION['monto_a_pagar']);
// Redirigir al script de envío de correo con los datos necesarios
header("Location: ../../jefe/Enviar_mails.php?id_pedido=$id_pedido&id_pago=$id_pago");

// header("Location: gracias.php");
exit();
