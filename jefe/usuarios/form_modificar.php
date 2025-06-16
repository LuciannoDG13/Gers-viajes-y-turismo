<?php 
session_start();
require('../../db.php');

if (!isset($_POST['id_usuario'])) {
    echo "No se recibió el ID del usuario.";
    exit();
}

$id_usuario = $_POST['id_usuario'];

// Consulta el usuario por ID
$sql = "SELECT * FROM usuarios WHERE id_usuario = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$result = $stmt->get_result();
$datos = $result->fetch_assoc();

if (!$datos) {
    echo "Usuario no encontrado.";
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Modificar Usuario</title>

  <link rel="stylesheet" href="/viajes turismo/css/bootstrap.min.css" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" />
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
      background: #fff;
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
      color: #1d3c73;
    }
    label {
      font-weight: 600;
      margin-bottom: 0.5rem;
      color: #1d3c73;
    }
    input, select {
      border-radius: 12px;
      border: 1px solid #4db0e6;
      background-color: #f0f8fc;
      color: #1d3c73;
      padding: 10px;
      font-size: 1rem;
      margin-bottom: 1rem;
      width: 100%;
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
.sidebar a {
    display: flex;
    align-items: center;
    gap: 10px;
    color: #1d3c73;
    padding: 10px 15px;
    text-decoration: none;
    border-radius: 8px;
    margin-bottom: 10px;
}
.sidebar a:hover {
    background-color: #e6f3fa;
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
    
  <form action="modificar.php" method="POST">
    <h1>Modificar Usuario</h1>

    <!-- Campo oculto para enviar el id -->
    <input type="hidden" name="id_usuario" value="<?php echo $datos['id_usuario']; ?>">

    <label>Nombre</label>
    <input type="text" name="nombre" value="<?php echo htmlspecialchars($datos['nombre']); ?>" required>

    <label>Email</label>
    <input type="email" name="email" value="<?php echo htmlspecialchars($datos['email']); ?>" required>

    <label>Contraseña (dejar vacío para no cambiar)</label>
    <input type="password" name="contrasena" placeholder="Nueva contraseña">

    <label>Tipo de Usuario</label>
<select name="tipo_usuario" required>
  <?php 
    $tipos = ['cliente' => 'Cliente', 'personal' => 'Personal'];
    foreach ($tipos as $key => $val) {
      $selected = ($datos['tipo_usuario'] == $key) ? 'selected' : '';
      echo "<option value='$key' $selected>$val</option>";
    }
  ?>
</select>


    <div class="d-flex justify-content-between">
      <button type="submit" class="btn btn-success w-50">Modificar</button>
      <a href="usuarios.php" class="btn btn-secondary w-50 text-center">Cancelar</a>
    </div>
  </form>
</div>
</body>
</html>
