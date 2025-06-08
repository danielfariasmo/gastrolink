<?php
header('Content-Type: application/json');
include '../../../server/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
    exit;
}

// DELETE
$data = json_decode(file_get_contents('php://input'), true);

$id_oferta = isset($data['id_oferta']) ? intval($data['id_oferta']) : 0;
$id_restaurante = isset($data['id_restaurante']) ? intval($data['id_restaurante']) : 0;

if ($id_oferta <= 0 || $id_restaurante <= 0) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Datos inválidos']);
    exit;
}

// Borrar oferta 
$sql = "DELETE FROM oferta WHERE id_oferta = $id_oferta AND id_restaurante = $id_restaurante";
if (mysqli_query($connection, $sql)) {
    echo json_encode(['success' => true]);
} else {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Error al eliminar la oferta']);
}
