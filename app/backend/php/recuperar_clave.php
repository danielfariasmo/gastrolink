<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require '../../../PHPMiller/Exception.php';
require '../../../PHPMiller/PHPMailer.php';
require '../../../PHPMiller/SMTP.php';

include '../../../server/database.php';

if (!isset($connection)) {
    die("Error: No se pudo establecer la conexión a la base de datos.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);

    $stmt = $connection->prepare("SELECT * FROM usuario WHERE correo = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        $usuario = $resultado->fetch_assoc();
        $nombre = $usuario['nombre'];

        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'gastrolink25@gmail.com';
            $mail->Password   = 'zisz czot jjim zsot';
            $mail->SMTPSecure = 'ssl';
            $mail->Port       = 465;

            $mail->setFrom('gastrolink25@gmail.com', 'Gastrolink Support');
            $mail->addAddress($email, $nombre);

            $mail->isHTML(true);
            $mail->Subject = 'Recuperación de Contraseña - Gastrolink';

            $token = bin2hex(random_bytes(50));
            $stmt = $connection->prepare("UPDATE usuario SET token = ? WHERE correo = ?");
            $stmt->bind_param("ss", $token, $email);
            $stmt->execute();
            $stmt->close();

            $url = "http://localhost/gastrolink/app/frontend/html/nueva_clave.html?token=$token&email=" . urlencode($email);

            $mail->Body = '
                <div style="max-width: 600px; margin: auto; font-family: Arial, sans-serif; color: #333; padding: 20px; border: 1px solid #ddd; border-radius: 10px;">
                    <div style="text-align: center; margin-bottom: 20px;">
                        <h1 style="color: #CD4533;">Gastrolink</h1>
                    </div>

                    <h3 style="text-align: center;">Recuperación de Contraseña</h3>
                    <p>Hola ' . htmlspecialchars($nombre) . ',</p>
                    <p>Hemos recibido una solicitud para restablecer la contraseña de tu cuenta en <strong>Gastrolink</strong>.</p>
                    <p>Si no solicitaste esto, puedes ignorar este mensaje.</p>
                    <p>Para restablecer tu contraseña, haz clic en el siguiente botón:</p>
                    <div style="text-align: center; margin: 20px 0;">
                        <a href="' . $url . '" style="background-color: #CD4533; color: #fff; padding: 12px 20px; text-decoration: none; border-radius: 5px; font-size: 16px;">Restablecer Contraseña</a>
                    </div>
        
                    <p>O copia y pega el siguiente enlace en tu navegador:</p>
                    <p><a href="' . $url . '" style="word-break: break-all;">' . $url . '</a></p>
                    <p>Este enlace es válido por <strong>24 horas</strong>.</p>
                    <p>Saludos,<br><strong>El equipo de Gastrolink</strong></p>
                </div>';

            $mail->send();
            echo 'Correo enviado con éxito';
            exit();
        } catch (Exception $e) {
            error_log("Mailer Error: {$mail->ErrorInfo}");
            echo 'Error al enviar el correo.';
        }
    } else {
        echo 'Correo no encontrado.';
        exit();
    }
}
