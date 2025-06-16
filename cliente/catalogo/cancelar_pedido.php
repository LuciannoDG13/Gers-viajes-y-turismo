<?php
session_start();
include '../../db.php';

if (!isset($_SESSION['id_usuario'])) {
    header('Location: login.php');
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id_pedido'])) {
    $id_usuario = $_SESSION['id_usuario'];
    $id_pedido = $_POST['id_pedido'];

    // Obtener el total de la venta asociada al pedido
    $stmt = $conn->prepare("SELECT total FROM ventas WHERE id_pedido = ? LIMIT 1");
    $stmt->bind_param("i", $id_pedido);
    $stmt->execute();
    $result = $stmt->get_result();
    $venta = $result->fetch_assoc();

    if ($venta) {
        $total_venta = floatval($venta['total']);
        $credito_nuevo = $total_venta * 0.60;

        // Actualizar el crédito del usuario
        $stmt2 = $conn->prepare("UPDATE usuarios SET credito = IFNULL(credito, 0) + ? WHERE id_usuario = ?");
        $stmt2->bind_param("di", $credito_nuevo, $id_usuario);
        $stmt2->execute();
    }

    // Cambiar el estado del pedido a 'cancelado' siempre
    $stmt3 = $conn->prepare("UPDATE pedidos SET estado = 'cancelado' WHERE id_pedido = ?");
    $stmt3->bind_param("i", $id_pedido);
    $stmt3->execute();
}

// Redirigir de vuelta
header("Location: mispedidos.php");
exit();
