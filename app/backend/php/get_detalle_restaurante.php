<?php
require_once '../../../server/database.php';
header('Content-Type: application/json');

if (!isset($_GET['id'])) {
    echo json_encode(['error' => 'ID no proporcionado']);
    exit;
}

// Validación de ID
$id = intval($_GET['id']);

// Get restaurant details
$sql = "SELECT 
            u.nombre, u.img_usuario, u.correo,
            r.tipo_restaurante, r.descripcion, r.direccion, r.web, r.telefono, r.ubicacion, r.historial, r.rango_precio
        FROM usuario u
        JOIN restaurante r ON u.id_usuario = r.id_restaurante
        WHERE u.id_usuario = ?";


$stmt = $connection->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$datos = $result->fetch_assoc();
$stmt->close();

if (!$datos) {
    echo json_encode(['error' => 'Restaurante no encontrado']);
    exit;
}

// Hourly schedule
$sql_horario = "SELECT dia_semana, hora_apertura, hora_cierre FROM horario_restaurante WHERE id_restaurante = ? 
ORDER BY FIELD(dia_semana, 'Lunes','Martes','Miércoles','Jueves','Viernes','Sábado','Domingo')";
$stmt = $connection->prepare($sql_horario);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$horarios = [];
while ($row = $result->fetch_assoc()) {
    $horarios[] = $row;
}
$stmt->close();

// Images
$sql_img = "SELECT url_imagen, alt FROM imagen_restaurante WHERE id_restaurante = ?";
$stmt = $connection->prepare($sql_img);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$imagenes = [];
while ($row = $result->fetch_assoc()) {
    $imagenes[] = $row;
}
$stmt->close();

// Similar restaurants
$sql_similares = "SELECT u.id_usuario, u.nombre, u.img_usuario, r.tipo_restaurante, r.rango_precio, r.descripcion 
                  FROM usuario u 
                  JOIN restaurante r ON u.id_usuario = r.id_restaurante 
                  WHERE r.tipo_restaurante = ? AND u.id_usuario != ?";
$stmt = $connection->prepare($sql_similares);
$stmt->bind_param("si", $datos['tipo_restaurante'], $id);
$stmt->execute();
$result = $stmt->get_result();
$similares = [];
while ($row = $result->fetch_assoc()) {
    $similares[] = $row;
}
$stmt->close();


// Answer
echo json_encode([
    'restaurante' => $datos,
    'horarios' => $horarios,
    'imagenes' => $imagenes,
    'similares' => $similares
]);
