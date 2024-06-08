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

$query = "SELECT Nombre_usuario, Correo_electronico, Rol_usuario FROM usuarios WHERE ID_usuario= ?";
$stmt_query = $conn->prepare($query);

if ($stmt_query) {
    $stmt_query->bind_param("i", $userId);
    $stmt_query->execute();
    $result = $stmt_query->get_result();

    if ($result->num_rows > 0) {
        $usuario = $result->fetch_assoc();

        $data = array(
            "nombreUsuario" => $usuario['Nombre_usuario'],
            "correo" => $usuario['Correo_electronico'],
            "rol" => $usuario['Rol_usuario']
        );

       
        header('Content-Type: application/json');
        echo json_encode($data);
    } else {
        echo "No se encontraron resultados.";
    }

    $stmt_query->close();
} else {
    echo "Error al preparar la consulta.";
}

$conn->close();
?>