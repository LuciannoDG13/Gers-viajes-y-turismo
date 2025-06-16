<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Agregar Usuario</title>
  <link rel="icon" type="image/x-icon" href="../../imagenes/icoon.png">
  <link rel="stylesheet" href="/viajes turismo/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
  <style>
    body {
      margin: 0;
      font-family: 'Segoe UI', sans-serif;
      background: linear-gradient(135deg, #eaf6fb, #d3ecf5);
      min-height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      color: #1d3c73;
    }
    .form-container {
      background: #ffffff;
      border-radius: 18px;
      box-shadow: 0 0 25px rgba(77, 176, 230, 0.25);
      padding: 2rem;
      width: 100%;
      max-width: 500px;
      margin-left: 200px;
    }
    h1 {
      text-align: center;
      font-weight: bold;
      margin-bottom: 1.5rem;
    }
    label {
      font-weight: 600;
      margin-bottom: 0.5rem;
    }
    input, select {
      border-radius: 12px;
      border: 1px solid #4db0e6;
      background-color: #f0f8fc;
      padding: 10px;
      font-size: 1rem;
      margin-bottom: 1rem;
      width: 100%;
    }
    .sidebar {
    height: 100vh;
    width: 250px;
    position: fixed;
    left: 0;
    top: 0;
    background-color: rgb(248, 245, 245);
    box-shadow: 2px 0 15px rgba(77, 176, 230, 0.2);
    padding: 1rem;
    border-radius: 0 18px 18px 0;
}

.sidebar h4 {
    text-align: center;
    margin-bottom: 2rem;
    color: #1d3c73;
    font-weight: bold;
}
   .logo-link {
    padding: 0;
    margin: 0;
    display: block;
    background: transparent !important;
    text-decoration: none !important;
    color: inherit !important;
    cursor: pointer;
}

.logo-link:hover,
.logo-link:focus,
.logo-link:active {
    background: transparent !important;
    text-decoration: none !important;
    color: inherit !important;
}

.sidebar a {
    display: flex;
    align-items: center;
    gap: 10px;
    color: #1d3c73;
    padding: 10px 15px;
    text-decoration: none;
    border-radius: 8px;
    transition: background 0.3s ease;
    margin-bottom: 10px;
}

.sidebar a:hover {
    background-color: #e6f3fa;
}
    .btn {
      border-radius: 12px;
      font-weight: 600;
    }
    .btn-success {
      background-color: #38a169;
    }
    .btn-success:hover {
      background-color: #2f855a;
    }
    .btn-secondary {
      background-color: #f8f9fa;
      color: #1d3c73;
    }
    .btn-secondary:hover {
      background-color: #e2e6ea;
    }
    .d-flex {
      gap: 10px;
    }
  </style>
</head>
<body>
  <div class="sidebar">
    <a href="../principal.php" class="logo-link">
    <img style="width: 200px; margin-left:-20px; margin-bottom: 15px;" src="../../img/logoo.png" alt="Logo">
</a>

    <a href="../pedidos/pedidos.php"><i class="bi bi-basket-fill"></i> Pedidos</a>
    <a href="../productos/productos.php"><i class="bi bi-box-seam"></i> Productos</a>
    <a href="../clientes/cliente.php"><i class="bi bi-people-fill"></i> Clientes</a>
    <a href="usuarios.php"><i class="bi bi-people-fill"></i> Usuarios</a>
    <a href="../reportes/reportes.php"><i class="bi bi-graph-up"></i> Reportes</a>
    <a href="../ventas/historial_ventas.php"><i class="bi bi-clock-history"></i> Historial de Ventas</a>
    <a href="../estado_cuenta/estado_cuenta.php"><i class="bi bi-cash-coin"></i> Estado de Cuentas</a>
    <a href="../../cierre_sesion.php"><i class="bi bi-box-arrow-right"></i> Cerrar sesión</a>
</div>

  <div class="form-container">
    <form action="agregar.php" method="POST">
      <h1>Agregar Usuario</h1>

      <label>NOMBRE DE USUARIO</label>
      <input type="text" name="nombre" required>

      <label>CORREO ELECTRÓNICO</label>
      <input type="email" name="email">

      <label>CONTRASEÑA</label>
      <input type="password" name="contrasena" required>

      <label>TIPO DE USUARIO</label>
      <select name="tipo_usuario" class="form-select" required>
        <option value="">Seleccionar tipo</option>
        <option value="cliente">Cliente</option>
        <option value="personal">Personal</option>
      </select>


   

      <div class="d-flex justify-content-between">
        <button type="submit" class="btn btn-success w-50">Agregar</button>
        <a href="usuarios.php" class="btn btn-secondary w-50 text-center">Cancelar</a>
      </div>
    </form>
  </div>

</body>
</html>
