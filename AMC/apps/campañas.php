<?php
// Iniciar sesión
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../login.php");
    exit();
}

// Incluir la conexión a la base de datos
require '../db_connect.php';

// Configuración de la página
$pagina_titulo = "Gestión de Campañas";
$estilos_adicionales = [
    '../assets/css/vendor/dataTables.bootstrap5.css',
    '../assets/css/vendor/responsive.bootstrap5.css',
    '../assets/css/vendor/select2.min.css',
    '../assets/css/vendor/buttons.dataTables.min.css'
];
$scripts_adicionales = [
    '../assets/js/vendor/jquery.dataTables.min.js',
    '../assets/js/vendor/dataTables.bootstrap5.js',
    '../assets/js/vendor/select2.min.js',
    '../assets/js/vendor/dataTables.buttons.min.js',
    '../assets/js/vendor/jszip.min.js',
    '../assets/js/vendor/pdfmake.min.js',
    '../assets/js/vendor/vfs_fonts.js',
    '../assets/js/vendor/buttons.html5.min.js',
    '../assets/js/vendor/buttons.print.min.js'
];

// Procesar acciones
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'crear':
                $nombre = $_POST['nombre'];
                $asunto = $_POST['asunto'];
                $mensaje = $_POST['mensaje'];
                $tipo_grupo = $_POST['tipo_grupo'];
                $destinatarios = count($_POST['destinatarios'] ?? []);
                
                $sql = "INSERT INTO campañas (nombre, asunto, mensaje, destinatarios, fecha_creacion, estado, tipo_grupo)
                        VALUES (?, ?, ?, ?, NOW(), 'Borrador', ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("sssis", $nombre, $asunto, $mensaje, $destinatarios, $tipo_grupo);
                $stmt->execute();
                
                $campana_id = $conn->insert_id;
                
                // Insertar destinatarios
                if (!empty($_POST['destinatarios'])) {
                    $sql_dest = "INSERT INTO campaña_destinatarios (campaña_id, cliente_id, email) VALUES (?, ?, ?)";
                    $stmt_dest = $conn->prepare($sql_dest);
                    foreach ($_POST['destinatarios'] as $cliente_id) {
                        $sql_cliente = "SELECT correo FROM todos_los_clientes WHERE cod_cliente = ?";
                        $stmt_cliente = $conn->prepare($sql_cliente);
                        $stmt_cliente->bind_param("s", $cliente_id);
                        $stmt_cliente->execute();
                        $result_cliente = $stmt_cliente->get_result();
                        if ($row = $result_cliente->fetch_assoc()) {
                            $stmt_dest->bind_param("iss", $campana_id, $cliente_id, $row['correo']);
                            $stmt_dest->execute();
                        }
                    }
                }
                break;
                
            case 'editar':
                $id = $_POST['id'];
                $nombre = $_POST['nombre'];
                $asunto = $_POST['asunto'];
                $mensaje = $_POST['mensaje'];
                $tipo_grupo = $_POST['tipo_grupo'];
                $destinatarios = count($_POST['destinatarios'] ?? []);
                
                $sql = "UPDATE campañas SET nombre = ?, asunto = ?, mensaje = ?, destinatarios = ?, tipo_grupo = ? WHERE id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("sssisi", $nombre, $asunto, $mensaje, $destinatarios, $tipo_grupo, $id);
                $stmt->execute();
                
                // Actualizar destinatarios
                $sql_delete = "DELETE FROM campaña_destinatarios WHERE campaña_id = ?";
                $stmt_delete = $conn->prepare($sql_delete);
                $stmt_delete->bind_param("i", $id);
                $stmt_delete->execute();
                
                if (!empty($_POST['destinatarios'])) {
                    $sql_dest = "INSERT INTO campaña_destinatarios (campaña_id, cliente_id, email) VALUES (?, ?, ?)";
                    $stmt_dest = $conn->prepare($sql_dest);
                    foreach ($_POST['destinatarios'] as $cliente_id) {
                        $sql_cliente = "SELECT correo FROM todos_los_clientes WHERE cod_cliente = ?";
                        $stmt_cliente = $conn->prepare($sql_cliente);
                        $stmt_cliente->bind_param("s", $cliente_id);
                        $stmt_cliente->execute();
                        $result_cliente = $stmt_cliente->get_result();
                        if ($row = $result_cliente->fetch_assoc()) {
                            $stmt_dest->bind_param("iss", $id, $cliente_id, $row['correo']);
                            $stmt_dest->execute();
                        }
                    }
                }
                break;
                
            case 'eliminar':
                $id = $_POST['id'];
                $sql = "DELETE FROM campañas WHERE id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $id);
                $stmt->execute();
                
                $sql = "DELETE FROM campaña_destinatarios WHERE campaña_id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $id);
                $stmt->execute();
                break;
                
            case 'enviar':
                $id = $_POST['id'];
                $sql = "UPDATE campañas SET estado = 'Enviando', fecha_envio = NOW() WHERE id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $id);
                $stmt->execute();
                
                // Simular envío (en un entorno real, aquí iría la lógica de envío de emails)
                $sql_dest = "UPDATE campaña_destinatarios SET enviado = 1, fecha_envio = NOW(), estado = 'Enviado' WHERE campaña_id = ?";
                $stmt_dest = $conn->prepare($sql_dest);
                $stmt_dest->bind_param("i", $id);
                $stmt_dest->execute();
                
                $sql = "UPDATE campañas SET estado = 'Enviada' WHERE id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $id);
                $stmt->execute();
                break;
        }
        header("Location: campañas.php");
        exit();
    }
}
?>

