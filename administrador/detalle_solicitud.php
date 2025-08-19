<?php
require_once "../conector_DB/db.php";
$id = intval($_GET['id'] ?? 0);
$sql = "SELECT s.*, c.nombre_completo FROM solicitudes_servicio s LEFT JOIN clientes c ON s.cliente_id = c.id WHERE s.id = ?";
$stmt = mysqli_prepare($db, $sql);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$res = mysqli_stmt_get_result($stmt);
$sol = mysqli_fetch_assoc($res);
mysqli_stmt_close($stmt);

if (!$sol) {
    echo "<p>No encontrada.</p>";
    exit;
}

// Obtener imágenes adjuntas
$imagenes = [];
$sql = "SELECT nombre_archivo FROM solicitud_archivos WHERE solicitud_id = ?";
$stmt = mysqli_prepare($db, $sql);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$res = mysqli_stmt_get_result($stmt);
while ($img = mysqli_fetch_assoc($res)) {
    $imagenes[] = $img['nombre_archivo'];
}
mysqli_stmt_close($stmt);
?>
<div class="detalle-solicitud-panel">
    <h3>Solicitud #<?php echo $sol['id']; ?></h3>
    <p><strong>Cliente:</strong> <?php echo htmlspecialchars($sol['nombre_completo']); ?></p>
    <p><strong>Título:</strong> <?php echo htmlspecialchars($sol['titulo']); ?></p>
    <p><strong>Tipo de servicio:</strong> <?php echo htmlspecialchars($sol['tipo_servicio']); ?></p>
    <p><strong>Descripción:</strong><br>
        <span style="white-space: pre-line;"><?php echo htmlspecialchars($sol['descripcion']); ?></span>
    </p>
    <p><strong>Prioridad:</strong> <?php echo htmlspecialchars($sol['prioridad']); ?></p>
    <p><strong>Estado:</strong> <?php echo htmlspecialchars($sol['estado']); ?></p>
    <p><strong>Fecha de solicitud:</strong> <?php echo htmlspecialchars($sol['fecha_solicitud']); ?></p>
    <?php if ($imagenes): ?>
        <div><strong>Imágenes adjuntas:</strong><br>
            <?php foreach ($imagenes as $img): ?>
                <img src="../uploads/solicitudes/<?php echo htmlspecialchars($img); ?>" style="max-width:320px; margin:10px;">
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
    <?php
    if ($sol['estado'] === 'en_proceso') {
        // Botón para completar
        echo '<form method="post" action="cambiar_estado_solicitud.php" style="display:inline;">
                <input type="hidden" name="id" value="'.$sol['id'].'">
                <input type="hidden" name="nuevo_estado" value="completada">
                <button type="submit" class="btn btn-success">Marcar como Completada</button>
              </form>';
        // Botón para cancelar
        echo '<form method="post" action="cambiar_estado_solicitud.php" style="display:inline; margin-left:10px;">
                <input type="hidden" name="id" value="'.$sol['id'].'">
                <input type="hidden" name="nuevo_estado" value="cancelada">
                <button type="submit" class="btn btn-danger">Cancelar</button>
              </form>';
    } elseif ($sol['estado'] === 'completada') {
        echo '<div class="alert alert-success" style="margin-top:20px;">Esta solicitud ya está completada.</div>';
    } elseif ($sol['estado'] === 'cancelada') {
        echo '<div class="alert alert-danger" style="margin-top:20px;">Esta solicitud fue cancelada.</div>';
    }
    ?>
</div>