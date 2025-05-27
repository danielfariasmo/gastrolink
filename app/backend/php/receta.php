<?php
include '../../../server/database.php';
header('Content-Type: application/json');

$id = $_GET['id'] ?? null;

if (!$id || !is_numeric($id)) {
    echo json_encode(['error' => 'ID de receta no válido']);
    exit;
}

$sql = "SELECT 
            r.*, 
            u.nombre AS nombre_cocinero,
            u.img_usuario AS img_cocinero
        FROM receta r
        JOIN cocinero c ON r.id_cocinero = c.id_cocinero
        JOIN usuario u ON c.id_cocinero = u.id_usuario
        WHERE r.id_receta = ?";

$stmt = mysqli_prepare($connection, $sql);
mysqli_stmt_bind_param($stmt, 'i', $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if ($receta = mysqli_fetch_assoc($result)) {
    $tipo = $receta['tipo_receta'];
    $recetaId = $receta['id_receta'];

    $sqlRelacionadas = "SELECT id_receta, titulo, introduccion, img_receta 
                        FROM receta 
                        WHERE tipo_receta = ? AND id_receta != ? 
                        ORDER BY RAND() 
                        LIMIT 3";

    $stmtRelacionadas = mysqli_prepare($connection, $sqlRelacionadas);
    mysqli_stmt_bind_param($stmtRelacionadas, 'si', $tipo, $recetaId);
    mysqli_stmt_execute($stmtRelacionadas);
    $resultRelacionadas = mysqli_stmt_get_result($stmtRelacionadas);

    $relacionadas = [];
    while ($row = mysqli_fetch_assoc($resultRelacionadas)) {
        $relacionadas[] = $row;
    }

    $receta['relacionadas'] = $relacionadas;

    echo json_encode($receta);
} else {
    echo json_encode(['error' => 'Receta no encontrada']);
}
