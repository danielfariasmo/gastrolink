<?php
header('Content-Type: application/json');
include '../../../server/database.php';

$tipo = isset($_GET['tipo']) ? $_GET['tipo'] : 'cocinero';

try {
    if ($tipo === 'cocinero') {
        $query = "SELECT u.id_usuario, u.nombre, u.img_usuario, c.descripcion, c.especialidad, c.experiencia FROM usuario u INNER JOIN cocinero c ON u.id_usuario = c.id_cocinero";
    } elseif ($tipo === 'camarero') {
        $query = "SELECT u.id_usuario, u.nombre, u.img_usuario, c.descripcion, c.idiomas, c.experiencia FROM usuario u INNER JOIN camarero c ON u.id_usuario = c.id_camarero";
    } else {
        echo json_encode(['error' => 'Tipo no válido']);
        exit;
    }

    $result = mysqli_query($connection, $query);

    if (!$result) {
        echo json_encode(['error' => 'Error en la consulta: ' . mysqli_error($connection)]);
        exit;
    }

    $miembros = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $miembros[] = $row;
    }

    echo json_encode($miembros);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
