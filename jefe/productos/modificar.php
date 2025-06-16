<?php
session_start();
require('../../db.php');

// Confirmación previa
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST['confirmar_modificar'])) {
    $id_producto = $_POST['producto'];
    $nombre = $_POST['nombre'];
    $cod = $_POST['cod'];
    $descripcion = $_POST['descripcion'];
    $precio = $_POST['precio'];
    $categoria = $_POST['categoria'];
    $servicio = $_POST['servicio'];
    ?>

    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>Confirmar Modificación</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <style>
            body {
                background: url('../../imagenes/fondo.webp') no-repeat center center fixed;
                background-size: cover;
                height: 100vh;
                display: flex;
                justify-content: center;
                align-items: center;
                background: linear-gradient(135deg, #eaf6fb, #d3ecf5);
            }
            .modal-content {
                background-color: linear-gradient(135deg, #eaf6fb, #d3ecf5);
                border-radius: 15px;
            }
        </style>
    </head>
    <body>
    <div class="modal show fade" tabindex="-1" style="display: block;">
      <div class="modal-dialog">
        <div class="modal-content shadow">
          <div class="modal-header">
            <h5 class="modal-title">Confirmar Modificación</h5>
          </div>
          <div class="modal-body">
            <p>¿Estás segura de que querés modificar el producto <strong><?php echo htmlspecialchars($nombre); ?></strong>?</p>
            <ul>
                <li><strong>Código:</strong> <?php echo htmlspecialchars($cod); ?></li>
                <li><strong>Descripción:</strong> <?php echo htmlspecialchars($descripcion); ?></li>
                <li><strong>Precio:</strong> $<?php echo htmlspecialchars($precio); ?></li>
                <li><strong>Categoría ID:</strong> <?php echo htmlspecialchars($categoria); ?></li>
                <li><strong>Servicio ID:</strong> <?php echo htmlspecialchars($servicio); ?></li>
            </ul>
          </div>
          <div class="modal-footer">
            <form method="POST">
                <input type="hidden" name="confirmar_modificar" value="1">
                <input type="hidden" name="producto" value="<?php echo $id_producto; ?>">
                <input type="hidden" name="nombre" value="<?php echo htmlspecialchars($nombre); ?>">
                <input type="hidden" name="cod" value="<?php echo htmlspecialchars($cod); ?>">
                <input type="hidden" name="descripcion" value="<?php echo htmlspecialchars($descripcion); ?>">
                <input type="hidden" name="precio" value="<?php echo htmlspecialchars($precio); ?>">
                <input type="hidden" name="categoria" value="<?php echo htmlspecialchars($categoria); ?>">
                <input type="hidden" name="servicio" value="<?php echo htmlspecialchars($servicio); ?>">
                <button type="submit" class="btn btn-success">Sí, modificar</button>
            </form>
            <button class="btn btn-secondary" onclick="window.history.back()">Cancelar</button>
          </div>
        </div>
      </div>
    </div>
    </body>
    </html>

<?php
    exit();
}

// Si se confirmó la modificación
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirmar_modificar'])) {

    $id_producto = $_POST['producto'];
    $nombre = $_POST['nombre'];
    $cod = $_POST['cod'];
    $descripcion = $_POST['descripcion'];
    $precio = $_POST['precio'];
    $categoria = $_POST['categoria'];
    $servicio = $_POST['servicio'];

    $update = $conn->prepare("UPDATE productos SET nombre_producto = ?, codigo_producto = ?, descripcion = ?, precio_unitario = ?, id_categoria = ?, id_tipo = ? WHERE id_producto = ?");
    $update->bind_param("sssiiii", $nombre, $cod, $descripcion, $precio, $categoria, $servicio, $id_producto);
    $result = $update->execute();

    if ($result) {
        header("Location: productos.php?modificado=1");
    } else {
        header("Location: productos.php?error=1");
    }
    $update->close();
    $conn->close();
    exit();
}
?>
