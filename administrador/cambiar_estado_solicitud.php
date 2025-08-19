<?php
session_start();
require_once "../conector_DB/db.php";
$id = intval($_POST['id'] ?? 0);
$nuevo_estado = $_POST['nuevo_estado'] ?? '';

if ($id > 0 && in_array($nuevo_estado, ['en_proceso', 'completada', 'cancelada'])) {
    $sql = "UPDATE solicitudes_servicio SET estado = ? " . 
           ($nuevo_estado === 'completada' ? ", fecha_completado = NOW()" : "") . " WHERE id = ?";
    $stmt = mysqli_prepare($db, $sql);
    mysqli_stmt_bind_param($stmt, "si", $nuevo_estado, $id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    // Notificar al cliente
    $sql = "SELECT cliente_id FROM solicitudes_servicio WHERE id=?";
    $stmt = mysqli_prepare($db, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($res);
    mysqli_stmt_close($stmt);

    if ($row && $row['cliente_id']) {
        $cliente_id = $row['cliente_id'];
        if ($nuevo_estado === 'en_proceso') {
            $titulo = "¡Tu solicitud está en proceso!";
            $mensaje = "La solicitud #$id ha sido marcada como 'en proceso'.";
            $tipo = "actualizacion";
        } elseif ($nuevo_estado === 'completada') {
            $titulo = "¡Equipo listo para entrega!";
            $mensaje = "¡Tu equipo está listo! La solicitud #$id ha sido completada.";
            $tipo = "completado";
        } else {
            $titulo = "Solicitud cancelada";
            $mensaje = "La solicitud #$id ha sido cancelada.";
            $tipo = "urgente";
        }
        $sql = "INSERT INTO notificaciones_cliente (cliente_id, titulo, mensaje, tipo, leida, solicitud_id, fecha_creacion) 
                VALUES (?, ?, ?, ?, 0, ?, NOW())";
        $stmt = mysqli_prepare($db, $sql);
        mysqli_stmt_bind_param($stmt, "isssi", $cliente_id, $titulo, $mensaje, $tipo, $id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }
}

header("Location: administrador.php#solicitudes");
exit;
?>