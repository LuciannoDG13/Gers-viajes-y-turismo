<?php
require('../../db.php');

if (isset($_POST['id_producto']) && isset($_POST['estado_actual'])) {
    $id_producto = $_POST['id_producto'];
    $estado_actual = $_POST['estado_actual'];

    // Cambiar estado
    $nuevo_estado = ($estado_actual === 'activo') ? 'inactivo' : 'activo';

    $stmt = $conn->prepare("UPDATE productos SET estado = ? WHERE id_producto = ?");
    $stmt->bind_param("si", $nuevo_estado, $id_producto);

    if ($stmt->execute()) {
        header("Location: productos.php"); // redirigir a la página principal
        exit();
    } else {
        echo "Error al cambiar el estado.";
    }
}
?>
