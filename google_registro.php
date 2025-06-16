<?php
require 'db.php';

$nombre = ucwords(strtolower(mysqli_real_escape_string($conn, $_POST['nombre'])));
$email = mysqli_real_escape_string($conn, $_POST['email']);
$google_id = mysqli_real_escape_string($conn, $_POST['google_id']);


// Verificamos si el usuario ya existe por su google_id
$check = mysqli_query($conn, "SELECT id_usuario FROM usuarios WHERE google_id = '$google_id'");
if (mysqli_num_rows($check) > 0) {
    echo "Ya estás registrado con Google.";
    exit;
}

// Insertar en usuarios (sin contraseña)
$sql1 = "INSERT INTO usuarios (nombre, email, contrasena, tipo_usuario, google_id, creado_en)
         VALUES ('$nombre', '$email', NULL, 'cliente', '$google_id', NOW())";

if (mysqli_query($conn, $sql1)) {
    $id_usuario = mysqli_insert_id($conn);

    // Insertar en clientes con campos vacíos o por defecto
    $sql2 = "INSERT INTO clientes (id_usuario, direccion, telefono, dni, fecha_nacimiento)
             VALUES ($id_usuario, '', '', '', NULL)";

    if (mysqli_query($conn, $sql2)) {
        echo "Registro con Google exitoso.";
    } else {
        echo "Error en clientes: " . mysqli_error($conn);
    }
} else {
    echo "Error en usuarios: " . mysqli_error($conn);
}
?>
