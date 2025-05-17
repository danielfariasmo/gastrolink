<?php
session_start();
header("Content-Type: application/json");

include "../../../server/database.php";

// Configuración de la base de datos
$servidor = "localhost";
$usuarioBD = "root";
$password = "";
$db = "gastrolink";

// Conexión a la base de datos
$conexion = mysqli_connect($servidor, $usuarioBD, $password, $db);
if (!$conexion) {
    echo json_encode(['status' => 'error', 'message' => 'Error en el sistema']);
    exit;
}

// Verificar datos recibidos
if (!isset($_POST['funcion']) || $_POST['funcion'] !== 'registrar') {
    echo json_encode(['status' => 'error', 'message' => 'Acción no válida']);
    exit;
}

// Validar campos obligatorios
$camposRequeridos = ['nombre', 'apellidos', 'email', 'password', 'rol'];
foreach ($camposRequeridos as $campo) {
    if (empty($_POST[$campo])) {
        echo json_encode(['status' => 'error', 'message' => 'Todos los campos son obligatorios']);
        exit;
    }
}

// Sanitizar y validar datos
$nombre = mysqli_real_escape_string($conexion, $_POST['nombre']);
$apellidos = mysqli_real_escape_string($conexion, $_POST['apellidos']);
$nombreCompleto = $nombre . ' ' . $apellidos;
$email = mysqli_real_escape_string($conexion, $_POST['email']);
$password = $_POST['password'];
$rol = mysqli_real_escape_string($conexion, $_POST['rol']);

// Validar formato de email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['status' => 'error', 'message' => 'Email no válido']);
    exit;
}

// Validar rol
$rolesPermitidos = ['restaurante', 'cocinero', 'camarero'];
if (!in_array($rol, $rolesPermitidos)) {
    echo json_encode(['status' => 'error', 'message' => 'Rol no válido']);
    exit;
}

// Verificar si el email ya existe
$query = "SELECT id_usuario FROM usuario WHERE correo = '$email'";
$result = mysqli_query($conexion, $query);

if (mysqli_num_rows($result) > 0) {
    echo json_encode(['status' => 'error', 'message' => 'El email ya está registrado']);
    exit;
}

// Procesar imagen de perfil
$nombreImagen = null;
if (isset($_FILES['fotoPerfil']) && $_FILES['fotoPerfil']['error'] === UPLOAD_ERR_OK) {
    $directorio = "../../../uploads/perfiles/";
    if (!file_exists($directorio)) {
        mkdir($directorio, 0777, true);
    }
    
    $extension = pathinfo($_FILES['fotoPerfil']['name'], PATHINFO_EXTENSION);
    $nombreImagen = uniqid() . '.' . $extension;
    $rutaCompleta = $directorio . $nombreImagen;
    
    if (!move_uploaded_file($_FILES['fotoPerfil']['tmp_name'], $rutaCompleta)) {
        echo json_encode(['status' => 'error', 'message' => 'Error al subir la imagen']);
        exit;
    }
}

// Hash de la contraseña
$passwordHash = password_hash($password, PASSWORD_DEFAULT);

// Insertar nuevo usuario
$query = "INSERT INTO usuario (nombre, correo, clave, tipo_usuario, img_usuario) 
          VALUES ('$nombre', '$email', '$passwordHash', '$rol', " . ($nombreImagen ? "'$nombreImagen'" : "NULL") . ")";//NOW())";

if (mysqli_query($conexion, $query)) {
    $userId = mysqli_insert_id($conexion);
    
    // Iniciar sesión automáticamente
    $_SESSION['id_usuario'] = $userId;
    $_SESSION['correo'] = $email;
    $_SESSION['rol'] = $rol;
    
    echo json_encode([
        'status' => 'success',
        'message' => 'Registro exitoso',
        'userId' => $userId,
        'nombre' => $nombre,
        'role' => $rol,
        'fotoPerfil' => $nombreImagen ? "/uploads/perfiles/$nombreImagen" : null
    ]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Error en el registro: ' . mysqli_error($conexion)]);
}

mysqli_close($conexion);
?>