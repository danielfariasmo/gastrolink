<?php
session_start();
header("Content-Type: application/json");

include "../../../server/database.php";

// Verificar datos recibidos
if (
    !isset($_POST['funcion']) || $_POST['funcion'] !== 'validando' ||
    !isset($_POST['usuario']) || !isset($_POST['contra'])
) {
    echo json_encode(['status' => 'error', 'message' => 'Datos incompletos']);
    exit;
}

$usuario = mysqli_real_escape_string($connection, $_POST['usuario']);
$clave = $_POST['contra'];

// Consulta mejorada para obtener todos los datos necesarios
$query = "SELECT id_usuario, nombre, correo, img_usuario, tipo_usuario, clave FROM usuario WHERE correo = '$usuario'";
$result = mysqli_query($connection, $query);

if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);

    if (password_verify($clave, $row['clave'])) {
        $_SESSION['id_usuario'] = $row['id_usuario'];
        $_SESSION['correo'] = $row['correo'];
        $_SESSION['rol'] = $row['tipo_usuario'];
        $_SESSION['nombre'] = $row['nombre'];
        $_SESSION['img_usuario'] = $row['img_usuario'];

        echo json_encode([
            'status' => 'success',
            'message' => 'Login exitoso',
            'userData' => [
                'id_usuario' => $row['id_usuario'],
                'nombre' => $row['nombre'],
                'correo' => $row['correo'],
                'tipo_usuario' => $row['tipo_usuario'],
                'img_usuario' => $row['img_usuario'] ? '/gastrolink/app/img/usuarios/' . $row['img_usuario'] : '/gastrolink/app/img/default-avatar.jpg'
            ]
        ]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Correo o contraseña incorrectos']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Correo o contraseña incorrectos']);
}

mysqli_free_result($result);
mysqli_close($connection);
?>