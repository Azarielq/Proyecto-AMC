<?php
session_start();
require '../db_connect.php';

// Verificar si el token es válido
if (!isset($_GET['token'])) {
    $_SESSION['reset_error'] = "Token no válido o ha expirado.";
    header("Location: pages-recoveryww.php");
    exit();
}

$token = $conn->real_escape_string($_GET['token']);
$now = date('Y-m-d H:i:s');

// Buscar usuario con este token no expirado
$query = "SELECT id FROM usuarios WHERE reset_token = '$token' AND reset_expira > '$now'";
$result = $conn->query($query);

if ($result->num_rows === 0) {
    $_SESSION['reset_error'] = "El enlace ha expirado o no es válido. Por favor solicita un nuevo enlace.";
    header("Location: pages-recoveryww.php");
    exit();
}

$user = $result->fetch_assoc();

// Procesar el formulario de cambio de contraseña
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    
    if (empty($password) || empty($confirm_password)) {
        $_SESSION['reset_error'] = "Ambos campos son requeridos.";
    } elseif ($password !== $confirm_password) {
        $_SESSION['reset_error'] = "Las contraseñas no coinciden.";
    } elseif (strlen($password) < 8) {
        $_SESSION['reset_error'] = "La contraseña debe tener al menos 8 caracteres.";
    } else {
        // Hash de la nueva contraseña
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        // Actualizar contraseña y limpiar token
        $updateQuery = "UPDATE usuarios SET 
                        contraseña = '$hashed_password', 
                        reset_token = NULL, 
                        reset_expira = NULL 
                        WHERE id = {$user['id']}";
        
        if ($conn->query($updateQuery)) {
            $_SESSION['reset_success'] = "Tu contraseña ha sido actualizada correctamente. Ahora puedes iniciar sesión.";
            header("Location: pages-login.php");
            exit();
        } else {
            $_SESSION['reset_error'] = "Error al actualizar la contraseña. Por favor intenta nuevamente.";
        }
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8" />
    <title>Restablecer Contraseña</title>
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
                                <span><img src="../assets/images/logo.png" alt="" height="18"></span>
                            </a>
                        </div>
                        
                        <div class="card-body p-4">
                            <div class="text-center w-75 m-auto">
                                <h4 class="text-dark-50 text-center mt-0 fw-bold">Restablecer Contraseña</h4>
                                <p class="text-muted mb-4">Ingresa tu nueva contraseña.</p>
                            </div>

                            <?php if (isset($_SESSION['reset_error'])): ?>
                                <div class="alert alert-danger"><?php echo $_SESSION['reset_error']; unset($_SESSION['reset_error']); ?></div>
                            <?php endif; ?>

                            <form action="reset-password.php?token=<?php echo htmlspecialchars($token); ?>" method="POST">
                                <div class="mb-3">
                                    <label for="password" class="form-label">Nueva Contraseña</label>
                                    <input type="password" class="form-control" id="password" name="password" required minlength="8" placeholder="Mínimo 8 caracteres">
                                </div>
                                
                                <div class="mb-3">
                                    <label for="confirm_password" class="form-label">Confirmar Contraseña</label>
                                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" required minlength="8" placeholder="Repite tu contraseña">
                                </div>

                                <div class="mb-0 text-center">
                                    <button class="btn btn-primary" type="submit">Actualizar Contraseña</button>
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
    
    <script>
    // Validación de contraseñas coincidentes
    document.querySelector('form').addEventListener('submit', function(e) {
        const password = document.getElementById('password').value;
        const confirmPassword = document.getElementById('confirm_password').value;
        
        if (password !== confirmPassword) {
            e.preventDefault();
            alert('Las contraseñas no coinciden. Por favor verifica.');
        }
    });
    </script>
</body>
</html>