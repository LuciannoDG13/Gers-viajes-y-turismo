<?php
session_start();
unset($_SESSION['carrito']);
header("Location: ver_carrito.php");
exit;
?>
