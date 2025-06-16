<?php
session_start();
unset($_SESSION['carrito']); // Limpiar el carrito después del pago
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gracias por tu compra</title>
    <link rel="stylesheet" href="/viajes turismo/css/bootstrap.min.css">
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #eaf6fb;
            color: #1d3c73;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }
        .mensaje {
            background: #fff;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 0 15px rgba(77, 176, 230, 0.3);
            text-align: center;
        }
        .mensaje h1 {
            font-size: 2rem;
            color: #1d3c73;
        }
        .mensaje p {
            margin-top: 15px;
        }
        a.btn {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="mensaje">
        <h1>¡Gracias por tu compra!</h1>
        <p>Tu pago fue procesado exitosamente.</p>
        <a href="../../cliente/catalogo/productos.php" class="btn btn-primary">Volver al catálogo</a>
    </div>
</body>
</html>
