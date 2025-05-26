<?php
session_start();
require '../vendor/autoload.php'; // Requiere PHPMailer
require '../db_connect.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
// Redirigir si ya está logueado
if (isset($_SESSION['usuario_id'])) {
    header("Location: ../index.php");
    exit();
}

// Procesar el formulario si se envió
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $conn->real_escape_string($_POST['email']);
    
    // Verificar si el email existe
    $query = "SELECT id, nombre_usuario FROM usuarios WHERE email = '$email'";
    $result = $conn->query($query);
    
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $token = bin2hex(random_bytes(32));
        $expira = date('Y-m-d H:i:s', strtotime('+1 hour'));

        // Verificar/crear columnas si no existen
        $checkColumns = $conn->query("SHOW COLUMNS FROM usuarios LIKE 'reset_token'");
        if ($checkColumns->num_rows == 0) {
            $conn->query("ALTER TABLE usuarios 
                         ADD COLUMN reset_token VARCHAR(64) NULL,
                         ADD COLUMN reset_expira DATETIME NULL");
        }

        // Guardar token en la base de datos
        $conn->query("UPDATE usuarios SET 
                     reset_token = '$token', 
                     reset_expira = '$expira' 
                     WHERE id = {$user['id']}");

        // Configurar PHPMailer para Google SMTP
        $mail = new PHPMailer(true);
        
        try {
            // Configuración del servidor SMTP de Google
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'azarielnst@gmail.com'; // Tu correo institucional
            $mail->Password = 'Carreras123!'; // Tu contraseña
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;
            $mail->CharSet = 'UTF-8';

            // Remitente y destinatario
            $mail->setFrom('azarielnst@gmail.com', 'Soporte del Sistema');
            $mail->addAddress($email, $user['nombre_usuario']);

            // Contenido del correo
            $resetLink = "http://".$_SERVER['HTTP_HOST']."/PROYECTOANALISIS12/auth/reset-password.php?token=$token";
            
            $mail->isHTML(true);
            $mail->Subject = 'Restablecer tu contraseña - Sistema de Gestión';
            
            $mail->Body = "
                <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;'>
                    <h2 style='color: #2c3e50;'>Restablecimiento de contraseña</h2>
                    <p>Estimado/a {$user['nombre_usuario']},</p>
                    <p>Hemos recibido una solicitud para restablecer la contraseña de tu cuenta.</p>
                    <p>Por favor, haz clic en el siguiente botón para continuar:</p>
                    <p style='text-align: center; margin: 25px 0;'>
                        <a href='$resetLink' style='background-color: #3498db; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>Restablecer contraseña</a>
                    </p>
                    <p>Si no solicitaste este cambio, por favor ignora este mensaje.</p>
                    <p><small>Este enlace expirará en 1 hora.</small></p>
                    <hr style='border: none; border-top: 1px solid #eee; margin: 20px 0;'>
                    <p style='font-size: 0.9em; color: #7f8c8d;'>Este es un mensaje automático, por favor no respondas directamente a este correo.</p>
                </div>
            ";
            
            $mail->AltBody = "Hola {$user['nombre_usuario']},\n\nPara restablecer tu contraseña, visita este enlace:\n$resetLink\n\nEste enlace expirará en 1 hora.\n\nSi no solicitaste este cambio, ignora este mensaje.";

            $mail->send();
            $_SESSION['recovery_message'] = "Se ha enviado un correo con instrucciones para restablecer tu contraseña.";
        } catch (Exception $e) {
            error_log("Error al enviar correo: " . $mail->ErrorInfo);
            $_SESSION['recovery_error'] = "Error al enviar el correo. Por favor contacta al administrador del sistema.";
        }
    } else {
        $_SESSION['recovery_error'] = "No se encontró una cuenta con ese correo electrónico.";
    }
    
    $conn->close();
    header("Location: pages-recoverypww.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8" />
    <title>Recuperar Contraseña</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- App favicon -->
    <link rel="shortcut icon" href="../assets/images/favicon.ico">

    <!-- App css -->
    <link href="../assets/css/icons.min.css" rel="stylesheet" type="text/css" />
    <link href="../assets/css/app.min.css" rel="stylesheet" type="text/css" id="app-style"/>
</head>

<body class="loading authentication-bg" data-layout-config='{"darkMode":false}'>
    <div class="account-pages pt-2 pt-sm-5 pb-4 pb-sm-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xxl-4 col-lg-5">
                    <div class="card">
                        <!-- Logo -->
                        <div class="card-header pt-4 pb-4 text-center bg-primary">
                            <a href="../index.php">
                                <span><img src="../assets/images/logo.png" alt="" height="80"></span>
                            </a>
                        </div>
                        
                        <div class="card-body p-4">
                            <div class="text-center w-75 m-auto">
                                <h4 class="text-dark-50 text-center mt-0 fw-bold">Recuperar Contraseña</h4>
                                <p class="text-muted mb-4">Ingresa tu correo electrónico y te enviaremos un enlace para restablecer tu contraseña.</p>
                            </div>

                            <?php if (isset($_SESSION['recovery_message'])): ?>
                                <div class="alert alert-success"><?php echo $_SESSION['recovery_message']; unset($_SESSION['recovery_message']); ?></div>
                            <?php endif; ?>
                            
                            <?php if (isset($_SESSION['recovery_error'])): ?>
                                <div class="alert alert-danger"><?php echo $_SESSION['recovery_error']; unset($_SESSION['recovery_error']); ?></div>
                            <?php endif; ?>

                            <form action="pages-recoverypww.php" method="POST">
                                <div class="mb-3">
                                    <label for="email" class="form-label">Correo electrónico</label>
                                    <input class="form-control" type="email" id="email" name="email" required placeholder="Ingresa tu correo">
                                </div>

                                <div class="mb-0 text-center">
                                    <button class="btn btn-primary" type="submit">Enviar Enlace</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-12 text-center">
                            <p class="text-muted">Volver al <a href="pages-login.php" class="text-muted ms-1"><b>Inicio de sesión</b></a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer class="footer footer-alt">
        2018 - <?php echo date("Y"); ?> © TuEmpresa
    </footer>

    <!-- bundle -->
    <script src="../assets/js/vendor.min.js"></script>
    <script src="../assets/js/app.min.js"></script>
</body>
</html>