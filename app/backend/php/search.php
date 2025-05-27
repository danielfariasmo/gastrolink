<?php
header('Content-Type: application/json');
include '../../../server/database.php';

$searchQuery = isset($_GET['query']) ? '%' . $_GET['query'] . '%' : '%';

try {
    $resultados = [];

    // Camareros
    $query = "SELECT u.id_usuario, u.nombre, 'camarero' AS tipo FROM usuario u INNER JOIN camarero c ON u.id_usuario = c.id_camarero WHERE u.nombre LIKE ?";
    $stmt = mysqli_prepare($connection, $query);
    mysqli_stmt_bind_param($stmt, 's', $searchQuery);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    while ($row = mysqli_fetch_assoc($res)) {
        $resultados[] = $row;
    }

    // Cocineros
    $query = "SELECT u.id_usuario, u.nombre, 'cocinero' AS tipo FROM usuario u INNER JOIN cocinero c ON u.id_usuario = c.id_cocinero WHERE u.nombre LIKE ?";
    $stmt = mysqli_prepare($connection, $query);
    mysqli_stmt_bind_param($stmt, 's', $searchQuery);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    while ($row = mysqli_fetch_assoc($res)) {
        $resultados[] = $row;
    }

    // Restaurantes
    $query = "SELECT u.id_usuario, u.nombre, 'restaurante' AS tipo FROM usuario u INNER JOIN restaurante r ON u.id_usuario = r.id_restaurante WHERE u.nombre LIKE ?";
    $stmt = mysqli_prepare($connection, $query);
    mysqli_stmt_bind_param($stmt, 's', $searchQuery);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    while ($row = mysqli_fetch_assoc($res)) {
        $resultados[] = $row;
    }

    // Recetas
    $query = "SELECT id_receta AS id_usuario, titulo AS nombre, 'receta' AS tipo FROM receta WHERE titulo LIKE ?";
    $stmt = mysqli_prepare($connection, $query);
    mysqli_stmt_bind_param($stmt, 's', $searchQuery);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    while ($row = mysqli_fetch_assoc($res)) {
        $resultados[] = $row;
    }

    echo json_encode($resultados);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
?>
