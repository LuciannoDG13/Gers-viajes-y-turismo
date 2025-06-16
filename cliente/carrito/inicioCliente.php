<?php
require __DIR__ . '/vendor/autoload.php';
session_start();
require '../../db.php';

// Seteo token MercadoPago
MercadoPago\SDK::setAccessToken('APP_USR-756198707439439-061215-f03f13aa3e3423f4aad4f998d6675111-2490547367');

if (!isset($_SESSION['id_usuario'])) {
    header('Location: ../../login.php');
    exit();
}

$id_usuario = $_SESSION['id_usuario'];

// Obtengo crédito usuario
$sql = "SELECT credito FROM usuarios WHERE id_usuario = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$stmt->bind_result($credito_usuario);
$stmt->fetch();
$stmt->close();

if (!isset($_SESSION['carrito'])) {
    $_SESSION['carrito'] = [];
}

// Manejo toggle del uso de crédito con botón
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['toggle_credito'])) {
    if (isset($_SESSION['usar_credito']) && $_SESSION['usar_credito'] === true) {
        $_SESSION['usar_credito'] = false;
    } else {
        $_SESSION['usar_credito'] = true;
    }
    $usar_credito = $_SESSION['usar_credito'];
} else if (isset($_SESSION['usar_credito'])) {
    $usar_credito = $_SESSION['usar_credito'];
} else {
    $usar_credito = false;
}

$total_original = 0;
$items = [];

if (!empty($_SESSION['carrito'])) {
    foreach ($_SESSION['carrito'] as $item) {
        $total_original += $item['precio'] * $item['cantidad'];
    }
}

// Calculo monto a pagar considerando crédito
if ($usar_credito) {
    if ($credito_usuario >= $total_original) {
        $monto_a_pagar = 0;
    } else {
        $monto_a_pagar = $total_original - $credito_usuario;
    }
} else {
    $monto_a_pagar = $total_original;
}

// Creo preferencia sólo si monto a pagar > 0
$preference = null;
if ($monto_a_pagar > 0 && !empty($_SESSION['carrito'])) {
    $preference = new MercadoPago\Preference();

    foreach ($_SESSION['carrito'] as $item) {
        $mp_item = new MercadoPago\Item();
        $mp_item->title = $item['nombre'];
        $mp_item->quantity = $item['cantidad'];
        if ($usar_credito && $credito_usuario < $total_original) {
            $proporcion = $monto_a_pagar / $total_original;
            $mp_item->unit_price = round($item['precio'] * $proporcion, 2);
        } else {
            $mp_item->unit_price = $item['precio'];
        }
        $items[] = $mp_item;
    }

    $external_reference = uniqid('compra_', true);
    $preference->external_reference = $external_reference;
    $_SESSION['external_reference'] = $external_reference;

    $preference->items = $items;
    $preference->payment_methods = [
        "installments" => 6
    ];

    $preference->save();
}

$_SESSION['total_original'] = $total_original;
$_SESSION['monto_a_pagar'] = $monto_a_pagar;

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Pago - GERS Viajes y Turismo</title>
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
            top: 0;
            left: 0;
            right: 0;
            height: 60px;
            display: flex;
            align-items: center;
            background-color: #4db0e6;
            padding: 0 15px;
            justify-content: space-between;
            z-index: 9999;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
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

        main.container {
            max-width: 600px;
            margin: auto;
            padding: 40px 20px;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 0 15px rgba(77, 176, 230, 0.3);
            text-align: center;
        }

        .btn-cerrar-sesion {
            background-color: #1d3c73;
            color: white;
            border-radius: 8px;
            padding: 10px 20px;
            font-weight: 600;
            border: none;
            transition: background-color 0.3s;
            cursor: pointer;
            margin-bottom: 20px;
        }

        .btn-cerrar-sesion:hover {
            background-color: #4db0e6;
            color: #163055;
        }

        .cho-container {
            margin-top: 30px;
        }

        h1 {
            color: #1d3c73;
            margin-bottom: 0;
        }

        p {
            color: #4a4a4a;
            margin-top: 5px;
        }

        .mensaje-vacio {
            color: #a94442;
            background-color: #f2dede;
            padding: 15px;
            border-radius: 8px;
            margin-top: 20px;
            font-weight: 600;
        }
    </style>
</head>

<body>

    <header>
        <div class="header-logo">
            <img src="../../img/logoo.png" alt="Logo GERS Viajes" />
        </div>
        <div class="header-title">
            <span class="gers">GERS</span>
            <span class="turismo">Viajes y Turismo</span>
        </div>
        <nav class="nav-links">
    <a href="../../cliente/catalogo/productos.php"><i class="bi bi-box-seam"></i> Catálogo</a>
    <a href="../../cliente/carrito/ver_carrito.php"><i class="bi bi-cart"></i> Mi carrito</a>
    <a href="../../cliente/catalogo/mispedidos.php"><i class="bi bi-receipt"></i> Mis pedidos</a>
    <a href="../../cierre_sesion.php"><i class="bi bi-box-arrow-right"></i> Cerrar sesión</a>
