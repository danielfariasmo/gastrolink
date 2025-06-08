<?php
session_start();
header("Content-Type: application/json");

include "../../../server/database.php";

// Verificar datos recibidos
if (!isset($_POST['funcion']) || $_POST['funcion'] !== 'registrar') {
    echo json_encode(['status' => 'error', 'message' => 'Acción no válida']);
    exit;
}

// Validar campos obligatorios
$camposRequeridos = ['nombre', 'email', 'password', 'rol'];
foreach ($camposRequeridos as $campo) {
    if (empty($_POST[$campo])) {
        echo json_encode(['status' => 'error', 'message' => 'Todos los campos son obligatorios']);
        exit;
    }
}

// Sanitizar y validar datos
$nombre = mysqli_real_escape_string($connection, $_POST['nombre']);
// $apellidos = mysqli_real_escape_string($connection, $_POST['apellidos']);
$nombreCompleto = $nombre ;
$email = mysqli_real_escape_string($connection, $_POST['email']);
$password = $_POST['password'];
$rol = mysqli_real_escape_string($connection, $_POST['rol']);

// Validaciones
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['status' => 'error', 'message' => 'Email no válido']);
    exit;
}

$rolesPermitidos = ['restaurante', 'cocinero', 'camarero'];
if (!in_array($rol, $rolesPermitidos)) {
    echo json_encode(['status' => 'error', 'message' => 'Rol no válido']);
    exit;
}

// Verificar si el email ya existe
$query = "SELECT id_usuario FROM usuario WHERE correo = '$email'";
$result = mysqli_query($connection, $query);

if (mysqli_num_rows($result) > 0) {
    echo json_encode(['status' => 'error', 'message' => 'El email ya está registrado']);
    exit;
}

// Procesar imagen de perfil
$nombreImagen = null;

if (isset($_FILES['fotoPerfil']) && $_FILES['fotoPerfil']['error'] === UPLOAD_ERR_OK) {
    // Configuración
    $directorio = $_SERVER['DOCUMENT_ROOT'] . "/gastrolink/app/img/usuarios/";
    $extensionesPermitidas = ['jpg', 'jpeg', 'png', 'gif'];
    $tamanoMaximo = 2 * 1024 * 1024; // 2MB
    
    // Verificar y crear directorio
    if (!file_exists($directorio)) {
        if (!mkdir($directorio, 0755, true)) {
            echo json_encode([
                'status' => 'error', 
                'message' => 'No se pudo crear el directorio de destino'
            ]);
            exit;
        }
    }
    
    // Validar archivo
    $extension = strtolower(pathinfo($_FILES['fotoPerfil']['name'], PATHINFO_EXTENSION));
    $tamanoArchivo = $_FILES['fotoPerfil']['size'];
    $tipoArchivo = $_FILES['fotoPerfil']['type'];
    
    // Validaciones
    if (!in_array($extension, $extensionesPermitidas)) {
        echo json_encode([
            'status' => 'error', 
            'message' => 'Tipo de archivo no permitido. Solo se aceptan: ' . implode(', ', $extensionesPermitidas)
        ]);
        exit;
    }
    
    if ($tamanoArchivo > $tamanoMaximo) {
        echo json_encode([
            'status' => 'error', 
            'message' => 'El archivo es demasiado grande. Tamaño máximo: 2MB'
        ]);
        exit;
    }
    
    // Generar nombre único y mover archivo
    $nombreImagen = uniqid() . '.' . $extension;
    $rutaCompleta = $directorio . $nombreImagen;
    
    if (!move_uploaded_file($_FILES['fotoPerfil']['tmp_name'], $rutaCompleta)) {
        // Más información sobre el error
        $error = error_get_last();
        echo json_encode([
            'status' => 'error', 
            'message' => 'Error al mover el archivo subido',
            'error_details' => $error ? $error['message'] : 'Error desconocido'
        ]);
        exit;
    }
    
    // Verificar que el archivo existe después de moverlo
    if (!file_exists($rutaCompleta)) {
        echo json_encode([
            'status' => 'error', 
            'message' => 'El archivo no se guardó correctamente en el destino'
        ]);
        exit;
    }
}

// Hash de la contraseña
$passwordHash = password_hash($password, PASSWORD_DEFAULT);

// Insertar nuevo usuario
$query = "INSERT INTO usuario (nombre, correo, clave, tipo_usuario, img_usuario)
          VALUES ('$nombreCompleto', '$email', '$passwordHash', '$rol', " . ($nombreImagen ? "'/gastrolink/app/img/usuarios/$nombreImagen'" : "NULL") . ")";

if (mysqli_query($connection, $query)) {
    $userId = mysqli_insert_id($connection);
    
    // Configurar datos de sesión
    $_SESSION['id_usuario'] = $userId;
    $_SESSION['correo'] = $email;
    $_SESSION['rol'] = $rol;
    $_SESSION['nombre'] = $nombreCompleto;
    $_SESSION['img_usuario'] = $nombreImagen;

    echo json_encode([
        'status' => 'success',
        'message' => 'Registro exitoso',
        'userData' => [
            'id_usuario' => $userId,
            'nombre' => $nombreCompleto,
            'correo' => $email,
            'tipo_usuario' => $rol,
            'img_usuario' => $nombreImagen ? '/gastrolink/app/img/usuarios/' . $nombreImagen  : '/gastrolink/app/img/default-avatar.jpg'
        ]
    ]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Error en el registro: ' . mysqli_error($connection)]);
}

mysqli_close($connection);
?>