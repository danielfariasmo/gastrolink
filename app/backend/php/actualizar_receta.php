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

// Verificar sesión de usuario
// Obtener el ID del usuario directamente del POST
if (!isset($_POST['id_usuario'])) {
    echo json_encode(['success' => false, 'message' => 'ID de usuario no proporcionado']);
    exit;
}

$userId = mysqli_real_escape_string($connection, $_POST['id_usuario']);

// $userData = json_decode($_SESSION['userData'], true);
$recetaId = mysqli_real_escape_string($connection, $_POST['id_receta']);

// Verificar que el usuario sea el creador
$query = "SELECT id_cocinero FROM receta WHERE id_receta = '$recetaId'";
$result = mysqli_query($connection, $query);

if (!$result || mysqli_num_rows($result) === 0) {
    echo json_encode(['success' => false, 'message' => 'Receta no encontrada']);
    exit;
}

$receta = mysqli_fetch_assoc($result);
if ($receta['id_cocinero'] != $userId) {
    echo json_encode(['success' => false, 'message' => 'No tienes permiso para editar esta receta']);
    exit;
}

// Procesar actualización
if ($_POST['action'] === 'update_recipe') {
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
            
            // Eliminar la imagen anterior si existe
            $oldImageQuery = "SELECT img_receta FROM receta WHERE id_receta = '$recetaId'";
            $oldImageResult = mysqli_query($connection, $oldImageQuery);
            if ($oldImageResult && $oldImage = mysqli_fetch_assoc($oldImageResult)) {
                if ($oldImage['img_receta'] && file_exists('../../..' . $oldImage['img_receta'])) {
                    unlink('../../..' . $oldImage['img_receta']);
                }
            }
        }
    }

    // Preparar datos
    $title = mysqli_real_escape_string($connection, $_POST['title']);
    $description = mysqli_real_escape_string($connection, $_POST['description']);
    $ingredients = mysqli_real_escape_string($connection, $_POST['ingredients']);
    $steps = mysqli_real_escape_string($connection, $_POST['steps']);
    $time = mysqli_real_escape_string($connection, $_POST['time']);
    $portions = mysqli_real_escape_string($connection, $_POST['portions']);
    $difficulty = mysqli_real_escape_string($connection, $_POST['difficulty']);

    // Construir consulta
    $query = "UPDATE receta SET
        titulo = '$title',
        introduccion = '$description',
        ingredientes = '$ingredients',
        pasos = '$steps',
        tiempo_preparacion = '$time',
        porciones = '$portions',
        dificultad = '$difficulty'
        $imageUpdate
        WHERE id_receta = '$recetaId'";
    
    if (mysqli_query($connection, $query)) {
        echo json_encode(['success' => true, 'message' => 'Receta actualizada con éxito']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al actualizar: ' . mysqli_error($connection)]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Acción no válida']);
}

mysqli_close($connection);
?>