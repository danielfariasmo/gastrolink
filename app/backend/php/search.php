<?php
header('Content-Type: application/json');
require_once '../../../server/database.php';      

/* 1. Sanitizar término de búsqueda */
$term = isset($_GET['query']) && $_GET['query'] !== ''
        ? '%'.$_GET['query'].'%'
        : '%';

try {
    /* 2. Consulta única (restaurante + receta) */
    $sql = "
        /* Restaurantes */
        SELECT
            u.id_usuario           AS id,
            u.nombre               AS nombre,
            'restaurante'          AS tipo
        FROM   usuario u
        WHERE  u.tipo_usuario = 'restaurante'
          AND  u.nombre       LIKE ?

        UNION ALL

        /* Recetas */
        SELECT
            r.id_receta            AS id,
            r.titulo               AS nombre,
            'receta'               AS tipo
        FROM   receta r
        WHERE  r.titulo LIKE ?

        ORDER BY nombre
        LIMIT 30
    ";

    $stmt = $connection->prepare($sql);
    if (!$stmt) {
        throw new Exception('Prepare failed: '.$connection->error);
    }

    /* 3. Ejecutar */
    $stmt->bind_param('ss', $term, $term);
    $stmt->execute();
    $res = $stmt->get_result();

    /* 4. Formatear salida */
    $data = [];
    while ($row = $res->fetch_assoc()) {
        $data[] = $row;
    }

    echo json_encode($data);

} catch (Throwable $e) {
    /* 5. Manejo de errores */
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}

