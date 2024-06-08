<?php
$servername = "localhost";
$username = "Cliente"; 
$password = "Cliente_123"; 
$dbname = "pcsell_tienda"; 

$data = json_decode($_POST['pedido'], true);

$nombre = $data['nombre'];
$apellido = $data['apellido'];
$nombreUsuario = $data['nombreUsuario'];
$direccion = $data['direccion'];
$email = $data['email'];
$pais = $data['pais'];
$nombreTarjeta = $data['nombreTarjeta'];
$numeroTarjeta = $data['numeroTarjeta'];
$fechaCaducidad = $data['fechaCaducidad'];
$cvv = $data['cvv'];
$totalPedido = $data['totalPedido'];
$productos = $data['productos'];

try {

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        throw new Exception("Conexión fallida: " . $conn->connect_error);
    }

    $conn->begin_transaction();

    $sql_pedido = "INSERT INTO pedidos (ID_usuario, Fecha_hora_pedido, Estado_pedido, Nombre, Apellido, Nombre_usuario, Correo, Direccion, Pais, Nombre_tarjeta, Numero_tarjeta, Vencimiento, CVV)
               VALUES (?, NOW(), 'Pendiente', ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt_pedido = $conn->prepare($sql_pedido);
    $stmt_pedido->bind_param("issssssssss", $id_usuario, $nombre, $apellido, $nombreUsuario, $email, $direccion, $pais, $nombreTarjeta, $numeroTarjeta, $fechaCaducidad, $cvv);

    $sql_id_usuario = "SELECT ID_usuario FROM usuarios WHERE Nombre_usuario = ?";
    $stmt_id_usuario = $conn->prepare($sql_id_usuario);
    $stmt_id_usuario->bind_param("s", $nombreUsuario);
    $stmt_id_usuario->execute();
    $stmt_id_usuario->bind_result($id_usuario);
    $stmt_id_usuario->fetch();
    $stmt_id_usuario->close();

    if (!$id_usuario) {
        throw new Exception("Usuario no encontrado");
    }

    if (!$stmt_pedido->execute()) {
        throw new Exception("Error al insertar en la tabla pedidos: " . $stmt_pedido->error);
    }

    $id_pedido = $stmt_pedido->insert_id;


    $sql_detalle = "INSERT INTO detalles_pedido (ID_pedido, ID_producto, Cantidad, Precio_unitario, Estado_producto_pedido)
                    VALUES (?, ?, ?, ?, 'Pendiente')";

    $stmt_detalle = $conn->prepare($sql_detalle);
    $stmt_detalle->bind_param("iiid", $id_pedido, $id_producto, $cantidad, $precio_unitario);

    foreach ($productos as $producto) {
        $id_producto = $producto['id'];
        $cantidad = $producto['cantidad'];
        $precio_unitario = $producto['precio'];

        if (!$stmt_detalle->execute()) {
            throw new Exception("Error al insertar en la tabla detalles_pedido: " . $stmt_detalle->error);
        }
    }

    $conn->commit();

    $conn->close();

    echo "Pedido insertado con éxito";
} catch (Exception $e) {

    $conn->rollback();

    echo "Error: " . $e->getMessage();
}
?>
