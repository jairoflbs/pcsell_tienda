<?php

if (isset($_GET['id'])) {

    $servername = "localhost";
    $username = "Cliente"; 
    $password = "Cliente_123"; 
    $dbname = "pcsell_tienda";


    $conn = new mysqli($servername, $username, $password, $dbname);


    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }

    $idProducto = $_GET['id'];

   
    $sql = "SELECT Nombre_producto, Descripcion, Precio, Categoria, Imagen_producto, Disponibilidad_stock FROM productos WHERE ID_Producto= $idProducto";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {

        $row = $result->fetch_assoc();

        $producto = array(
            
            'nombre' => $row['Nombre_producto'],
            'descripcion' => $row['Descripcion'], 
            'precio' => $row['Precio'],
            'categoria' => $row['Categoria'],
            'imagen' => $row['Imagen_producto'],
            'stock' => $row['Disponibilidad_stock'],
            'id'=> $idProducto
            
        );

       
        header('Content-Type: application/json');
        echo json_encode($producto);
    } else {

        http_response_code(404);
        echo json_encode(array('error' => 'Producto no encontrado'));
    }


    $conn->close();
} else {
    
    http_response_code(400);
    echo json_encode(array('error' => 'ID del producto no proporcionada'));
}
?>