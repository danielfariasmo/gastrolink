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
if (!isset($_POST['funcion']) || $_POST['funcion'] !== 'validando' || !isset($_POST['usuario']) || !isset($_POST['contra'])) {
    echo json_encode(['status' => 'error', 'message' => 'Datos incompletos']);
    exit;
}

$usuario = mysqli_real_escape_string($conexion, $_POST['usuario']);
$clave = $_POST['contra'];

// Consulta única
$query = "SELECT id_usuario, nombre, img_usuario, tipo_usuario, clave FROM usuario WHERE correo = '$usuario'";
$result = mysqli_query($conexion, $query);

if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    
    if (password_verify($clave, $row['clave'])) {
        $_SESSION['id_usuario'] = $row['id_usuario'];
        $_SESSION['correo'] = $usuario;
        $_SESSION['rol'] = $row['tipo_usuario'];
        
        echo json_encode([
            'status' => 'success', 
            'message' => 'Login exitoso', 
            'role' => $row['tipo_usuario'],
            'userId' => $row['id_usuario'],
            'nombre' => $row['nombre'], // Asegúrate de seleccionar este campo en tu consulta SQL
            'fotoPerfil' => $row['img_usuario'] ?? null // Campo opcional
        ]);
    } else {
        // Mensaje genérico para error en usuario o contraseña
        echo json_encode(['status' => 'error', 'message' => 'Correo o contraseña incorrectos']);
    }
} else {
    // Mismo mensaje genérico aunque no exista el usuario
    echo json_encode(['status' => 'error', 'message' => 'Correo o contraseña incorrectos']);
}

mysqli_free_result($result);
mysqli_close($conexion);
?>