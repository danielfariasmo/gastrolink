<?php
include '../../../server/database.php';
header('Content-Type: application/json');

$filtros = [];
$sqlFiltros = "SELECT DISTINCT tipo_receta FROM receta";
$resultFiltros = $connection->query($sqlFiltros);

if ($resultFiltros->num_rows > 0) {
    while ($row = $resultFiltros->fetch_assoc()) {
        $filtros[] = $row['tipo_receta'];
    }
}

$sql = "SELECT * FROM receta";
if (isset($_GET['tipo']) && $_GET['tipo'] !== 'todos') {
    $tipo = $connection->real_escape_string($_GET['tipo']);
    $sql .= " WHERE tipo_receta = '$tipo'";
}

$result = $connection->query($sql);
$recetas = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $recetas[] = $row;
    }
}

$connection->close();
echo json_encode(['recetas' => $recetas, 'filtros' => $filtros]);
