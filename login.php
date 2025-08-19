<?php
session_start();
require_once "conector_DB/db.php"; // Asegúrate de que esta ruta sea correcta

$mensaje = '';

// --- LÓGICA DE REGISTRO (SIMPLIFICADA) ---
// Ahora solo registra en la tabla 'clientes'
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['registrar'])) {

  $nombre_completo = trim($_POST['nombre_completo'] ?? '');
  $usuario         = trim($_POST['usuario'] ?? '');
  $email           = trim($_POST['email'] ?? '');
  $contrasena      = trim($_POST['contrasena'] ?? '');
  $telefono        = trim($_POST['telefono'] ?? '');

  if (empty($nombre_completo) || empty($usuario) || empty($email) || empty($contrasena)) {
    $mensaje = "Por favor completa todos los campos obligatorios para registrarte.";
  } else {
    // Verificar si el usuario o email ya existen
    $check_sql = "SELECT id FROM clientes WHERE usuario = ? OR email = ?";
    $check_stmt = mysqli_prepare($db, $check_sql);
    mysqli_stmt_bind_param($check_stmt, "ss", $usuario, $email);
    mysqli_stmt_execute($check_stmt);
    $check_result = mysqli_stmt_get_result($check_stmt);

    if (mysqli_num_rows($check_result) > 0) {
      $mensaje = "Error: El nombre de usuario o el email ya existen.";
      mysqli_stmt_close($check_stmt);
    } else {
      mysqli_stmt_close($check_stmt);

      // Hashear la contraseña es crucial para la seguridad
      $contrasena_hasheada = password_hash($contrasena, PASSWORD_DEFAULT);

      $sql = "INSERT INTO clientes (nombre_completo, usuario, email, contrasena, telefono) VALUES (?, ?, ?, ?, ?)";
      $stmt = mysqli_prepare($db, $sql);
      mysqli_stmt_bind_param($stmt, "sssss", $nombre_completo, $usuario, $email, $contrasena_hasheada, $telefono);

      if (mysqli_stmt_execute($stmt)) {
        $mensaje = "Registro exitoso. Ahora puedes iniciar sesión como cliente.";
      } else {
        $mensaje = "Error al registrar: " . mysqli_error($db);
      }
      mysqli_stmt_close($stmt);
    }
  }
}

// --- LÓGICA DE LOGIN (CON SELECTOR DE ROL) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
  $usuario      = trim($_POST['nombre_usuario'] ?? '');
  $pass         = trim($_POST['contrasena'] ?? '');
  $tipo_usuario = $_POST['tipo_usuario'] ?? '';

  if (empty($usuario) || empty($pass)) {
    $mensaje = "Por favor, ingresa tu usuario y contraseña.";
  } elseif (empty($tipo_usuario) || $tipo_usuario === 'Elegir tipo de sesión...') {
    $mensaje = "Por favor, selecciona el tipo de sesión.";
  } else {
    $login_exitoso = false;

    if ($tipo_usuario === 'administrador') {
      // Buscar en la tabla de administradores
      $sql = "SELECT id, contrasena, nombre_completo FROM administradores WHERE nombre_usuario = ?";
      $stmt = mysqli_prepare($db, $sql);
      mysqli_stmt_bind_param($stmt, "s", $usuario);
      mysqli_stmt_execute($stmt);
      $resultado = mysqli_stmt_get_result($stmt);

      if ($admin = mysqli_fetch_assoc($resultado)) {
        if (password_verify($pass, $admin['contrasena'])) {
          // Login de admin exitoso
          $_SESSION['admin_id'] = $admin['id'];
          $_SESSION['admin_nombre'] = $admin['nombre_completo'];
          $_SESSION['rol'] = 'administrador';
          $login_exitoso = true;

          mysqli_stmt_close($stmt);
          header("Location: ./administrador/administrador.php");
          exit;
        }
      }
      mysqli_stmt_close($stmt);
    } elseif ($tipo_usuario === 'cliente') {
      // Buscar en la tabla de clientes
      $sql = "SELECT id, contrasena, nombre_completo, usuario FROM clientes WHERE usuario = ?";
      $stmt = mysqli_prepare($db, $sql);
      mysqli_stmt_bind_param($stmt, "s", $usuario);
      mysqli_stmt_execute($stmt);
      $resultado = mysqli_stmt_get_result($stmt);

      if ($cliente = mysqli_fetch_assoc($resultado)) {
        if (password_verify($pass, $cliente['contrasena'])) {
          // Login de cliente exitoso
          $_SESSION['cliente_id'] = $cliente['id'];
          $_SESSION['cliente_nombre'] = $cliente['nombre_completo'];
          $_SESSION['usuario'] = $cliente['usuario'];
          $_SESSION['rol'] = 'cliente';
          $login_exitoso = true;

          mysqli_stmt_close($stmt);

          // Debug: Verificar que el archivo existe
          $ruta_destino = "./clientes_web/clienWeb.php";
          if (file_exists($ruta_destino)) {
            header("Location: " . $ruta_destino);
            exit;
          } else {
            $mensaje = "Error: No se puede encontrar la página de cliente en: " . $ruta_destino;
          }
        }
      }
      mysqli_stmt_close($stmt);
    }

    // Si el código llega hasta aquí, el login falló
    if (!$login_exitoso) {
      $mensaje = "Usuario o contraseña incorrectos para el rol seleccionado.";
    }
  }
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login / Registro</title>
  <link rel="stylesheet" href="./css/login.css">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
   <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
  <script src="./js/loginJs.js"></script>
  <link rel="shortcut icon" href="./assets/img/logoofical.png" />
