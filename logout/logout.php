<?php
session_start();

// Guardar el nombre del administrador antes de destruir la sesión
$nombre_admin = $_SESSION['admin_nombre'] ?? 'Administrador';

// Destruir todas las variables de sesión
$_SESSION = array();

// Si se usan cookies de sesión, eliminarla también
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
    <title>Sesión Cerrada - Administrador</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0;
        }
        
        .logout-container {
            background: white;
            padding: 3rem 2.5rem;
            border-radius: 15px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
            text-align: center;
            max-width: 450px;
            width: 90%;
        }
        
        .logo-section {
            margin-bottom: 2rem;
        }
        
        .logo-icon {
            font-size: 4rem;
            color: #2c3e50;
            margin-bottom: 1rem;
        }
        
        .success-icon {
            font-size: 3rem;
            color: #27ae60;
            margin-bottom: 1.5rem;
            animation: checkmark 0.6s ease-in-out;
        }
        
        @keyframes checkmark {
            0% {
                transform: scale(0);
            }
            50% {
                transform: scale(1.2);
            }
            100% {
                transform: scale(1);
            }
        }
        
        h1 {
            color: #2c3e50;
            margin-bottom: 1rem;
            font-size: 1.8rem;
            font-weight: 600;
        }
        
        .admin-name {
            color: #3498db;
            font-weight: 700;
            font-size: 1.1rem;
            margin: 0.5rem 0;
        }
        
        p {
            color: #7f8c8d;
            margin-bottom: 2rem;
            line-height: 1.6;
            font-size: 1rem;
        }
        
        .actions {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
            margin-bottom: 2rem;
        }
        
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
            font-size: 0.95rem;
        }
        
        .btn-primary {
            background: #3498db;
            color: white;
        }
        
        .btn-primary:hover {
            background: #2980b9;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(52, 152, 219, 0.3);
        }
        
        .btn-secondary {
            background: #95a5a6;
            color: white;
        }
        
        .btn-secondary:hover {
            background: #7f8c8d;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(149, 165, 166, 0.3);
        }
        
        .countdown {
            background: #ecf0f1;
            padding: 1rem;
            border-radius: 8px;
            color: #34495e;
            font-size: 0.9rem;
            border-left: 4px solid #3498db;
        }
        
        .countdown-number {
            font-weight: 700;
            color: #3498db;
            font-size: 1.1rem;
        }
        
        .security-note {
            margin-top: 2rem;
            padding: 1rem;
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 8px;
            color: #856404;
            font-size: 0.85rem;
        }
        
        .security-note i {
            color: #f39c12;
            margin-right: 8px;
        }
        
        @media (max-width: 480px) {
            .logout-container {
                padding: 2rem 1.5rem;
            }
            
            .actions {
                flex-direction: column;
            }
            
            .btn {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <div class="logout-container">
        <div class="logo-section">
            <div class="logo-icon">
                <i class="fas fa-tools"></i>
            </div>
            <h2 style="color: #2c3e50; margin: 0; font-size: 1.2rem;">ServicioTech Admin</h2>
        </div>
        
        <div class="success-icon">
            <i class="fas fa-check-circle"></i>
        </div>
        
        <h1>Sesión Cerrada Correctamente</h1>
        
        <div class="admin-name">
            <?php echo htmlspecialchars($nombre_admin); ?>
        </div>
        
        <p>Tu sesión de administrador ha sido cerrada de forma segura. Todos los datos han sido protegidos.</p>
        
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
        
        <div class="countdown">
            <i class="fas fa-clock"></i>
            Redirigiendo al login en <span class="countdown-number" id="contador">8</span> segundos...
        </div>
        
        <div class="security-note">
            <i class="fas fa-shield-alt"></i>
            Por seguridad, asegúrate de cerrar completamente tu navegador si estás en una computadora compartida.
        </div>
    </div>

    <script>
        // Redirección automática después de 8 segundos
        let countdown = 8;
        const counterElement = document.getElementById('contador');
        
        const timer = setInterval(() => {
            countdown--;
            counterElement.textContent = countdown;
            
            if (countdown <= 0) {
                clearInterval(timer);
                window.location.href = '../login.php';
            }
        }, 1000);
        
        // Log de seguridad
        console.log('Sesión de administrador cerrada correctamente');
        console.log('Timestamp:', new Date().toISOString());
        
        // Limpiar cualquier dato almacenado localmente (si los hubiera)
        if (typeof(Storage) !== "undefined") {
            localStorage.clear();
            sessionStorage.clear();
        }
        
        // Prevenir navegación hacia atrás
        history.pushState(null, null, location.href);
        window.onpopstate = function () {
            history.go(1);
        };
    </script>
</body>
</html>