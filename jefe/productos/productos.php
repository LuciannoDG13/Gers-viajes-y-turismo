<?php
session_start();
require('../../db.php');

$busqueda = "";

// Si se presiona el botón Volver, se limpia la búsqueda
if (isset($_POST['volver'])) {
    $busqueda = "";
}
// Verifico si el usuario ha enviado una búsqueda y si el campo de búsqueda no está vacío
elseif (isset($_POST['submit']) && !empty($_POST['b'])) {
    $busqueda = $_POST['b'];
}

// Si se realiza una búsqueda, se utiliza una consulta con LIKE para filtrar
if ($busqueda != "") {
    $sql = "SELECT * FROM productos 
        INNER JOIN categorias_productos ON categorias_productos.id_categoria = productos.id_categoria 
        INNER JOIN tipos_productos ON tipos_productos.id_tipo = productos.id_tipo
        WHERE productos.nombre_producto LIKE '%$busqueda%' 
           OR productos.codigo_producto LIKE '%$busqueda%' 
           OR categorias_productos.nombre_categorias LIKE '%$busqueda%'
           OR tipos_productos.nombre LIKE '%$busqueda%'
        ORDER BY productos.nombre_producto ASC";

} else {
    // Si no hay búsqueda, se obtienen todos los registros
    $sql = "SELECT * FROM productos 
            INNER JOIN categorias_productos ON categorias_productos.id_categoria = productos.id_categoria 
            INNER JOIN tipos_productos ON tipos_productos.id_tipo = productos.id_tipo
            ORDER BY productos.nombre_producto ASC;";
}

// Cambié $mysqli por $conn aquí
$result = $conn->query($sql);
?>


