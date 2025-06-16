<?php
require 'db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['id_usuario']) && isset($_SESSION['nuevo_google'])) {
    $id_usuario = $_SESSION['id_usuario'];

    $nombre = ucwords(strtolower(mysqli_real_escape_string($conn, $_POST['nombre'])));
$direccion = ucwords(strtolower(mysqli_real_escape_string($conn, $_POST['direccion'])));
$telefono = ucwords(strtolower(mysqli_real_escape_string($conn, $_POST['telefono'])));
$dni = mysqli_real_escape_string($conn, $_POST['dni']);
$fecha_nacimiento = mysqli_real_escape_string($conn, $_POST['fecha_nacimiento']);


    // Actualizar nombre en tabla usuarios
    $sql_update_usuario = "UPDATE usuarios SET nombre = '$nombre' WHERE id_usuario = $id_usuario";
    mysqli_query($conn, $sql_update_usuario);

    // Verificar si ya existe un registro en clientes para este usuario
    $check = mysqli_query($conn, "SELECT * FROM clientes WHERE id_usuario = $id_usuario LIMIT 1");

    if (mysqli_num_rows($check) == 0) {
        // Insertar datos extra en tabla clientes
        $sql = "INSERT INTO clientes (id_usuario, direccion, telefono, dni, fecha_nacimiento)
                VALUES ($id_usuario, '$direccion', '$telefono', '$dni', '$fecha_nacimiento')";
    } else {
        // Si ya existe, actualizar los datos
        $sql = "UPDATE clientes SET direccion='$direccion', telefono='$telefono', dni='$dni', fecha_nacimiento='$fecha_nacimiento' WHERE id_usuario = $id_usuario";
    }

    if (mysqli_query($conn, $sql)) {
        unset($_SESSION['nuevo_google']); // Ya completó el registro extra
        header("Location: login.php");
        exit;
    } else {
        echo "Error al guardar datos extra: " . mysqli_error($conn);
    }
} else {
    header("Location: login.php");
    exit;
}
?>
