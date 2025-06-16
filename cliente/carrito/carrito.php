<?php
session_start();
include '../../db.php';

if (isset($_POST['id_producto']) && isset($_POST['cantidad'])) {
    $id_producto = mysqli_real_escape_string($conn, $_POST['id_producto']);
    $cantidad = max(1, (int)$_POST['cantidad']);

    $sql = "SELECT id_producto, nombre_producto, precio_unitario FROM productos WHERE id_producto = '$id_producto'";
    $resultado = mysqli_query($conn, $sql);

    if ($producto = mysqli_fetch_assoc($resultado)) {
        $nuevoProducto = array(
            'id' => $producto['id_producto'],
            'nombre' => $producto['nombre_producto'],
            'precio' => $producto['precio_unitario'],
            'cantidad' => $cantidad
        );

        if (!isset($_SESSION['carrito'])) {
            $_SESSION['carrito'] = array();
        }

        $productoExistente = false;
        foreach ($_SESSION['carrito'] as $key => $item) {
            if ($item['id'] == $nuevoProducto['id']) {
                $_SESSION['carrito'][$key]['cantidad'] += $cantidad;
                $productoExistente = true;
                break;
            }
        }

        if (!$productoExistente) {
            $_SESSION['carrito'][] = $nuevoProducto;
        }
    }
}

header("Location: ver_carrito.php");
exit;
