<?php
$conn = new mysqli("localhost", "root", "", "viajes_turismo");

if ($conn->connect_error) {
  die("Conexión fallida: " . $conn->connect_error);
}
?>
