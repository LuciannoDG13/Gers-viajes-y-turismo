<?php
require __DIR__ . '/vendor/autoload.php';

use MercadoPago\SDK;

SDK::setAccessToken('APP_USR-756198707439439-061215-f03f13aa3e3423f4aad4f998d6675111-2490547367');

session_start();
$external_reference = $_SESSION['external_reference'] ?? '';

$url = "https://api.mercadopago.com/v1/payments/search?external_reference=$external_reference";
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Authorization: Bearer " . SDK::getAccessToken()
]);
$response = curl_exec($ch);
curl_close($ch);

$data = json_decode($response, true);

// Valores por defecto
$aprobado = false;
$cuotas = 1;
$monto_cuota = 0;

if (!empty($data['results'])) {
    foreach ($data['results'] as $pago) {
        if ($pago['status'] === 'approved') {
            $aprobado = true;
            $cuotas = $pago['installments'] ?? 1;
            $monto_cuota = $pago['transaction_details']['installment_amount'] ?? 0;

            // Guardar en sesión
            $_SESSION['cuotas'] = $cuotas;
            $_SESSION['monto_cuota'] = $monto_cuota;
            break;
        }
    }
}

// Responder con JSON
header('Content-Type: application/json');
echo json_encode([
    'pago_aprobado' => $aprobado,
    'cuotas' => $cuotas,
    'monto_cuota' => $monto_cuota
]);
