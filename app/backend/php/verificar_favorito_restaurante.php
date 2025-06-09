<?php
session_start();
header("Content-Type: application/json");

include "../../../server/database.php";

if (!$connection) {
    echo json_encode(['success' => false, 'message' => 'Error de conexión a la base de datos']);
    exit;
}

$restauranteId = $_GET['restaurante_id'] ?? null;
$usuarioId = $_GET['usuario_id'] ?? null;

if (!$restauranteId || !$usuarioId) {
    echo json_encode(['error' => 'Datos incompletos']);
    exit;
}

$restauranteId = mysqli_real_escape_string($connection, $restauranteId);
$usuarioId = mysqli_real_escape_string($connection, $usuarioId);

$query = "SELECT COUNT(*) FROM favorito_restaurante WHERE id_usuario = '$usuarioId' AND id_restaurante = '$restauranteId'";
$result = mysqli_query($connection, $query);

if ($result) {
    $count = mysqli_fetch_row($result)[0];
    echo json_encode(['isSaved' => $count > 0]);
} else {
    echo json_encode(['error' => mysqli_error($connection)]);
}

mysqli_close($connection);
?>