<!DOCTYPE html>
    <html lang="en">

    
<!-- Mirrored from coderthemes.com/hyper/saas/index.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 29 Jul 2022 10:18:47 GMT -->
<head>
        <meta charset="utf-8" />
        <title>Panel de Control</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta content="A fully featured admin theme which can be used to build CRM, CMS, etc." name="description" />
        <meta content="Coderthemes" name="author" />
        <!-- App favicon -->
        <link rel="shortcut icon" href="../assets/images/favicon.ico">

        <!-- third party css -->
        <link href="../assets/css/vendor/jquery-jvectormap-1.2.2.css" rel="stylesheet" type="text/css" />
        <!-- third party css end -->
<!-- En el head de tu HTML -->
        <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
        <script src="https://cdn.jsdelivr.net/npm/jquery/dist/jquery.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/moment/min/moment.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
        <!-- App css -->
        <link href="../assets/css/icons.min.css" rel="stylesheet" type="text/css" />
        <link href="../assets/css/app.min.css" rel="stylesheet" type="text/css" id="app-style"/>

    </head>
    <body class="loading" data-layout-color="light" data-leftbar-theme="dark" data-layout-mode="fluid" data-rightbar-onstart="true">
        <div class="wrapper">
            <!-- ========== Left Sidebar Start ========== -->
            <div class="leftside-menu">
                <!-- Logo principal con superposición -->
                <a href="../index.php" class="logo text-center logo-light transition-all relative inline-block"> <!-- Contenedor relativo -->
                    <!-- Logo grande -->
                    <span</span>
                    <span class="logo-lg relative inline-block">
                        <img 
                            src="../assets/images/logo.png" 
                            alt="Company Logo" 
                            height="80" 
                            class="hover:scale-105 transition-transform relative z-0"
                        >
                        <!-- Badge superpuesto ENCIMA del logo -->

                    </span>
                    <!-- Logo pequeño (sin superposición) -->
                    <span class="logo-sm">
                        <img src="../assets/images/logo_sm.png" alt="Company Logo" height="41" class="hover:scale-105 transition-transform">
                    </span>
                </a>

    
                <div class="h-100" id="leftside-menu-container" data-simplebar>

                    <!--- Sidemenu -->

                    <ul class="side-nav">
    <!-- Título -->
    <li class="side-nav-title side-nav-item text-uppercase font-weight-bold text-muted">Navegación</li>

    <!-- Inicio -->
    <li class="side-nav-item">
        <a href="../index.php" class="side-nav-link active">
            <i class="uil-home-alt"></i>
            <span> Inicio </span>
        </a>
    </li>

    <!-- Clientes -->
            <li class="side-nav-item">
        <a href="registrarclientes.php" class="side-nav-link active">
        <i class="uil-users-alt"></i>
            <span> Clientes </span>
        </a>
    </li>

    <!-- Campañas -->
    <li class="side-nav-item">
        <a data-bs-toggle="collapse" href="#sidebarCampanas" aria-expanded="false" aria-controls="sidebarCampanas" class="side-nav-link collapsed">
            <i class="uil-megaphone"></i>
            <span> Campañas </span>
            <span class="menu-arrow"></span>
        </a>
        <div class="collapse" id="sidebarCampanas">
            <ul class="side-nav-second-level">
                <li>
                    <a href="registrar.php" class="flex items-center">
                        <i class="uil-plus-circle mr-2"></i> Registrar campaña
                    </a>
                </li>
                <li>
                    <a href="campañas.php" class="flex items-center">
                        <i class="uil-list-ul mr-2"></i> Lista de campañas
                    </a>
                </li>
            </ul>
        </div>
    </li>

    <!-- Tablas -->
    <li class="side-nav-title side-nav-item text-uppercase font-weight-bold text-muted">Datos</li>
    <li class="side-nav-item">
        <a data-bs-toggle="collapse" href="#sidebarTables" aria-expanded="false" aria-controls="sidebarTables" class="side-nav-link collapsed">
            <i class="uil-table"></i>
            <span> Tablas </span>
            <span class="menu-arrow"></span>
        </a>
        <div class="collapse" id="sidebarTables">
            <ul class="side-nav-second-level">
                <li>
                    <a href="tables-basic.html" class="flex items-center">
                        <i class="uil-list-ui-alt mr-2"></i> Tablas básicas
                    </a>
                </li>
                <li>
                    <a href="tables-datatable.html" class="flex items-center">
                        <i class="uil-server mr-2"></i> Datatables
                    </a>
                </li>
                <li>
                    <a href="#" class="flex items-center">
                        <i class="uil-chart-bar mr-2"></i> Tablas avanzadas
                    </a>
                </li>
            </ul>
        </div>
    </li>

    <!-- Cronograma -->
    <li class="side-nav-title side-nav-item text-uppercase font-weight-bold text-muted">Organización</li>
    <li class="side-nav-item">
        <a href="cronograma.php" class="side-nav-link">
            <i class="uil-calendar-alt"></i>
            <span> Cronograma </span>
        </a>
    </li>
