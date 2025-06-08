<?php
header('Content-Type: application/json');

include '../../../server/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_oferta = $_POST['id_oferta'] ?? null;
    $id_restaurante = $_POST['id_restaurante'] ?? null;
    $titulo = $_POST['titulo'] ?? '';
    $descripcion = $_POST['descripcion'] ?? '';
    $tipo_puesto = $_POST['tipo_puesto'] ?? '';
    $fecha_publicacion = $_POST['fecha_publicacion'] ?? '';
    $estado = $_POST['estado'] ?? '';

    if (!$id_oferta || !$id_restaurante || !$titulo || !$tipo_puesto || !$fecha_publicacion || !$estado) {
        echo json_encode(['success' => false, 'message' => 'Campos obligatorios faltantes']);
        exit;
    }

    $stmt = $connection->prepare("UPDATE oferta 
    SET titulo = ?, descripcion = ?, tipo_puesto = ?, fecha_publicacion = ?, estado = ? WHERE id_oferta = ? AND id_restaurante = ?");
    $stmt->bind_param("ssssssi", $titulo, $descripcion, $tipo_puesto, $fecha_publicacion, $estado, $id_oferta, $id_restaurante);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al actualizar la oferta']);
    }
}
