<?php
session_start();
include '../../db.php';

if (!isset($_SESSION['id_usuario'])) {
    header('Location: ../../login.php');
    exit();
}

$id_usuario = $_SESSION['id_usuario'];

// Obtener datos del usuario
$sqlUsuario = "SELECT * FROM usuarios WHERE id_usuario = ?";
$stmtUsuario = $conn->prepare($sqlUsuario);
$stmtUsuario->bind_param("i", $id_usuario);
$stmtUsuario->execute();
$resultUsuario = $stmtUsuario->get_result();

$usuario = $resultUsuario->fetch_assoc();

if (!$usuario) {
    echo "Usuario no encontrado.";
    exit();
}

$datosCliente = null;
if ($usuario['tipo_usuario'] === 'cliente') {
    $sqlCliente = "
        SELECT clientes.*, usuarios.credito 
        FROM clientes
        INNER JOIN usuarios ON clientes.id_usuario = usuarios.id_usuario
        WHERE clientes.id_usuario = ?
        ORDER BY clientes.id_cliente DESC
        LIMIT 1
    ";
    $stmtCliente = $conn->prepare($sqlCliente);
    $stmtCliente->bind_param("i", $id_usuario);
    $stmtCliente->execute();
    $resultCliente = $stmtCliente->get_result();

    if ($resultCliente->num_rows > 0) {
        $datosCliente = $resultCliente->fetch_assoc();
    }
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mi Perfil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(to bottom right, #e3f2fd, #f1f8ff);
            font-family: 'Segoe UI', sans-serif;
        }

        .perfil-container {
            max-width: 700px;
            margin: 50px auto;
            background: white;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 0 25px rgba(0, 123, 255, 0.1);
        }

        h2 {
            text-align: center;
            margin-bottom: 30px;
            color: #007bff;
            font-weight: bold;
        }

        .form-label {
            font-weight: 600;
            color: #1d3c73;
        }

        .form-control:disabled {
            background-color: #f8f9fa;
        }

        .btn-primary {
            background-color: #007bff;
            border: none;
            padding: 10px 25px;
            font-weight: bold;
            transition: 0.3s;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }

        .volver a {
            display: inline-block;
            margin-top: 20px;
            text-decoration: none;
            color: #007bff;
            font-weight: 500;
        }

        .volver a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="perfil-container">
    <h2>Mi Perfil</h2>

    <form action="actualizar_perfil.php" method="post">
        <input type="hidden" name="id_usuario" value="<?= $usuario['id_usuario'] ?>">

        <div class="mb-3">
            <label class="form-label">Nombre</label>
            <input type="text" class="form-control" name="nombre" value="<?= htmlspecialchars($usuario['nombre']) ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" class="form-control" name="email" value="<?= htmlspecialchars($usuario['email']) ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Registrado en</label>
            <input type="text" class="form-control" value="<?= htmlspecialchars($usuario['creado_en']) ?>" disabled>
        </div>

        <?php if ($datosCliente): ?>
            <hr>
            <input type="hidden" name="id_cliente" value="<?= $datosCliente['id_cliente'] ?>">

            <div class="mb-3">
                <label class="form-label">Dirección</label>
                <input type="text" class="form-control" name="direccion" value="<?= htmlspecialchars($datosCliente['direccion']) ?>">
            </div>

            <div class="mb-3">
                <label class="form-label">Teléfono</label>
                <input type="text" class="form-control" name="telefono" value="<?= htmlspecialchars($datosCliente['telefono']) ?>">
            </div>

            <div class="mb-3">
                <label class="form-label">DNI</label>
                <input type="text" class="form-control" name="dni" value="<?= htmlspecialchars($datosCliente['dni']) ?>">
            </div>

            <div class="mb-3">
                <label class="form-label">Fecha de nacimiento</label>
                <input type="date" class="form-control" name="fecha_nacimiento" value="<?= htmlspecialchars($datosCliente['fecha_nacimiento']) ?>">
            </div>

            <div class="mb-3">
                <label class="form-label">Crédito disponible</label>
                <?= $datosCliente['credito'] !== null ? '$' . number_format($datosCliente['credito'], 2, ',', '.') : 'No disponible' ?>

            </div>
        <?php endif; ?>

        <div class="text-center mt-4">
            <button type="submit" class="btn btn-primary">Guardar cambios</button>
        </div>
    </form>

    <div class="volver text-center">
        <a href="javascript:history.back()">← Volver</a>
    </div>
</div>

</body>
</html>
