<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Incluir PHPMailer
require 'PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer-master/src/SMTP.php';
require 'PHPMailer-master/src/Exception.php';

// Conexión a la base de datos
$conn = mysqli_connect("localhost", "root", "", "viajes_turismo");
if (!$conn) {
    die("Error de conexión: " . mysqli_connect_error());
}

$id_pedido = isset($_GET['id_pedido']) ? intval($_GET['id_pedido']) : 0;
$id_pago = isset($_GET['id_pago']) ? intval($_GET['id_pago']) : 0;

if ($id_pedido <= 0 || $id_pago <= 0) {
    die("Faltan datos de pedido o pago.");
}
// Obtener total y detalle desde la venta
$query_detalle = "
    SELECT v.total, GROUP_CONCAT(CONCAT(p.nombre_producto, ' x', dv.cantidad) SEPARATOR ', ') AS detalle
    FROM ventas v
    JOIN detalle_ventas dv ON v.id_venta = dv.id_venta
    JOIN productos p ON dv.id_producto = p.id_producto
    WHERE v.id_pedido = $id_pedido
    GROUP BY v.id_venta
";

$res_detalle = mysqli_query($conn, $query_detalle);

if (!$res_detalle || mysqli_num_rows($res_detalle) === 0) {
    die("No se pudo obtener el detalle de la venta.");
}

$venta = mysqli_fetch_assoc($res_detalle);
$total = $venta['total'];
$detalle = $venta['detalle'];


// Buscar email del cliente desde la tabla usuarios (vía pedidos)
$query = "
    SELECT u.email, u.id_usuario
    FROM pedidos p
    JOIN usuarios u ON p.id_usuario = u.id_usuario
    WHERE p.id_pedido = $id_pedido
    LIMIT 1
";

$result = mysqli_query($conn, $query);

if (!$result || mysqli_num_rows($result) == 0) {
    die("No se encontró el email del cliente.");
}

$usuario = mysqli_fetch_assoc($result);
$email_cliente = $usuario['email'];
$id_destino_email = $usuario['id_usuario'];

// Configurar y enviar correo con PHPMailer
$mail = new PHPMailer(true);
try {
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'gersviajesturismo@gmail.com';      // Cuenta desde la que se envía
    $mail->Password = 'rqdo oxtl vjsl qony'; // Contraseña real o clave de aplicación de esa cuenta
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    $mail->setFrom('gersviajesturismo@gmail.com', 'Agencia de Viajes');
    $mail->addAddress($email_cliente);

    $mail->isHTML(true);
    $asunto = 'Confirmación de tu compra turística';
    $cuerpo = "Gracias por tu compra. Has reservado: $detalle. Total pagado: $" . number_format($total, 2);

    $mail->Subject = $asunto;
    $mail->Body = $cuerpo;
    $mail->AltBody = strip_tags($cuerpo);

    $mail->send();
    $estado_envio = "enviado";
} catch (Exception $e) {
    $estado_envio = "fallido: " . $mail->ErrorInfo;
}

// Insertar registro en log de notificaciones (consulta directa)
$asunto_sql = mysqli_real_escape_string($conn, $asunto);
$cuerpo_sql = mysqli_real_escape_string($conn, $cuerpo);
$estado_sql = mysqli_real_escape_string($conn, $estado_envio);

$insert_sql = "
    INSERT INTO log_notificaciones_email 
    (id_pedido, id_pago, id_email_destino, asunto, cuerpo_email, fecha_envio, estado_envio)
    VALUES 
    ($id_pedido, $id_pago, $id_destino_email, '$asunto_sql', '$cuerpo_sql', NOW(), '$estado_sql')
";

if (mysqli_query($conn, $insert_sql)) {
    echo "✅ Correo $estado_envio y registrado correctamente.";
} else {
    echo "❌ Error al registrar el log: " . mysqli_error($conn);
}

mysqli_close($conn);
?>