</ul>



                        <!-- Divider -->
                        <li class="side-nav-divider mt-4 mb-2"></li>


                    </ul>

                    <!-- User Profile Card at Bottom -->
                    <div class="leftbar-user-card p-3 text-center position-absolute bottom-0 start-0 end-0">
                        <img src="../assets/images/users/avatar-1.jpg" alt="user-image" class="rounded-circle shadow-sm img-thumbnail mb-2" width="80">
                        <h6 class="mb-1">John Doe</h6>
                        <p class="text-muted small">Administrador</p>
                        <a href="#" class="btn btn-sm btn-primary w-100">Mi perfil</a>
                    </div>

                    <div class="clearfix"></div>
                </div>
            </div>
                <!-- Sidebar -left -->

            </div>
            <!-- Left Sidebar End -->

            <!-- ============================================================== -->
            <!-- Start Page Content here -->
            <!-- ============================================================== -->

            <div class="content-page">
                <div class="content">
                    <!-- Topbar Start -->
                    <div class="navbar-custom">
                        <ul class="list-unstyled topbar-menu float-end mb-0">
                            <li class="dropdown notification-list d-lg-none">
                                <a class="nav-link dropdown-toggle arrow-none" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                                    <i class="dripicons-search noti-icon"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-animated dropdown-lg p-0">
                                    <form class="p-3">
                                        <input type="text" class="form-control" placeholder="Search ..." aria-label="Recipient's username">
                                    </form>
                                </div>
                            </li>


                            <li class="dropdown notification-list">
                                <a class="nav-link dropdown-toggle arrow-none" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                            
                                </a>
                                <div class="dropdown-menu dropdown-menu-end dropdown-menu-animated dropdown-lg">

                                    <!-- item-->
                                    <div class="dropdown-item noti-title px-3">
                                        <h5 class="m-0">
                                            <span class="float-end">
                                                <a href="javascript: void(0);" class="text-dark">
                                                    <small>Clear All</small>
                                                </a>
                                            </span>Notification
                                        </h5>
                                    </div>

                                    <div class="px-3" style="max-height: 300px;" data-simplebar>

                                        <h5 class="text-muted font-13 fw-normal mt-0">Today</h5>
                                        <!-- item-->
                                        <a href="javascript:void(0);" class="dropdown-item p-0 notify-item card unread-noti shadow-none mb-2">
                                            <div class="card-body">
                                                <span class="float-end noti-close-btn text-muted"><i class="mdi mdi-close"></i></span>   
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-shrink-0">
                                                        <div class="notify-icon bg-primary">
                                                            <i class="mdi mdi-comment-account-outline"></i>
                                                        </div>
                                                    </div>
                                                    <div class="flex-grow-1 text-truncate ms-2">
                                                        <h5 class="noti-item-title fw-semibold font-14">Datacorp <small class="fw-normal text-muted ms-1">1 min ago</small></h5>
                                                        <small class="noti-item-subtitle text-muted">Caleb Flakelar commented on Admin</small>
                                                    </div>
                                                  </div>
                                            </div>
                                        </a>

                                        <!-- item-->
                                        <a href="javascript:void(0);" class="dropdown-item p-0 notify-item card read-noti shadow-none mb-2">
                                            <div class="card-body">
                                                <span class="float-end noti-close-btn text-muted"><i class="mdi mdi-close"></i></span>   
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-shrink-0">
                                                        <div class="notify-icon bg-info">
                                                            <i class="mdi mdi-account-plus"></i>
                                                        </div>
                                                    </div>
                                                    <div class="flex-grow-1 text-truncate ms-2">
                                                        <h5 class="noti-item-title fw-semibold font-14">Admin <small class="fw-normal text-muted ms-1">1 hours ago</small></h5>
                                                        <small class="noti-item-subtitle text-muted">New user registered</small>
                                                    </div>
                                                  </div>
                                            </div>
                                        </a>

                                        <h5 class="text-muted font-13 fw-normal mt-0">Yesterday</h5>

                                        <!-- item-->
                                        <a href="javascript:void(0);" class="dropdown-item p-0 notify-item card read-noti shadow-none mb-2">
                                            <div class="card-body">
                                                <span class="float-end noti-close-btn text-muted"><i class="mdi mdi-close"></i></span>   
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-shrink-0">
                                                        <div class="notify-icon">
                                                            <img src="../assets/images/users/avatar-2.jpg" class="img-fluid rounded-circle" alt="" />
                                                        </div>
                                                    </div>
                                                    <div class="flex-grow-1 text-truncate ms-2">
                                                        <h5 class="noti-item-title fw-semibold font-14">Cristina Pride <small class="fw-normal text-muted ms-1">1 day ago</small></h5>
                                                        <small class="noti-item-subtitle text-muted">Hi, How are you? What about our next meeting</small>
                                                    </div>
                                                  </div>
                                            </div>
                                        </a>

                                        <h5 class="text-muted font-13 fw-normal mt-0">30 Dec 2021</h5>

                                        <!-- item-->
                                        <a href="javascript:void(0);" class="dropdown-item p-0 notify-item card read-noti shadow-none mb-2">
                                            <div class="card-body">
                                                <span class="float-end noti-close-btn text-muted"><i class="mdi mdi-close"></i></span>   
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-shrink-0">
                                                        <div class="notify-icon bg-primary">
                                                            <i class="mdi mdi-comment-account-outline"></i>
                                                        </div>
                                                    </div>
                                                    <div class="flex-grow-1 text-truncate ms-2">
                                                        <h5 class="noti-item-title fw-semibold font-14">Datacorp</h5>
                                                        <small class="noti-item-subtitle text-muted">Caleb Flakelar commented on Admin</small>
                                                    </div>
                                                  </div>
                                            </div>
                                        </a>

                                         <!-- item-->
                                         <a href="javascript:void(0);" class="dropdown-item p-0 notify-item card read-noti shadow-none mb-2">
                                            <div class="card-body">
                                                <span class="float-end noti-close-btn text-muted"><i class="mdi mdi-close"></i></span>   
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-shrink-0">
                                                        <div class="notify-icon">
                                                            <img src="../assets/images/users/avatar-4.jpg" class="img-fluid rounded-circle" alt="" />
                                                        </div>
                                                    </div>
                                                    <div class="flex-grow-1 text-truncate ms-2">
                                                        <h5 class="noti-item-title fw-semibold font-14">Karen Robinson</h5>
                                                        <small class="noti-item-subtitle text-muted">Wow ! this admin looks good and awesome design</small>
                                                    </div>
                                                  </div>
                                            </div>
                                        </a>

                                        <div class="text-center">
                                            <i class="mdi mdi-dots-circle mdi-spin text-muted h3 mt-0"></i>
                                        </div>
                                    </div>

                                    <!-- All-->
                                    <a href="javascript:void(0);" class="dropdown-item text-center text-primary notify-item border-top border-light py-2">
                                        View All
                                    </a>

                                </div>
                            </li>



                            <li class="dropdown notification-list">
                                <a class="nav-link dropdown-toggle nav-user arrow-none me-0" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false"
                                    aria-expanded="false">
                                    <span class="account-user-avatar"> 
                                        <img src="../assets/images/users/avatar-1.jpg" alt="user-image" class="rounded-circle">
                                    </span>
                                    <span>
                                        <span class="account-user-name">Dominic Keller</span>
                                        <span class="account-position">Founder</span>
                                    </span>
                                </a>
                                <div class="dropdown-menu dropdown-menu-end dropdown-menu-animated topbar-dropdown-menu profile-dropdown">
                                    <!-- item-->
                                    <div class=" dropdown-header noti-title">
                                        <h6 class="text-overflow m-0">Bienvenido!</h6>
                                    </div>

                                    <!-- item-->
                                    <a href="javascript:void(0);" class="dropdown-item notify-item">
                                        <i class="mdi mdi-account-circle me-1"></i>
                                        <span>My Cuenta</span>
                                    </a>

                                    <!-- item-->
                                    <a href="javascript:void(0);" class="dropdown-item notify-item">
                                        <i class="mdi mdi-account-edit me-1"></i>
                                        <span>Configuracion</span>
                                    </a>

                                    <!-- item-->
                                    <a href="javascript:void(0);" class="dropdown-item notify-item">
                                        <i class="mdi mdi-lifebuoy me-1"></i>
                                        <span>Soporte</span>
                                    </a>

                                    <!-- item-->
                                    <a href="javascript:void(0);" class="dropdown-item notify-item">
                                        <h5 class="mt-0 mb-2">Tema</h5>
                                        <hr class="mt-1 mb-2" />
                                        
                                        <div class="form-check form-switch mb-2">
                                            <input class="form-check-input" type="checkbox" name="color-scheme-mode" value="light" id="light-mode-check" checked onchange="toggleModes('light')">
                                            <label class="form-check-label" for="light-mode-check"> Claro</label>
                                        </div>
                                    
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" name="color-scheme-mode" value="dark" id="dark-mode-check" onchange="toggleModes('dark')">
                                            <label class="form-check-label" for="dark-mode-check"> Oscuro</label>
                                        </div>
                                    </a>
                                    
                                    <script>
                                        function toggleModes(mode) {
                                            var lightModeCheck = document.getElementById('light-mode-check');
                                            var darkModeCheck = document.getElementById('dark-mode-check');
                                            
                                            if (mode === 'light') {
                                                // Si el modo "Light" es activado, desactiva "Dark"
                                                darkModeCheck.checked = false;
                                            } else if (mode === 'dark') {
                                                // Si el modo "Dark" es activado, desactiva "Light"
                                                lightModeCheck.checked = false;
                                            }
                                        }
                                    </script>
                                    
                                    
                                    <!-- item-->
                                    <a href="../auth/pages-logout.php" class="dropdown-item notify-item">
    <i class="mdi mdi-logout me-1"></i>
    <span>Salir</span>
