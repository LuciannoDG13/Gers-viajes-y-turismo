<?php
require 'db.php';
session_start();

$alert = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Login tradicional
    if (isset($_POST['email']) && isset($_POST['clave'])) {
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $clave = mysqli_real_escape_string($conn, $_POST['clave']);

        $sql = "SELECT * FROM usuarios WHERE email = '$email' LIMIT 1";
        $resultado = mysqli_query($conn, $sql);

        if ($fila = mysqli_fetch_assoc($resultado)) {
            if (password_verify($clave, $fila['contrasena'])) {
                $_SESSION['id_usuario'] = $fila['id_usuario'];
                $_SESSION['nombre'] = $fila['nombre'];
                $_SESSION['tipo_usuario'] = $fila['tipo_usuario'];

                header("Location: " . ($fila['tipo_usuario'] === 'cliente' ? "cliente/carrito/inicioCliente.php" : "jefe/principal.php"));
                exit;
            } else {
                $alert = "<div class='alert alert-danger'>Contraseña incorrecta.</div>";
            }
        } else {
            $alert = "<div class='alert alert-danger'>El correo no está registrado.</div>";
        }
    }

    // Login con Google
    if (isset($_POST['google_id'])) {
        $nombre = ucwords(strtolower(mysqli_real_escape_string($conn, $_POST['nombre'])));

        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $google_id = mysqli_real_escape_string($conn, $_POST['google_id']);

        $check = mysqli_query($conn, "SELECT * FROM usuarios WHERE google_id = '$google_id' LIMIT 1");

        if ($fila = mysqli_fetch_assoc($check)) {
            // Usuario ya existe
            $_SESSION['id_usuario'] = $fila['id_usuario'];
            $_SESSION['nombre'] = $fila['nombre'];
            $_SESSION['tipo_usuario'] = $fila['tipo_usuario'];

            header("Location: " . ($fila['tipo_usuario'] === 'cliente' ? "cliente/carrito/inicioCliente.php" : "jefe/principal.php"));

            exit;
        } else {
            // Registrar nuevo usuario
            $sql1 = "INSERT INTO usuarios (nombre, email, contrasena, tipo_usuario, google_id, creado_en)
                     VALUES ('$nombre', '$email', NULL, 'cliente', '$google_id', NOW())";

            if (mysqli_query($conn, $sql1)) {
                $id_usuario = mysqli_insert_id($conn);

                $sql2 = "INSERT INTO clientes (id_usuario, direccion, telefono, dni, fecha_nacimiento)
                         VALUES ($id_usuario, '', '', '', NULL)";
                mysqli_query($conn, $sql2);

                $_SESSION['id_usuario'] = $id_usuario;
                $_SESSION['nombre'] = $nombre;
                $_SESSION['tipo_usuario'] = 'cliente';
                $_SESSION['nuevo_google'] = true;

                // Redirigimos a completar datos
                header("Location: completarDatos.php"); // creá este archivo para completar datos extra
                exit;
            } else {
                $alert = "<div class='alert alert-danger'>Error al registrar usuario Google: " . mysqli_error($conn) . "</div>";
            }
        }
    }
}
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Inicio de Sesión</title>
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
            margin: 0;
            padding: 1rem;
        }

        .login-container {
            margin-top: 300px;
            margin-bottom: 150px;
            background: #ffffff;
            padding: 2rem;
            border-radius: 18px;
            box-shadow: 0 0 25px rgba(77, 176, 230, 0.25);
            width: 100%;
            max-width: 400px;
            position: relative;
            box-sizing: border-box;
        }

        .logo {
            width: 155px;
            height: 140px;
            position: absolute;
            top: -80px;
            left: 50%;
            transform: translateX(-50%);
        }

        .login-container h4 {
            color: #1d3c73;
            font-weight: bold;
            text-align: center;
            margin-top: 25px;
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

        .btn-registrarse {
            margin-top: 1rem;
            background-color: #4db0e6;
            border: none;
            width: 100%;
            padding: 0.5rem;
            font-weight: 600;
            cursor: pointer;
            color: white;
            border-radius: 6px;
            transition: 0.3s ease-in-out;
        }

        .btn-registrarse:hover {
            background-color: #3ca3d4;
        }

        #buttonDiv {
            margin-top: 1rem;
            display: flex;
            justify-content: center;
        }

        /* Media queries para responsive */
        @media (max-width: 480px) {
            .login-container {
                padding: 1.5rem 1rem 2rem;
                max-width: 90%;
            }

            .logo {
                width: 110px;
                height: 100px;
                top: -60px;
            }

            .login-container h4 {
                font-size: 1.3rem;
                margin-top: 15px;
            }

            .btn-primary, .btn-registrarse {
                font-size: 1rem;
                padding: 0.5rem;
            }

            body {
                padding: 0.5rem;
            }
        }

        @media (max-height: 600px) {
            body {
                align-items: flex-start;
                padding-top: 2rem;
                height: auto;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <img src="img/logoo.png" alt="Logo" class="logo" />
        <h4>GERS</h4>
        <h4>Iniciar Sesión</h4>

        <?= $alert ?>

        <form method="POST" novalidate>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" required autocomplete="username" />
            </div>
            <div class="mb-3">
                <label for="clave" class="form-label">Contraseña</label>
                <input type="password" class="form-control" id="clave" name="clave" required autocomplete="current-password" />
            </div>
            <button type="submit" class="btn btn-primary w-100">Ingresar</button>
        </form>

        <form action="registrarse.php" method="get">
            <br />
            ¿No posee una cuenta? Haga click y registrate
            <button type="submit" class="btn-registrarse">Registrarse</button>
        </form>

        <form action="" method="POST" id="googleForm" style="display:none;">
            <input type="hidden" name="nombre" id="g_nombre" />
            <input type="hidden" name="email" id="g_email" />
            <input type="hidden" name="google_id" id="g_id" />
        </form>

        <div id="buttonDiv"></div>
    </div>

    <script src="https://accounts.google.com/gsi/client" async defer></script>
    <script>
        window.onload = function () {
            google.accounts.id.initialize({
                client_id: "581286126667-f5gdtna1h6h61vkiiq3sc3975uoqjn73.apps.googleusercontent.com",
                callback: handleCredentialResponse
            });

            google.accounts.id.renderButton(
                document.getElementById("buttonDiv"),
                { theme: "outline", size: "large" }
            );
        };

        function handleCredentialResponse(response) {
            const data = parseJwt(response.credential);
            document.getElementById('g_nombre').value = data.name;
            document.getElementById('g_email').value = data.email;
            document.getElementById('g_id').value = data.sub;
            document.getElementById('googleForm').submit();
        }

        function parseJwt(token) {
            const base64 = token.split('.')[1].replace(/-/g, '+').replace(/_/g, '/');
            const json = decodeURIComponent(atob(base64).split('').map(c =>
                '%' + ('00' + c.charCodeAt(0).toString(16)).slice(-2)
            ).join(''));
            return JSON.parse(json);
        }
    </script>
</body>
</html>
