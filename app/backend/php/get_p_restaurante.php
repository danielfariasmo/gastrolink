<?php
header("Content-Type: application/json");
include '../../../server/database.php';

// Verificar si se recibió el ID del restaurante
if (!isset($_GET['id_restaurante'])) {
    echo json_encode(['success' => false, 'message' => 'ID de restaurante no proporcionado']);
    exit;
}

$id_restaurante = intval($_GET['id_restaurante']);

// Obtener información básica del restaurante
$query_restaurante = "SELECT u.id_usuario, u.nombre, u.correo, u.img_usuario, u.tipo_usuario, 
                     r.tipo_restaurante, r.descripcion, r.direccion, r.web, r.telefono
                     FROM usuario u
                     JOIN restaurante r ON u.id_usuario = r.id_restaurante
                     WHERE u.id_usuario = $id_restaurante";

$result_restaurante = mysqli_query($connection, $query_restaurante);

if (!$result_restaurante || mysqli_num_rows($result_restaurante) === 0) {
    echo json_encode(['success' => false, 'message' => 'Restaurante no encontrado']);
    exit;
}

$restaurante = mysqli_fetch_assoc($result_restaurante);

// Obtener ofertas de empleo del restaurante
$query_ofertas = "SELECT id_oferta, titulo, descripcion, tipo_puesto, fecha_publicacion, estado
                 FROM oferta
                 WHERE id_restaurante = $id_restaurante
                 ORDER BY fecha_publicacion DESC";

$result_ofertas = mysqli_query($connection, $query_ofertas);
$ofertas = [];
while ($row = mysqli_fetch_assoc($result_ofertas)) {
    $ofertas[] = $row;
}

// Preparar la respuesta
$response = [
    'success' => true,
    'restaurante' => [
        'id' => $restaurante['id_usuario'],
        'nombre' => $restaurante['nombre'],
        'correo' => $restaurante['correo'],
        'imagen' => $restaurante['img_usuario'],
        'tipo' => $restaurante['tipo_usuario'],
        'tipo_restaurante' => $restaurante['tipo_restaurante'],
        'descripcion' => $restaurante['descripcion'],
        'direccion' => $restaurante['direccion'],
        'web' => $restaurante['web'],
        'telefono' => $restaurante['telefono']
    ],
    'ofertas' => $ofertas
];

echo json_encode($response);
mysqli_close($connection);
?>