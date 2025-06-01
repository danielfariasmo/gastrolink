<?php
require_once '../../../server/database.php';
header('Content-Type: application/json');

// Total de cocineros + camareros
$sqlPersonal = "SELECT COUNT(*) as total FROM usuario WHERE tipo_usuario IN ('camarero', 'cocinero')";
$resultPersonal = $connection->query($sqlPersonal);
$totalCocineroCamarero = $resultPersonal->fetch_assoc()['total'];

// Total de restaurantes
$sqlRestaurantes = "SELECT COUNT(*) as total FROM usuario WHERE tipo_usuario = 'restaurante'";
$resultRestaurantes = $connection->query($sqlRestaurantes);
$totalRestaurantes = $resultRestaurantes->fetch_assoc()['total'];

echo json_encode([
    'totalCocineroCamarero' => $totalCocineroCamarero,
    'totalRestaurantes' => $totalRestaurantes
]);
