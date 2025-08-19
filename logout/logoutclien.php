<?php
session_start();

// Capturar información antes de destruir la sesión
$nombre_usuario = '';
$rol_usuario = '';
$mensaje_personalizado = '';

if (isset($_SESSION['admin_nombre']) && $_SESSION['rol'] === 'administrador') {
    $nombre_usuario = $_SESSION['admin_nombre'];
    $rol_usuario = 'Administrador';
    $mensaje_personalizado = 'Gracias por administrar nuestro sistema.';
} elseif (isset($_SESSION['cliente_nombre']) && $_SESSION['rol'] === 'cliente') {
    $nombre_usuario = $_SESSION['cliente_nombre'];
    $rol_usuario = 'Cliente';
    $mensaje_personalizado = 'Esperamos verte pronto de nuevo.';
} else {
    // Si no hay sesión activa, redirigir al login
    header("Location: login.php");
    exit;
}

// Destruir todas las variables de sesión
$_SESSION = array();

// Destruir la cookie de sesión si existe
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Finalmente, destruir la sesión
session_destroy();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sesión Cerrada - <?php echo htmlspecialchars($rol_usuario); ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        html, body {
            height: 100%;
            font-family: 'Roboto', sans-serif;
            background: linear-gradient(135deg, #000000 0%, #1a1a1a 50%, #33c2d1 100%);
            overflow: hidden;
        }

        .logout-container {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 2rem;
            position: relative;
        }

        .logout-card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 25px;
            padding: 3rem 2.5rem;
            text-align: center;
            max-width: 500px;
            width: 100%;
            box-shadow: 0 30px 60px rgba(0, 0, 0, 0.3), 
                        0 20px 40px rgba(51, 194, 209, 0.2);
            backdrop-filter: blur(15px);
            border: 2px solid rgba(255, 255, 255, 0.3);
            position: relative;
            animation: slideInScale 0.8s cubic-bezier(0.4, 0.0, 0.2, 1);
        }

        @keyframes slideInScale {
            0% {
                opacity: 0;
                transform: translateY(30px) scale(0.9);
            }
            100% {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        .logo-container {
            margin-bottom: 2rem;
            position: relative;
        }

        .logo {
            width: 120px;
            height: 120px;
            background: linear-gradient(135deg, #33c2d1 0%, #0288d1 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            box-shadow: 0 15px 30px rgba(51, 194, 209, 0.4);
            position: relative;
            animation: logoFloat 3s ease-in-out infinite;
        }

        @keyframes logoFloat {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }

        .logo::before {
            content: '';
            position: absolute;
            top: 15px;
            left: 15px;
            right: 15px;
            bottom: 15px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            animation: logoRotate 8s linear infinite;
        }

        @keyframes logoRotate {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .logo-text {
            color: #FFFFFF;
            font-size: 1.8rem;
            font-weight: 600;
            letter-spacing: 2px;
        }

        .company-name {
            color: #33c2d1;
            font-size: 1.5rem;
            font-weight: 300;
            margin-bottom: 2.5rem;
            letter-spacing: 1px;
        }

        .farewell-title {
            color: #000000;
            font-size: 2.2rem;
            font-weight: 400;
            margin-bottom: 1rem;
            position: relative;
        }

        .farewell-title::after {
            content: '';
            position: absolute;
            bottom: -8px;
            left: 50%;
            transform: translateX(-50%);
            width: 60px;
            height: 3px;
            background: linear-gradient(90deg, #33c2d1, #0288d1);
            border-radius: 2px;
        }

        .user-info {
            background: linear-gradient(135deg, rgba(51, 194, 209, 0.1) 0%, rgba(51, 194, 209, 0.05) 100%);
            padding: 1.5rem;
            border-radius: 15px;
            margin: 1.5rem 0;
            border-left: 4px solid #33c2d1;
        }

        .user-name {
            color: #000000;
            font-size: 1.3rem;
            font-weight: 500;
            margin-bottom: 0.5rem;
        }

        .user-role {
            color: #33c2d1;
            font-size: 1rem;
            font-weight: 400;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .farewell-message {
            color: #666;
            font-size: 1.1rem;
            line-height: 1.6;
            margin: 1.5rem 0 2.5rem;
            font-style: italic;
        }

        .actions {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn {
            padding: 14px 28px;
            border: none;
            border-radius: 12px;
            font-size: 1rem;
            font-weight: 500;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            cursor: pointer;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            min-width: 160px;
            justify-content: center;
        }

        .btn-primary {
            background: linear-gradient(135deg, #33c2d1 0%, #0288d1 100%);
            color: #FFFFFF;
            box-shadow: 0 8px 20px rgba(51, 194, 209, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 30px rgba(51, 194, 209, 0.5);
        }

        .btn-secondary {
            background: transparent;
            color: #33c2d1;
            border: 2px solid #33c2d1;
        }

        .btn-secondary:hover {
            background: rgba(51, 194, 209, 0.1);
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(51, 194, 209, 0.2);
        }

        .security-note {
            margin-top: 2rem;
            padding: 1rem;
            background: linear-gradient(135deg, rgba(0, 0, 0, 0.05) 0%, rgba(0, 0, 0, 0.1) 100%);
            border-radius: 10px;
            font-size: 0.9rem;
            color: #666;
            border-left: 3px solid #33c2d1;
        }

        .particles {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            pointer-events: none;
            z-index: -1;
        }

        .particle {
            position: absolute;
            width: 4px;
            height: 4px;
            background: rgba(51, 194, 209, 0.6);
            border-radius: 50%;
            animation: float 6s ease-in-out infinite;
        }

        .particle:nth-child(1) { left: 10%; animation-delay: 0s; }
        .particle:nth-child(2) { left: 20%; animation-delay: 1s; }
        .particle:nth-child(3) { left: 30%; animation-delay: 2s; }
        .particle:nth-child(4) { left: 40%; animation-delay: 3s; }
        .particle:nth-child(5) { left: 50%; animation-delay: 4s; }
        .particle:nth-child(6) { left: 60%; animation-delay: 5s; }
        .particle:nth-child(7) { left: 70%; animation-delay: 0.5s; }
        .particle:nth-child(8) { left: 80%; animation-delay: 1.5s; }
        .particle:nth-child(9) { left: 90%; animation-delay: 2.5s; }

        @keyframes float {
            0%, 100% {
                transform: translateY(100vh) scale(0);
                opacity: 0;
            }
            10% {
                opacity: 1;
                transform: translateY(90vh) scale(1);
            }
            90% {
                opacity: 1;
                transform: translateY(10vh) scale(1);
            }
        }

        /* Auto-redirect countdown */
        .countdown {
            color: #33c2d1;
            font-weight: 500;
            font-size: 1rem;
            margin-top: 1.5rem;
        }

        @media (max-width: 768px) {
            .logout-card {
                padding: 2rem 1.5rem;
                margin: 1rem;
            }
            
            .farewell-title {
                font-size: 1.8rem;
            }
            
            .actions {
                flex-direction: column;
            }
            
            .btn {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="particles">
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
    </div>

    <div class="logout-container">
        <div class="logout-card">
            <div class="logo-container">
                <div class="logo">
                    
                     <img src="../assets/img/logoblack.svg" alt="Logo" class="logo-img" />
                </div>
                <div class="company-name">Más que mantenimiento, soluciones personalizadas.</div>
            </div>

            <h1 class="farewell-title">¡Hasta Pronto!</h1>

            <div class="user-info">
                <div class="user-name">
                    <i class="fas fa-user"></i>
                    <?php echo htmlspecialchars($nombre_usuario); ?>
                </div>
                <div class="user-role"><?php echo htmlspecialchars($rol_usuario); ?></div>
            </div>

            <p class="farewell-message">
                <?php echo htmlspecialchars($mensaje_personalizado); ?>
                <br>
                Tu sesión ha sido cerrada correctamente.
            </p>

            <div class="actions">
                <a href="../login.php" class="btn btn-primary">
                    <i class="fas fa-sign-in-alt"></i>
                    Iniciar Sesión
                </a>
                <a href="../index.php" class="btn btn-secondary">
                    <i class="fas fa-home"></i>
                    Ir al Inicio
                </a>
            </div>

            <div class="security-note">
                <i class="fas fa-shield-alt"></i>
                Por tu seguridad, hemos cerrado completamente tu sesión. 
                Si este dispositivo es compartido, te recomendamos cerrar el navegador.
            </div>

            <div class="countdown" id="countdown">
                Redirigiendo al login en <span id="timer">10</span> segundos...
            </div>
        </div>
    </div>

    <script>
        // Auto-redirect después de x segundos
        let timeLeft = 5;
        const timerElement = document.getElementById('timer');
        const countdownElement = document.getElementById('countdown');
        
        const countdown = setInterval(() => {
            timeLeft--;
            timerElement.textContent = timeLeft;
            
            if (timeLeft <= 0) {
                clearInterval(countdown);
                countdownElement.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Redirigiendo...';
                window.location.href = '../login.php';
            }
        }, 1000);

        // Cancelar auto-redirect si el usuario interactúa con la página
        document.addEventListener('click', () => {
            clearInterval(countdown);
            countdownElement.style.display = 'none';
        });

        document.addEventListener('keydown', () => {
            clearInterval(countdown);
            countdownElement.style.display = 'none';
        });

        // Limpiar cualquier dato almacenado localmente
        if (typeof(Storage) !== "undefined") {
            localStorage.clear();
            sessionStorage.clear();
        }

        // Prevenir el botón "atrás" del navegador
        history.pushState(null, null, location.href);
        window.onpopstate = function () {
            history.go(1);
        };
    </script>
</body>
</html>