</a>
                                </div>
                            </li>

                        </ul>
                        <button class="button-menu-mobile open-left">
                            <i class="mdi mdi-menu"></i>
                        </button>
                        <div class="app-search dropdown d-none d-lg-block">
                            <form>
                                <div class="input-group">
                                    <input type="text" class="form-control dropdown-toggle"  placeholder="Buscar..." id="top-search">
                                    <span class="mdi mdi-magnify search-icon"></span>
                                    <button class="input-group-text btn-primary" type="submit">Buscar</button>
                                </div>
                            </form>

                            <div class="dropdown-menu dropdown-menu-animated dropdown-lg" id="search-dropdown">
                                <!-- item-->
                                <div class="dropdown-header noti-title">
                                    <h5 class="text-overflow mb-2">Found <span class="text-danger">17</span> Resultados</h5>
                                </div>

                                <!-- item-->
                                <a href="javascript:void(0);" class="dropdown-item notify-item">
                                    <i class="uil-notes font-16 me-1"></i>
                                    <span>Analytics Report</span>
                                </a>

                                <!-- item-->
                                <a href="javascript:void(0);" class="dropdown-item notify-item">
                                    <i class="uil-life-ring font-16 me-1"></i>
                                    <span>How can I help you?</span>
                                </a>

                                <!-- item-->
                                <a href="javascript:void(0);" class="dropdown-item notify-item">
                                    <i class="uil-cog font-16 me-1"></i>
                                    <span>User profile settings</span>
                                </a>

                                <!-- item-->
                                <div class="dropdown-header noti-title">
                                    <h6 class="text-overflow mb-2 text-uppercase">Users</h6>
                                </div>

                                <div class="notification-list">
                                    <!-- item-->
                                    <a href="javascript:void(0);" class="dropdown-item notify-item">
                                        <div class="d-flex">
                                            <img class="d-flex me-2 rounded-circle" src="../assets/images/users/avatar-2.jpg" alt="Generic placeholder image" height="32">
                                            <div class="w-100">
                                                <h5 class="m-0 font-14">Erwin Brown</h5>
                                                <span class="font-12 mb-0">UI Designer</span>
                                            </div>
                                        </div>
                                    </a>

                                    <!-- item-->
                                    <a href="javascript:void(0);" class="dropdown-item notify-item">
                                        <div class="d-flex">
                                            <img class="d-flex me-2 rounded-circle" src="../assets/images/users/avatar-5.jpg" alt="Generic placeholder image" height="32">
                                            <div class="w-100">
                                                <h5 class="m-0 font-14">Jacob Deo</h5>
                                                <span class="font-12 mb-0">Developer</span>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- end Topbar -->
                    
                    <!-- Start Content-->
                    <div class="container-fluid">

                        <!-- start page title -->
