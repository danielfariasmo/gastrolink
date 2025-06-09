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

$query = "DELETE FROM favorito_receta WHERE id_usuario = '$usuarioId' AND id_receta = '$recetaId'";
$result = mysqli_query($connection, $query);

if ($result) {
    echo json_encode(['success' => mysqli_affected_rows($connection) > 0]);
} else {
    echo json_encode(['error' => mysqli_error($connection)]);
}

mysqli_close($connection);
?>