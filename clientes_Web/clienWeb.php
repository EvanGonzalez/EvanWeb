<?php
session_start();

// Verificar que el usuario esté logueado como cliente
if (!isset($_SESSION['cliente_id']) || $_SESSION['rol'] !== 'cliente') {
    header("Location: ../login.php");
    exit;
}

require_once "../conector_DB/db.php";

// Obtener información del cliente
$cliente_info = null;
if (isset($_SESSION['cliente_id'])) {
    $sql = "SELECT * FROM clientes WHERE id = ?";
    $stmt = mysqli_prepare($db, $sql);
    mysqli_stmt_bind_param($stmt, "i", $_SESSION['cliente_id']);
    mysqli_stmt_execute($stmt);
    $resultado = mysqli_stmt_get_result($stmt);
    $cliente_info = mysqli_fetch_assoc($resultado);
    mysqli_stmt_close($stmt);
}

// Obtener anuncios activos
$anuncios = [];
$sql = "SELECT * FROM anuncios WHERE activo = 1 AND (fecha_expiracion IS NULL OR fecha_expiracion >= CURDATE()) ORDER BY fecha_creacion DESC LIMIT 5";
$result = mysqli_query($db, $sql);
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $anuncios[] = $row;
    }
}

// Obtener solicitudes del cliente
$solicitudes_count = 0;
$solicitudes_recientes = [];
$sql = "SELECT COUNT(*) as total FROM solicitudes_servicio WHERE cliente_id = ?";
$stmt = mysqli_prepare($db, $sql);
mysqli_stmt_bind_param($stmt, "i", $_SESSION['cliente_id']);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
if ($result) {
    $row = mysqli_fetch_assoc($result);
    $solicitudes_count = $row['total'];
}
mysqli_stmt_close($stmt);

// Obtener solicitudes recientes
$sql = "SELECT * FROM solicitudes_servicio WHERE cliente_id = ? ORDER BY fecha_solicitud DESC LIMIT 3";
$stmt = mysqli_prepare($db, $sql);
mysqli_stmt_bind_param($stmt, "i", $_SESSION['cliente_id']);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $solicitudes_recientes[] = $row;
    }
}
mysqli_stmt_close($stmt);

// Obtener notificaciones no leídas
$notificaciones = [];
$sql = "SELECT id, titulo, mensaje, fecha_creacion FROM notificaciones_cliente WHERE cliente_id = ? AND leida = 0 ORDER BY fecha_creacion DESC";
$stmt = mysqli_prepare($db, $sql);
mysqli_stmt_bind_param($stmt, "i", $_SESSION['cliente_id']);
mysqli_stmt_execute($stmt);
$res = mysqli_stmt_get_result($stmt);
$notificaciones = [];
while ($row = mysqli_fetch_assoc($res)) {
    $notificaciones[] = $row;
}
mysqli_stmt_close($stmt);

