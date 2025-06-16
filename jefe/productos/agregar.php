<?php
require('../../db.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtener los datos del formulario
    $nombre = $_POST['nombre'];
    $codigo = $_POST['cod'];
    $descripcion = $_POST['descripcion'];
    $precio = $_POST['precio'];
    $categoria = $_POST['categoria'];
    $servicio = $_POST['servicio'];

    // Manejar la imagen
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
        $imagenTmp = $_FILES['imagen']['tmp_name'];
        $nombreImagen = basename($_FILES['imagen']['name']);
        $rutaDestino = "../../img/productos/" . $nombreImagen;

        // Mover la imagen a la carpeta final
        if (move_uploaded_file($imagenTmp, $rutaDestino)) {
            // Guardar en base de datos
            $urlImagen = "img/productos/" . $nombreImagen;

            $sql = "INSERT INTO productos (
                        nombre_producto, 
                        codigo_producto, 
                        descripcion, 
                        precio_unitario, 
                        creado_en, 
                        estado, 
                        id_tipo, 
                        id_categoria, 
                        url
                    ) VALUES (?, ?, ?, ?, NOW(), 'activo', ?, ?, ?)";

            $stmt = $conn->prepare($sql);
            if ($stmt) {
                $stmt->bind_param("sssdiis", $nombre, $codigo, $descripcion, $precio, $servicio, $categoria, $urlImagen);
                if ($stmt->execute()) {
                    header("Location: productos.php?exito=1");
                    exit();
                } else {
                    echo "Error al insertar el producto: " . $stmt->error;
                }
                $stmt->close();
            } else {
                echo "Error al preparar la consulta: " . $conn->error;
            }
        } else {
            echo "Error al mover la imagen al directorio de destino.";
        }
    } else {
        echo "Error al subir la imagen.";
    }
} else {
    echo "Acceso no permitido.";
}
?>
