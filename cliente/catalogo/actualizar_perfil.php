<?php
session_start();
include '../../db.php';

if (!isset($_POST['id_usuario']) || $_POST['id_usuario'] != $_SESSION['id_usuario']) {
    echo "Acceso no autorizado.";
    exit();
}

// Datos del usuario
$id_usuario = $_POST['id_usuario'];
$nombre = $_POST['nombre'];
$email = $_POST['email'];

// Actualizamos la tabla usuarios
$sqlUpdateUsuario = "UPDATE usuarios SET nombre = ?, email = ? WHERE id_usuario = ?";
$stmtUpdateUsuario = $conn->prepare($sqlUpdateUsuario);
$stmtUpdateUsuario->bind_param("ssi", $nombre, $email, $id_usuario);
$stmtUpdateUsuario->execute();

// Si hay datos de cliente (formulario los envió)
if (isset($_POST['id_cliente'])) {
    $id_cliente = $_POST['id_cliente'];
    $direccion = $_POST['direccion'];
    $telefono = $_POST['telefono'];
    $dni = $_POST['dni'];
    $fecha_nacimiento = $_POST['fecha_nacimiento'];

    $sqlUpdateCliente = "UPDATE clientes SET direccion = ?, telefono = ?, dni = ?, fecha_nacimiento = ? WHERE id_cliente = ?";
    $stmtUpdateCliente = $conn->prepare($sqlUpdateCliente);
    $stmtUpdateCliente->bind_param("ssssi", $direccion, $telefono, $dni, $fecha_nacimiento, $id_cliente);
    $stmtUpdateCliente->execute();
}

header("Location: perfil.php");
exit();
?>
