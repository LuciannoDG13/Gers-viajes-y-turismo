<?php
session_start();
require('../../db.php');

// Verificamos que se haya recibido el id_usuario
if (!isset($_POST['id_usuario'])) {
    echo "No se recibió el ID del usuario.";
    exit();
}

$id_usuario = $_POST['id_usuario'];
$nombre = $_POST['nombre'] ?? '';
$email = $_POST['email'] ?? '';
$tipo_usuario = $_POST['tipo_usuario'] ?? '';
$contrasena = $_POST['contrasena'] ?? ''; // Puede estar vacío

// Validar datos mínimos (podés agregar más validaciones)
if (empty($nombre) || empty($email) || empty($tipo_usuario)) {
    echo "Complete todos los campos obligatorios.";
    exit();
}

$key_encrypt = "mi_clave_secreta"; // Clave para AES_ENCRYPT - pon la tuya

if ($contrasena !== '') {
    // Si viene contraseña nueva, actualizamos todo incluido contraseña
    $sql = "UPDATE usuarios SET nombre = ?, email = ?, contrasena = AES_ENCRYPT(?, ?), tipo_usuario = ? WHERE id_usuario = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssi", $nombre, $email, $contrasena, $key_encrypt, $tipo_usuario, $id_usuario);
} else {
    // Si no hay contraseña nueva, actualizamos todo menos la contraseña
    $sql = "UPDATE usuarios SET nombre = ?, email = ?, tipo_usuario = ? WHERE id_usuario = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssi", $nombre, $email, $tipo_usuario, $id_usuario);
}

if ($stmt->execute()) {
    // Redirigir a listado o mostrar mensaje
    header("Location: usuarios.php?msg=modificado");
    exit();
} else {
    echo "Error al modificar el usuario: " . $stmt->error;
}
?>
