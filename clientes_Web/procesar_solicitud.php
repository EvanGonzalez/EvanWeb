<?php
session_start();
if (!isset($_SESSION['cliente_id']) || $_SESSION['rol'] !== 'cliente') {
    header("Location: ../login.php");
    exit;
}

require_once "../conector_DB/db.php";

$cliente_id = $_SESSION['cliente_id'];
$titulo = mb_convert_case(trim($_POST['titulo'] ?? ''), MB_CASE_TITLE, "UTF-8");
$descripcion = ucfirst(mb_strtolower(trim($_POST['descripcion'] ?? ''), "UTF-8"));
$tipo_servicio = trim($_POST['tipo_servicio'] ?? '');
$prioridad = trim($_POST['prioridad'] ?? '');
$direccion_servicio = mb_convert_case(trim($_POST['direccion_servicio'] ?? ''), MB_CASE_TITLE, "UTF-8");
$telefono_contacto = trim($_POST['telefono_contacto'] ?? '');
$fecha_preferida = trim($_POST['fecha_preferida'] ?? '');
$hora_preferida = trim($_POST['hora_preferida'] ?? '');

if (empty($descripcion)) {
    header("Location: clienWeb.php?error=descripcion");
    exit;
}

// Insertar la solicitud (ajusta los campos obligatorios según tu tabla)
$sql = "INSERT INTO solicitudes_servicio 
    (cliente_id, tipo_servicio, titulo, descripcion, prioridad, direccion_servicio, telefono_contacto, fecha_preferida, hora_preferida, estado, fecha_solicitud)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'pendiente', NOW())";
$stmt = mysqli_prepare($db, $sql);
mysqli_stmt_bind_param(
    $stmt, "issssssss",
    $cliente_id, $tipo_servicio, $titulo, $descripcion, $prioridad,
    $direccion_servicio, $telefono_contacto, $fecha_preferida, $hora_preferida
);
mysqli_stmt_execute($stmt);
$solicitud_id = mysqli_insert_id($db);
mysqli_stmt_close($stmt);

// Procesar imágenes
if (isset($_FILES['imagenes']) && $solicitud_id) {
    $total = count($_FILES['imagenes']['name']);
    $total = min($total, 3); // Máximo 3 imágenes

    for ($i = 0; $i < $total; $i++) {
        $tmp = $_FILES['imagenes']['tmp_name'][$i];
        $nombre_original = $_FILES['imagenes']['name'][$i];
        $tipo_archivo = $_FILES['imagenes']['type'][$i];
        $tamano_archivo = $_FILES['imagenes']['size'][$i];
        $ext = strtolower(pathinfo($nombre_original, PATHINFO_EXTENSION));
        $permitidas = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

        if (in_array($ext, $permitidas) && is_uploaded_file($tmp)) {
            $nuevo_nombre = uniqid('img_') . '.' . $ext;
            $ruta_destino = "../uploads/solicitudes/" . $nuevo_nombre;

            if (!is_dir("../uploads/solicitudes/")) {
                mkdir("../uploads/solicitudes/", 0777, true);
            }

            if (move_uploaded_file($tmp, $ruta_destino)) {
                // Guarda la información en la tabla solicitud_archivos
                $sql_img = "INSERT INTO solicitud_archivos (solicitud_id, nombre_original, nombre_archivo, tipo_archivo, tamano_archivo, ruta_archivo) VALUES (?, ?, ?, ?, ?, ?)";
                $stmt_img = mysqli_prepare($db, $sql_img);
                mysqli_stmt_bind_param($stmt_img, "isssis", $solicitud_id, $nombre_original, $nuevo_nombre, $tipo_archivo, $tamano_archivo, $ruta_destino);
                mysqli_stmt_execute($stmt_img);
                mysqli_stmt_close($stmt_img);
            }
        }
    }
}

header("Location: clienWeb.php?exito=1");
exit;
?>