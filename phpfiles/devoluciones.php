<?php
session_start();

$servername = "localhost";
$username = "Cliente"; 
$password = "Cliente_123"; 
$dbname = "pcsell_tienda"; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombreProducto = $_POST['nombreProducto'];
    $cantidadProducto = $_POST['cantidadProducto'];
    $motivoDevolucion = $_POST['motivoDevolucion'];
    $mejoras = $_POST['mejoras'];
    $idUsuario = $_SESSION['user_id'];
    $idPedido = $_POST['idPedido'];
    $idProducto= $_POST['idDetalle'];

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Error de conexión a la base de datos: " . $conn->connect_error);
    }

    $sql_insert = "INSERT INTO devoluciones (ID_pedido, ID_usuario, Fecha_hora_devolucion, Motivo_devolucion, Estado_devolucion, Detalles_adicionales, ID_producto)
            VALUES (?, ?, NOW(), ?, 'Pendiente', ?, ?)";

    $stmt_insert = $conn->prepare($sql_insert);

    if ($stmt_insert) {
        $stmt_insert->bind_param("iissi", $idPedido, $idUsuario, $motivoDevolucion, $mejoras, $idProducto);

        if ($stmt_insert->execute()) {
          
            $sql_update = "UPDATE detalles_pedido SET Estado_producto_pedido = 'En proceso de devolucion' WHERE ID_detalle = ?";

           
            $stmt_update = $conn->prepare($sql_update);

            if ($stmt_update) {
               
                $stmt_update->bind_param("i", $idProducto);

               
                if ($stmt_update->execute()) {
                    echo "Devolución registrada exitosamente";
                } else {
                    echo "Error al actualizar el estado del producto: " . $stmt_update->error;
                }

                
                $stmt_update->close();
            } else {
                echo "Error al preparar la consulta de actualización: " . $conn->error;
            }
        } else {
            echo "Error al registrar la devolución: " . $stmt_insert->error;
        }

        
        $stmt_insert->close();
    } else {
        echo "Error al preparar la consulta de inserción: " . $conn->error;
    }

    $conn->close();
} else {
    echo "Método no permitido";
}
?>