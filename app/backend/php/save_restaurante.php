<?php
header('Content-Type: application/json');
include '../../../server/database.php';

$id_restaurante = $_POST['id_restaurante'] ?? null;
$nombre = $_POST['restaurant-name'] ?? '';
$tipo = $_POST['cuisine-type'] ?? '';
$descripcion = $_POST['description'] ?? '';
$direccion = $_POST['address'] ?? '';
$telefono = $_POST['phone'] ?? '';
$correo = $_POST['email'] ?? '';
$web = $_POST['website'] ?? '';
$rango = $_POST['price-range'] ?? '';
$historial = $_POST['history'] ?? ''; 

// Validar ID
if (!$id_restaurante) {
    echo json_encode(['success' => false, 'message' => 'ID del restaurante no proporcionado']);
    exit;
}

// Actualizar nombre del restaurante en tabla usuario
$stmt = $connection->prepare("UPDATE usuario SET nombre = ? WHERE id_usuario = ?");
$stmt->bind_param("si", $nombre, $id_restaurante);
$stmt->execute();
$stmt->close();

// Convertir precio a formato tipo '25-50'
$precio_map = ['€' => '10-15', '€€' => '15-30', '€€€' => '30-50', '€€€€' => '50-120'];
$rango_precio = $rango ?? '0-0';

// Subida de imagen principal
$img_usuario = null;
if (isset($_FILES['cover-image']) && $_FILES['cover-image']['error'] === 0) {
    $ruta_destino = '../../img/usuarios/' . $id_restaurante . '_' . time() . '.' . pathinfo($_FILES['cover-image']['name'], PATHINFO_EXTENSION);
    if (move_uploaded_file($_FILES['cover-image']['tmp_name'], $ruta_destino)) {
        $img_usuario = $ruta_destino;

        $stmt = $connection->prepare("UPDATE usuario SET img_usuario = ? WHERE id_usuario = ?");
        $stmt->bind_param("si", $img_usuario, $id_restaurante);
        $stmt->execute();
        $stmt->close();
    }
}

// Verificar si el restaurante ya existe
$stmt = $connection->prepare("SELECT COUNT(*) FROM restaurante WHERE id_restaurante = ?");
$stmt->bind_param("i", $id_restaurante);
$stmt->execute();
$stmt->bind_result($existe);
$stmt->fetch();
$stmt->close();

if ($existe > 0) {
    // UPDATE
    $sql = "UPDATE restaurante 
            SET tipo_restaurante=?, descripcion=?, direccion=?, web=?, telefono=?, rango_precio=?, historial=?, ubicacion=NULL 
            WHERE id_restaurante=?";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("sssssssi", $tipo, $descripcion, $direccion, $web, $telefono, $rango_precio, $historial, $id_restaurante);
    $stmt->execute();
    $stmt->close();
} else {
    // INSERT
    $sql = "INSERT INTO restaurante 
            (id_restaurante, tipo_restaurante, descripcion, direccion, web, telefono, rango_precio, historial, ubicacion) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, NULL)";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("isssssss", $id_restaurante, $tipo, $descripcion, $direccion, $web, $telefono, $rango_precio, $historial);
    $stmt->execute();
    $stmt->close();
}

// Actualizar horarios
$stmtDelete = $connection->prepare("DELETE FROM horario_restaurante WHERE id_restaurante = ?");
$stmtDelete->bind_param("i", $id_restaurante);
$stmtDelete->execute();
$stmtDelete->close();

$dias = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'];
foreach ($dias as $dia) {
    $apertura = $_POST["apertura_$dia"] ?? '';
    $cierre = $_POST["cierre_$dia"] ?? '';
    if ($apertura && $cierre) {
        $stmt = $connection->prepare("INSERT INTO horario_restaurante 
            (id_restaurante, dia_semana, hora_apertura, hora_cierre) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("isss", $id_restaurante, $dia, $apertura, $cierre);
        $stmt->execute();
        $stmt->close();
    }
}

// Actualizar galería
// Obtener imágenes existentes que el usuario quiere mantener
$imagenes_actuales = $_POST['existing_images'] ?? [];

// Obtener imágenes en BD actualmente
$stmt = $connection->prepare("SELECT url_imagen FROM imagen_restaurante WHERE id_restaurante = ?");
$stmt->bind_param("i", $id_restaurante);
$stmt->execute();
$result = $stmt->get_result();
$imagenes_en_bd = [];
while ($row = $result->fetch_assoc()) {
    $imagenes_en_bd[] = $row['url_imagen'];
}
$stmt->close();

// Borrar solo las imágenes que no están en las actuales
foreach ($imagenes_en_bd as $url) {
    if (!in_array($url, $imagenes_actuales)) {
        $stmt = $connection->prepare("DELETE FROM imagen_restaurante WHERE id_restaurante = ? AND url_imagen = ?");
        $stmt->bind_param("is", $id_restaurante, $url);
        $stmt->execute();
        $stmt->close();
        if (file_exists($url)) unlink($url); // Borra el archivo del servidor
    }
}

if (!empty($_FILES['gallery']) && isset($_FILES['gallery']['tmp_name']) && is_array($_FILES['gallery']['tmp_name'])) {
    foreach ($_FILES['gallery']['tmp_name'] as $index => $tmpName) {
        if (!empty($tmpName) && $_FILES['gallery']['error'][$index] === 0) {
            $nombre_archivo = $_FILES['gallery']['name'][$index];
            $extension = pathinfo($nombre_archivo, PATHINFO_EXTENSION);
            $nombre_final = $id_restaurante . '_' . time() . '_' . $index . '.' . $extension;

            // Rutas
            $ruta_relativa = '/gastrolink/app/img/restaurantes/' . $nombre_final; 
            $ruta_servidor = __DIR__ . '/../../img/restaurantes/' . $nombre_final; 

            // Crear directorio si no existe
            if (!is_dir(dirname($ruta_servidor))) {
                mkdir(dirname($ruta_servidor), 0775, true);
            }

            // Guardar imagen y registrar en BD
            if (move_uploaded_file($tmpName, $ruta_servidor)) {
                $alt = pathinfo($nombre_archivo, PATHINFO_FILENAME);
                $stmt = $connection->prepare("INSERT INTO imagen_restaurante (id_restaurante, url_imagen, alt) VALUES (?, ?, ?)");
                $stmt->bind_param("iss", $id_restaurante, $ruta_relativa, $alt);
                $stmt->execute();
                $stmt->close();
            }
        }
    }
}





echo json_encode(['success' => true, 'message' => 'Perfil actualizado correctamente.']);