</head>

<body>
    <div id="back">
        <div class="backRight"></div>
        <div class="backLeft"></div>
    </div>

    <div id="slideBox">
        <div class="topLayer">
            <!-- REGISTRO (Lado Izquierdo) -->
            <div class="left">
                <div class="content">
                    <h2>Registrarse</h2>
                    <div id="registerMessages"></div>
                    
                    <!-- Mensaje PHP para registro -->
                    <?php if (!empty($mensaje) && isset($_POST['registrar'])): ?>
                        <div class="message message-error">
                            <i class="fas fa-exclamation-circle"></i>
                            <?php echo htmlspecialchars($mensaje); ?>
                        </div>
                    <?php endif; ?>

                    <form id="registerForm" method="post" action="login.php">
                        <input type="text" name="nombre_completo" placeholder="Nombre Completo" required />
                        <input type="text" name="usuario" placeholder="Usuario" required />
                        <input type="email" name="email" placeholder="Correo electrónico" required />
                        <input type="password" name="contrasena" placeholder="Contraseña" required />
                        <input type="text" name="telefono" placeholder="Teléfono (Opcional)" />
                        <button type="submit" name="registrar">
                            <span class="btn-text">Registrarse</span>
                        </button>
                        <button id="goLeft" class="off" type="button">Iniciar sesión</button>
                    </form>
                </div>
            </div>

            <!-- LOGIN (Lado Derecho) -->
            <div class="right">
                <div class="content">
                    <h2>Iniciar sesión</h2>
                    <div id="loginMessages"></div>
                    
                    <!-- Mensaje PHP para login -->
                    <?php if (!empty($mensaje) && isset($_POST['login'])): ?>
                        <div class="message message-error">
                            <i class="fas fa-exclamation-circle"></i>
                            <?php echo htmlspecialchars($mensaje); ?>
                        </div>
                    <?php endif; ?>

                    <form id="loginForm" method="POST" action="login.php">
                        <input type="text" name="nombre_usuario" placeholder="Usuario" required>
                        <input type="password" name="contrasena" placeholder="Contraseña" required>

                        <!-- Selector de rol en el login -->
                        <select name="tipo_usuario" required>
                            <option value="">Elegir tipo de sesión...</option>
                            <option value="cliente">Ingresar como Cliente</option>
                            <option value="administrador">Ingresar como Administrador</option>
                        </select>

                        <button type="submit" name="login">
                            <span class="btn-text">Iniciar sesión</span>
                        </button>
                        <button id="goRight" class="off" type="button">Registrarse</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Botón de regreso -->
    <a href="./index.php" class="btn-back" title="Regresar">
        <i class="fas fa-arrow-left"></i>
    </a>
</body>
</html>

</html>