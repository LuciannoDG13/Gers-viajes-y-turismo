<?php
require('../../db.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = trim($_POST['nombre']);
    $email = trim($_POST['email']);
    $contrasena = $_POST['contrasena'];
    $tipo = $_POST['tipo_usuario'];

    // Validación básica
    if (empty($nombre) || empty($contrasena) || empty($tipo)) {
        echo "Por favor, completá todos los campos obligatorios correctamente.";
        exit();
    }

    // Generar hash seguro de la contraseña
    $contrasena_hashed = password_hash($contrasena, PASSWORD_DEFAULT);

    // Consulta SQL sin cifrado AES
    $sql = "INSERT INTO usuarios (nombre, email, contrasena, tipo_usuario, creado_en) 
            VALUES (?, ?, ?, ?, NOW())";

    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("ssss", $nombre, $email, $contrasena_hashed, $tipo);

        if ($stmt->execute()) {
            header("Location: usuarios.php?exito=1");
            exit();
        } else {
            echo "Error al insertar el usuario: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Error en la preparación de la consulta: " . $conn->error;
    }
} else {
    echo "Acceso no permitido.";
}
?>
