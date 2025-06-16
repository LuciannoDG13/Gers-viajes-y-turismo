<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Registro</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="/viajes turismo/css/bootstrap.min.css" />
  <style>
    body {
      margin: 0;
      background: linear-gradient(135deg, #eaf6fb, #d3ecf5);
      font-family: 'Segoe UI', sans-serif;
    }

    header {
      display: flex;
      align-items: center;
      background-color: #4db0e6;
      padding: 2px;
    }

    .header-logo img {
      width: 60px;
    }

    .header-title {
      margin-left: 15px;
      font-size: 1.8rem;
      font-weight: 700;
      display: flex;
      gap: 8px;
    }

    .header-title .gers {
      color: rgb(14, 57, 104);
    }

    .header-title .turismo {
      color: #fff;
    }

    .card {
      border: none;
      border-radius: 20px;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
      background-color: #fff;
    }

    .btn-primary {
      background-color: #1d3c73;
      border: none;
      border-radius: 12px;
      padding: 10px;
      font-weight: 600;
    }

    .btn-primary:hover {
      background-color: #163055;
    }

    .btn2 {
      background-color: #4db0e6;
      border-radius: 12px;
      padding: 10px;
      font-weight: 600;
      color: white;
    }

    .btn2:hover {
      background-color: #3ca3d4;
      color: white;
    }

    .form-label {
      font-weight: 500;
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

    @media (max-width: 576px) {
      .header-title {
        flex-direction: column;
        align-items: center;
        text-align: center;
      }

      .header-title span {
        display: block;
      }
    }
  </style>
</head>
<body>

<header>
  <div class="header-logo">
    <img src="img/logoo.png" alt="Logo">
  </div>
  <div class="header-title">
    <span class="gers">GERS</span>
    <span class="turismo">Viajes y Turismo</span>
  </div>
</header>

<div class="container my-4">
  <div class="row justify-content-center">
    <div class="col-lg-5 col-md-7">
      <div class="card p-4">

        <!-- Registro normal -->
        <form action="registro.php" method="POST" id="formNormal">
          <div class="mb-3">
            <label class="form-label" style="color: #1d3c73">Nombre Completo</label>
            <input type="text" name="nombre" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label" style="color: #1d3c73">Correo electrónico</label>
            <input type="email" name="email" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label" style="color: #1d3c73">Contraseña</label>
            <input type="password" name="contrasena" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label" style="color: #1d3c73">DNI</label>
            <input type="text" name="dni" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label" style="color: #1d3c73">Teléfono</label>
            <input type="text" name="telefono" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label" style="color: #1d3c73">Dirección</label>
            <input type="text" name="direccion" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label" style="color: #1d3c73">Fecha de Nacimiento</label>
            <input type="date" name="fecha_nacimiento" class="form-control" required>
          </div>
          <button type="submit" class="btn btn-primary w-100 mt-2">Registrarse</button>
        </form>

        <hr class="my-4">

        <!-- Botón de Google -->
        <button class="btn btn2 w-100" onclick="registrarConGoogle()">
          <img src="https://www.svgrepo.com/show/355037/google.svg" alt="Google" width="20" class="me-2">
          Registrarse con Google
        </button>

        <!-- Formulario adicional tras login con Google -->
        <form id="formDatosExtra" method="POST" action="googleLogin.php" style="display: none;">

  <input type="hidden" name="google_id" id="extra_google_id">
  <input type="hidden" name="email" id="extra_email">

  <div class="mb-3">
    <label>Nombre completo</label>
    <input type="text" name="nombre" id="extra_nombre" class="form-control" required>
  </div>
  <div class="mb-3">
    <label>Dirección</label>
    <input type="text" name="direccion" class="form-control" required>
  </div>
  <div class="mb-3">
    <label>Teléfono</label>
    <input type="text" name="telefono" class="form-control" required>
  </div>
  <div class="mb-3">
    <label>DNI</label>
    <input type="text" name="dni" class="form-control" required>
  </div>
  <div class="mb-3">
    <label>Fecha de nacimiento</label>
    <input type="date" name="fecha_nacimiento" class="form-control" required>
  </div>

  <button type="submit" class="btn btn-primary w-100">Finalizar Registro</button>
</form>


      </div>
    </div>
  </div>
</div>

<script src="/viajes turismo/js/bootstrap.bundle.min.js"></script>
<script src="https://www.gstatic.com/firebasejs/9.22.2/firebase-app-compat.js"></script>
<script src="https://www.gstatic.com/firebasejs/9.22.2/firebase-auth-compat.js"></script>

<script>
  const firebaseConfig = {
    apiKey: "AIzaSyAvGsNA4ljCjUZ-ejvp2MwgLTjAsk4BFn8",
    authDomain: "login-b8fbd.firebaseapp.com",
    projectId: "login-b8fbd",
    storageBucket: "login-b8fbd.appspot.com",
    messagingSenderId: "630092908715",
    appId: "1:630092908715:web:e3c5f88cf0bbbea9860fee",
    measurementId: "G-RN84S3103J"
  };

  firebase.initializeApp(firebaseConfig);

  function registrarConGoogle() {
    const provider = new firebase.auth.GoogleAuthProvider();

    firebase.auth().signInWithPopup(provider)
      .then((result) => {
        const user = result.user;
        document.getElementById('extra_google_id').value = user.uid;
        document.getElementById('extra_email').value = user.email;
        document.getElementById('extra_nombre').value = user.displayName;

        document.getElementById('formNormal').style.display = 'none';
        document.getElementById('formDatosExtra').style.display = 'block';
      })
      .catch((error) => {
        alert("Error con Google: " + error.message);
      });
  }
</script>

</body>
</html>
