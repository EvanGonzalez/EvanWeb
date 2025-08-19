<?php
require_once "../conector_DB/db.php";
$id = intval($_POST['id'] ?? 0);

if ($id > 0) {
    // Marcar como completado
    $sql = "UPDATE solicitudes_servicio SET estado='completado', fecha_completado=NOW() WHERE id=?";
    $stmt = mysqli_prepare($db, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    // Obtener el cliente de la solicitud
    $sql = "SELECT cliente_id FROM solicitudes_servicio WHERE id=?";
    $stmt = mysqli_prepare($db, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($res);
    mysqli_stmt_close($stmt);

    if ($row && $row['cliente_id']) {
        $cliente_id = $row['cliente_id'];
        $mensaje = "¡Tu equipo está listo! La solicitud #$id ha sido completada. Puedes pasar a retirarlo o consultar detalles en tu panel.";
        $sql = "INSERT INTO notificaciones_cliente (cliente_id, titulo, mensaje, tipo, leida, solicitud_id, fecha_creacion) 
                VALUES (?, ?, ?, 'completado', 0, ?, NOW())";
        $stmt = mysqli_prepare($db, $sql);
        $titulo = "¡Equipo listo para entrega!";
        mysqli_stmt_bind_param($stmt, "issi", $cliente_id, $titulo, $mensaje, $id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }
}

header("Location: ../administrador/administrador.php#solicitudes");
exit;
?>