<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Productos</title>
    <link rel="stylesheet" href="/viajes turismo/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" />

    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #eaf6fb, #d3ecf5);
            min-height: 100vh;
            color: #1d3c73;
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

        .main-content {
            margin-left: 300px;
            padding: 2rem;
            margin-top: -50px;
        }

        .card {
            background: #ffffff;
            border-radius: 18px;
            box-shadow: 0 0 25px rgba(77, 176, 230, 0.25);
            padding: 2rem;
            margin-top: 60px;
        }

        .table {
            width: 100%;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 0 15px rgba(77, 176, 230, 0.2);
            margin-top: 20px;
            font-size: 1.1rem;
        }

        .table th {
            background-color: #eaf6fb;
            color: #1d3c73;
            font-weight: bold;
            text-align: center;
            padding: 16px;
        }

        .table td {
            text-align: center;
            padding: 14px;
        }

        .table tbody tr:hover {
            background-color: #f0f9ff;
        }

        .btn-volver,
        .btn-modificar {
            background-color: #1d3c73;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 10px;
            margin-bottom: 20px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .btn-volver:hover,
        .btn-modificar:hover {
            background-color: #163055;
        }

        /* Estilo para el formulario de búsqueda */
        form.mb-3.d-flex.gap-2 {
            margin-bottom: 1.5rem;
            gap: 10px;
        }

        form.mb-3.d-flex.gap-2 input.form-control {
            flex-grow: 1;
        }

        form.mb-3.d-flex.gap-2 button {
            padding: 0.4rem 1.25rem;
        }

        @media screen and (max-width: 768px) {
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
                border-radius: 0;
            }

            .main-content {
                margin-left: 0;
                margin-top: 20px;
            }
        }

        /* Estilos para el buscador */
        form.mb-3.d-flex.gap-2 {
            margin-bottom: 1.5rem;
            gap: 10px;
            background: #f5fbff;
            padding: 12px 15px;
            border-radius: 18px;
            box-shadow: 0 2px 12px rgba(77, 176, 230, 0.15);
            align-items: center;
        }

        form.mb-3.d-flex.gap-2 input.form-control {
            flex-grow: 1;
            border: 2px solid #9cc8e2;
            border-radius: 18px;
            padding: 10px 15px;
            font-size: 1rem;
            transition: border-color 0.3s ease;
            color: #1d3c73;
            box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        form.mb-3.d-flex.gap-2 input.form-control::placeholder {
            color: #7a9cc3;
        }

        form.mb-3.d-flex.gap-2 input.form-control:focus {
            border-color: #4da0e6;
            outline: none;
            box-shadow: 0 0 8px #4da0e6;
        }

        form.mb-3.d-flex.gap-2 button {
            padding: 10px 22px;
            font-weight: 600;
            border-radius: 18px;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s ease;
            color: white;
            box-shadow: 0 3px 8px rgba(77, 176, 230, 0.6);
        }

        form.mb-3.d-flex.gap-2 button.btn-primary {
            background-color: #1d3c73;
        }

        form.mb-3.d-flex.gap-2 button.btn-primary:hover {
            background-color: #163055;
        }

        form.mb-3.d-flex.gap-2 button.btn-secondary {
            background-color: #7a9cc3;
            box-shadow: 0 3px 8px rgba(122, 156, 195, 0.6);
        }

        form.mb-3.d-flex.gap-2 button.btn-secondary:hover {
            background-color: #5f7ea1;
        }

        .btn-estado {
            padding: 6px 14px;
            border-radius: 12px;
            font-weight: 600;
            border: none;
            cursor: pointer;
            font-size: 0.9rem;
            transition: background-color 0.3s ease, box-shadow 0.3s ease;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.12);
        }

        .btn-estado.activo {
            background-color: #28a745;
            /* Verde */
            color: white;
        }

        .btn-estado.activo:hover {
            background-color: #218838;
            box-shadow: 0 4px 10px rgba(40, 167, 69, 0.6);
        }

        .btn-estado.inactivo {
            background-color: #6c757d;
            /* Gris oscuro */
            color: white;
        }

        .btn-estado.inactivo:hover {
            background-color: #5a6268;
            box-shadow: 0 4px 10px rgba(108, 117, 125, 0.6);
        }

        .btn-agregar {
            display: inline-block;
            background-color: #1d3c73;
            color: white;
            padding: 10px 22px;
            font-size: 1.05rem;
            font-weight: bold;
            border-radius: 18px;
            text-decoration: none;
            box-shadow: 0 4px 12px rgba(77, 176, 230, 0.4);
            margin-bottom: 20px;
            transition: background-color 0.3s ease, box-shadow 0.3s ease;
            margin-top: 15px;
        }

        .btn-agregar:hover {
            background-color: #163055;
            box-shadow: 0 6px 18px rgba(77, 176, 230, 0.6);
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
        <a href="productos.php"><i class="bi bi-box-seam"></i> Productos</a>
        <a href="../clientes/cliente.php"><i class="bi bi-people-fill"></i> Clientes</a>
        <a href="../usuarios/usuarios.php"><i class="bi bi-people-fill"></i> Usuarios</a>
        <a href="../reportes/reportes.php"><i class="bi bi-graph-up"></i> Reportes</a>
        <a href="../ventas/historial_ventas.php"><i class="bi bi-clock-history"></i> Historial de Ventas</a>
        <a href="../estado_cuenta/estado_cuenta.php"><i class="bi bi-cash-coin"></i> Estado de Cuentas</a>
        <a href="../../cierre_sesion.php"><i class="bi bi-box-arrow-right"></i> Cerrar sesión</a>
    </div>
    <div class="main-content">
        <div class="card">
            <h2>Productos</h2>

            <!-- Formulario buscador -->
            <form action="" method="post" class="mb-3 d-flex gap-2">
                <input type="text" name="b" placeholder="Buscar por producto, código, categoría o tipo..."
                    class="form-control" value="<?php echo htmlspecialchars($busqueda); ?>" autocomplete="off" />
                <button type="submit" name="submit" class="btn btn-primary">Buscar</button>

                <?php if ($busqueda != ""): ?>
                    <button type="submit" name="volver" class="btn btn-secondary">Volver</button>
                <?php endif; ?>
            </form>
            <div class="table-responsive">
                <table class="table table-hover table-bordered">
                    <thead>
                        <tr>
                            <th>PRODUCTO</th>
                            <th>CODIGO</th>
                            <th>DESCRIPCION</th>
                            <th>PRECIO UNITARIO</th>
                            <th>HORA Y FECHA DE CREACIÓN</th>
                            <th>ESTADO</th>
                            <th>TIPO DE SERVICIO</th>
                            <th>CATEGORÍA DE SERVICIO</th>
                            <th>MODIFICAR</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($datos = mysqli_fetch_assoc($result)): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($datos['nombre_producto']); ?></td>
                                <td><?php echo htmlspecialchars($datos['codigo_producto']); ?></td>
                                <td><?php echo htmlspecialchars($datos['descripcion']); ?></td>
                                <td><?php echo '$' . number_format($datos['precio_unitario'], 2); ?></td>
                                <td><?php echo htmlspecialchars($datos['creado_en']); ?></td>
                                <td>
                                    <form action="cambiar_estado.php" method="post" style="margin: 0;">
                                        <input type="hidden" name="id_producto"
                                            value="<?php echo $datos['id_producto']; ?>">
                                        <input type="hidden" name="estado_actual" value="<?php echo $datos['estado']; ?>">
                                        <?php if ($datos['estado'] === 'activo'): ?>
                                            <button type="submit" class="btn-estado activo">Activo</button>
                                        <?php else: ?>
                                            <button type="submit" class="btn-estado inactivo">Inactivo</button>
                                        <?php endif; ?>

                                    </form>
                                </td>
                                <td><?php echo htmlspecialchars($datos['nombre']); ?></td>
                                <td><?php echo htmlspecialchars($datos['nombre_categorias']); ?></td>
                                <td>
                                    <form action="form_modificar.php" method="post" style="margin: 0;">
                                        <input name="codigo" type="hidden"
                                            value="<?php echo htmlspecialchars($datos['id_producto']); ?>">
                                        <button type="submit" class="btn-modificar">Modificar</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <a href="form_agregar.php" class="btn-agregar">+ Agregar Producto</a>

    </div>

    <script src="/viajes turismo/js/bootstrap.bundle.min.js"></script>

</body>

</html>
<!-- #region -->