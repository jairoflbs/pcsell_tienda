<?php
// Verificar si se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $host = 'localhost';
    $dbname = 'pcsell_tienda';
    $username = 'Cliente';
    $password = 'Cliente_123';

    // Intenta realizar la conexión
    $conn = new mysqli($host, $username, $password, $dbname);

    // Verifica si hay errores de conexión
    if ($conn->connect_error) {
        die("Error de conexión a la base de datos: " . $conn->connect_error);
    }

    // Obtener los datos del formulario
    $username = $_POST["username"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $confirmPassword = $_POST["confirmPassword"];

    // Verificar si la contraseña y la confirmación coinciden
    if ($password !== $confirmPassword) {
        $mensaje = "La contraseña y la confirmación no coinciden.";
    } else {
        // Consulta SQL para insertar un nuevo usuario en la base de datos
        $sql = "INSERT INTO usuarios (Nombre_usuario, Correo_electronico, Contraseña_hash) VALUES (?, ?, ?)";

        // Preparar la consulta
        $stmt = $conn->prepare($sql);

        // Verificar si la preparación de la consulta fue exitosa
        if ($stmt) {
            // Hashear la contraseña antes de almacenarla en la base de datos (opcional pero recomendado)
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Enlazar los parámetros y ejecutar la consulta
            $stmt->bind_param("sss", $username, $email, $hashedPassword);
            $stmt->execute();

            // Verificar si se insertó el nuevo usuario correctamente
            if ($stmt->affected_rows > 0) {
                $mensaje = "Usuario registrado exitosamente.";
            } else {
                $mensaje = "Error al registrar el usuario. Por favor, inténtalo de nuevo.";
            }
        } else {
            $mensaje = "Error al preparar la consulta.";
        }

        // Cerrar la consulta
        $stmt->close();
    }

    // Cerrar la conexión
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de usuario</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="shortcut icon" type="image/x-icon" href="assets/img/pcsell.ico">
    <!-- CSS personalizado -->
    <style>
        .gradiente-personalizado-2 {
            /* Fallback para navegadores antiguos */
            background: #fccb90;

            /* Chrome 10-25, Safari 5.1-6 */
            background: -webkit-linear-gradient(to right, #ee7724, #d8363a, #dd3675, #b44593);

            /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */
            background: linear-gradient(to right, #ee7724, #d8363a, #dd3675, #b44593);
        }

        @media (min-width: 768px) {
            .gradiente-formulario {
                height: 100vh !important;
            }
        }

        @media (min-width: 769px) {
            .gradiente-personalizado-2 {
                border-top-right-radius: .3rem;
                border-bottom-right-radius: .3rem;
            }
        }

        /* Ajuste de tamaño de letra */
        body {
            font-size: 0.85rem;
        }
    </style>
</head>

<body>

    <section class="vh-100" style="background-color: #cae1fc;">
        <div class="container py-4 h-100">
            <div class="row d-flex justify-content-center align-items-center h-100">
                <div class="col col-xl-10">
                    <div class="card" style="border-radius: 1rem;">
                        <div class="row g-0">
                            <div class="col-md-6 col-lg-5 d-none d-md-block">
                                <img src="img/auth/registerphoto.png" alt="formulario de registro" class="img-fluid"
                                    style="border-radius: 1rem 0 0 1rem;" />
                            </div>
                            <div class="col-md-6 col-lg-7 d-flex align-items-center">
                                <div class="card-body p-4 p-lg-5 text-black">

                                    <form method="post">
                                        <div class="d-flex align-items-center mb-2 pb-1">
                                            <i class="fas fa-cubes fa-2x me-2" style="color: #ff6219;"></i>
                                            <span class="h1 fw-bold mb-0"><img src="img/auth/logo.png"
                                                    style="width: 40%;" /></span>
                                        </div>

                                        <h5 class="fw-normal mb-2 pb-2" style="letter-spacing: 0.5px;">Regístrese aquí</h5>

                                        <?php
                                        if (isset($mensaje)) {
                                            echo '<div class="alert alert-danger" role="alert">' . $mensaje . '</div>';
                                        }
                                        ?>

                                        <div data-mdb-input-init class="form-outline mb-3">
                                            <input type="text" id="username" name="username"
                                                class="form-control form-control-lg" required/>
                                            <label class="form-label" for="username">Nombre de usuario</label>
                                        </div>

                                        <div data-mdb-input-init class="form-outline mb-3">
                                            <input type="email" id="email" name="email"
                                                class="form-control form-control-lg" required/>
                                            <label class="form-label" for="email">Correo electrónico</label>
                                        </div>

                                        <div data-mdb-input-init class="form-outline mb-3">
                                            <input type="password" id="password" name="password"
                                                class="form-control form-control-lg" required/>
                                            <label class="form-label" for="password">Contraseña</label>
                                        </div>

                                        <div data-mdb-input-init class="form-outline mb-3">
                                            <input type="password" id="confirmPassword" name="confirmPassword"
                                                class="form-control form-control-lg" required/>
                                            <label class="form-label" for="confirmPassword">Confirmar contraseña</label>
                                        </div>

                                        <div class="pt-1 mb-3">
                                            <button data-mdb-button-init data-mdb-ripple-init
                                                class="btn btn-dark btn-lg btn-block" type="submit">Registrarse</button>
                                        </div>
                                        <p class="mb-5 pb-lg-2" style="color: #393f81;">¿Ya tienes una cuenta? <a href="login.php" style="color: #393f81;">Inicie sesión aquí</a></p>
                                        
                                    </form>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Bootstrap Bundle JS (Bootstrap JS + Popper.js) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <!-- JavaScript personalizado -->
    <script>
        // Agrega tu JavaScript personalizado aquí
    </script>
</body>

</html>
