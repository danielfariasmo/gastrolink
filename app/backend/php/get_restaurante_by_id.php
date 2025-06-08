<?php
include '../../../server/database.php';
header('Content-Type: application/json');

$id_restaurante = $_GET['id_restaurante'] ?? null;

if (!$id_restaurante) {
    echo json_encode(['success' => false, 'message' => 'ID del restaurante no especificado']);
    exit;
}

try {
    $stmt = $connection->prepare("
    SELECT 
        r.tipo_restaurante,
        r.descripcion,
        r.direccion,
        r.web,
        r.telefono,
        r.rango_precio,
        r.ubicacion,
        r.historial,
        u.nombre AS nombre_restaurante,
        u.correo,
        u.img_usuario
    FROM restaurante r
    JOIN usuario u ON r.id_restaurante = u.id_usuario
    WHERE r.id_restaurante = ?
");

    $stmt->bind_param('i', $id_restaurante);
    $stmt->execute();
    $result = $stmt->get_result();

    if (!$row = $result->fetch_assoc()) {
        echo json_encode(['success' => false, 'message' => 'Restaurante no encontrado']);
        exit;
    }
    $restaurante = $row;

    $stmt = $connection->prepare("
        SELECT dia_semana, hora_apertura, hora_cierre
        FROM horario_restaurante
        WHERE id_restaurante = ?
        ORDER BY FIELD(dia_semana, 'Lunes','Martes','Miércoles','Jueves','Viernes','Sábado','Domingo')
    ");
    $stmt->bind_param('i', $id_restaurante);
    $stmt->execute();
    $result = $stmt->get_result();

    $horarios = [];
    while ($horario = $result->fetch_assoc()) {
        $horarios[] = $horario;
    }
    $restaurante['horarios'] = $horarios;

    $stmt = $connection->prepare("
        SELECT url_imagen, alt
        FROM imagen_restaurante
        WHERE id_restaurante = ?
    ");
    $stmt->bind_param('i', $id_restaurante);
    $stmt->execute();
    $result = $stmt->get_result();

    $imagenes = [];
    while ($img = $result->fetch_assoc()) {
        $imagenes[] = $img;
    }
    $restaurante['imagenes'] = $imagenes;

    echo json_encode(['success' => true, 'restaurante' => $restaurante], JSON_UNESCAPED_UNICODE);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error en el servidor: ' . $e->getMessage()]);
}
