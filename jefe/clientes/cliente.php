
<?php
session_start();
require('../../db.php');

$busqueda = "";

if (isset($_POST['submit']) && !empty($_POST['b'])) {
    $busqueda = $_POST['b'];
}

if ($busqueda != "") {
    $sql = "SELECT * FROM clientes
            INNER JOIN usuarios on usuarios.id_usuario=clientes.id_usuario 
            WHERE usuarios.nombre LIKE '%$busqueda%'";
} else {
    $sql = "SELECT * FROM clientes
            INNER JOIN usuarios ON usuarios.id_usuario=clientes.id_usuario";
}

$result = $conn->query($sql);
?>




<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <title>Clientes</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="/viajes turismo/css/bootstrap.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
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

.btn-editar, .btn-eliminar {
    border: none;
    border-radius: 8px;
    padding: 6px 12px;
    color: white;
    text-decoration: none;
    margin: 2px;
    display: inline-block;
}

        .btn-editar {
            background-color: #1d3c73;
        }
        .btn-editar:hover {
            background-color: #163055;
        }
    </style>
</head>
<body>

<!-- Menú lateral -->
   <div class="sidebar">
    <a href="../principal.php" class="logo-link">
    <img style="width: 200px; margin-left:-20px; margin-bottom: 15px;" src="../../img/logoo.png" alt="Logo">
</a>

    <a href="../pedidos/pedidos.php"><i class="bi bi-basket-fill"></i> Pedidos</a>
    <a href="../productos/productos.php"><i class="bi bi-box-seam"></i> Productos</a>
    <a href="cliente.php"><i class="bi bi-people-fill"></i> Clientes</a>
    <a href="../usuarios/usuarios.php"><i class="bi bi-people-fill"></i> Usuarios</a>
    <a href="../reportes/reportes.php"><i class="bi bi-graph-up"></i> Reportes</a>
    <a href="../ventas/historial_ventas.php"><i class="bi bi-clock-history"></i> Historial de Ventas</a>
    <a href="../estado_cuenta/estado_cuenta.php"><i class="bi bi-cash-coin"></i> Estado de Cuentas</a>
    <a href="../../cierre_sesion.php"><i class="bi bi-box-arrow-right"></i> Cerrar sesión</a>
</div>


<!-- Contenido principal -->
<div class="main-content">
    <div class="card">
      <h2 style="font-size:30px;">Clientes</h2>
   <form action="" method="post" class="mb-3 d-flex gap-2">
            <input style="width: 900px;"
                type="text" 
                name="b" 
                placeholder="Buscar por nombre del cliente" 
                class="form-control"
                value="<?php echo htmlspecialchars($busqueda); ?>"
                autocomplete="off"
            />
            <button type="submit" name="submit" class="btn btn-primary">Buscar</button>

            <?php if ($busqueda != ""): ?>
                <button type="submit" name="volver" class="btn btn-secondary">Volver</button>
            <?php endif; ?>
        </form>
       
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>NOMBRE</th>
                    <th>DIRECCIÓN</th>
                    <th>TELEFONO</th>
                    <th>DNI</th>
                    <th>FECHA NACIMIENTO</th>
                    <th>ACCIÓN</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['nombre']) ?></td>
                        <td><?= htmlspecialchars($row['direccion']) ?></td>
                        <td><?= htmlspecialchars($row['telefono']) ?></td>
                        <td><?= htmlspecialchars($row['dni']) ?></td>
                        <td><?= htmlspecialchars($row['fecha_nacimiento']) ?></td>
                        <td>
                            <a href="form_modificar.php?id_cliente=<?= $row['id_cliente'] ?>" class="btn-editar">Modificar</a>
                            
                      
                    </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

    </div>
</div>

</body>
</html>
