<?php
require '../../../server/database.php';

header('Content-Type: application/json');

$id_usuario = $_GET['id_usuario'] ?? null;

if (!$id_usuario) {
    echo json_encode(['success' => false, 'message' => 'Falta el ID del usuario']);
    exit;
}

try {
    $favoritos = [
        'restaurantes' => [],
        'recetas' => []
    ];

    // 🔹 Restaurantes favoritos
    $stmt = $connection->prepare("
        SELECT u.id_usuario, u.nombre, u.img_usuario
        FROM favorito_restaurante f
        JOIN usuario u ON f.id_restaurante = u.id_usuario
        WHERE f.id_usuario = ?
    ");
    $stmt->bind_param('i', $id_usuario);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $favoritos['restaurantes'][] = $row;
    }

    // 🔹 Recetas favoritas
$stmt = $connection->prepare("
    SELECT r.id_receta, r.titulo AS nombre, r.img_receta AS imagen
    FROM favorito_receta f
    JOIN receta r ON f.id_receta = r.id_receta
    WHERE f.id_usuario = ?
");


    $stmt->bind_param('i', $id_usuario);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $favoritos['recetas'][] = $row;
    }


    echo json_encode(['success' => true] + $favoritos);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error en el servidor: ' . $e->getMessage()]);
}