</nav>

    </header>
    <br><br><br>
    <button onclick="location.href='../../cliente/catalogo/productos.php'" class="btn-cerrar-sesion">
            Cancelar compra
        </button>
    <main class="container">

        
        <form method="POST" action="" id="form-credito">
            <input type="hidden" name="toggle_credito" value="1" />
            <div style="margin-bottom: 20px; text-align: left;">
                <center>
                <strong>Crédito disponible:</strong> $<?= number_format($credito_usuario, 2, ',', '.') ?><br>
                <button type="submit" 
                    style="
                        padding: 8px 20px;
                        font-weight: 600;
                        border-radius: 6px;
                        border: none;
                        cursor: pointer;
                        background-color: <?= $usar_credito ? '#4db0e6' : '#d3d3d3' ?>;
                        color: <?= $usar_credito ? '#fff' : '#444' ?>;
                        transition: background-color 0.3s;
                    "
                    onmouseover="this.style.backgroundColor='<?= $usar_credito ? '#3a95d1' : '#bfbfbf' ?>'"
                    onmouseout="this.style.backgroundColor='<?= $usar_credito ? '#4db0e6' : '#d3d3d3' ?>'"
                >
                    <?= $usar_credito ? 'Crédito activado para esta compra' : 'Usar mi crédito para esta compra' ?>
                </button>
                </center>
            </div>
        </form>

        

        <h1>Pago con Mercado Pago</h1>
        <p>Por favor, complete el pago de los productos de su carrito.</p>

        <?php if (!empty($_SESSION['carrito'])): ?>
            <?php if ($monto_a_pagar == 0): ?>
                <div style="padding: 20px; background-color: #d9edf7; border: 1px solid #bce8f1; border-radius: 8px;">
                    <h4>Tu crédito cubre el total de la compra.</h4>
                    <p>Podes confirmar la compra sin realizar pago adicional.</p>
                </div>
            <?php else: ?>
                <center>
                    <div class="cho-container"></div>
                </center>
            <?php endif; ?>

            <div
                style="margin-top: 30px; padding: 20px; background-color: #d9edf7; border: 1px solid #bce8f1; border-radius: 8px;">
                <h4>¿Ya realizaste el pago?</h4>
                <p>Para completar tu compra, hacé clic en el botón. Si no lo hacés, no se confirmará tu pedido.</p>


                <form id="form-confirmar-compra" action="procesar_compra.php" method="POST"
                    onsubmit="return verificarPagoAntesDeEnviar(event)">

                    <input type="hidden" name="enviar_compra" value="1">
                    <input type="hidden" name="external_reference" value="<?= $_SESSION['external_reference'] ?? '' ?>">
                    <input type="hidden" name="usar_credito" value="<?= $usar_credito ? '1' : '0' ?>">
                    <input type="hidden" name="monto_a_pagar" value="<?= $monto_a_pagar ?>">

                    <?php foreach ($_SESSION['carrito'] as $index => $item): ?>
                        <input type="hidden" name="carrito[<?= $index ?>][id]" value="<?= $item['id'] ?>">
                        <input type="hidden" name="carrito[<?= $index ?>][nombre]" value="<?= $item['nombre'] ?>">
                        <input type="hidden" name="carrito[<?= $index ?>][precio]" value="<?= $item['precio'] ?>">
                        <input type="hidden" name="carrito[<?= $index ?>][cantidad]" value="<?= $item['cantidad'] ?>">
                    <?php endforeach; ?>

                    <button type="submit" class="btn btn-primary mt-3">Confirmar y continuar</button>
                </form>

                <div id="alerta-validacion" class="alert alert-warning alert-dismissible fade d-none" role="alert">
                    <span id="mensaje-alerta"></span>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
                </div>

                <script>
                    function verificarPagoAntesDeEnviar(event) {
                        event.preventDefault();

                        const montoAPagar = parseFloat(<?= json_encode($monto_a_pagar) ?>);
                        if (montoAPagar === 0) {
                            document.getElementById("form-confirmar-compra").submit();
                            return false;
                        }

                        fetch("validar_pago.php")
                            .then(response => response.json())
                            .then(data => {
                                if (data.pago_aprobado) {
                                    document.getElementById("form-confirmar-compra").submit();
                                } else {
                                    mostrarAlerta("⚠️ Aún no se registró un pago. Por favor, realizalo antes de continuar.");
                                }
                            })
                            .catch(error => {
                                mostrarAlerta("⚠️ Error al validar el pago. Intentalo nuevamente.");
                                console.error("Error de validación:", error);
                            });

                        return false;
                    }

                    function mostrarAlerta(mensaje) {
                        const alerta = document.getElementById("alerta-validacion");
                        const mensajeSpan = document.getElementById("mensaje-alerta");

                        mensajeSpan.textContent = mensaje;
                        alerta.classList.remove("d-none");
                        alerta.classList.add("show");

                        setTimeout(() => {
                            alerta.classList.remove("show");
                            alerta.classList.add("d-none");
                        }, 5000);
                    }
                </script>
            </div>

        <?php else: ?>
            <div class="mensaje-vacio">Tu carrito está vacío. Por favor, agrega productos antes de pagar.</div>
            <a href="../../cliente/catalogo/productos.php" class="btn btn-primary mt-3">Volver al catálogo</a>
        <?php endif; ?>

    </main>

    <script src="https://sdk.mercadopago.com/js/v2"></script>
    <script>
        <?php if (!empty($_SESSION['carrito']) && $monto_a_pagar > 0): ?>
            const mp = new MercadoPago('APP_USR-f67a3754-dd13-4e16-994e-7e070a7c9ccf', {
                locale: 'es-AR'
            });

            mp.checkout({
                preference: {
                    id: '<?= $preference->id ?>'
                },
                render: {
                    container: '.cho-container',
                    label: 'Pagar'
                }
            });
        <?php endif; ?>
    </script>

    <script src="/viajes turismo/js/bootstrap.bundle.min.js"></script>
</body>

</html>