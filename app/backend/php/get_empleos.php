<?php
include '../../../server/database.php';
header('Content-Type: application/json');

$sql = "SELECT 
            o.titulo, o.descripcion, o.tipo_puesto, o.fecha_publicacion, u.nombre AS restaurante, u.img_usuario, u.correo
        FROM oferta o
        JOIN restaurante r ON o.id_restaurante = r.id_restaurante
        JOIN usuario u ON r.id_restaurante = u.id_usuario
        WHERE o.estado = 'abierta'
        ORDER BY o.fecha_publicacion DESC";

$resultado = mysqli_query($connection, $sql);

$empleos = [];

while ($fila = mysqli_fetch_assoc($resultado)) {
    $empleos[] = $fila;
}

echo json_encode($empleos);
