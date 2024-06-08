<?php
session_start();

$servername = "localhost";
$username = "Cliente"; 
$password = "Cliente_123"; 
$dbname = "pcsell_tienda";

$userId = $_SESSION['user_id'];

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Error de conexión a la base de datos: " . $conn->connect_error);
}

$queryPedido = "SELECT ID_pedido, Fecha_hora_pedido, Estado_pedido, Direccion FROM pedidos WHERE ID_usuario = ?";
$stmt_queryPedido = $conn->prepare($queryPedido);

if ($stmt_queryPedido) {
    $stmt_queryPedido->bind_param("i", $userId);
    $stmt_queryPedido->execute();
    $resultPedido = $stmt_queryPedido->get_result();

    $pedidos = array();

    while ($pedido = $resultPedido->fetch_assoc()) {
        
        $pedidoId = $pedido['ID_pedido'];
        $queryDetalles = "SELECT dp.Cantidad, dp.ID_detalle, dp.Estado_producto_pedido, p.Nombre_producto, p.Precio, p.Imagen_producto 
                          FROM detalles_pedido dp 
                          INNER JOIN productos p ON dp.ID_producto = p.ID_producto
                          WHERE dp.ID_pedido = ?";
        $stmt_queryDetalles = $conn->prepare($queryDetalles);
        $stmt_queryDetalles->bind_param("i", $pedidoId);
        $stmt_queryDetalles->execute();
        $resultDetalles = $stmt_queryDetalles->get_result();

        $detalles = array();
        while ($detalle = $resultDetalles->fetch_assoc()) {
            
            $detalles[] = array(
                "idDetalle" => $detalle['ID_detalle'],
                "nombreProducto" => $detalle['Nombre_producto'],
                "cantidad" => $detalle['Cantidad'],
                "precio" => $detalle['Precio'],
                "imagenProducto" => $detalle['Imagen_producto'],
                "estadoProductoPedido" => $detalle['Estado_producto_pedido'] 
            );
        }

        
        $pedidos[] = array(
            "idPedido" => $pedido['ID_pedido'],
            "fecha" => $pedido['Fecha_hora_pedido'],
            "estadoPedido" => $pedido['Estado_pedido'],
            "direccion" => $pedido['Direccion'],
            "detalles" => $detalles
        );

        $stmt_queryDetalles->close();
    }

  
    header('Content-Type: application/json');
    echo json_encode($pedidos);

    $stmt_queryPedido->close();
} else {
    echo "Error al preparar la consulta de pedidos.";
}

$conn->close();
?>
