<?php
require 'db.php';

$nombre = ucwords(strtolower(mysqli_real_escape_string($conn, $_POST['nombre'])));
$email = mysqli_real_escape_string($conn, $_POST['email']); // no modificar mayúsculas
$contrasena = mysqli_real_escape_string($conn, $_POST['contrasena']); // no modificar
$dni = strtoupper(mysqli_real_escape_string($conn, $_POST['dni'])); // se mantiene en mayúsculas
$telefono = strtoupper(mysqli_real_escape_string($conn, $_POST['telefono'])); // se mantiene en mayúsculas
$direccion = ucwords(strtolower(mysqli_real_escape_string($conn, $_POST['direccion'])));
$fecha_nacimiento = mysqli_real_escape_string($conn, $_POST['fecha_nacimiento']); // no modificar



// Hashear la contraseña de forma segura
$contrasena_hash = password_hash($contrasena, PASSWORD_DEFAULT);

$sql1 = "INSERT INTO usuarios (nombre, email, contrasena, tipo_usuario, google_id, creado_en)
         VALUES ('$nombre', '$email', '$contrasena_hash', 'cliente', NULL, NOW())";

if (mysqli_query($conn, $sql1)) {
    $id_usuario = mysqli_insert_id($conn);

    $sql2 = "INSERT INTO clientes (id_usuario, direccion, telefono, dni, fecha_nacimiento)
             VALUES ($id_usuario, '$direccion', '$telefono', '$dni', '$fecha_nacimiento')";

    if (mysqli_query($conn, $sql2)) {
        header("Location: login.php"); // o donde corresponda
exit;

    } else {
        echo "Error al registrar en clientes: " . mysqli_error($conn);
    }
} else {
    echo "Error al registrar en usuarios: " . mysqli_error($conn);
}
?>
