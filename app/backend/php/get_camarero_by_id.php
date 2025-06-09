<?php
header('Content-Type: application/json');
include '../../../server/database.php';

if (!isset($_GET['id'])) {
    echo json_encode([
        'success' => false,
        'message' => 'ID no proporcionado'
    ]);
    exit;
}

$id = intval($_GET['id']);

$sql = "SELECT u.id_usuario, u.nombre, u.correo, u.img_usuario, c.descripcion, c.experiencia, c.idiomas
        FROM usuario u
        LEFT JOIN camarero c ON u.id_usuario = c.id_camarero
        WHERE u.id_usuario = ? AND u.tipo_usuario = 'camarero'";

$stmt = $connection->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();

$resultado = $stmt->get_result();
if ($resultado->num_rows === 1) {
    $camarero = $resultado->fetch_assoc();
    echo json_encode([
        'success' => true,
        'camarero' => $camarero
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Camarero no encontrado'
    ]);
}

$stmt->close();
$connection->close();