// Agrega esta línea:
$notificaciones_count = count($notificaciones);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Cliente - ServicioTech</title>
    <link rel="stylesheet" href="../css/cliente.css">
    <link rel="shortcut icon" href="../assets/img/logoofical.png" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body>
    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <div class="logo">
                <img src="../assets/img/logowhitefull.svg" alt="Logo" class="logo-img" />
               
            </div>
            <button class="sidebar-toggle" id="sidebarToggle">
                <i class="fas fa-bars"></i>
            </button>
        </div>

        <nav class="sidebar-nav">
            <ul class="nav-menu">
                <li class="nav-item">
                    <a href="#inicio" class="nav-link active" data-section="inicio">
                        <i class="fas fa-home"></i>
                        <span>Inicio</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#solicitar" class="nav-link" data-section="solicitar">
                        <i class="fas fa-plus-circle"></i>
                        <span>Solicitar Servicio</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#mis-solicitudes" class="nav-link" data-section="mis-solicitudes">
                        <i class="fas fa-list-alt"></i>
                        <span>Mis Solicitudes</span>
                        <?php if ($solicitudes_count > 0): ?>
                            <span class="badge"><?php echo $solicitudes_count; ?></span>
                        <?php endif; ?>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#seguimiento" class="nav-link" data-section="seguimiento">
                        <i class="fas fa-search"></i>
                        <span>Seguimiento</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#perfil" class="nav-link" data-section="perfil">
                        <i class="fas fa-user"></i>
                        <span>Mi Perfil</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#notificaciones" class="nav-link" data-section="notificaciones">
                        <i class="fas fa-bell"></i>
                        <span>Notificaciones</span>
                        <?php if ($notificaciones_count > 0): ?>
                            <span class="badge badge-warning"><?php echo $notificaciones_count; ?></span>
                        <?php endif; ?>
                    </a>
                </li>
            </ul>
        </nav>

        <div class="sidebar-footer">
            <div class="client-info">
                <i class="fas fa-user-circle"></i>
                <div class="client-details">
                    <span class="client-name"><?php echo htmlspecialchars($cliente_info['nombre_completo'] ?? 'Cliente'); ?></span>
                    <span class="client-role">Cliente</span>
                </div>
            </div>
            <div class="logout-section">
                <a href="../index.php" class="logout-btn" title="Ir al inicio">
                    <i class="fas fa-home"></i>
                </a>
                <a href="../logout/logoutclien.php" class="logout-btn" title="Cerrar sesión">
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
                <h1 id="pageTitle">Inicio</h1>
                <p class="page-subtitle">Bienvenido a tu panel de servicios</p>
            </div>
            <div class="header-right">
                <div class="header-actions">
                    <button class="header-btn" title="Notificaciones" data-section="notificaciones">
                        <i class="fas fa-bell"></i>
                        <?php if ($notificaciones_count > 0): ?>
                            <span class="notification-count"><?php echo $notificaciones_count; ?></span>
                        <?php endif; ?>
                    </button>
                    <div class="client-profile">
                        <span class="welcome-text">Hola, <strong><?php echo htmlspecialchars(explode(' ', $cliente_info['nombre_completo'] ?? 'Cliente')[0]); ?></strong></span>
                    </div>
                </div>
            </div>
        </header>

        <!-- Content Area -->
        <div class="content-area">
            <!-- Inicio Section -->
            <section id="inicio-content" class="content-section active">
                <!-- Stats Cards -->
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-clipboard-list"></i>
                        </div>
                        <div class="stat-content">
                            <h3><?php echo $solicitudes_count; ?></h3>
                            <p>Solicitudes Totales</p>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon warning">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="stat-content">
                            <h3><?php
                                $pendientes = 0;
                                foreach ($solicitudes_recientes as $sol) {
                                    if ($sol['estado'] == 'pendiente' || $sol['estado'] == 'en_proceso') $pendientes++;
                                }
                                echo $pendientes;
                                ?></h3>
                            <p>En Proceso</p>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon success">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div class="stat-content">
                            <h3><?php
                                $completadas = 0;
                                foreach ($solicitudes_recientes as $sol) {
                                    if ($sol['estado'] == 'completada') $completadas++;
                                }
                                echo $completadas;
                                ?></h3>
                            <p>Completadas</p>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon info">
                            <i class="fas fa-bell"></i>
                        </div>
                        <div class="stat-content">
                            <h3><?php echo $notificaciones_count; ?></h3>
                            <p>Notificaciones</p>
                        </div>
                    </div>
                </div>

                <!-- Main Dashboard -->
                <div class="dashboard-grid">
                    <!-- Anuncios -->
                    <div class="dashboard-card">
                        <div class="card-header">
                            <h3><i class="fas fa-bullhorn"></i> Anuncios y Noticias</h3>
                        </div>
                        <div class="card-content">
                            <?php if (!empty($anuncios)): ?>
                                <div class="announcements-list">
                                    <?php foreach ($anuncios as $anuncio): ?>
                                        <div class="announcement-item <?php echo $anuncio['tipo']; ?>">
                                            <div class="announcement-header">
                                                <h4><?php echo htmlspecialchars($anuncio['titulo']); ?></h4>
                                                <span class="announcement-type"><?php echo ucfirst($anuncio['tipo']); ?></span>
                                            </div>
                                            <p><?php echo htmlspecialchars($anuncio['contenido']); ?></p>
                                            <small><i class="fas fa-calendar"></i> <?php echo date('d/m/Y', strtotime($anuncio['fecha_creacion'])); ?></small>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php else: ?>
                                <div class="empty-state">
                                    <i class="fas fa-info-circle"></i>
                                    <p>No hay anuncios disponibles en este momento.</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Solicitudes Recientes -->
                    <div class="dashboard-card">
                        <div class="card-header">
                            <h3><i class="fas fa-history"></i> Solicitudes Recientes</h3>
                            <button class="btn-small" data-section="mis-solicitudes">Ver Todas</button>
                        </div>
                        <div class="card-content">
                            <?php if (!empty($solicitudes_recientes)): ?>
                                <div class="requests-list">
                                    <?php foreach ($solicitudes_recientes as $solicitud): ?>
                                        <div class="request-item">
                                            <div class="request-info">
                                                <h4><?php echo htmlspecialchars($solicitud['titulo']); ?></h4>
                                                <p><?php echo ucfirst(str_replace('_', ' ', $solicitud['tipo_servicio'])); ?></p>
                                                <small><?php echo date('d/m/Y H:i', strtotime($solicitud['fecha_solicitud'])); ?></small>
                                            </div>
                                            <div class="request-status">
                                                <span class="status-badge <?php echo $solicitud['estado']; ?>">
                                                    <?php echo ucfirst(str_replace('_', ' ', $solicitud['estado'])); ?>
                                                </span>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php else: ?>
                                <div class="empty-state">
                                    <i class="fas fa-clipboard"></i>
                                    <p>No tienes solicitudes aún.</p>
                                    <button class="btn btn-primary" data-section="solicitar">
                                        <i class="fas fa-plus"></i> Crear Primera Solicitud
                                    </button>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="quick-actions-card">
                    <h3>Acciones Rápidas</h3>
                    <div class="quick-actions">
                        <button class="quick-action-btn" data-section="solicitar">
                            <i class="fas fa-plus-circle"></i>
                            <span>Nueva Solicitud</span>
                        </button>
                        <button class="quick-action-btn" data-section="seguimiento">
                            <i class="fas fa-search"></i>
                            <span>Seguir Solicitud</span>
                        </button>
                        <button class="quick-action-btn" data-section="perfil">
                            <i class="fas fa-user-edit"></i>
                            <span>Actualizar Perfil</span>
                        </button>
                        <button class="quick-action-btn" onclick="window.open('tel:+507-1234-5678', '_self')">
                            <i class="fas fa-phone"></i>
                            <span>Llamar Soporte</span>
                        </button>
                    </div>
                </div>
            </section>

            <!-- Otras secciones (placeholder por ahora) -->
            <section id="solicitar-content" class="content-section">
                <div class="section-header">
                    <h2>Solicitar Nuevo Servicio</h2>
                </div>
                <form id="form-solicitud-servicio" action="procesar_solicitud.php" method="POST" enctype="multipart/form-data" class="solicitud-form">
                    <div class="form-group">
                        <label for="tipo_servicio">Tipo de servicio:</label>
                        <select id="tipo_servicio" name="tipo_servicio" required>
                            <option value="">Seleccione...</option>
                            <option value="reparacion">Reparación</option>
                            <option value="mantenimiento">Mantenimiento</option>
                            <option value="instalacion">Instalación</option>
                            <option value="consultoria">Consultoría</option>
                            <option value="otro">Otro</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="titulo">Título de la solicitud:</label>
                        <input type="text" id="titulo" name="titulo" maxlength="200" required placeholder="Ejemplo: Reparación de laptop">
                    </div>
                    <div class="form-group">
                        <label for="descripcion">Describe tu problema:</label>
                        <textarea id="descripcion" name="descripcion" rows="4" required placeholder="Describe detalladamente el problema..."></textarea>
                    </div>
                    <div class="form-group">
                        <label for="prioridad">Prioridad:</label>
                        <select id="prioridad" name="prioridad" required>
                            <option value="media">Media</option>
                            <option value="baja">Baja</option>
                            <option value="alta">Alta</option>
                            <option value="urgente">Urgente</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="direccion_servicio">Dirección de servicio (opcional):</label>
                        <input type="text" id="direccion_servicio" name="direccion_servicio" maxlength="300">
                    </div>
                    <div class="form-group">
                        <label for="telefono_contacto">Teléfono de contacto (opcional):</label>
                        <input type="text" id="telefono_contacto" name="telefono_contacto" maxlength="20">
                    </div>
                    <div class="form-group">
                        <label for="fecha_preferida">Fecha preferida (opcional):</label>
                        <input type="date" id="fecha_preferida" name="fecha_preferida">
                    </div>
                    <div class="form-group">
                        <label for="hora_preferida">Hora preferida (opcional):</label>
                        <input type="time" id="hora_preferida" name="hora_preferida">
                    </div>
                    <div class="form-group">
                        <label for="imagenes">Adjunta imágenes (máximo 3):</label>
                        <input type="file" id="imagenes" name="imagenes[]" accept="image/*" multiple onchange="validarCantidadImagenes(this)">
                        <small>Puedes adjuntar hasta 3 imágenes.</small>
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-paper-plane"></i> Enviar Solicitud
                    </button>
                </form>
                <script>
                    function validarCantidadImagenes(input) {
                        if (input.files.length > 3) {
                            alert('Solo puedes adjuntar hasta 3 imágenes.');
                            input.value = '';
                        }
                    }
                </script>
            </section>

            <section id="mis-solicitudes-content" class="content-section">
                <div class="section-header">
                    <h2>Mis Solicitudes de Servicio</h2>
                </div>
                <div class="content-placeholder">
                    <i class="fas fa-list-alt"></i>
                    <h3>Lista de Solicitudes</h3>
                    <p>Ver todas tus solicitudes de servicio y su estado actual.</p>
                </div>
            </section>

            <section id="seguimiento-content" class="content-section">
                <div class="section-header">
                    <h2>Seguimiento de Servicios</h2>
                </div>
                <div class="content-placeholder">
                    <i class="fas fa-search"></i>
                    <h3>Estado del Servicio</h3>
                    <p>Rastrea el progreso de tus equipos en reparación o mantenimiento.</p>
                </div>
            </section>

            <section id="perfil-content" class="content-section">
                <div class="section-header">
                    <h2>Mi Perfil</h2>
                </div>
                <div class="content-placeholder">
                    <i class="fas fa-user"></i>
                    <h3>Información Personal</h3>
                    <p>Actualiza tu información de contacto y preferencias.</p>
                </div>
            </section>

            <section id="notificaciones-content" class="content-section">
                <div class="section-header">
                    <h2>Notificaciones</h2>
                </div>
                <?php if (count($notificaciones) > 0): ?>
                    <div class="notificaciones-list">
                        <?php foreach ($notificaciones as $noti): ?>
                            <div class="notificacion-item">
                                <i class="fas fa-bell"></i>
                                <strong><?php echo htmlspecialchars($noti['titulo']); ?>:</strong>
                                <?php echo htmlspecialchars($noti['mensaje']); ?>
                                <small style="color:#888;"><?php echo htmlspecialchars($noti['fecha_creacion']); ?></small>
                                <form method="post" action="marcar_leida.php" style="display:inline;">
                                    <input type="hidden" name="id" value="<?php echo $noti['id']; ?>">
                                    <button type="submit" class="btn btn-success btn-sm">Visto bueno</button>
                                </form>
                                <form method="post" action="eliminar_notificacion.php" style="display:inline;">
                                    <input type="hidden" name="id" value="<?php echo $noti['id']; ?>">
                                    <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
                                </form>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="empty-state">
                        <i class="fas fa-bell-slash"></i>
                        <p>No tienes notificaciones nuevas.</p>
                    </div>
                <?php endif; ?>
            </section>

            
        </div>
    </main>

    <script src="../js/cliente_panel.js"></script>
</body>

</html>