<?php
session_start();

// Verificar que el usuario esté logueado como administrador
if (!isset($_SESSION['admin_id']) || $_SESSION['rol'] !== 'administrador') {
    header("Location: ../login.php");
    exit;
}

// Conectar a la base de datos para obtener información del administrador
require_once "../conector_DB/db.php";

$admin_info = null;
if (isset($_SESSION['admin_id'])) {
    $sql = "SELECT nombre_completo, nombre_usuario FROM administradores WHERE id = ?";
    $stmt = mysqli_prepare($db, $sql);
    mysqli_stmt_bind_param($stmt, "i", $_SESSION['admin_id']);
    mysqli_stmt_execute($stmt);
    $resultado = mysqli_stmt_get_result($stmt);
    $admin_info = mysqli_fetch_assoc($resultado);
    mysqli_stmt_close($stmt);
}

// Obtener estadísticas básicas
$total_clientes = 0;
$solicitudes_pendientes = 0;
$equipos_en_servicio = 0;

// Contar clientes
$sql = "SELECT COUNT(*) as total FROM clientes";
$result = mysqli_query($db, $sql);
if ($result) {
    $row = mysqli_fetch_assoc($result);
    $total_clientes = $row['total'];
}

// Aquí puedes agregar más consultas para obtener estadísticas reales
// Por ahora uso datos de ejemplo
$solicitudes_pendientes = 12; // Ejemplo
$equipos_en_servicio = 8; // Ejemplo

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Administrador - Servicio Técnico</title>
    <link rel="stylesheet" href="../css/administrador.css">
    <link rel="shortcut icon" href="../assets/img/logoofical.png" />
    <!-- Font Awesome para iconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <div class="logo">
                <i class="fas fa-tools"></i>
                <span>ServicioTech</span>
            </div>
            <button class="sidebar-toggle" id="sidebarToggle">
                <i class="fas fa-bars"></i>
            </button>
        </div>

        <nav class="sidebar-nav">
            <ul class="nav-menu">
                <li class="nav-item">
                    <a href="#dashboard" class="nav-link active" data-section="dashboard">
                        <i class="fas fa-chart-dashboard"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#clientes" class="nav-link" data-section="clientes">
                        <i class="fas fa-users"></i>
                        <span>Ver Clientes</span>
                        <span class="badge"><?php echo $total_clientes; ?></span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#solicitudes" class="nav-link" data-section="solicitudes">
                        <i class="fas fa-clipboard-list"></i>
                        <span>Solicitudes</span>
                        <span class="badge badge-warning"><?php echo $solicitudes_pendientes; ?></span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#equipos" class="nav-link" data-section="equipos">
                        <i class="fas fa-laptop-medical"></i>
                        <span>Equipos en Servicio</span>
                        <span class="badge badge-info"><?php echo $equipos_en_servicio; ?></span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#informes" class="nav-link" data-section="informes">
                        <i class="fas fa-file-alt"></i>
                        <span>Informes</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#configuracion" class="nav-link" data-section="configuracion">
                        <i class="fas fa-cog"></i>
                        <span>Configuración</span>
                    </a>
                </li>
            </ul>
        </nav>

        <div class="sidebar-footer">
            <div class="admin-info">
                <i class="fas fa-user-shield"></i>
                <div class="admin-details">
                    <span class="admin-name"><?php echo htmlspecialchars($admin_info['nombre_completo'] ?? 'Administrador'); ?></span>
                    <span class="admin-role">Administrador</span>
                </div>
            </div>
            <div class="logout-section">
                <a href="../index.php" class="logout-btn" title="Ir al inicio">
                    <i class="fas fa-home"></i>
                </a>
                <a href="logout_admin.php" class="logout-btn" title="Cerrar sesión">
                    <i class="fas fa-sign-out-alt"></i>
                </a>
            </div>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Top Header -->
        <header class="top-header">
            <div class="header-left">
                <h1 id="pageTitle">Dashboard Principal</h1>
                <p class="page-subtitle">Panel de administración - Servicio Técnico</p>
            </div>
            <div class="header-right">
                <div class="header-actions">
                    <button class="header-btn" title="Notificaciones">
                        <i class="fas fa-bell"></i>
                        <span class="notification-count">3</span>
                    </button>
                    <div class="admin-profile">
                        <span class="welcome-text">Bienvenido, <strong><?php echo htmlspecialchars(explode(' ', $admin_info['nombre_completo'] ?? 'Admin')[0]); ?></strong></span>
                    </div>
                </div>
            </div>
        </header>

        <!-- Content Area -->
        <div class="content-area">
            <!-- Dashboard Section -->
            <section id="dashboard-content" class="content-section active">
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="stat-content">
                            <h3><?php echo $total_clientes; ?></h3>
                            <p>Clientes Registrados</p>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon warning">
                            <i class="fas fa-clipboard-list"></i>
                        </div>
                        <div class="stat-content">
                            <h3><?php echo $solicitudes_pendientes; ?></h3>
                            <p>Solicitudes Pendientes</p>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon info">
                            <i class="fas fa-laptop-medical"></i>
                        </div>
                        <div class="stat-content">
                            <h3><?php echo $equipos_en_servicio; ?></h3>
                            <p>Equipos en Servicio</p>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon success">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div class="stat-content">
                            <h3>24</h3>
                            <p>Servicios Completados</p>
                        </div>
                    </div>
                </div>

                <div class="dashboard-cards">
                    <div class="dashboard-card">
                        <div class="card-header">
                            <h3>Actividad Reciente</h3>
                        </div>
                        <div class="card-content">
                            <div class="activity-list">
                                <div class="activity-item">
                                    <i class="fas fa-user-plus"></i>
                                    <span>Nuevo cliente registrado: Juan Pérez</span>
                                    <small>Hace 2 horas</small>
                                </div>
                                <div class="activity-item">
                                    <i class="fas fa-wrench"></i>
                                    <span>Reparación completada: Laptop HP</span>
                                    <small>Hace 4 horas</small>
                                </div>
                                <div class="activity-item">
                                    <i class="fas fa-exclamation-triangle"></i>
                                    <span>Nueva solicitud de servicio urgente</span>
                                    <small>Hace 6 horas</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="dashboard-card">
                        <div class="card-header">
                            <h3>Acciones Rápidas</h3>
                        </div>
                        <div class="card-content">
                            <div class="quick-actions">
                                <button class="quick-action-btn" data-section="clientes">
                                    <i class="fas fa-user-plus"></i>
                                    <span>Agregar Cliente</span>
                                </button>
                                <button class="quick-action-btn" data-section="solicitudes">
                                    <i class="fas fa-plus-circle"></i>
                                    <span>Nueva Solicitud</span>
                                </button>
                                <button class="quick-action-btn" data-section="informes">
                                    <i class="fas fa-file-export"></i>
                                    <span>Generar Informe</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Otras secciones -->
            <section id="clientes-content" class="content-section">
                <div class="section-header">
                    <h2>Gestión de Clientes</h2>
                    <button class="btn btn-primary">
                        <i class="fas fa-user-plus"></i> Agregar Cliente
                    </button>
                </div>
                <div class="content-placeholder">
                    <i class="fas fa-users"></i>
                    <h3>Módulo de Clientes</h3>
                    <p>Aquí se mostrará la lista de clientes, sus datos de contacto y historial de servicios.</p>
                </div>
            </section>

            <section id="solicitudes-content" class="content-section">
                <div class="section-header">
                    <h2>Solicitudes de Servicio</h2>
                    <button class="btn btn-primary">
                        <i class="fas fa-plus"></i> Nueva Solicitud
                    </button>
                </div>
                <div class="content-placeholder">
                    <i class="fas fa-clipboard-list"></i>
                    <h3>Solicitudes Pendientes</h3>
                    <p>Gestiona las solicitudes de mantenimiento, reparaciones y servicios técnicos.</p>
                </div>
            </section>

            <section id="equipos-content" class="content-section">
                <div class="section-header">
                    <h2>Equipos en Servicio</h2>
                    <button class="btn btn-primary">
                        <i class="fas fa-laptop"></i> Registrar Equipo
                    </button>
                </div>
                <div class="content-placeholder">
                    <i class="fas fa-laptop-medical"></i>
                    <h3>Control de Equipos</h3>
                    <p>Seguimiento de computadoras, equipos y dispositivos en reparación o mantenimiento.</p>
                </div>
            </section>

            <section id="informes-content" class="content-section">
                <div class="section-header">
                    <h2>Informes y Reportes</h2>
                    <button class="btn btn-primary">
                        <i class="fas fa-download"></i> Exportar Informe
                    </button>
                </div>
                <div class="content-placeholder">
                    <i class="fas fa-chart-bar"></i>
                    <h3>Generación de Informes</h3>
                    <p>Crea informes de servicios realizados, estadísticas y reportes para clientes.</p>
                </div>
            </section>

            <section id="configuracion-content" class="content-section">
                <div class="section-header">
                    <h2>Configuración del Sistema</h2>
                </div>
                <div class="content-placeholder">
                    <i class="fas fa-cog"></i>
                    <h3>Configuración</h3>
                    <p>Ajustes del sistema, configuración de usuarios y parámetros generales.</p>
                </div>
            </section>
        </div>
    </main>

    <script src="../js/admin.js"></script>
</body>
</html>