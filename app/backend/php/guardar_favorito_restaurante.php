<?php
session_start();
header("Content-Type: application/json");

include "../../../server/database.php";

if (!$connection) {
    echo json_encode(['success' => false, 'message' => 'Error de conexión a la base de datos']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);

if (empty($data['restaurante_id']) || empty($data['usuario_id'])) {
    echo json_encode(['error' => 'Datos incompletos']);
    exit;
}

$restauranteId = mysqli_real_escape_string($connection, $data['restaurante_id']);
$usuarioId = mysqli_real_escape_string($connection, $data['usuario_id']);

// Verificar si ya existe
$checkQuery = "SELECT COUNT(*) FROM favorito_restaurante WHERE id_usuario = '$usuarioId' AND id_restaurante = '$restauranteId'";
$checkResult = mysqli_query($connection, $checkQuery);

if (!$checkResult) {
    echo json_encode(['error' => mysqli_error($connection)]);
    exit;
}

$count = mysqli_fetch_row($checkResult)[0];

if ($count > 0) {
    echo json_encode(['success' => true, 'message' => 'Ya estaba guardado']);
    exit;
}

// Insertar nuevo favorito
$insertQuery = "INSERT INTO favorito_restaurante (id_usuario, id_restaurante) VALUES ('$usuarioId', '$restauranteId')";
$insertResult = mysqli_query($connection, $insertQuery);

if ($insertResult) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['error' => mysqli_error($connection)]);
}

mysqli_close($connection);
?>