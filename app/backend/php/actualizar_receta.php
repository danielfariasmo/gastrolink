<?php
session_start();
header("Content-Type: application/json");

include "../../../server/database.php";

// Verificar conexión a la base de datos
if (!$connection) {
    echo json_encode(['success' => false, 'message' => 'Error de conexión a la base de datos']);
    exit;
}

// Verificar si hay una acción y ID de receta
if (!isset($_POST['action']) || !isset($_POST['id_receta'])) {
    echo json_encode(['success' => false, 'message' => 'Acción o ID de receta no especificada']);
    exit;
}

$usuarioId = mysqli_real_escape_string($connection, $_POST['id_usuario']);
$recetaId = mysqli_real_escape_string($connection, $_POST['id_receta']);

// Primero verificar que el usuario sea el creador de la receta
$query = "SELECT id_cocinero FROM receta WHERE id_receta = '$recetaId'";
$result = mysqli_query($connection, $query);

if (!$result || mysqli_num_rows($result) === 0) {
    echo json_encode(['success' => false, 'message' => 'Receta no encontrada']);
    exit;
}

// Procesar actualización de receta
if ($_POST['action'] === 'update_recipe') {
    // Validar datos requeridos
    $requiredFields = ['title', 'description', 'ingredients', 'steps', 'time', 'portions', 'difficulty'];
    foreach ($requiredFields as $field) {
        if (empty($_POST[$field])) {
            echo json_encode(['success' => false, 'message' => "El campo $field es requerido"]);
            exit;
        }
    }

    // Procesar imagen (si se subió una nueva)
    $imageUpdate = '';
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
            $imageUpdate = ", img_receta = '" . mysqli_real_escape_string($connection, $imagePath) . "'";
            
            // Opcional: eliminar la imagen anterior si existe
            $oldImageQuery = "SELECT img_receta FROM receta WHERE id_receta = '$recetaId'";
            $oldImageResult = mysqli_query($connection, $oldImageQuery);
            if ($oldImageResult && $oldImage = mysqli_fetch_assoc($oldImageResult)) {
                if ($oldImage['img_receta'] && file_exists('../../..' . $oldImage['img_receta'])) {
                    unlink('../../..' . $oldImage['img_receta']);
                }
            }
        }
    }

    // Preparar datos para actualización
    $title = mysqli_real_escape_string($connection, $_POST['title']);
    $description = mysqli_real_escape_string($connection, $_POST['description']);
    $ingredients = mysqli_real_escape_string($connection, $_POST['ingredients']);
    $steps = mysqli_real_escape_string($connection, $_POST['steps']);
    $time = mysqli_real_escape_string($connection, $_POST['time']);
    $portions = mysqli_real_escape_string($connection, $_POST['portions']);
    $difficulty = mysqli_real_escape_string($connection, $_POST['difficulty']);
    $calories = mysqli_real_escape_string($connection, $_POST['calories'] ?? 0);
    $proteins = mysqli_real_escape_string($connection, $_POST['proteins'] ?? 0);
    $carbohydrates = mysqli_real_escape_string($connection, $_POST['carbohydrates'] ?? 0);
    $fats = mysqli_real_escape_string($connection, $_POST['fats'] ?? 0);

    // Construir consulta de actualización
    $query = "UPDATE receta SET
        titulo = '$title',
        introduccion = '$description',
        ingredientes = '$ingredients',
        pasos = '$steps',
        tiempo_preparacion = '$time',
        porciones = '$portions',
        dificultad = '$difficulty'";
    
    // Agregar actualización de imagen solo si hay una nueva imagen
    if (!empty($imageUpdate)) {
        $query .= $imageUpdate;
    }
    
    $query .= " WHERE id_receta = '$recetaId'";
    
    if (mysqli_query($connection, $query)) {
        echo json_encode(['success' => true, 'message' => 'Receta actualizada con éxito']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al actualizar la receta: ' . mysqli_error($connection)]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Acción no válida']);
}

mysqli_close($connection);
?>