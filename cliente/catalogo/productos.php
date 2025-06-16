<?php
include '../../db.php';

// Obtener todas las categorías para el menú desplegable
$categorias_query = "SELECT * FROM categorias_productos";
$categorias_result = $conn->query($categorias_query);

// Filtrar por categoría si se seleccionó
$filtro_categoria = isset($_GET['id_categoria']) ? intval($_GET['id_categoria']) : 0;

$where = "WHERE p.estado = 'activo'";
if (!empty($_GET['categoria'])) {
    $categoria_id = intval($_GET['categoria']);
    $where .= " AND p.id_categoria = $categoria_id";
}

$sql = "SELECT p.*, 
               c.nombre_categorias, 
               t.nombre AS nombre_tipo
        FROM productos p 
        JOIN categorias_productos c ON p.id_categoria = c.id_categoria 
        JOIN tipos_productos t ON p.id_tipo = t.id_tipo
        $where";

if ($filtro_categoria > 0) {
    $sql .= " AND p.id_categoria = $filtro_categoria";
}

$resultado = $conn->query($sql);
$productos = [];

if ($resultado) {
    while ($row = $resultado->fetch_assoc()) {
        $productos[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <title>Catálogo de Productos</title>
    <link rel="stylesheet" href="/viajes turismo/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
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

        h2 {
            text-align: center;
            color: #1d3c73;
            margin: 30px 0 20px 0;
        }

        .catalogo {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            padding: 0 20px 40px 20px;
        }

        .tarjeta {
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 0 25px rgba(77, 176, 230, 0.25);
            overflow: hidden;
            transition: transform 0.3s ease;
            display: flex;
            flex-direction: column;
        }

        .tarjeta:hover {
            transform: translateY(-5px);
        }

        .tarjeta img {
            width: 100%;
            height: 180px;
            object-fit: cover;
        }

        .contenido {
            padding: 16px;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .titulo-container {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            margin-bottom: 8px;
        }

        .titulo {
            font-size: 20px;
            font-weight: bold;
            color: #1d3c73;
            flex-grow: 1;
        }

        .agregar-container {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .agregar-cantidad {
            width: 60px;
            padding: 5px 8px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 1rem;
            color: #1d3c73;
        }

        .btn-agregar {
            background-color: #1d3c73;
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
            transition: background-color 0.3s ease;
        }

        .btn-agregar:hover {
            background-color: #4db0e6;
            color: #163055;
        }

        .precio {
            color: #00a859;
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .detalles {
            background: #f0f8fc;
            padding: 10px 16px 16px 16px;
            font-size: 14px;
            color: #1d3c73;
        }

        .detalles strong {
            color: #163055;
        }

        .mensaje-agregado {
            margin-top: 8px;
            color: #1d3c73;
            font-weight: 600;
            font-size: 0.9rem;
            opacity: 0;
            transition: opacity 0.5s ease;
        }

        .mensaje-agregado.visible {
            opacity: 1;
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
    
    </div>
    <a href="../carrito/ver_carrito.php"><i class="bi bi-cart"></i> Ver mi carrito</a>
    <a href="../../login.php"><i class="bi bi-box-arrow-in-right"></i> Iniciar sesión</a>
    <a href="mispedidos.php"><i class="bi bi-receipt"></i> Mis Pedidos</a>
    <a href="perfil.php"><i class="bi bi-person-circle"></i> Mi Perfil</a>
  </nav>
</header>

<h2>Catálogo de Productos</h2>
<?php
// Obtener categorías para el filtro
$categorias = $conn->query("SELECT * FROM categorias_productos");
$categoria_seleccionada = isset($_GET['categoria']) ? $_GET['categoria'] : '';
?>
<div class="container mb-4">
    <div class="row justify-content-center">
        <div class="col-12 d-flex flex-wrap justify-content-center gap-2">
            <a href="?categoria=" class="btn btn-outline-primary rounded-pill px-3 py-2 shadow-sm <?= empty($categoria_seleccionada) ? 'active' : '' ?>">
                <i class="bi bi-grid me-1"></i> Todos
            </a>
            <?php while ($cat = $categorias->fetch_assoc()): ?>
                <a href="?categoria=<?= $cat['id_categoria'] ?>" class="btn btn-outline-primary rounded-pill px-3 py-2 shadow-sm <?= $categoria_seleccionada == $cat['id_categoria'] ? 'active' : '' ?>">
                    <i class="bi bi-tags me-1"></i> <?= htmlspecialchars($cat['nombre_categorias']) ?>
                </a>
            <?php endwhile; ?>
        </div>
    </div>
</div>



<div class="catalogo">
    <?php foreach ($productos as $producto): ?>
    <div class="tarjeta">
        <img src="../../<?= htmlspecialchars($producto['url']) ?>" alt="<?= htmlspecialchars($producto['nombre_producto']) ?>" />
        <div class="contenido">
            <div class="titulo-container">
                <div class="titulo"><?= htmlspecialchars($producto['nombre_producto']) ?></div>
                <div class="agregar-container">
                    <form method="POST" action="../../cliente/carrito/carrito.php">
                        <input type="hidden" name="id_producto" value="<?= $producto['id_producto'] ?>" />
                        <input type="number" class="agregar-cantidad" name="cantidad" value="1" min="1" />
                        <button type="submit" class="btn-agregar">Agregar</button>
                    </form>
                </div>
            </div>
            <div class="mensaje-agregado">Producto añadido al carrito</div>
            <div class="precio">$<?= number_format($producto['precio_unitario'], 2, ',', '.') ?></div>
            <div class="detalles">
                <p><strong>Código:</strong> <?= htmlspecialchars($producto['codigo_producto']) ?></p>
                <p><strong>Descripción:</strong> <?= htmlspecialchars($producto['descripcion']) ?></p>
                <p><strong>Categoría:</strong> <?= htmlspecialchars($producto['nombre_categorias']) ?></p>
                <p><strong>Tipo:</strong> <?= htmlspecialchars($producto['nombre_tipo']) ?></p>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<script>
    document.querySelectorAll('.btn-agregar').forEach(button => {
        button.addEventListener('click', () => {
            const tarjeta = button.closest('.tarjeta');
            const mensaje = tarjeta.querySelector('.mensaje-agregado');
            mensaje.classList.add('visible');
            setTimeout(() => mensaje.classList.remove('visible'), 2000);
        });
    });
</script>

</body>
</html>
