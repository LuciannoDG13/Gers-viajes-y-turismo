<?php
session_start();
require('../../db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recoger datos del formulario
    $id_cliente = $_POST['id_cliente'];
    $nombre = $_POST['nombre'];  // Está en tabla usuarios
    $direccion = $_POST['direccion'];
    $telefono = $_POST['telefono'];
    $dni = $_POST['dni'];
    $fecha_nacimiento = $_POST['fecha_nacimiento'];

    // Primero, obtener id_usuario de ese cliente
    $sql = "SELECT id_usuario FROM clientes WHERE id_cliente = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_cliente);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows == 0) {
        echo "Cliente no encontrado.";
        exit;
    }
    $fila = $result->fetch_assoc();
    $id_usuario = $fila['id_usuario'];
    $stmt->close();

    // Actualizar tabla clientes (sin nombre)
    $sql_clientes = "UPDATE clientes SET direccion = ?, telefono = ?, dni = ?, fecha_nacimiento = ? WHERE id_cliente = ?";
    $stmt_clientes = $conn->prepare($sql_clientes);
    $stmt_clientes->bind_param("ssssi", $direccion, $telefono, $dni, $fecha_nacimiento, $id_cliente);
    $stmt_clientes->execute();
    $stmt_clientes->close();

    // Actualizar tabla usuarios para el nombre
    $sql_usuarios = "UPDATE usuarios SET nombre = ? WHERE id_usuario = ?";
    $stmt_usuarios = $conn->prepare($sql_usuarios);
    $stmt_usuarios->bind_param("si", $nombre, $id_usuario);
    $stmt_usuarios->execute();
    $stmt_usuarios->close();

    // Redirigir o mostrar mensaje
    header("Location: cliente.php?modificado=1");
    exit;
}
?>
