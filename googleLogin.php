<?php
require 'db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
   $google_id = mysqli_real_escape_string($conn, $_POST['google_id']);
$email = mysqli_real_escape_string($conn, $_POST['email']);
$nombre = ucwords(strtolower(mysqli_real_escape_string($conn, $_POST['nombre'])));
$direccion = ucwords(strtolower(mysqli_real_escape_string($conn, $_POST['direccion'])));
$telefono = ucwords(strtolower(mysqli_real_escape_string($conn, $_POST['telefono'])));
$dni = strtoupper(mysqli_real_escape_string($conn, $_POST['dni']));  // puede quedar en mayúsculas
$fecha_nacimiento = mysqli_real_escape_string($conn, $_POST['fecha_nacimiento']);


    // Verificamos si el usuario ya existe por su google_id
    $check = mysqli_query($conn, "SELECT * FROM usuarios WHERE google_id = '$google_id' LIMIT 1");

    if ($fila = mysqli_fetch_assoc($check)) {
        // Actualizar datos extra en tablas usuarios y clientes
        $id_usuario = $fila['id_usuario'];
        
        $sql_update_usuario = "UPDATE usuarios SET nombre = '$nombre', email = '$email' WHERE id_usuario = $id_usuario";
        mysqli_query($conn, $sql_update_usuario);
        
        $sql_update_cliente = "UPDATE clientes SET direccion = '$direccion', telefono = '$telefono', dni = '$dni', fecha_nacimiento = '$fecha_nacimiento' WHERE id_usuario = $id_usuario";
        mysqli_query($conn, $sql_update_cliente);

        // Iniciar sesión
        $_SESSION['id_usuario'] = $id_usuario;
        $_SESSION['nombre'] = $nombre;
        $_SESSION['tipo_usuario'] = $fila['tipo_usuario'];
        header("Location: cliente/inicioCliente.php");
        exit;
    } else {
        // Registrar nuevo usuario
        $sql1 = "INSERT INTO usuarios (nombre, email, contrasena, tipo_usuario, google_id, creado_en)
                 VALUES ('$nombre', '$email', NULL, 'cliente', '$google_id', NOW())";
        if (mysqli_query($conn, $sql1)) {
            $id_usuario = mysqli_insert_id($conn);
            $sql2 = "INSERT INTO clientes (id_usuario, direccion, telefono, dni, fecha_nacimiento)
                     VALUES ($id_usuario, '$direccion', '$telefono', '$dni', '$fecha_nacimiento')";
            mysqli_query($conn, $sql2);

            $_SESSION['id_usuario'] = $id_usuario;
            $_SESSION['nombre'] = $nombre;
            $_SESSION['tipo_usuario'] = 'cliente';

            header("Location: cliente/inicioCliente.php");
            exit;
        } else {
            echo "Error al registrar usuario: " . mysqli_error($conn);
        }
    }
}
?>
