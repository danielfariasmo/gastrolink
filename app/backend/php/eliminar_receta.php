<?php
header("Content-Type: application/json");

// Asegurar la ruta correcta al database.php (ajusta según tu estructura)
$database_path = __DIR__ . '/../../../server/database.php';
if (!file_exists($database_path)) {
    echo json_encode(['success' => false, 'message' => 'Error de configuración del servidor']);
    exit;
}

include $database_path;

// Verificar método HTTP
if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
    exit;
}

// Obtener parámetros de la URL
$query_string = $_SERVER['QUERY_STRING'] ?? '';
parse_str($query_string, $params);

$id_receta = isset($params['id_receta']) ? intval($params['id_receta']) : null;
$id_cocinero = isset($params['id_cocinero']) ? intval($params['id_cocinero']) : null;

// Validar parámetros
if (!$id_receta || !$id_cocinero) {
    echo json_encode([
        'success' => false,
        'message' => 'Parámetros requeridos no proporcionados',
        'received' => ['id_receta' => $id_receta, 'id_cocinero' => $id_cocinero]
    ]);
    exit;
}

// Verificar conexión a la base de datos
if (!$connection) {
    echo json_encode(['success' => false, 'message' => 'Error de conexión a la base de datos']);
    exit;
}

// Verificar que la receta pertenece al cocinero
$query_verificar = "SELECT id_cocinero FROM receta WHERE id_receta = ?";
$stmt = $connection->prepare($query_verificar);
$stmt->bind_param("i", $id_receta);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(['success' => false, 'message' => 'Receta no encontrada']);
    exit;
}

$receta = $result->fetch_assoc();
if ($receta['id_cocinero'] != $id_cocinero) {
    echo json_encode(['success' => false, 'message' => 'No tienes permiso para eliminar esta receta']);
    exit;
}

// Eliminar la receta (usando sentencias preparadas para seguridad)
$query_eliminar = "DELETE FROM receta WHERE id_receta = ? AND id_cocinero = ?";
$stmt = $connection->prepare($query_eliminar);
$stmt->bind_param("ii", $id_receta, $id_cocinero);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Receta eliminada con éxito']);
} else {
    echo json_encode(['success' => false, 'message' => 'Error al eliminar la receta: ' . $connection->error]);
}

$stmt->close();
$connection->close();
?>