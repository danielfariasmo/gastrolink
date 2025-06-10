<?php
header("Content-Type: application/json");
include '../../../server/database.php';

if (!isset($_GET['id'])) {
    echo json_encode(['success' => false, 'message' => 'ID no especificado']);
    exit;
}

$id = intval($_GET['id']);

$sql = "SELECT 
            u.nombre, 
            u.correo AS correo, 
            u.img_usuario AS img_usuario,
            c.descripcion, 
            c.especialidad, 
            c.experiencia
        FROM usuario u
        JOIN cocinero c ON u.id_usuario = c.id_cocinero
        WHERE u.id_usuario = ?";

$stmt = $connection->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$resultado = $stmt->get_result();

if ($fila = $resultado->fetch_assoc()) {
    echo json_encode(['success' => true, 'cocinero' => $fila]);
} else {
    echo json_encode(['success' => false, 'message' => 'Cocinero no encontrado']);
}

$stmt->close();
$connection->close();
