<?php

$servername = "localhost";
$username = "Cliente"; 
$password = "Cliente_123"; 
$dbname = "pcsell_tienda";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

$sql = "SELECT ID_producto, Nombre_producto, Descripcion, Precio, Categoria, Imagen_producto, Disponibilidad_stock FROM productos";

$result = $conn->query($sql);

$productos = array(); 

if ($result->num_rows > 0) {
    
    while($row = $result->fetch_assoc()) {
        $productos[] = $row;
    }
} else {
    echo json_encode(array("message" => "No se encontraron productos."));
}

echo json_encode($productos);

$conn->close();
?>
