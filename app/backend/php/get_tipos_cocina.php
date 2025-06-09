<?php
include '../../../server/database.php';
header('Content-Type: application/json');

$sql = "SELECT DISTINCT tipo_restaurante FROM restaurante WHERE tipo_restaurante IS NOT NULL";
$result = $connection->query($sql);

$tipos = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $tipos[] = $row['tipo_restaurante'];
    }
}

echo json_encode(['success' => true, 'tipos' => $tipos]);
$connection->close();

