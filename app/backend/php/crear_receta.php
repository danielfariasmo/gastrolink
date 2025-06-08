<?php
session_start();
header("Content-Type: application/json");

include "../../../server/database.php";

// Verificar conexión a la base de datos
if (!$connection) {
    echo json_encode(['success' => false, 'message' => 'Error de conexión a la base de datos']);
    exit;
}

// Verificar si hay una acción
if (!isset($_POST['action'])) {
    echo json_encode(['success' => false, 'message' => 'Acción no especificada']);
    exit;
}

// Procesar creación de receta
if ($_POST['action'] === 'create_recipe') {
    // Validar datos requeridos
    $requiredFields = ['title', 'type', 'description', 'ingredients', 'steps', 'time', 'portions', 'difficulty', 'id_cocinero'];
    foreach ($requiredFields as $field) {
        if (empty($_POST[$field])) {
            echo json_encode(['success' => false, 'message' => "El campo $field es requerido"]);
            exit;
        }
    }

    // Procesar imagen
    $imagePath = null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = '../../img/recetas/';
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        
        $extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $filename = uniqid() . '.' . $extension;
        $destination = $uploadDir . $filename;
        
        if (move_uploaded_file($_FILES['image']['tmp_name'], $destination)) {
            $imagePath = '/gastrolink/app/img/recetas/' . $filename;
        }
    }

    // Insertar en la base de datos
    $id_cocinero = mysqli_real_escape_string($connection, $_POST['id_cocinero']);
    $title = mysqli_real_escape_string($connection, $_POST['title']);
    $type = mysqli_real_escape_string($connection, $_POST['type']);
    $description = mysqli_real_escape_string($connection, $_POST['description']);
    $ingredients = mysqli_real_escape_string($connection, $_POST['ingredients']);
    $steps = mysqli_real_escape_string($connection, $_POST['steps']);
    $time = mysqli_real_escape_string($connection, $_POST['time']);
    $portions = mysqli_real_escape_string($connection, $_POST['portions']);
    $difficulty = mysqli_real_escape_string($connection, $_POST['difficulty']);
    $calories = mysqli_real_escape_string($connection, $_POST['calories']);
    $proteins = mysqli_real_escape_string($connection, $_POST['proteins']);
    $carbohydrates = mysqli_real_escape_string($connection, $_POST['carbohydrates']);
    $fats = mysqli_real_escape_string($connection, $_POST['fats']);
    $imagePath = $imagePath ? "'" . mysqli_real_escape_string($connection, $imagePath) . "'" : "NULL";

    $query = "INSERT INTO receta (
        id_cocinero, 
        titulo, 
        tipo_receta, 
        introduccion, 
        ingredientes, 
        pasos, 
        tiempo_preparacion, 
        porciones, 
        dificultad, 
        calorias,
        proteinas,
        carbohidratos,
        grasas,
        img_receta,
        fecha_publicacion
    ) VALUES (
        '$id_cocinero',
        '$title',
        '$type',
        '$description',
        '$ingredients',
        '$steps',
        '$time',
        '$portions',
        '$difficulty',
        '$calories',
        '$proteins',
        '$carbohydrates',
        '$fats',
        $imagePath,
        NOW()
    )";
    
    if (mysqli_query($connection, $query)) {
        echo json_encode(['success' => true, 'message' => 'Receta creada con éxito']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al crear la receta: ' . mysqli_error($connection)]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Acción no válida']);
}

mysqli_close($connection);
?>