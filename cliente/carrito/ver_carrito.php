<?php
require __DIR__.'/vendor/autoload.php'; // Aunque no se usa para MP aquí, podría ser necesario para otras funciones

session_start();

if (!isset($_SESSION['carrito'])) {
    $_SESSION['carrito'] = [];
}

$total = 0;
// No necesitamos $items aquí ya que no se crea la preferencia MP
if (!empty($_SESSION['carrito'])) {
    foreach ($_SESSION['carrito'] as $item) {
        $total += ($item['precio'] ?? 0) * ($item['cantidad'] ?? 0); // Usar operador null coalescing para evitar errores si no existen las claves
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mi Carrito</title>
    <link rel="stylesheet" href="/viajes turismo/css/bootstrap.min.css">
    <style>
        /* Tus estilos CSS existentes */
        body {
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #eaf6fb, #d3ecf5);
            color: #1d3c73;
            padding-top: 60px;
        }

        header {
            position: fixed;
            top: 0; left: 0; right: 0;
            height: 60px;
            display: flex;
            align-items: center;
            background-color: #4db0e6;
            padding: 0 15px;
            justify-content: space-between;
            z-index: 9999;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .header-logo img {
            width: 58px;
            height: 55px;
        }

        .header-title {
            margin-left: 15px;
            font-size: 1.5rem;
            font-weight: 700;
            display: flex;
            gap: 8px;
            color: #fff;
            flex-grow: 1;
            align-items: center;
        }

        .header-title .gers {
            color: rgb(14, 57, 104);
        }

        .header-title .turismo {
            color: #fff;
        }

        .nav-links {
            display: flex;
            gap: 20px;
        }

        .nav-links a {
            font-weight: 600;
            text-decoration: none;
            color: #fff;
            padding: 6px 14px;
            border-radius: 6px;
            border: 1px solid transparent;
            transition: background-color 0.3s, border-color 0.3s;
        }

        .nav-links a:hover {
            background-color: rgba(255, 255, 255, 0.3);
            border-color: #fff;
        }

        .container {
            max-width: 800px;
            margin: auto;
        }

        h2 {
            text-align: center;
            color: #1d3c73;
            margin-bottom: 20px;
        }

        .list-group-item {
            background-color: #fff;
            border: 1px solid #cce4f6;
            border-radius: 8px;
            margin-bottom: 10px;
            padding: 15px;
            box-shadow: 0 0 10px rgba(77, 176, 230, 0.15);
        }

        .total {
            font-size: 1.2rem;
            color: #1d3c73;
            font-weight: bold;
        }

        .btn-custom {
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 600;
        }

        .btn-vaciar {
            background-color: #1d3c73;
            color: white;
        }

        .btn-vaciar:hover {
            background-color: #4db0e6;
            color: #163055;
        }

        .btn-comprar {
            background-color: #1d3c73;
            color: white;
        }

        .btn-comprar:hover {
            background-color: #4db0e6;
            color: #163055;
        }

        .cho-container { /* Aunque no se usa aquí, lo mantengo si no está en otro lado */
            text-align: center;
        }
    </style>
</head>
<body>

<header>
    <div class="header-logo">
        <img src="../../img/logoo.png" alt="Logo" />
    </div>
    <div class="header-title">
        <span class="gers">GERS</span>
        <span class="turismo">Viajes y Turismo</span>
    </div>
    <nav class="nav-links">
        <a href="../../cliente/catalogo/productos.php">Catálogo</a>
        <a href="login.php">Iniciar sesión</a>
        <a href="mispedidos.php">Mis Pedidos</a>
    </nav>
</header>

<main class="container py-5">
    <h2>Mi Carrito</h2>

    <?php if (!empty($_SESSION['carrito'])): ?>
        <ul class="list-group mb-4">
            <?php foreach ($_SESSION['carrito'] as $item): ?>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <?= htmlspecialchars($item['nombre'] ?? 'N/A') ?>
                    <span><?= $item['cantidad'] ?? 'N/A' ?> x $<?= number_format($item['precio'] ?? 0, 2, ',', '.') ?></span>
                </li>
            <?php endforeach; ?>
        </ul>

        <div class="total mb-4">Total: $<?= number_format($total, 2, ',', '.') ?></div>

        <div class="d-flex justify-content-between mb-4">
            <a href="vaciar_carrito.php" class="btn btn-vaciar btn-custom">Vaciar carrito</a>
            <a href="#" class="btn btn-comprar btn-custom" data-bs-toggle="modal" data-bs-target="#modalSesion">
                Realizar compra
            </a>
            </div>

        <div class="cho-container my-4" style="display:none;"></div> 

    <?php else: ?>
        <div class="alert alert-warning text-center">
            No agregaste productos al carrito.
        </div>
    <?php endif; ?>

    <div class="volver">
        <a class="btn btn-comprar btn-custom" href="javascript:history.back()">← Volver</a>
    </div>
</main>

<div class="modal fade" id="modalSesion" tabindex="-1" aria-labelledby="modalSesionLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content text-center">
      <div class="modal-header text-dark">
        <h5 class="modal-title w-100" id="modalSesionLabel">Iniciar sesión requerida</h5>
      </div>
      <div class="modal-body">
        Debes iniciar sesión para poder realizar una compra.
      </div>
      <div class="modal-footer justify-content-center">
        <a href="/../viajes turismo/login.php" class="btn btn-primary">Ir</a>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>