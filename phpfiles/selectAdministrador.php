<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$servername = "localhost";
$username = "Administrador";
$password = "Admin_1234";
$dbname = "pcsell_tienda";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

function getPedidos()
{
    global $conn;
    $sql = "SELECT * FROM pedidos";
    $result = $conn->query($sql);
    if ($result) {
        $pedidos = $result->fetch_all(MYSQLI_ASSOC);
        echo json_encode(['columns' => array_keys($pedidos[0]), 'data' => $pedidos]);
    } else {
        echo json_encode(['error' => 'Error al obtener pedidos']);
    }
}

function updateEstadoPedidos($estado, $id_pedido)
{
    global $conn;
    $sql = "UPDATE pedidos SET Estado_pedido = ? WHERE ID_pedido = ?";
    $stmt = $conn->prepare($sql);

    $stmt->bind_param("si", $estado, $id_pedido);

    if ($stmt->execute()) {
        return true;
    } else {
        return false;
    }
}

function updateEstadoDevoluciones($estado_devolucion, $id_devolucion)
{
    global $conn;
    $sql = "UPDATE devoluciones SET Estado_devolucion = ? WHERE ID_devolucion = ?";
    $stmt = $conn->prepare($sql);

    $stmt->bind_param("si", $estado_devolucion, $id_devolucion);

    if ($stmt->execute()) {
        return true;
    } else {
        return false;
    }
}

function getUsuarios()
{
    global $conn;
    $sql = "SELECT * FROM usuarios";
    $result = $conn->query($sql);
    if ($result) {
        $usuarios = $result->fetch_all(MYSQLI_ASSOC);
        echo json_encode(['columns' => array_keys($usuarios[0]), 'data' => $usuarios]);
    } else {
        echo json_encode(['error' => 'Error al obtener usuarios']);
    }
}

function getDevoluciones()
{
    global $conn;
    $sql = "SELECT * FROM devoluciones";
    $result = $conn->query($sql);
    if ($result) {
        $devoluciones = $result->fetch_all(MYSQLI_ASSOC);
        echo json_encode(['columns' => array_keys($devoluciones[0]), 'data' => $devoluciones]);
    } else {
        echo json_encode(['error' => 'Error al obtener devoluciones']);
    }
}

function getMensajes()
{
    global $conn;
    $sql = "SELECT * FROM cuestiones";
    $result = $conn->query($sql);
    if ($result) {
        $mensajes = $result->fetch_all(MYSQLI_ASSOC);
        echo json_encode(['columns' => array_keys($mensajes[0]), 'data' => $mensajes]);
    } else {
        echo json_encode(['error' => 'Error al obtener mensajes']);
    }
}

if (isset($_POST['action'])) {
    $action = $_POST['action'];
    switch ($action) {
        case 'getPedidos':
            getPedidos();
            break;
        case 'getUsuarios':
            getUsuarios();
            break;
        case 'getDevoluciones':
            getDevoluciones();
            break;
        case 'getMensajes':
            getMensajes();
            break;
        case 'updateEstadoPedidos':

            if (isset($_POST['estado']) && isset($_POST['id_pedido'])) {
                $estado = $_POST['estado'];
                $id_pedido = $_POST['id_pedido'];

                updateEstadoPedidos($estado, $id_pedido);
            } else {
                echo json_encode(['error' => 'Parámetros incompletos para actualizar el estado del pedido']);
            }
            break;

        case 'updateEstadoDevoluciones':
            if (isset($_POST['estado_devolucion']) && isset($_POST['id_devolucion'])) {
                $estado_devolucion = $_POST['estado_devolucion'];
                $id_devolucion = $_POST['id_devolucion'];

                updateEstadoDevoluciones($estado_devolucion, $id_devolucion);
            } else {
                echo json_encode(['error' => 'Parámetros incompletos para actualizar el estado de la devolución']);
            }
            break;
        default:
            echo json_encode(['error' => 'Acción no válida']);
            break;
    }
} else {
    echo json_encode(['error' => 'No se ha especificado ninguna acción']);
}
$conn->close();
?>