<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

  $host = 'localhost';
  $dbname = 'pcsell_tienda';
  $username = 'Inicio_sesion';
  $password = 'InicioSesion_123';

  $conn = new mysqli($host, $username, $password, $dbname);

  if ($conn->connect_error) {
    die("Error de conexión a la base de datos: " . $conn->connect_error);
  }

  $email = $_POST["email"];
  $password = $_POST["password"];

  $sql = "SELECT ID_usuario, Correo_electronico, Contraseña_hash FROM usuarios WHERE Correo_electronico=?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("s", $email);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $hashedPassword = $row['Contraseña_hash'];

    // Verificar si la contraseña proporcionada coincide con la contraseña encriptada
    if (password_verify($password, $hashedPassword)) {
      $userId = $row['ID_usuario'];
      $rol_usuario = $row['Rol_usuario'];
      $_SESSION['user_id'] = $userId;
      $_SESSION['rol_usuario'] = $rol_usuario;
      echo '<script>
        localStorage.removeItem("carrito");
        window.location.href = "index.html?userId=' . $userId . '";
      </script>';

      exit();
    } else {
      $mensaje = "Usuario y/o contraseña incorrectos.";
    }
  } else {
    $mensaje = "Usuario y/o contraseña incorrectos.";
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
  <title>Iniciar sesión</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
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
  </style>
</head>

<body>

  <?php if (isset($_SESSION['rol_usuario'])): ?>
    <script>
      const rolUsuario = "<?php echo $_SESSION['rol_usuario']; ?>";
      sessionStorage.setItem('rolUsuario', rolUsuario);
      console.log("Rol del usuario:", rolUsuario); 
    </script>
  <?php endif; ?>

  <section class="vh-100" style="background-color: #fcdfca;">
    <div class="container py-5 h-100">
      <div class="row d-flex justify-content-center align-items-center h-100">
        <div class="col col-xl-10">
          <div class="card" style="border-radius: 1rem;">
            <div class="row g-0">
              <div class="col-md-6 col-lg-5 d-none d-md-block">
                <img src="img/auth/authphoto.png" alt="formulario de inicio de sesión" class="img-fluid"
                  style="border-radius: 1rem 0 0 1rem;" />
              </div>
              <div class="col-md-6 col-lg-7 d-flex align-items-center">
                <div class="card-body p-4 p-lg-5 text-black">

                  <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">

                    <div class="d-flex align-items-center mb-3 pb-1">
                      <i class="fas fa-cubes fa-2x me-3" style="color: #ff6219;"></i>
                      <span class="h1 fw-bold mb-0"><img src="img/auth/logo.png" style="width: 40%;" /></span>
                    </div>

                    <h5 class="fw-normal mb-3 pb-3" style="letter-spacing: 1px;">Inicia sesión en tu cuenta</h5>

                    <?php if (isset($mensaje)) { ?>
                      <div class="alert alert-danger" role="alert">
                        <?php echo $mensaje; ?>
                      </div>
                    <?php } ?>

                    <div data-mdb-input-init class="form-outline mb-4">
                      <input type="email" id="email" name="email" class="form-control form-control-lg" required />
                      <label class="form-label" for="email">Correo electrónico</label>
                    </div>

                    <div data-mdb-input-init class="form-outline mb-4">
                      <input type="password" id="password" name="password" class="form-control form-control-lg"
                        required />
                      <label class="form-label" for="password">Contraseña</label>
                    </div>

                    <div class="pt-1 mb-4">
                      <button data-mdb-button-init data-mdb-ripple-init class="btn btn-dark btn-lg btn-block"
                        type="submit">Iniciar sesión</button>
                    </div>

                    
                    <p class="mb-5 pb-lg-2" style="color: #393f81;">¿No tienes una cuenta? <a href="register.php"
                        style="color: #393f81;">Regístrate aquí</a></p>

                    
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

</body>

</html>