<?php
header('Content-Type: application/json');
include '../../../server/database.php';

// Iniciar transacción
$connection->begin_transaction();

try {
    // Validar ID
    $id_restaurante = intval($_POST['id_restaurante'] ?? 0);
    if (!$id_restaurante) {
        throw new Exception('ID del restaurante no proporcionado');
    }

    // Sanitizar inputs
    $nombre = trim($_POST['restaurant-name'] ?? '');
    $tipo = trim($_POST['cuisine-type'] ?? '');
    $descripcion = trim($_POST['description'] ?? '');
    $direccion = trim($_POST['address'] ?? '');
    $telefono = trim($_POST['phone'] ?? '');
    $correo = trim($_POST['email'] ?? '');
    $web = trim($_POST['website'] ?? '');
    $rango = trim($_POST['price-range'] ?? '');
    $historial = trim($_POST['history'] ?? '');

    // Validar campos obligatorios
    if (empty($nombre) || empty($correo)) {
        throw new Exception('Nombre y correo son campos obligatorios');
    }

    // Convertir precio a formato tipo '25-50'
    $precio_map = ['€' => '10-15', '€€' => '15-30', '€€€' => '30-50', '€€€€' => '50-120'];
    $rango_precio = $precio_map[$rango] ?? '0-0';

    // Procesar imagen principal
    $img_usuario = null;
    if (isset($_FILES['cover-image']) && $_FILES['cover-image']['error'] === 0) {
        // Validar tipo de imagen
        $permitidos = ['image/jpeg', 'image/png', 'image/gif'];
        if (!in_array($_FILES['cover-image']['type'], $permitidos)) {
            throw new Exception('Tipo de imagen no permitido');
        }

        // Generar nombre único
        $extension = pathinfo($_FILES['cover-image']['name'], PATHINFO_EXTENSION);
        $nombre_archivo = $id_restaurante . '_' . time() . '.' . $extension;
        $ruta_relativa = '/gastrolink/app/img/usuarios/' . $nombre_archivo;
        $ruta_destino = __DIR__ . '/../../img/usuarios/' . $nombre_archivo;

        // Crear directorio si no existe
        if (!is_dir(dirname($ruta_destino))) {
            mkdir(dirname($ruta_destino), 0775, true);
        }

        // Mover archivo y actualizar BD
        if (move_uploaded_file($_FILES['cover-image']['tmp_name'], $ruta_destino)) {
            $img_usuario = $ruta_relativa;

            // Eliminar imagen anterior si existe
            $stmt = $connection->prepare("SELECT img_usuario FROM usuario WHERE id_usuario = ?");
            $stmt->bind_param("i", $id_restaurante);
            $stmt->execute();
            $result = $stmt->get_result();
            $img_anterior = $result->fetch_assoc()['img_usuario'] ?? null;
            $stmt->close();

            if ($img_anterior && file_exists(__DIR__ . '/../../' . parse_url($img_anterior, PHP_URL_PATH))) {
                @unlink(__DIR__ . '/../../' . parse_url($img_anterior, PHP_URL_PATH));
            }

            $stmt = $connection->prepare("UPDATE usuario SET img_usuario = ? WHERE id_usuario = ?");
            $stmt->bind_param("si", $img_usuario, $id_restaurante);
            $stmt->execute();
            $stmt->close();
        }
    }

    // Actualizar datos básicos del usuario
    $stmt = $connection->prepare("UPDATE usuario SET nombre = ?, correo = ? WHERE id_usuario = ?");
    $stmt->bind_param("ssi", $nombre, $correo, $id_restaurante);
    if (!$stmt->execute()) {
        throw new Exception('Error al actualizar usuario');
    }
    $stmt->close();

    // Verificar si el restaurante ya existe
    $stmt = $connection->prepare("SELECT COUNT(*) FROM restaurante WHERE id_restaurante = ?");
    $stmt->bind_param("i", $id_restaurante);
    $stmt->execute();
    $stmt->bind_result($existe);
    $stmt->fetch();
    $stmt->close();

    // Actualizar/Insertar datos del restaurante
    if ($existe > 0) {
        // UPDATE
        $sql = "UPDATE restaurante SET tipo_restaurante=?, descripcion=?, direccion=?, web=?, telefono=?, rango_precio=?, historial=? WHERE id_restaurante=?";
        $types = "sssssssi"; // 7 strings + 1 integer
        $params = [$tipo, $descripcion, $direccion, $web, $telefono, $rango_precio, $historial, $id_restaurante];
    } else {
        // INSERT
        $sql = "INSERT INTO restaurante (id_restaurante, tipo_restaurante, descripcion, direccion, web, telefono, rango_precio, historial) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $types = "isssssss"; // 1 integer + 7 strings
        $params = [$id_restaurante, $tipo, $descripcion, $direccion, $web, $telefono, $rango_precio, $historial];
    }

    $stmt = $connection->prepare($sql);
    if (!$stmt) {
        throw new Exception('Error al preparar la consulta: ' . $connection->error);
    }

    $stmt->bind_param($types, ...$params);
    if (!$stmt->execute()) {
        throw new Exception('Error al guardar datos del restaurante: ' . $stmt->error);
    }
    $stmt->close();

    // Actualizar horarios
    $stmtDelete = $connection->prepare("DELETE FROM horario_restaurante WHERE id_restaurante = ?");
    $stmtDelete->bind_param("i", $id_restaurante);
    if (!$stmtDelete->execute()) {
        throw new Exception('Error al eliminar horarios antiguos');
    }
    $stmtDelete->close();

    $dias = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'];
    foreach ($dias as $dia) {
        $apertura = $_POST["apertura_$dia"] ?? '';
        $cierre = $_POST["cierre_$dia"] ?? '';
        if ($apertura && $cierre) {
            $stmt = $connection->prepare("INSERT INTO horario_restaurante (id_restaurante, dia_semana, hora_apertura, hora_cierre) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("isss", $id_restaurante, $dia, $apertura, $cierre);
            if (!$stmt->execute()) {
                throw new Exception("Error al guardar horario para $dia");
            }
            $stmt->close();
        }
    }

    // Procesar galería de imágenes
    $imagenes_actuales = $_POST['existing_images'] ?? [];
    
    // Obtener imágenes actuales en BD
    $stmt = $connection->prepare("SELECT url_imagen FROM imagen_restaurante WHERE id_restaurante = ?");
    $stmt->bind_param("i", $id_restaurante);
    $stmt->execute();
    $result = $stmt->get_result();
    $imagenes_en_bd = [];
    while ($row = $result->fetch_assoc()) {
        $imagenes_en_bd[] = $row['url_imagen'];
    }
    $stmt->close();

    // Eliminar imágenes no seleccionadas
    foreach ($imagenes_en_bd as $url) {
        if (!in_array($url, $imagenes_actuales)) {
            $stmt = $connection->prepare("DELETE FROM imagen_restaurante WHERE id_restaurante = ? AND url_imagen = ?");
            $stmt->bind_param("is", $id_restaurante, $url);
            if (!$stmt->execute()) {
                throw new Exception("Error al eliminar imagen $url");
            }
            $stmt->close();
            
            $ruta_absoluta = __DIR__ . '/../../' . parse_url($url, PHP_URL_PATH);
            if (file_exists($ruta_absoluta)) {
                @unlink($ruta_absoluta);
            }
        }
    }

    // Procesar nuevas imágenes de la galería
    if (!empty($_FILES['gallery']) && isset($_FILES['gallery']['tmp_name']) && is_array($_FILES['gallery']['tmp_name'])) {
        foreach ($_FILES['gallery']['tmp_name'] as $index => $tmpName) {
            if (!empty($tmpName) && $_FILES['gallery']['error'][$index] === 0) {
                // Validar tipo de imagen
                if (!in_array($_FILES['gallery']['type'][$index], $permitidos)) {
                    continue; // Saltar imágenes no permitidas
                }

                $nombre_archivo = $id_restaurante . '_' . time() . '_' . $index . '.' . pathinfo($_FILES['gallery']['name'][$index], PATHINFO_EXTENSION);
                $ruta_relativa = '/gastrolink/app/img/restaurantes/' . $nombre_archivo;
                $ruta_servidor = __DIR__ . '/../../img/restaurantes/' . $nombre_archivo;

                // Crear directorio si no existe
                if (!is_dir(dirname($ruta_servidor))) {
                    mkdir(dirname($ruta_servidor), 0775, true);
                }

                if (move_uploaded_file($tmpName, $ruta_servidor)) {
                    $alt = pathinfo($_FILES['gallery']['name'][$index], PATHINFO_FILENAME);
                    $stmt = $connection->prepare("INSERT INTO imagen_restaurante (id_restaurante, url_imagen, alt) VALUES (?, ?, ?)");
                    $stmt->bind_param("iss", $id_restaurante, $ruta_relativa, $alt);
                    if (!$stmt->execute()) {
                        throw new Exception("Error al guardar imagen en galería");
                    }
                    $stmt->close();
                }
            }
        }
    }

    // Confirmar transacción
    $connection->commit();

    // Devolver respuesta con todos los datos actualizados
    echo json_encode([
        'success' => true,
        'message' => 'Perfil actualizado correctamente',
        'userData' => [
            'id_usuario' => $id_restaurante,
            'nombre' => $nombre,
            'correo' => $correo,
            'img_usuario' => $img_usuario,
            'tipo_usuario' => 'restaurante',
            'restaurante' => [
                'tipo_restaurante' => $tipo,
                'descripcion' => $descripcion,
                'direccion' => $direccion,
                'web' => $web,
                'telefono' => $telefono,
                'rango_precio' => $rango_precio,
                'historial' => $historial
            ]
        ]
    ]);

} catch (Exception $e) {
    $connection->rollback();
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}

$connection->close();