<?php
header('Content-Type: application/json');
include '../../../server/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
    exit;
}

// Recibir datos
$id = intval($_POST['id']);
$nombre = trim($_POST['nombre']);
$correo = trim($_POST['correo']);
$descripcion = trim($_POST['descripcion']);
$experiencia = trim($_POST['experiencia']);
$idiomas = trim($_POST['idiomas']);

if (!$id || !$nombre || !$correo) {
    echo json_encode(['success' => false, 'message' => 'Faltan campos obligatorios']);
    exit;
}

// Imagen
$imgPath = null;
$sqlImagen = "SELECT img_usuario FROM usuario WHERE id_usuario = ?";
$stmtImagen = $connection->prepare($sqlImagen);
$stmtImagen->bind_param("i", $id);
$stmtImagen->execute();
$resultImagen = $stmtImagen->get_result();
$imgActual = $resultImagen->fetch_assoc()['img_usuario'] ?? null;
$stmtImagen->close();

// Si se sube nueva imagen, se reemplaza
if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === 0) {
    $nombreArchivo = 'camarero_' . $id . '_' . time() . '.' . pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION);
    $rutaDestino = '../../img/usuarios/' . $nombreArchivo;
    if (move_uploaded_file($_FILES['imagen']['tmp_name'], $rutaDestino)) {
        $imgPath = '/gastrolink/app/img/usuarios/' . $nombreArchivo;
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al subir la imagen']);
        exit;
    }
} else {
    $imgPath = $imgActual;
}

// Actualizar usuario 
if ($imgPath) {
    $sqlUsuario = "UPDATE usuario SET nombre = ?, correo = ?, img_usuario = ? WHERE id_usuario = ?";
    $stmtUsuario = $connection->prepare($sqlUsuario);
    $stmtUsuario->bind_param("sssi", $nombre, $correo, $imgPath, $id);
} else {
    $sqlUsuario = "UPDATE usuario SET nombre = ?, correo = ? WHERE id_usuario = ?";
    $stmtUsuario = $connection->prepare($sqlUsuario);
    $stmtUsuario->bind_param("ssi", $nombre, $correo, $id);
}
$stmtUsuario->execute();
$stmtUsuario->close();

// Comprobar si ya existe en camarero 
$sqlCheck = "SELECT id_camarero FROM camarero WHERE id_camarero = ?";
$stmtCheck = $connection->prepare($sqlCheck);
$stmtCheck->bind_param("i", $id);
$stmtCheck->execute();
$resultCheck = $stmtCheck->get_result();
$existe = $resultCheck->num_rows > 0;
$stmtCheck->close();

// INSERT o UPDATE en camarero 
if ($existe) {
    $sqlCamarero = "UPDATE camarero SET descripcion = ?, experiencia = ?, idiomas = ? WHERE id_camarero = ?";
    $stmtCamarero = $connection->prepare($sqlCamarero);
    $stmtCamarero->bind_param("sssi", $descripcion, $experiencia, $idiomas, $id);
} else {
    $sqlCamarero = "INSERT INTO camarero (id_camarero, descripcion, experiencia, idiomas) VALUES (?, ?, ?, ?)";
    $stmtCamarero = $connection->prepare($sqlCamarero);
    $stmtCamarero->bind_param("isss", $id, $descripcion, $experiencia, $idiomas);
}

if ($stmtCamarero->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Error al guardar los datos del camarero']);
}

$stmtCamarero->close();
$connection->close();
