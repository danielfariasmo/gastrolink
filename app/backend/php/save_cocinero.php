<?php
header('Content-Type: application/json');
include '../../../server/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
    exit;
}

// Validar datos obligatorios
$id = intval($_POST['id']);
$nombre = trim($_POST['nombre']);
$correo = trim($_POST['email']); // Mantengo el nombre que usas en el frontend
$descripcion = trim($_POST['descripcion'] ?? '');
$experiencia = trim($_POST['experiencia'] ?? '');
$especialidad = trim($_POST['especialidad'] ?? '');

if (!$id || !$nombre || !$correo) {
    echo json_encode(['success' => false, 'message' => 'Faltan campos obligatorios']);
    exit;
}

// Obtener imagen actual
$imgPath = null;
$sqlImagen = "SELECT img_usuario FROM usuario WHERE id_usuario = ?";
$stmtImagen = $connection->prepare($sqlImagen);
$stmtImagen->bind_param("i", $id);
$stmtImagen->execute();
$resultImagen = $stmtImagen->get_result();
$imgActual = $resultImagen->fetch_assoc()['img_usuario'] ?? null;
$stmtImagen->close();

// Procesar nueva imagen si se subió
$nuevaImagenSubida = false;
if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === 0) {
    // Validar tipo de archivo
    $permitidos = ['image/jpeg', 'image/png', 'image/gif'];
    if (!in_array($_FILES['imagen']['type'], $permitidos)) {
        echo json_encode(['success' => false, 'message' => 'Tipo de imagen no permitido']);
        exit;
    }

    // Generar nombre único para el archivo
    $extension = pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION);
    $nombreArchivo = 'cocinero_' . $id . '_' . time() . '.' . $extension;
    $rutaDestino = '../../img/usuarios/' . $nombreArchivo;
    
    if (move_uploaded_file($_FILES['imagen']['tmp_name'], $rutaDestino)) {
        $imgPath = '/gastrolink/app/img/usuarios/' . $nombreArchivo;
        $nuevaImagenSubida = true;
        
        // Eliminar imagen anterior si existe y es diferente a la nueva
        if ($imgActual && $imgActual != $imgPath && file_exists('../../' . parse_url($imgActual, PHP_URL_PATH))) {
            @unlink('../../' . parse_url($imgActual, PHP_URL_PATH));
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al subir la imagen']);
        exit;
    }
} else {
    $imgPath = $imgActual;
}

// Iniciar transacción para asegurar la integridad de los datos
$connection->begin_transaction();

try {
    // Actualizar tabla usuario
    if ($imgPath) {
        $sqlUsuario = "UPDATE usuario SET nombre = ?, correo = ?, img_usuario = ? WHERE id_usuario = ?";
        $stmtUsuario = $connection->prepare($sqlUsuario);
        $stmtUsuario->bind_param("sssi", $nombre, $correo, $imgPath, $id);
    } else {
        $sqlUsuario = "UPDATE usuario SET nombre = ?, correo = ? WHERE id_usuario = ?";
        $stmtUsuario = $connection->prepare($sqlUsuario);
        $stmtUsuario->bind_param("ssi", $nombre, $correo, $id);
    }
    
    if (!$stmtUsuario->execute()) {
        throw new Exception('Error al actualizar usuario');
    }
    $stmtUsuario->close();

    // Comprobar si ya existe en cocinero
    $sqlCheck = "SELECT id_cocinero FROM cocinero WHERE id_cocinero = ?";
    $stmtCheck = $connection->prepare($sqlCheck);
    $stmtCheck->bind_param("i", $id);
    $stmtCheck->execute();
    $resultCheck = $stmtCheck->get_result();
    $existe = $resultCheck->num_rows > 0;
    $stmtCheck->close();

    // INSERT o UPDATE en cocinero
    if ($existe) {
        $sqlCocinero = "UPDATE cocinero SET descripcion = ?, experiencia = ?, especialidad = ? WHERE id_cocinero = ?";
        $stmtCocinero = $connection->prepare($sqlCocinero);
        $stmtCocinero->bind_param("sssi", $descripcion, $experiencia, $especialidad, $id);
    } else {
        $sqlCocinero = "INSERT INTO cocinero (id_cocinero, descripcion, experiencia, especialidad) VALUES (?, ?, ?, ?)";
        $stmtCocinero = $connection->prepare($sqlCocinero);
        $stmtCocinero->bind_param("isss", $id, $descripcion, $experiencia, $especialidad);
    }

    if (!$stmtCocinero->execute()) {
        throw new Exception('Error al guardar datos de cocinero');
    }
    $stmtCocinero->close();

    // Confirmar transacción
    $connection->commit();

    // Devolver respuesta con todos los datos actualizados
    echo json_encode([
        'success' => true,
        'nuevaImagenUrl' => $imgPath, // URL completa de la imagen
        'userData' => [
            'id_usuario' => $id,
            'nombre' => $nombre,
            'correo' => $correo,
            'img_usuario' => $imgPath,
            'tipo_usuario' => 'cocinero',
            'descripcion' => $descripcion,
            'experiencia' => $experiencia,
            'especialidad' => $especialidad
        ]
    ]);

} catch (Exception $e) {
    $connection->rollback();
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}

$connection->close();