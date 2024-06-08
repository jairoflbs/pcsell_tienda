<?php
$servername = "localhost";
$username = "Cliente"; 
$password = "Cliente_123"; 
$dbname = "pcsell_tienda"; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nombre = $_POST['name'];
    $correo_electronico = $_POST['email'];
    $asunto = $_POST['subject'];
    $mensaje = $_POST['message'];

    $sql = "INSERT INTO cuestiones (Nombre, Correo_electronico, Asunto, Mensaje) VALUES (?, ?, ?, ?)";
    
    $mysqli = new mysqli($servername, $username, $password, $dbname);
    
    if ($mysqli->connect_error) {
        die("Error de conexión: " . $mysqli->connect_error);
    }

    if ($stmt = $mysqli->prepare($sql)) {

        $stmt->bind_param("ssss", $nombre, $correo_electronico, $asunto, $mensaje);


        if ($stmt->execute()) {

            echo json_encode(["status" => "success"]);
        } else {

            echo json_encode(["status" => "error", "message" => $stmt->error]);
        }

        $stmt->close();
    } else {
       
        echo json_encode(["status" => "error", "message" => $mysqli->error]);
    }

    $mysqli->close();
} else {
    echo json_encode(["status" => "error", "message" => "Método de solicitud no válido."]);
}
?>