<!-- start page title -->
<div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <div class="page-title-box">
                                <h4 class="page-title">Gestión de Campañas</h4>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="mb-3">
                                        <a href="registrar.php" class="btn btn-primary">
                                            <i class="mdi mdi-plus-circle me-1"></i> Nueva Campaña
                                        </a>
                                    </div>
                                    
                                    <table id="campanasTable" class="table table-striped table-bordered dt-responsive nowrap">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Nombre</th>
                                                <th>Asunto</th>
                                                <th>Destinatarios</th>
                                                <th>Estado</th>
                                                <th>Fecha Creación</th>
                                                <th>Fecha Envío</th>
                                                <th>Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $sql = "SELECT * FROM campañas ORDER BY fecha_creacion DESC";
                                            $result = $conn->query($sql);
                                            while ($row = $result->fetch_assoc()):
                                            ?>
                                            <tr>
                                                <td><?php echo $row['id']; ?></td>
                                                <td><?php echo htmlspecialchars($row['nombre']); ?></td>
                                                <td><?php echo htmlspecialchars($row['asunto']); ?></td>
                                                <td><?php echo $row['destinatarios']; ?></td>
                                                <td>
                                                    <span class="badge <?php
                                                        switch ($row['estado']) {
                                                            case 'Borrador': echo 'bg-warning'; break;
                                                            case 'Enviando': echo 'bg-info'; break;
                                                            case 'Enviada': echo 'bg-success'; break;
                                                            case 'Fallida': echo 'bg-danger'; break;
                                                            default: echo 'bg-secondary';
                                                        }
                                                    ?>">
                                                        <?php echo $row['estado']; ?>
                                                    </span>
                                                </td>
                                                <td><?php echo date('d/m/Y H:i', strtotime($row['fecha_creacion'])); ?></td>
                                                <td><?php echo $row['fecha_envio'] ? date('d/m/Y H:i', strtotime($row['fecha_envio'])) : '-'; ?></td>
                                                <td>
                                                    <div class="btn-group">
                                                        <button class="btn btn-sm btn-info view-campaign" data-id="<?php echo $row['id']; ?>">
                                                            <i class="mdi mdi-eye"></i>
                                                        </button>
                                                        <?php if ($row['estado'] == 'Borrador'): ?>
                                                        <button class="btn btn-sm btn-primary edit-campaign" data-id="<?php echo $row['id']; ?>">
                                                            <i class="mdi mdi-pencil"></i>
                                                        </button>
                                                        <button class="btn btn-sm btn-success send-campaign" data-id="<?php echo $row['id']; ?>">
                                                            <i class="mdi mdi-send"></i>
                                                        </button>
                                                        <button class="btn btn-sm btn-danger delete-campaign" data-id="<?php echo $row['id']; ?>">
                                                            <i class="mdi mdi-delete"></i>
                                                        </button>
                                                        <?php endif; ?>
                                                    </div>
                                                </td>
                                            </tr>
                                            <?php endwhile; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Footer -->
            <footer class="footer">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-6">
                            <script>document.write(new Date().getFullYear())</script> © CRM System
                        </div>
                        <div class="col-md-6">
                            <div class="text-md-end footer-links d-none d-md-block">
                                <a href="javascript: void(0);">About</a>
                                <a href="javascript: void(0);">Support</a>
                                <a href="javascript: void(0);">Contact Us</a>
                            </div>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>
    
    <!-- Modals -->
    <!-- View Campaign Modal -->
    <div class="modal fade" id="viewCampaignModal" tabindex="-1" aria-labelledby="viewCampaignModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewCampaignModalLabel">Detalles de la Campaña</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h6>Nombre:</h6>
                    <p id="view-nombre"></p>
                    <h6>Asunto:</h6>
                    <p id="view-asunto"></p>
                    <h6>Mensaje:</h6>
                    <div id="view-mensaje" class="border p-3 rounded"></div>
                    <h6>Grupo:</h6>
                    <p id="view-tipo_grupo"></p>
                    <h6>Destinatarios:</h6>
                    <ul id="view-destinatarios"></ul>
                    <h6>Estado:</h6>
                    <p id="view-estado"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Edit Campaign Modal -->
    <div class="modal fade" id="editCampaignModal" tabindex="-1" aria-labelledby="editCampaignModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form id="editCampaignForm" method="POST" action="campañas.php">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editCampaignModalLabel">Editar Campaña</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="action" value="editar">
                        <input type="hidden" name="id" id="edit-id">
                        <div class="mb-3">
                            <label for="edit-nombre" class="form-label">Nombre</label>
                            <input type="text" class="form-control" id="edit-nombre" name="nombre" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit-asunto" class="form-label">Asunto</label>
                            <input type="text" class="form-control" id="edit-asunto" name="asunto" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit-mensaje" class="form-label">Mensaje</label>
                            <textarea class="form-control" id="edit-mensaje" name="mensaje" rows="5" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="edit-tipo_grupo" class="form-label">Grupo</label>
                            <select class="form-select" id="edit-tipo_grupo" name="tipo_grupo">
                                <?php
                                $sql_grupos = "SELECT * FROM grupos_correo";
                                $result_grupos = $conn->query($sql_grupos);
                                while ($grupo = $result_grupos->fetch_assoc()):
                                ?>
                                <option value="<?php echo $grupo['nombre_grupo']; ?>"><?php echo $grupo['nombre_grupo']; ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="edit-destinatarios" class="form-label">Destinatarios</label>
                            <select class="form-select" id="edit-destinatarios" name="destinatarios[]" multiple>
                                <?php
                                $sql_clientes = "SELECT cod_cliente, nombre_cliente, correo FROM todos_los_clientes WHERE correo IS NOT NULL";
                                $result_clientes = $conn->query($sql_clientes);
                                while ($cliente = $result_clientes->fetch_assoc()):
                                ?>
                                <option value="<?php echo $cliente['cod_cliente']; ?>">
                                    <?php echo $cliente['nombre_cliente'] . ' (' . $cliente['correo'] . ')'; ?>
                                </option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Scripts -->
    <?php foreach ($scripts_adicionales as $script): ?>
        <script src="<?php echo $script; ?>"></script>
    <?php endforeach; ?>
    <script src="../assets/js/app.min.js"></script>
    
    <script>
        $(document).ready(function() {
            // Initialize DataTable
            var table = $('#campanasTable').DataTable({
                responsive: true,
                dom: 'Bfrtip',
                buttons: [
                    {
                        extend: 'pdf',
                        text: 'Exportar PDF',
                        title: 'Reporte de Campañas'
                    },
                    {
                        extend: 'print',
                        text: 'Imprimir',
                        title: 'Reporte de Campañas'
                    }
                ],
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.10.24/i18n/Spanish.json'
                }
            });
            
            // Check for success parameter to reload table
            if (new URLSearchParams(window.location.search).get('success') == '1') {
                table.ajax.reload();
                window.history.replaceState({}, document.title, window.location.pathname);
            }
            
            // View Campaign
            $('.view-campaign').click(function() {
                var id = $(this).data('id');
                $.ajax({
                    url: 'get_campaign.php',
                    method: 'GET',
                    data: { id: id },
                    dataType: 'json',
                    success: function(data) {
                        if (data.error) {
                            alert('Error: ' + data.error);
                            return;
                        }
                        $('#view-nombre').text(data.nombre || 'N/A');
                        $('#view-asunto').text(data.asunto || 'N/A');
                        $('#view-mensaje').html(data.mensaje || 'N/A');
                        $('#view-tipo_grupo').text(data.tipo_grupo || 'N/A');
                        $('#view-estado').text(data.estado || 'N/A');
                        
                        $('#view-destinatarios').empty();
                        $.ajax({
                            url: 'get_destinatarios.php',
                            method: 'GET',
                            data: { campaña_id: id },
                            dataType: 'json',
                            success: function(destinatarios) {
                                if (destinatarios.error) {
                                    $('#view-destinatarios').append('<li>Error al cargar destinatarios</li>');
                                    return;
                                }
                                if (destinatarios.length === 0) {
                                    $('#view-destinatarios').append('<li>No hay destinatarios</li>');
                                } else {
                                    destinatarios.forEach(function(dest) {
                                        $('#view-destinatarios').append('<li>' + (dest.email || 'N/A') + ' (' + (dest.estado || 'N/A') + ')</li>');
                                    });
                                }
                                $('#viewCampaignModal').modal('show');
                            },
                            error: function() {
                                $('#view-destinatarios').append('<li>Error al cargar destinatarios</li>');
                                $('#viewCampaignModal').modal('show');
                            }
                        });
                    },
                    error: function() {
                        alert('Error al cargar los detalles de la campaña');
                    }
                });
            });
            
            // Edit Campaign
            $('.edit-campaign').click(function() {
                var id = $(this).data('id');
                $.ajax({
                    url: 'get_campaign.php',
                    method: 'GET',
                    data: { id: id },
                    dataType: 'json',
                    success: function(data) {
                        if (data.error) {
                            alert('Error: ' + data.error);
                            return;
                        }
                        $('#edit-id').val(data.id);
                        $('#edit-nombre').val(data.nombre);
                        $('#edit-asunto').val(data.asunto);
                        $('#edit-mensaje').val(data.mensaje);
                        $('#edit-tipo_grupo').val(data.tipo_grupo);
                        
                        $.ajax({
                            url: 'get_destinatarios.php',
                            method: 'GET',
                            data: { campaña_id: id },
                            dataType: 'json',
                            success: function(destinatarios) {
                                if (!destinatarios.error) {
                                    $('#edit-destinatarios').val(destinatarios.map(d => d.cliente_id)).trigger('change');
                                }
                                $('#edit-destinatarios').select2();
                                $('#editCampaignModal').modal('show');
                            },
                            error: function() {
                                $('#edit-destinatarios').select2();
                                $('#editCampaignModal').modal('show');
                            }
                        });
                    },
                    error: function() {
                        alert('Error al cargar los datos para edición');
                    }
                });
            });
            
            // Delete Campaign
            $('.delete-campaign').click(function() {
                if (confirm('¿Está seguro de eliminar esta campaña?')) {
                    var id = $(this).data('id');
                    $.ajax({
                        url: 'campañas.php',
                        method: 'POST',
                        data: { action: 'eliminar', id: id },
                        success: function() {
                            table.ajax.reload();
                        },
                        error: function() {
                            alert('Error al eliminar la campaña');
                        }
                    });
                }
            });
            
            // Send Campaign
            $('.send-campaign').click(function() {
                if (confirm('¿Está seguro de enviar esta campaña?')) {
                    var id = $(this).data('id');
                    $.ajax({
                        url: 'campañas.php',
                        method: 'POST',
                        data: { action: 'enviar', id: id },
                        success: function() {
                            table.ajax.reload();
                        },
                        error: function() {
                            alert('Error al enviar la campaña');
                        }
                    });
                }
            });
            
            // Initialize Select2
            $('#edit-destinatarios').select2();
        });
    </script>
</body>
</html>