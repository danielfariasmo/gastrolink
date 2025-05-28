<?php
header("Content-Type: application/json");
include '../../../server/database.php';

// Verificar si se recibió el ID del cocinero
if (!isset($_GET['id_cocinero'])) {
    echo json_encode(['success' => false, 'message' => 'ID de cocinero no proporcionado']);
    exit;
}

$id_cocinero = intval($_GET['id_cocinero']);

// Obtener información básica del usuario
$query_usuario = "SELECT u.id_usuario, u.nombre, u.correo, u.img_usuario, u.tipo_usuario, 
                 c.descripcion, c.especialidad, c.experiencia
                 FROM usuario u
                 JOIN cocinero c ON u.id_usuario = c.id_cocinero
                 WHERE u.id_usuario = $id_cocinero";

$result_usuario = mysqli_query($connection, $query_usuario);

if (!$result_usuario || mysqli_num_rows($result_usuario) === 0) {
    echo json_encode(['success' => false, 'message' => 'Cocinero no encontrado']);
    exit;
}

$cocinero = mysqli_fetch_assoc($result_usuario);

// Obtener las recetas creadas por el cocinero
$query_recetas = "SELECT id_receta, titulo, img_receta, tiempo_preparacion, dificultad, fecha_publicacion
                 FROM receta
                 WHERE id_cocinero = $id_cocinero
                 ORDER BY fecha_publicacion DESC";

$result_recetas = mysqli_query($connection, $query_recetas);
$recetas = [];
while ($row = mysqli_fetch_assoc($result_recetas)) {
    $recetas[] = $row;
}

// Obtener restaurantes favoritos del cocinero (CONSULTA CORREGIDA)
$query_favoritos = "SELECT res.id_restaurante, u.nombre, u.img_usuario
                   FROM favorito_restaurante fr
                   JOIN restaurante res ON fr.id_restaurante = res.id_restaurante
                   JOIN usuario u ON res.id_restaurante = u.id_usuario
                   WHERE fr.id_usuario = $id_cocinero
                   LIMIT 5";

$result_favoritos = mysqli_query($connection, $query_favoritos);
$restaurantes_favoritos = [];
while ($row = mysqli_fetch_assoc($result_favoritos)) {
    $restaurantes_favoritos[] = $row;
}

// Preparar la respuesta
$response = [
    'success' => true,
    'cocinero' => [
        'id' => $cocinero['id_usuario'],
        'nombre' => $cocinero['nombre'],
        'correo' => $cocinero['correo'],
        'imagen' => $cocinero['img_usuario'],
        'tipo' => $cocinero['tipo_usuario'],
        'descripcion' => $cocinero['descripcion'],
        'especialidad' => $cocinero['especialidad'],
        'experiencia' => $cocinero['experiencia']
    ],
    'recetas' => $recetas,
    'restaurantes_favoritos' => $restaurantes_favoritos
];

echo json_encode($response);
mysqli_close($connection);
?>