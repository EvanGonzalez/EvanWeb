<?php
session_start();
require_once "../conector_DB/db.php";
$id = intval($_POST['id'] ?? 0);
if ($id > 0 && isset($_SESSION['cliente_id'])) {
    $sql = "UPDATE notificaciones_cliente SET leida = 1 WHERE id = ? AND cliente_id = ?";
    $stmt = mysqli_prepare($db, $sql);
    mysqli_stmt_bind_param($stmt, "ii", $id, $_SESSION['cliente_id']);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}
header("Location: clienWeb.php#notificaciones");
exit;
?>