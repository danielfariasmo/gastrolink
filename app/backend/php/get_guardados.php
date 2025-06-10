<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
header('Content-Type: application/json');
include "../../../server/database.php";

if (!isset($_GET['id_usuario'])) {
    echo json_encode(['success' => false, 'message' => 'ID de usuario no proporcionado']);
    exit;
}

$id_usuario = intval($_GET['id_usuario']);

// Obtener recetas favoritas
$sqlRecetas = "
    SELECT 
        r.id_receta,
        r.titulo,
        r.tiempo_preparacion,
        r.dificultad,
        r.img_receta
    FROM favorito_receta f
    INNER JOIN receta r ON f.id_receta = r.id_receta
    WHERE f.id_usuario = ?
";

$stmtRecetas = $connection->prepare($sqlRecetas);
$stmtRecetas->bind_param("i", $id_usuario);
$stmtRecetas->execute();
$resultRecetas = $stmtRecetas->get_result();

$recetas = [];
while ($row = $resultRecetas->fetch_assoc()) {
    $recetas[] = $row;
}

// Obtener restaurantes favoritos
$sqlRestaurantes = "
    SELECT 
        res.id_restaurante,
        u.nombre,
        u.img_usuario,
        res.tipo_restaurante,
        res.direccion
    FROM favorito_restaurante f
    INNER JOIN restaurante res ON f.id_restaurante = res.id_restaurante
    INNER JOIN usuario u ON res.id_restaurante = u.id_usuario
    WHERE f.id_usuario = ?
";

$stmtRestaurantes = $connection->prepare($sqlRestaurantes);
$stmtRestaurantes->bind_param("i", $id_usuario);
$stmtRestaurantes->execute();
$resultRestaurantes = $stmtRestaurantes->get_result();

$restaurantes = [];
while ($row = $resultRestaurantes->fetch_assoc()) {
    $restaurantes[] = $row;
}

// Devolver respuesta combinada
echo json_encode([
    'success' => true,
    'recetas' => $recetas,
    'restaurantes' => $restaurantes
]);
