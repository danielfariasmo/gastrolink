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
$correo = trim($_POST['email']); // Mantengo el nombre que usas en el frontend
$descripcion = trim($_POST['descripcion']);
$experiencia = trim($_POST['experiencia']);
$especialidad = trim($_POST['especialidad']);

if (!$id || !$nombre || !$correo) {
    echo json_encode(['success' => false, 'message' => 'Faltan campos obligatorios']);
    exit;
}

// Obtener imagen actual
$imgPath = null;
$sqlImagen = "SELECT img_usuario FROM usuario WHERE id_usuario = ?";
$stmtImagen = $connection->prepare($sqlImagen);
$stmtImagen->bind_param("i", $id);
$stmtImagen->execute();
$resultImagen = $stmtImagen->get_result();
$imgActual = $resultImagen->fetch_assoc()['img_usuario'] ?? null;
$stmtImagen->close();

// Procesar nueva imagen si se subió
if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === 0) {
    $nombreArchivo = 'cocinero_' . $id . '_' . time() . '.' . pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION);
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

// Actualizar tabla usuario
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

// Comprobar si ya existe en cocinero
$sqlCheck = "SELECT id_cocinero FROM cocinero WHERE id_cocinero = ?";
$stmtCheck = $connection->prepare($sqlCheck);
$stmtCheck->bind_param("i", $id);
$stmtCheck->execute();
$resultCheck = $stmtCheck->get_result();
$existe = $resultCheck->num_rows > 0;
$stmtCheck->close();

// INSERT o UPDATE en cocinero
if ($existe) {
    $sqlCocinero = "UPDATE cocinero SET descripcion = ?, experiencia = ?, especialidad = ? WHERE id_cocinero = ?";
    $stmtCocinero = $connection->prepare($sqlCocinero);
    $stmtCocinero->bind_param("sssi", $descripcion, $experiencia, $especialidad, $id);
} else {
    $sqlCocinero = "INSERT INTO cocinero (id_cocinero, descripcion, experiencia, especialidad) VALUES (?, ?, ?, ?)";
    $stmtCocinero = $connection->prepare($sqlCocinero);
    $stmtCocinero->bind_param("isss", $id, $descripcion, $experiencia, $especialidad);
}

if ($stmtCocinero->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Error al guardar los datos del cocinero']);
}

$stmtCocinero->close();
$connection->close();
