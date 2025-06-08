<?php
header('Content-Type: application/json');
include '../../../server/database.php';

$email = trim($_POST['email'] ?? '');
$token = trim($_POST['token'] ?? '');
$newPassword = trim($_POST['nuevaContrasena'] ?? '');

if (!$email || !$token || !$newPassword) {
    echo json_encode(['success' => false, 'message' => 'Faltan datos obligatorios.']);
    exit;
}

$stmt = $connection->prepare("SELECT * FROM usuario WHERE correo = ? AND token = ?");
$stmt->bind_param("ss", $email, $token);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows === 0) {
    echo json_encode(['success' => false, 'message' => 'El enlace no es válido o ha expirado.']);
    exit;
}

$nuevaClaveHash = password_hash($newPassword, PASSWORD_DEFAULT);

$update = $connection->prepare("UPDATE usuario SET clave = ?, token = NULL WHERE correo = ?");
$update->bind_param("ss", $nuevaClaveHash, $email);
$update->execute();

echo json_encode(['success' => true, 'message' => 'Contraseña actualizada correctamente.']);
?>
