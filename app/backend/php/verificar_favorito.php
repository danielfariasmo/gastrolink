<?php
session_start();
header("Content-Type: application/json");

include "../../../server/database.php";

// Verificar conexión a la base de datos
if (!$connection) {
    echo json_encode(['success' => false, 'message' => 'Error de conexión a la base de datos']);
    exit;
}

$recetaId = $_GET['receta_id'] ?? null;
$usuarioId = $_GET['usuario_id'] ?? null;

if (!$recetaId || !$usuarioId) {
    echo json_encode(['error' => 'Datos incompletos']);
    exit;
}

$recetaId = mysqli_real_escape_string($connection, $recetaId);
$usuarioId = mysqli_real_escape_string($connection, $usuarioId);

$query = "SELECT COUNT(*) FROM favorito_receta WHERE id_usuario = '$usuarioId' AND id_receta = '$recetaId'";
$result = mysqli_query($connection, $query);

if ($result) {
    $count = mysqli_fetch_row($result)[0];
    echo json_encode(['isSaved' => $count > 0]);
} else {
    echo json_encode(['error' => mysqli_error($connection)]);
}

mysqli_close($connection);
?>