<?php
session_start();

// Redirigir si ya está logueado
if (isset($_SESSION['usuario_id'])) {
    header("Location: ../index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8" />
    <title>Ingresar</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Login Panel" name="description" />
    <meta content="TuEmpresa" name="author" />

    <!-- Favicon -->
    <link rel="shortcut icon" href="../assets/images/favicon.ico">

    <!-- App CSS -->
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
                        <div class="card-header pt-4 pb-4 text-center" style="background-color: #caf2ff;">
                            <a href="#">
                                <span><img src="../assets/images/logo.png" alt="Logo" height="90"></span>
                            </a>
                        </div>

                        <div class="card-body p-4">
                            <div class="text-center w-75 m-auto">
                                <h4 class="text-dark-50 text-center pb-0 fw-bold">Ingresar</h4>
                                <p class="text-muted mb-4">Ingresa tu email y contraseña para continuar.</p>
                            </div>

                            <?php
                            // Mostrar mensaje de error si existe
                            if (isset($_SESSION['error_login'])) {
                                echo '<div class="alert alert-danger">' . $_SESSION['error_login'] . '</div>';
                                unset($_SESSION['error_login']);
                            }
                            ?>

                            <form action="login_process.php" method="POST">

                                <div class="mb-3">
                                    <label for="emailaddress" class="form-label">Email</label>
                                    <input class="form-control" type="email" id="emailaddress" name="email" required placeholder="Ingresa tu email">
                                </div>

                                <div class="mb-3">
                                    <a href="pages-recoverypww.php" class="text-muted float-end"><small>¿Olvidaste tu contraseña?</small></a>
                                    <label for="password" class="form-label">Contraseña</label>
                                    <div class="input-group input-group-merge">
                                        <input type="password" id="password" name="password" class="form-control" required placeholder="Ingresa tu contraseña">
                                        <div class="input-group-text" data-password="false">
                                            <span class="password-eye"></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="checkbox-signin" name="remember" checked>
                                        <label class="form-check-label" for="checkbox-signin">Recuérdame</label>
                                    </div>
                                </div>

                                <div class="mb-3 text-center">
                                    <button class="btn btn-primary" type="submit">Ingresar</button>
                                </div>

                            </form>
                        </div> <!-- end card-body -->
                    </div>
                    <!-- end card -->



                </div> <!-- end col -->
            </div> <!-- end row -->
        </div> <!-- end container -->
    </div> <!-- end page -->

    <!-- Scripts -->
    <script src="../assets/js/vendor.min.js"></script>
    <script src="../assets/js/app.min.js"></script>
</body>
</html>