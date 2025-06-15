<?php
header('Content-Type: application/json');
include '../../../server/database.php';

// Iniciar transacción para operaciones atómicas
$connection->begin_transaction();

try {
    // Validar método HTTP
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Método no permitido');
    }

    // Validar y sanitizar datos de entrada
    $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
    $nombre = trim($_POST['nombre'] ?? '');
    $correo = filter_input(INPUT_POST, 'correo', FILTER_SANITIZE_EMAIL);
    $descripcion = trim($_POST['descripcion'] ?? '');
    $experiencia = trim($_POST['experiencia'] ?? '');
    $idiomas = trim($_POST['idiomas'] ?? '');

    // Validar campos obligatorios
    if (!$id || !$nombre || !$correo) {
        throw new Exception('Faltan campos obligatorios');
    }

    // Validar formato de email
    if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        throw new Exception('Formato de correo electrónico inválido');
    }

    // Procesamiento de imagen
    $imgPath = null;
    $nuevaImagenSubida = false;

    // Obtener imagen actual
    $stmtImagen = $connection->prepare("SELECT img_usuario FROM usuario WHERE id_usuario = ?");
    $stmtImagen->bind_param("i", $id);
    $stmtImagen->execute();
    $imgActual = $stmtImagen->get_result()->fetch_assoc()['img_usuario'] ?? null;
    $stmtImagen->close();

    // Procesar nueva imagen si se subió
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
        // Validar tipo de imagen
        $permitidos = ['image/jpeg', 'image/png', 'image/gif'];
        $tipoImagen = mime_content_type($_FILES['imagen']['tmp_name']);
        
        if (!in_array($tipoImagen, $permitidos)) {
            throw new Exception('Tipo de imagen no permitido. Solo se aceptan JPEG, PNG o GIF');
        }

        // Generar nombre único para el archivo
        $extension = pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION);
        $nombreArchivo = 'camarero_' . $id . '_' . time() . '.' . $extension;
        $rutaDestino = '../../img/usuarios/' . $nombreArchivo;

        // Crear directorio si no existe
        if (!is_dir(dirname($rutaDestino))) {
            mkdir(dirname($rutaDestino), 0755, true);
        }

        // Mover archivo subido
        if (move_uploaded_file($_FILES['imagen']['tmp_name'], $rutaDestino)) {
            $imgPath = '/gastrolink/app/img/usuarios/' . $nombreArchivo;
            $nuevaImagenSubida = true;

            // Eliminar imagen anterior si existe
            if ($imgActual && file_exists('../../' . parse_url($imgActual, PHP_URL_PATH))) {
                @unlink('../../' . parse_url($imgActual, PHP_URL_PATH));
            }
        } else {
            throw new Exception('Error al mover el archivo subido');
        }
    } else {
        $imgPath = $imgActual;
    }

    // Actualizar datos en tabla usuario
    if ($imgPath) {
        $stmtUsuario = $connection->prepare("UPDATE usuario SET nombre = ?, correo = ?, img_usuario = ? WHERE id_usuario = ?");
        $stmtUsuario->bind_param("sssi", $nombre, $correo, $imgPath, $id);
    } else {
        $stmtUsuario = $connection->prepare("UPDATE usuario SET nombre = ?, correo = ? WHERE id_usuario = ?");
        $stmtUsuario->bind_param("ssi", $nombre, $correo, $id);
    }

    if (!$stmtUsuario->execute()) {
        throw new Exception('Error al actualizar datos de usuario: ' . $stmtUsuario->error);
    }
    $stmtUsuario->close();

    // Verificar si existe en tabla camarero
    $stmtCheck = $connection->prepare("SELECT id_camarero FROM camarero WHERE id_camarero = ?");
    $stmtCheck->bind_param("i", $id);
    $stmtCheck->execute();
    $existe = $stmtCheck->get_result()->num_rows > 0;
    $stmtCheck->close();

    // Insertar o actualizar datos específicos de camarero
    if ($existe) {
        $sqlCamarero = "UPDATE camarero SET descripcion = ?, experiencia = ?, idiomas = ? WHERE id_camarero = ?";
        $stmtCamarero = $connection->prepare($sqlCamarero);
        $stmtCamarero->bind_param("sssi", $descripcion, $experiencia, $idiomas, $id);
    } else {
        $sqlCamarero = "INSERT INTO camarero (id_camarero, descripcion, experiencia, idiomas) VALUES (?, ?, ?, ?)";
        $stmtCamarero = $connection->prepare($sqlCamarero);
        $stmtCamarero->bind_param("isss", $id, $descripcion, $experiencia, $idiomas);
    }

    if (!$stmtCamarero->execute()) {
        throw new Exception('Error al guardar datos de camarero: ' . $stmtCamarero->error);
    }
    $stmtCamarero->close();

    // Confirmar todas las operaciones
    $connection->commit();

    // Devolver respuesta con todos los datos actualizados
    echo json_encode([
        'success' => true,
        'message' => 'Perfil actualizado correctamente',
        'userData' => [
            'id_usuario' => $id,
            'nombre' => $nombre,
            'correo' => $correo,
            'img_usuario' => $imgPath,
            'tipo_usuario' => 'camarero',
            'camarero' => [
                'descripcion' => $descripcion,
                'experiencia' => $experiencia,
                'idiomas' => $idiomas
            ]
        ]
    ]);

} catch (Exception $e) {
    // Revertir todas las operaciones en caso de error
    $connection->rollback();
    
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}

$connection->close();