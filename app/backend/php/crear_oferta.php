<?php
header('Content-Type: application/json');
include '../../../server/database.php';

// Validar que todos los campos necesarios están presentes
$campos_obligatorios = ['id_restaurante', 'titulo', 'descripcion', 'tipo_puesto', 'fecha_publicacion', 'estado'];
foreach ($campos_obligatorios as $campo) {
    if (empty($_POST[$campo])) {
        echo json_encode([
            'success' => false,
            'message' => "El campo '$campo' es obligatorio."
        ]);
        exit;
    }
}

// Sanitizar los datos
$id_restaurante = intval($_POST['id_restaurante']);
$titulo = mysqli_real_escape_string($connection, $_POST['titulo']);
$descripcion = mysqli_real_escape_string($connection, $_POST['descripcion']);
$tipo_puesto = mysqli_real_escape_string($connection, $_POST['tipo_puesto']);
$fecha_publicacion = mysqli_real_escape_string($connection, $_POST['fecha_publicacion']);
$estado = mysqli_real_escape_string($connection, $_POST['estado']);

// Insertar en la base de datos
$sql = "INSERT INTO oferta (id_restaurante, titulo, descripcion, tipo_puesto, fecha_publicacion, estado)
        VALUES ('$id_restaurante', '$titulo', '$descripcion', '$tipo_puesto', '$fecha_publicacion', '$estado')";

if (mysqli_query($connection, $sql)) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Error al insertar la oferta: ' . mysqli_error($connection)
    ]);
}

mysqli_close($connection);
