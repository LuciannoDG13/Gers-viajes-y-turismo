<?php
session_start();
require('../../db.php');

$busqueda = "";

if (isset($_POST['submit']) && !empty($_POST['b'])) {
    $busqueda = $_POST['b'];
}

if ($busqueda != "") {
    $sql = "SELECT * FROM pedidos
            INNER JOIN usuarios on usuarios.id_usuario=pedidos.id_usuario 
            WHERE usuarios.nombre LIKE '%$busqueda%'";
} else {
    $sql = "SELECT * FROM pedidos
            INNER JOIN usuarios ON usuarios.id_usuario=pedidos.id_usuario 
            INNER JOIN detalle_pedidos on detalle_pedidos.id_pedido=pedidos.id_pedido;";
}

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Pedidos</title>
    <link rel="stylesheet" href="/viajes turismo/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

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
        background-color:rgb(248, 245, 245);
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
    }

    .card {
        background: #ffffff;
        border-radius: 18px;
        box-shadow: 0 0 25px rgba(77, 176, 230, 0.25);
        padding: 2rem;
        margin-top: 60px;
    }

    .search-container {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
        flex-wrap: wrap;
    }

    .search-container h2 {
        font-weight: bold;
        margin-bottom: 10px;
    }

    .search-container form {
        display: flex;
        gap: 10px;
    }

    .search-input {
        padding: 8px 14px;
        border-radius: 10px;
        border: 1px solid #4db0e6;
        background-color: #f0f8fc;
        color: #1d3c73;
    }

    .search-button {
        background-color: #1d3c73;
        color: white;
        border: none;
        padding: 8px 12px;
        border-radius: 10px;
    }

    .search-button:hover {
        background-color: #163055;
    }

  .table {
    width: 95%; /* o 100% para que ocupe todo el espacio */
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 0 15px rgba(77, 176, 230, 0.2);
    margin-left: auto;
    margin-right: auto;
    margin-top: 20px;
    font-size: 1.1rem;
}


.table th {
    background-color: #eaf6fb;
    color: #1d3c73;
    font-weight: bold;
    text-align: center;
    padding: 16px; /* Aumenta el padding */
}

.table td {
    text-align: center;
    padding: 14px; /* Aumenta el padding */
}

.table tbody tr:hover {
    background-color: #f0f9ff;
}


    .btn {
        border-radius: 10px;
        font-weight: 500;
        padding: 6px 14px;
        transition: all 0.3s ease;
        border: none;
    }

    .btn-sm {
        font-size: 0.85rem;
        padding: 5px 12px;
    }

    .btn-light {
        background-color: #f8f9fa;
        color: #1d3c73;
        border: 1px solid #ccc;
    }

    .btn-light:hover {
        background-color: #e2e6ea;
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

        .search-container {
            flex-direction: column;
            align-items: flex-start;
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
    box-shadow: inset 0 1px 3px rgba(0,0,0,0.1);
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

    <a href="pedidos.php"><i class="bi bi-basket-fill"></i> Pedidos</a>
    <a href="../productos/productos.php"><i class="bi bi-box-seam"></i> Productos</a>
    <a href="../clientes/cliente.php"><i class="bi bi-people-fill"></i> Clientes</a>
    <a href="../usuarios/usuarios.php"><i class="bi bi-people-fill"></i> Usuarios</a>
    <a href="../reportes/reportes.php"><i class="bi bi-graph-up"></i> Reportes</a>
    <a href="../ventas/historial_ventas.php"><i class="bi bi-clock-history"></i> Historial de Ventas</a>
    <a href="../estado_cuenta/estado_cuenta.php"><i class="bi bi-cash-coin"></i> Estado de Cuentas</a>
    <a href="../../cierre_sesion.php"><i class="bi bi-box-arrow-right"></i> Cerrar sesión</a>
</div>

<div class="main-content">
    <div class="card">
         <h2 style="font-size:30px;">Pedidos</h2>
    <form action="" method="post" class="mb-3 d-flex gap-2">
            <input style="width: 900px;"
                type="text" 
                name="b" 
                placeholder="Buscar por producto, código, categoría o tipo..." 
                class="form-control"
                value="<?php echo htmlspecialchars($busqueda); ?>"
                autocomplete="off"
            />
            <button type="submit" name="submit" class="btn btn-primary">Buscar</button>

            <?php if ($busqueda != ""): ?>
                <button type="submit" name="volver" class="btn btn-secondary">Volver</button>
            <?php endif; ?>
        </form>
       

        <table class="table table-hover table-bordered">
            <thead>
                <tr>
                    <th>USUARIO</th>
                    <th>FECHA Y HORA</th>
                    <th>TOTAL</th>
                    <th>ESTADO</th>
                    <th>D3TALLE</th>
                    
                </tr>
            </thead>
            <tbody>
                <?php while ($datos = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($datos['nombre']); ?></td>
                        <td><?php echo htmlspecialchars($datos['fecha_pedido']); ?></td>
                        <td><?php echo htmlspecialchars($datos['total']); ?></td>
                        <td>
                            <?php if ($datos['estado'] === 'entregado'): ?>
                                <span class="btn btn-success btn-sm disabled"><i class="bi bi-check-circle"></i> Entregado</span>
                            <?php elseif ($datos['estado'] === 'cancelado'): ?>
                                <span class="btn btn-danger btn-sm disabled"><i class="bi bi-x-circle"></i> Cancelado</span>
                            <?php else: ?>
                                <form action="cambiar_estado.php" method="post" style="display:inline;" onsubmit="return confirmarEntrega();">
                                    <input type="hidden" name="id_pedido" value="<?php echo $datos['id_pedido']; ?>">
                                    <input type="hidden" name="estado_actual" value="<?php echo $datos['estado']; ?>">
                                    <input type="hidden" name="accion" value="entregar">
                                    <button type="submit" class="btn btn-success btn-sm"><i class="bi bi-check-circle"></i> Entregar</button>
                                </form>
                                <form action="cambiar_estado.php" method="post" style="display:inline;" onsubmit="return confirmarCancelacion();">
                                    <input type="hidden" name="id_pedido" value="<?php echo $datos['id_pedido']; ?>">
                                    <input type="hidden" name="estado_actual" value="<?php echo $datos['estado']; ?>">
                
                                </form>
                            <?php endif; ?>
                        </td>
                        <td>
                            <form action="detalle_pedido.php" method="post">
                                <input type="hidden" name="id_pedido" value="<?php echo $datos['id_pedido']; ?>">
                                <button type="submit" class="btn btn-info btn-sm"><i class="bi bi-eye-fill"></i> Ver</button>
                            </form>
                        </td>
                        
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
function confirmarEntrega() {
    return confirm("¿Estás seguro de que este pedido fue entregado?");
}
function confirmarCancelacion() {
    return confirm("¿Estás seguro de cancelar este pedido?");
}
</script>

</body>
</html>
