<?php
require('../../db.php');

if (isset($_POST['id_pedido']) && isset($_POST['estado_actual']) && isset($_POST['accion'])) {
    $id_pedido = $_POST['id_pedido'];
    $estado_actual = strtolower($_POST['estado_actual']);
    $accion = strtolower($_POST['accion']); // 'entregar' o 'cancelar'

    // No permitir cambios si ya está entregado o cancelado
    if ($estado_actual === 'entregado') {
        echo "Este pedido ya fue entregado y no se puede modificar.";
        exit();
    } elseif ($estado_actual === 'cancelado') {
        echo "Este pedido ya fue cancelado y no se puede modificar.";
        exit();
    }

    if ($accion === 'entregar') {
        $nuevo_estado = 'entregado';

        // Cambiar estado a entregado
        $sql_update = "UPDATE pedidos SET estado = '$nuevo_estado' WHERE id_pedido = $id_pedido";
        if (mysqli_query($conn, $sql_update)) {
            // Obtener datos del pedido
            $sql_datos = "SELECT id_usuario, total FROM pedidos WHERE id_pedido = $id_pedido";
            $resultado = mysqli_query($conn, $sql_datos);
            $fila = mysqli_fetch_assoc($resultado);
            $id_usuario = $fila['id_usuario'];
            $total = $fila['total'];

            // Verificar si ya está en pedidos_entregados
            $sql_verificar = "SELECT * FROM pedidos_entregados WHERE id_pedido = $id_pedido";
            $resultado_verificar = mysqli_query($conn, $sql_verificar);

            if (mysqli_num_rows($resultado_verificar) == 0) {
                // Insertar en pedidos_entregados
                $fecha_entrega = date("Y-m-d H:i:s");
                $sql_insertar = "INSERT INTO pedidos_entregados (id_pedido, id_usuario, fecha_entrega, total)
                                VALUES ($id_pedido, $id_usuario, '$fecha_entrega', $total)";
                mysqli_query($conn, $sql_insertar);
            }

            header("Location: pedidos.php");
            exit();
        } else {
            echo "Error al cambiar el estado a entregado.";
        }
    } elseif ($accion === 'cancelar') {
        $nuevo_estado = 'cancelado';

        // Cambiar estado a cancelado
        $sql_update = "UPDATE pedidos SET estado = '$nuevo_estado' WHERE id_pedido = $id_pedido";
        if (mysqli_query($conn, $sql_update)) {
            header("Location: pedidos.php");
            exit();
        } else {
            echo "Error al cancelar el pedido.";
        }
    } else {
        echo "Acción no válida.";
    }
}
?>
