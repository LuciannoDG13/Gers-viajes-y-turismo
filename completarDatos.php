<?php
session_start();

if (!isset($_SESSION['nuevo_google']) || !$_SESSION['nuevo_google']) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Completar Datos</title>
    <link rel="stylesheet" href="/viajes turismo/css/bootstrap.min.css" />
    <style>
        body {
            background: linear-gradient(135deg, #eaf6fb, #d3ecf5);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: 'Segoe UI', sans-serif;
            color: #1d3c73;
        }

        .form-container {
            background: #ffffff;
            padding: 2rem;
            border-radius: 18px;
            box-shadow: 0 0 25px rgba(77, 176, 230, 0.25);
            width: 100%;
            max-width: 400px;
            position: relative;
        }

        .logo {
            width: 155px;
            height: 140px;
            position: absolute;
            top: -80px;
            left: 47%;
            transform: translateX(-50%);
        }

        .form-container h2 {
            color: #1d3c73;
            font-weight: bold;
            text-align: center;
            margin-top: 25px;
            margin-bottom: 1.5rem;
        }

        .form-control {
            background-color: #f0f8fc;
            border: 1px solid #4db0e6;
            color: #1d3c73;
        }

        .form-control:focus {
            border-color: #1d3c73;
            box-shadow: 0 0 8px rgba(29, 60, 115, 0.4);
            background-color: #e6f3fa;
        }

        .btn-primary {
            background-color: #1d3c73;
            border: none;
            color: white;
            transition: 0.3s ease-in-out;
        }

        .btn-primary:hover {
            background-color: #163055;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <img src="img/logoo.png" alt="Logo" class="logo" />
        <h2>Completa tus datos</h2>
        <form method="POST" action="procesarDatosExtra.php">
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre completo</label>
                <input type="text" id="nombre" name="nombre" class="form-control" required />
            </div>
            <div class="mb-3">
                <label for="direccion" class="form-label">Dirección</label>
                <input type="text" id="direccion" name="direccion" class="form-control" required />
            </div>
            <div class="mb-3">
                <label for="telefono" class="form-label">Teléfono</label>
                <input type="text" id="telefono" name="telefono" class="form-control" required />
            </div>
            <div class="mb-3">
                <label for="dni" class="form-label">DNI</label>
                <input type="text" id="dni" name="dni" class="form-control" required />
            </div>
            <div class="mb-3">
                <label for="fecha_nacimiento" class="form-label">Fecha de nacimiento</label>
                <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" class="form-control" required />
            </div>
            <button type="submit" class="btn btn-primary w-100">Finalizar Registro</button>
        </form>
    </div>
</body>
</html>
