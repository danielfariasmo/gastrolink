<?php
header("Content-Type: application/json");
include '../../../server/database.php';

// Verificar si se recibió el ID del camarero
if (!isset($_GET['id_camarero'])) {
    echo json_encode(['success' => false, 'message' => 'ID de camarero no proporcionado']);
    exit;
}

$id_camarero = intval($_GET['id_camarero']);

// Obtener información básica del usuario
$query_usuario = "SELECT u.id_usuario, u.nombre, u.correo, u.img_usuario, u.tipo_usuario, 
                 c.descripcion, c.experiencia, c.idiomas
                 FROM usuario u
                 JOIN camarero c ON u.id_usuario = c.id_camarero
                 WHERE u.id_usuario = $id_camarero";

$result_usuario = mysqli_query($connection, $query_usuario);

if (!$result_usuario || mysqli_num_rows($result_usuario) === 0) {
    echo json_encode(['success' => false, 'message' => 'Camarero no encontrado']);
    exit;
}

$camarero = mysqli_fetch_assoc($result_usuario);

// Obtener recetas favoritas del camarero
$query_recetas_favoritas = "SELECT r.id_receta, r.titulo, r.img_receta, r.tiempo_preparacion, r.dificultad, 
                           u.nombre as nombre_chef
                           FROM favorito_receta fr
                           JOIN receta r ON fr.id_receta = r.id_receta
                           JOIN cocinero c ON r.id_cocinero = c.id_cocinero
                           JOIN usuario u ON c.id_cocinero = u.id_usuario
                           WHERE fr.id_usuario = $id_camarero";

$result_recetas = mysqli_query($connection, $query_recetas_favoritas);
$recetas_favoritas = [];
while ($row = mysqli_fetch_assoc($result_recetas)) {
    $recetas_favoritas[] = $row;
}

// Obtener restaurantes favoritos del camarero
$query_restaurantes_favoritos = "SELECT res.id_restaurante, u.nombre, u.img_usuario, res.tipo_restaurante
                               FROM favorito_restaurante fr
                               JOIN restaurante res ON fr.id_restaurante = res.id_restaurante
                               JOIN usuario u ON res.id_restaurante = u.id_usuario
                               WHERE fr.id_usuario = $id_camarero";

$result_restaurantes = mysqli_query($connection, $query_restaurantes_favoritos);
$restaurantes_favoritos = [];
while ($row = mysqli_fetch_assoc($result_restaurantes)) {
    $restaurantes_favoritos[] = $row;
}

// Preparar la respuesta
$response = [
    'success' => true,
    'camarero' => [
        'id' => $camarero['id_usuario'],
        'nombre' => $camarero['nombre'],
        'correo' => $camarero['correo'],
        'imagen' => $camarero['img_usuario'],
        'tipo' => $camarero['tipo_usuario'],
        'descripcion' => $camarero['descripcion'],
        'experiencia' => $camarero['experiencia'],
        'idiomas' => $camarero['idiomas']
    ],
    'recetas_favoritas' => $recetas_favoritas,
    'restaurantes_favoritos' => $restaurantes_favoritos
];

echo json_encode($response);
mysqli_close($connection);
?>