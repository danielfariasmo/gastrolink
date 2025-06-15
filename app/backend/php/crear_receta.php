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

    // Procesar imagen - Establecer imagen por defecto primero
    $imagePath = '/gastrolink/app/img/default-restaurante.jpeg'; // Imagen por defecto
    
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/gastrolink/app/img/recetas/';
        
        // Verificar y crear directorio si no existe
        if (!file_exists($uploadDir)) {
            if (!mkdir($uploadDir, 0777, true)) {
                // Si falla la creación del directorio, mantener la imagen por defecto
                error_log("No se pudo crear el directorio: " . $uploadDir);
            }
        }
        
        // Validar archivo
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
        $fileExtension = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        $maxFileSize = 2 * 1024 * 1024; // 2MB
        
        if (in_array($fileExtension, $allowedExtensions) && 
            $_FILES['image']['size'] <= $maxFileSize) {
            
            // Generar nombre único para el archivo
            $filename = uniqid() . '.' . $fileExtension;
            $destination = $uploadDir . $filename;
            
            // Intentar mover el archivo subido
            if (move_uploaded_file($_FILES['image']['tmp_name'], $destination)) {
                $imagePath = '/gastrolink/app/img/recetas/' . $filename;
            } else {
                error_log("Error al mover el archivo subido: " . $_FILES['image']['tmp_name']);
            }
        } else {
            error_log("Archivo no válido o demasiado grande: " . $_FILES['image']['name']);
        }
    }

    // Escapar datos para SQL
    $id_cocinero = mysqli_real_escape_string($connection, $_POST['id_cocinero']);
    $title = mysqli_real_escape_string($connection, $_POST['title']);
    $type = mysqli_real_escape_string($connection, $_POST['type']);
    $description = mysqli_real_escape_string($connection, $_POST['description']);
    $ingredients = mysqli_real_escape_string($connection, $_POST['ingredients']);
    $steps = mysqli_real_escape_string($connection, $_POST['steps']);
    $time = intval($_POST['time']);
    $portions = intval($_POST['portions']);
    $difficulty = mysqli_real_escape_string($connection, $_POST['difficulty']);
    $calories = !empty($_POST['calories']) ? mysqli_real_escape_string($connection, $_POST['calories']) : '0';
    $proteins = !empty($_POST['proteins']) ? mysqli_real_escape_string($connection, $_POST['proteins']) : '0';
    $carbohydrates = !empty($_POST['carbohydrates']) ? mysqli_real_escape_string($connection, $_POST['carbohydrates']) : '0';
    $fats = !empty($_POST['fats']) ? mysqli_real_escape_string($connection, $_POST['fats']) : '0';
    $imagePath = mysqli_real_escape_string($connection, $imagePath);

    // Preparar consulta SQL
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
        '$imagePath',
        NOW()
    )";
    
    // Ejecutar consulta
    if (mysqli_query($connection, $query)) {
        $recipeId = mysqli_insert_id($connection);
        echo json_encode([
            'success' => true, 
            'message' => 'Receta creada con éxito',
            'recipeId' => $recipeId,
            'imagePath' => $imagePath
        ]);
    } else {
        echo json_encode([
            'success' => false, 
            'message' => 'Error al crear la receta: ' . mysqli_error($connection)
        ]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Acción no válida']);
}

mysqli_close($connection);
?>