<?php
header('Content-Type: application/json');
require_once '../../../server/database.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$filtro = isset($_GET['filtro']) ? $_GET['filtro'] : 'todos';

try {
    $query = "SELECT u.id_usuario, u.nombre, u.img_usuario, r.tipo_restaurante, r.descripcion, r.direccion, r.web, r.telefono 
              FROM usuario u 
              INNER JOIN restaurante r ON u.id_usuario = r.id_restaurante";

    if ($filtro !== 'todos') {
        $query .= " WHERE r.tipo_restaurante = ?";
    }

    $stmt = mysqli_prepare($connection, $query);

    if ($filtro !== 'todos') {
        mysqli_stmt_bind_param($stmt, 's', $filtro);
    }

    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $restaurantes = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $restaurantes[] = $row;
    }

    echo json_encode($restaurantes);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
?>