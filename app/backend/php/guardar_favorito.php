<?php
session_start();
header("Content-Type: application/json");

include "../../../server/database.php";

// Verificar conexión a la base de datos
if (!$connection) {
    echo json_encode(['success' => false, 'message' => 'Error de conexión a la base de datos']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);

if (empty($data['receta_id']) || empty($data['usuario_id'])) {
    echo json_encode(['error' => 'Datos incompletos']);
    exit;
}

$recetaId = mysqli_real_escape_string($connection, $data['receta_id']);
$usuarioId = mysqli_real_escape_string($connection, $data['usuario_id']);

// Verificar si ya existe
$checkQuery = "SELECT COUNT(*) FROM favorito_receta WHERE id_usuario = '$usuarioId' AND id_receta = '$recetaId'";
$checkResult = mysqli_query($connection, $checkQuery);

if (!$checkResult) {
    echo json_encode(['error' => mysqli_error($connection)]);
    exit;
}

$count = mysqli_fetch_row($checkResult)[0];

if ($count > 0) {
    echo json_encode(['success' => true, 'message' => 'Ya estaba guardada']);
    exit;
}

// Insertar nuevo favorito
$insertQuery = "INSERT INTO favorito_receta (id_usuario, id_receta) VALUES ('$usuarioId', '$recetaId')";
$insertResult = mysqli_query($connection, $insertQuery);

if ($insertResult) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['error' => mysqli_error($connection)]);
}

mysqli_close($connection);
?>