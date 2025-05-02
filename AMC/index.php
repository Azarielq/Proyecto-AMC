<?php
// Incluye la conexión a la base de datos
require 'db_connect.php';

// Verifica si se enviaron los datos del formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recibe el email y la contraseña del formulario
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Prepara la consulta para verificar las credenciales
    $sql = "SELECT * FROM usuarios WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Si el usuario existe, obtenemos los datos
        $user = $result->fetch_assoc();

        // Verifica si la contraseña ingresada es correcta
        if (password_verify($password, $user['password'])) {
            // Si la contraseña es correcta, redirige a index.php
            header("Location: index.php");
            exit();
        } else {
            // Si la contraseña es incorrecta
            echo "Email o contraseña incorrectos.";
        }
    } else {
        // Si el email no existe
        echo "Email o contraseña incorrectos.";
    }

    // Cierra la conexión
    $stmt->close();
    $conn->close();
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
        <link rel="shortcut icon" href="assets/images/favicon.ico">

        <!-- third party css -->
        <link href="assets/css/vendor/jquery-jvectormap-1.2.2.css" rel="stylesheet" type="text/css" />
        <!-- third party css end -->
<!-- En el head de tu HTML -->
        <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
        <script src="https://cdn.jsdelivr.net/npm/jquery/dist/jquery.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/moment/min/moment.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
        <!-- App css -->
        <link href="assets/css/icons.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/css/app.min.css" rel="stylesheet" type="text/css" id="app-style"/>

    </head>
    <body class="loading" data-layout-color="light" data-leftbar-theme="dark" data-layout-mode="fluid" data-rightbar-onstart="true">
        <div class="wrapper">
            <!-- ========== Left Sidebar Start ========== -->
            <div class="leftside-menu">
                <!-- Logo principal con superposición -->
                <a href="index.php" class="logo text-center logo-light transition-all relative inline-block"> <!-- Contenedor relativo -->
                    <!-- Logo grande -->
                    <span</span>
                    <span class="logo-lg relative inline-block">
                        <img 
                            src="assets/images/logo.png" 
                            alt="Company Logo" 
                            height="80" 
                            class="hover:scale-105 transition-transform relative z-0"
                        >
                        <!-- Badge superpuesto ENCIMA del logo -->

                    </span>
                    <!-- Logo pequeño (sin superposición) -->
                    <span class="logo-sm">
                        <img src="assets/images/logo_sm.png" alt="Company Logo" height="41" class="hover:scale-105 transition-transform">
                    </span>
                </a>

    
                <div class="h-100" id="leftside-menu-container" data-simplebar>

                    <!--- Sidemenu -->

                    <ul class="side-nav">
                        <li class="side-nav-title side-nav-item text-uppercase font-weight-bold text-muted"></li>
                        <!-- Main Menu Section -->
                        <li class="side-nav-title side-nav-item text-uppercase font-weight-bold text-muted">Navegación</li>

                        <!-- Dashboard Item with active state -->
                        <li class="side-nav-item">
                            <a href="index.php" class="side-nav-link active">
                                <i class="uil-home-alt"></i>
                                <span> Inicio </span>
                                <span class="badge bg-primary rounded-pill float-end">New</span>
                            </a>
                        </li>

                        <!-- Email Section with dropdown -->
                        <li class="side-nav-item">
                            <a data-bs-toggle="collapse" href="#sidebarEmail" aria-expanded="false" aria-controls="sidebarEmail" class="side-nav-link collapsed">
                                <i class="uil-envelope"></i>
                                <span> Correo </span>
                                <span class="menu-arrow"></span>
                                <span class="badge bg-danger rounded-pill float-end">3</span>
                            </a>
                            <div class="collapse" id="sidebarEmail">
                                <ul class="side-nav-second-level">
                                    <li>
                                        <a href="apps-email-inbox.html" class="flex items-center">
                                            <i class="uil-inbox mr-2"></i> Bandeja de entrada
                                        </a>
                                    </li>
                                    <li>
                                        <a href="apps-email-read.html" class="flex items-center">
                                            <i class="uil-envelope-open mr-2"></i> Leer correo
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#" class="flex items-center">
                                            <i class="uil-paper-plane mr-2"></i> Enviados
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>

                        <!-- Tables Section -->
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

                        <!-- Additional Menu Items -->
                        <li class="side-nav-title side-nav-item text-uppercase font-weight-bold text-muted">Herramientas</li>

                        <!-- Calendar Item -->
                        <li class="side-nav-item">
                            <a href="#" class="side-nav-link">
                                <i class="uil-calendar-alt"></i>
                                <span> Calendario </span>
                            </a>
                        </li>

                        <!-- Settings Item with dropdown -->
                        <li class="side-nav-item">
                            <a data-bs-toggle="collapse" href="#sidebarSettings" aria-expanded="false" aria-controls="sidebarSettings" class="side-nav-link collapsed">
                                <i class="uil-cog"></i>
                                <span> Configuración </span>
                                <span class="menu-arrow"></span>
                            </a>
                            <div class="collapse" id="sidebarSettings">
                                <ul class="side-nav-second-level">
                                    <li>
                                        <a href="#" class="flex items-center">
                                            <i class="uil-user-circle mr-2"></i> Perfil
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#" class="flex items-center">
                                            <i class="uil-shield-exclamation mr-2"></i> Seguridad
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#" class="flex items-center">
                                            <i class="uil-bell mr-2"></i> Notificaciones
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>

                        <!-- Divider -->
                        <li class="side-nav-divider mt-4 mb-2"></li>


                    </ul>

                    <!-- User Profile Card at Bottom -->
                    <div class="leftbar-user-card p-3 text-center position-absolute bottom-0 start-0 end-0">
                        <img src="assets/images/users/avatar-1.jpg" alt="user-image" class="rounded-circle shadow-sm img-thumbnail mb-2" width="80">
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
                                    <i class="dripicons-bell noti-icon"></i>
                                    <span class="noti-icon-badge"></span>
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
                                                            <img src="assets/images/users/avatar-2.jpg" class="img-fluid rounded-circle" alt="" />
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
                                                            <img src="assets/images/users/avatar-4.jpg" class="img-fluid rounded-circle" alt="" />
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
                                        <img src="assets/images/users/avatar-1.jpg" alt="user-image" class="rounded-circle">
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
¿
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
                                    <a href="pages-logout.php" class="dropdown-item notify-item">
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
                                            <img class="d-flex me-2 rounded-circle" src="assets/images/users/avatar-2.jpg" alt="Generic placeholder image" height="32">
                                            <div class="w-100">
                                                <h5 class="m-0 font-14">Erwin Brown</h5>
                                                <span class="font-12 mb-0">UI Designer</span>
                                            </div>
                                        </div>
                                    </a>

                                    <!-- item-->
                                    <a href="javascript:void(0);" class="dropdown-item notify-item">
                                        <div class="d-flex">
                                            <img class="d-flex me-2 rounded-circle" src="assets/images/users/avatar-5.jpg" alt="Generic placeholder image" height="32">
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
<div class="row mb-3">
    <div class="col-12">
        <div class="page-title-box align-items-center">
            <h4 class="page-title mb-md-0">Panel de Control</h4>
            
            <!-- Filtro para desktop (se muestra al lado del título) -->
            <div class="page-title-right d-none d-md-flex align-items-center ms-3">
                <div class="input-group" style="width: 220px;">
                    <input type="text" class="form-control form-control-sm" id="dash-daterange" placeholder="Rango de fechas">
                    <span class="input-group-text bg-primary text-white">
                        <i class="mdi mdi-calendar-range"></i>
                    </span>
                </div>
                <div class="d-flex ms-2" style="gap: 8px;">
                    <button id="btn-filtrar" class="btn btn-sm btn-primary px-2">
                        <i class="mdi mdi-filter-outline"></i>
                        <span>Filtrar</span>
                    </button>
                    <button id="btn-reset" class="btn btn-sm btn-outline-secondary px-2">
                        <i class="mdi mdi-refresh"></i>
                        <span>Reiniciar</span>
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Filtro para móvil (se muestra como tarjeta debajo del título) -->
        <div class="row d-md-none mt-2">
            <div class="col-12">
                <div class="card widget-flat" style="padding: 0.5rem;">
                    <div class="card-body p-2">
                        <h5 class="text-muted fw-normal mt-0 mb-1" style="font-size: 0.8rem;">Filtrar por fecha</h5>
                        <div class="input-group mb-2">
                            <input type="text" class="form-control form-control-sm" id="mobile-daterange" placeholder="Rango de fechas">
                            <span class="input-group-text bg-primary text-white">
                                <i class="mdi mdi-calendar-range"></i>
                            </span>
                        </div>
                        <div class="d-flex" style="gap: 8px;">
                            <button id="mobile-btn-filtrar" class="btn btn-sm btn-primary flex-grow-1">
                                <i class="mdi mdi-filter-outline"></i> Aplicar
                            </button>
                            <button id="mobile-btn-reset" class="btn btn-sm btn-outline-secondary">
                                <i class="mdi mdi-refresh"></i> Limpiar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- end page title -->
                        <!-- end page title -->


<!-- end page title --> <!-- end page title --><!-- end page title --><!-- end page title --><!-- end page title --><!-- end page title --><!-- end page title -->
<div class="row">
    <!-- Primera fila con 4 columnas principales -->
    <div class="col-md-3">
        <div class="card widget-flat gradient-1" style="padding: 0.5rem; border-radius: 12px; border: none; box-shadow: 0 4px 8px rgba(0,0,0,0.1);">
            <div class="card-body p-2">
                <div class="float-end">
                    <i class="mdi mdi-handshake widget-icon" style="font-size: 1.8rem; color: rgba(255,255,255,0.7);"></i>
                </div>
                <h5 class="text-white fw-normal mt-0 mb-1" style="font-size: 0.8rem; opacity: 0.9;">Empresas Concretadas</h5>
                <h3 class="mt-1 mb-1 text-white" style="font-size: 1.4rem; font-weight: 600;" id="empresas-concretadas">0</h3>
                <p class="mb-0 text-white" style="font-size: 0.7rem; opacity: 0.8;">
                    <span class="text-white me-1">
                        <i class="mdi mdi-arrow-up-bold"></i>
                        <span id="conversion-value">0.00%</span>
                    </span>
                    <span class="text-nowrap">Este mes</span>
                </p>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card widget-flat gradient-2" style="padding: 0.5rem; border-radius: 12px; border: none; box-shadow: 0 4px 8px rgba(0,0,0,0.1);">
            <div class="card-body p-2">
                <div class="float-end">
                    <i class="mdi mdi-alert-octagon widget-icon" style="font-size: 1.8rem; color: rgba(255,255,255,0.7);"></i>
                </div>
                <h5 class="text-white fw-normal mt-0 mb-1" style="font-size: 0.8rem; opacity: 0.9;">No Concretadas</h5>
                <h3 class="mt-1 mb-1 text-white" style="font-size: 1.4rem; font-weight: 600;" id="empresas-no-concretadas">0</h3>
                <p class="mb-0 text-white" style="font-size: 0.7rem; opacity: 0.8;">
                    <span class="text-white me-1"><i class="mdi mdi-arrow-down-bold"></i> <span id="no-concretadas-trend">0.00%</span></span>
                    <span class="text-nowrap">Desde mes anterior</span>
                </p>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card widget-flat gradient-3" style="padding: 0.5rem; border-radius: 12px; border: none; box-shadow: 0 4px 8px rgba(0,0,0,0.1);">
            <div class="card-body p-2">
                <div class="float-end">
                    <i class="mdi mdi-account-group widget-icon" style="font-size: 1.8rem; color: rgba(255,255,255,0.7);"></i>
                </div>
                <h5 class="text-white fw-normal mt-0 mb-1" style="font-size: 0.8rem; opacity: 0.9;">Pacientes Totales</h5>
                <h3 class="mt-1 mb-1 text-white" style="font-size: 1.4rem; font-weight: 600;" id="total-pacientes">0</h3>
                <p class="mb-0 text-white" style="font-size: 0.7rem; opacity: 0.8;">
                    <span class="text-white me-1"><i class="mdi mdi-arrow-up-bold"></i> <span id="pacientes-trend">0.00%</span></span>
                    <span class="text-nowrap">Desde mes anterior</span>
                </p>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card widget-flat gradient-4" style="padding: 0.5rem; border-radius: 12px; border: none; box-shadow: 0 4px 8px rgba(0,0,0,0.1);">
            <div class="card-body p-2">
                <div class="float-end">
                    <i class="mdi mdi-timer-sand widget-icon" style="font-size: 1.8rem; color: rgba(255,255,255,0.7);"></i>
                </div>
                <h5 class="text-white fw-normal mt-0 mb-1" style="font-size: 0.8rem; opacity: 0.9;">Ciclo Promedio</h5>
                <h3 class="mt-1 mb-1 text-white" style="font-size: 1.4rem; font-weight: 600;" id="ciclo-promedio">0 días</h3>
                <p class="mb-0 text-white" style="font-size: 0.7rem; opacity: 0.8;">
                    <span class="text-white me-1"><i class="mdi mdi-arrow-up-bold"></i> <span id="ciclo-trend">0.00%</span></span>
                    <span class="text-nowrap">Desde mes anterior</span>
                </p>
            </div>
        </div>
    </div>
</div>

<div class="row mt-3">
    <!-- Segunda fila con métricas adicionales -->
    <div class="col-md-3">
        <div class="card widget-flat gradient-5" style="padding: 0.5rem; border-radius: 12px; border: none; box-shadow: 0 4px 8px rgba(0,0,0,0.1);">
            <div class="card-body p-2">
                <div class="float-end">
                    <i class="mdi mdi-chart-line widget-icon" style="font-size: 1.8rem; color: rgba(255,255,255,0.7);"></i>
                </div>
                <h5 class="text-white fw-normal mt-0 mb-1" style="font-size: 0.8rem; opacity: 0.9;">Tasa de Conversión</h5>
                <h3 class="mt-1 mb-1 text-white" style="font-size: 1.4rem; font-weight: 600;" id="tasa-conversion">0%</h3>
                <p class="mb-0 text-white" style="font-size: 0.7rem; opacity: 0.8;">
                    <span class="text-white me-1"><i class="mdi mdi-arrow-up-bold"></i> <span id="conversion-trend">0.00%</span></span>
                    <span class="text-nowrap">Desde mes anterior</span>
                </p>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card widget-flat gradient-6" style="padding: 0.5rem; border-radius: 12px; border: none; box-shadow: 0 4px 8px rgba(0,0,0,0.1);">
            <div class="card-body p-2">
                <div class="float-end">
                    <i class="mdi mdi-account-supervisor widget-icon" style="font-size: 1.8rem; color: rgba(255,255,255,0.7);"></i>
                </div>
                <h5 class="text-white fw-normal mt-0 mb-1" style="font-size: 0.8rem; opacity: 0.9;">Contactos Activos</h5>
                <h3 class="mt-1 mb-1 text-white" style="font-size: 1.4rem; font-weight: 600;" id="contactos-activos">0</h3>
                <p class="mb-0 text-white" style="font-size: 0.7rem; opacity: 0.8;">
                    <span class="text-white me-1"><i class="mdi mdi-arrow-up-bold"></i> <span id="contactos-trend">0.00%</span></span>
                    <span class="text-nowrap">Desde mes anterior</span>
                </p>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card widget-flat gradient-7" style="padding: 0.5rem; border-radius: 12px; border: none; box-shadow: 0 4px 8px rgba(0,0,0,0.1);">
            <div class="card-body p-2">
                <div class="float-end">
                    <i class="mdi mdi-office-building widget-icon" style="font-size: 1.8rem; color: rgba(255,255,255,0.7);"></i>
                </div>
                <h5 class="text-white fw-normal mt-0 mb-1" style="font-size: 0.8rem; opacity: 0.9;">Empresas Medianas</h5>
                <h3 class="mt-1 mb-1 text-white" style="font-size: 1.4rem; font-weight: 600;" id="empresas-medianas">0</h3>
                <p class="mb-0 text-white" style="font-size: 0.7rem; opacity: 0.8;">
                    <span class="text-white me-1"><i class="mdi mdi-arrow-up-bold"></i> <span id="medianas-trend">0.00%</span></span>
                    <span class="text-nowrap">Desde mes anterior</span>
                </p>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card widget-flat gradient-8" style="padding: 0.5rem; border-radius: 12px; border: none; box-shadow: 0 4px 8px rgba(0,0,0,0.1);">
            <div class="card-body p-2">
                <div class="float-end">
                    <i class="mdi mdi-test-tube widget-icon" style="font-size: 1.8rem; color: rgba(255,255,255,0.7);"></i>
                </div>
                <h5 class="text-white fw-normal mt-0 mb-1" style="font-size: 0.8rem; opacity: 0.9;">Examen Más Solicitado</h5>
                <h3 class="mt-1 mb-1 text-white" style="font-size: 1.4rem; font-weight: 600;" id="examen-top">-</h3>
                <p class="mb-0 text-white" style="font-size: 0.7rem; opacity: 0.8;">
                    <span class="text-nowrap">Este mes</span>
                </p>
            </div>
        </div>
    </div>
</div>

<style>
    /* Gradientes oscuros y profesionales con base en los colores del logo */
    .gradient-1 {
        background: linear-gradient(135deg, #0a3d62 0%, #0a58a4 100%);
    }
    .gradient-2 {
        background: linear-gradient(135deg, #1e2a38 0%, #35424a 100%);
    }
    .gradient-3 {
        background: linear-gradient(135deg, #2c3e50 0%, #4b6584 100%);
    }
    .gradient-4 {
        background: linear-gradient(135deg, #1c2833 0%, #2e4053 100%);
    }
    .gradient-5 {
        background: linear-gradient(135deg, #34495e 0%, #5d6d7e 100%);
    }
    .gradient-6 {
        background: linear-gradient(135deg, #22313f 0%, #3a539b 100%);
    }
    .gradient-7 {
        background: linear-gradient(135deg, #1b2631 0%, #2c3e50 100%);
    }
    .gradient-8 {
        background: linear-gradient(135deg, #0a3d62 0%, #269fae 100%);
    }


    
    /* Transición suave al pasar el mouse */
    .card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
    }
    
    /* Mejor espaciado entre tarjetas */
    .row {
        margin-left: -8px;
        margin-right: -8px;
    }
    .col-md-3 {
        padding-left: 8px;
        padding-right: 8px;
        margin-bottom: 16px;
    }
</style>

<!-- JavaScript para cargar los datos (se mantiene igual) -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Función para cargar todos los datos
    function loadDashboardData() {
        fetch('get_dashboard_data.php')
            .then(response => response.json())
            .then(data => {
                // Actualizar los valores en los cuadros
                document.getElementById('empresas-concretadas').textContent = data.empresas_concretadas;
                document.getElementById('conversion-value').textContent = data.tasa_conversion + '%';
                document.getElementById('empresas-no-concretadas').textContent = data.empresas_no_concretadas;
                document.getElementById('no-concretadas-trend').textContent = data.trend_no_concretadas + '%';
                document.getElementById('total-pacientes').textContent = data.total_pacientes;
                document.getElementById('pacientes-trend').textContent = data.trend_pacientes + '%';
                document.getElementById('ciclo-promedio').textContent = data.ciclo_promedio + ' días';
                document.getElementById('ciclo-trend').textContent = data.trend_ciclo + '%';
                document.getElementById('tasa-conversion').textContent = data.tasa_conversion + '%';
                document.getElementById('conversion-trend').textContent = data.trend_conversion + '%';
                document.getElementById('contactos-activos').textContent = data.contactos_activos;
                document.getElementById('contactos-trend').textContent = data.trend_contactos + '%';
                document.getElementById('empresas-medianas').textContent = data.empresas_medianas;
                document.getElementById('medianas-trend').textContent = data.trend_medianas + '%';
                document.getElementById('examen-top').textContent = data.examen_top;
            })
            .catch(error => {
                console.error('Error:', error);
            });
    }

    // Cargar datos al inicio y cada 5 minutos
    loadDashboardData();
    setInterval(loadDashboardData, 300000);
});
</script>
    <!-- item-->    <!-- item-->    <!-- item-->    <!-- item-->    <!-- item-->    <!-- item-->    <!-- item-->
 <!-- Reemplaza todo el contenido desde <div class="row"> hasta </div> (los gráficos actuales) con esto: -->

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h4 class="header-title">Empresas Concretadas por Mes</h4>
                    <div class="dropdown">
                        <a href="#" class="dropdown-toggle arrow-none card-drop" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="mdi mdi-dots-vertical"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end">
                            <a href="javascript:void(0);" class="dropdown-item">Exportar Datos</a>
                            <a href="javascript:void(0);" class="dropdown-item">Ver Detalles</a>
                        </div>
                    </div>
                </div>
                
                <div class="chart-content-bg">
                    <div class="row text-center">
                        <div class="col-sm-6">
                            <p class="text-muted mb-0 mt-3">Total Empresas</p>
                            <h2 class="fw-normal mb-3">
                                <span id="total-empresas-grafico">0</span>
                            </h2>
                        </div>
                        <div class="col-sm-6">
                            <p class="text-muted mb-0 mt-3">Tasa de Crecimiento</p>
                            <h2 class="fw-normal mb-3">
                                <span id="tasa-crecimiento-grafico">0%</span>
                            </h2>
                        </div>
                    </div>
                </div>
                
                <div dir="ltr">
                    <div id="empresas-mes-chart" class="apex-charts mt-3" data-colors="#727cf5,#0acf97"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="header-title">Distribución por Tamaño</h4>
                    <div class="dropdown">
                        <a href="#" class="dropdown-toggle arrow-none card-drop" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="mdi mdi-dots-vertical"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end">
                            <a href="javascript:void(0);" class="dropdown-item">Exportar Datos</a>
                        </div>
                    </div>
                </div>
                
                <div id="distribucion-tamanio-chart" class="apex-charts mt-3" data-colors="#727cf5,#0acf97,#fa5c7c,#ffbc00"></div>
                
                <div class="chart-widget-list">
                    <p>
                        <i class="mdi mdi-square text-primary"></i> Grandes
                        <span class="float-end" id="porcentaje-grandes">0%</span>
                    </p>
                    <p>
                        <i class="mdi mdi-square text-danger"></i> Medianas
                        <span class="float-end" id="porcentaje-medianas">0%</span>
                    </p>
                    <p>
                        <i class="mdi mdi-square text-success"></i> Pequeñas
                        <span class="float-end" id="porcentaje-pequenas">0%</span>
                    </p>
                    <p class="mb-0">
                        <i class="mdi mdi-square text-warning"></i> Micro
                        <span class="float-end" id="porcentaje-micro">0%</span>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-3">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h4 class="header-title">Exámenes Más Solicitados</h4>
                    <div class="dropdown">
                        <a href="#" class="dropdown-toggle arrow-none card-drop" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="mdi mdi-dots-vertical"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end">
                            <a href="javascript:void(0);" class="dropdown-item">Exportar Datos</a>
                        </div>
                    </div>
                </div>
                
                <div id="examenes-chart" class="apex-charts" data-colors="#727cf5,#0acf97,#fa5c7c,#ffbc00,#5b69bc"></div>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h4 class="header-title">Evolución de Pacientes</h4>
                    <div class="dropdown">
                        <a href="#" class="dropdown-toggle arrow-none card-drop" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="mdi mdi-dots-vertical"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end">
                            <a href="javascript:void(0);" class="dropdown-item">Exportar Datos</a>
                        </div>
                    </div>
                </div>
                
                <div id="pacientes-chart" class="apex-charts" data-colors="#727cf5"></div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-3">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h4 class="header-title">Tasa de Conversión Mensual</h4>
                    <div class="dropdown">
                        <a href="#" class="dropdown-toggle arrow-none card-drop" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="mdi mdi-dots-vertical"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end">
                            <a href="javascript:void(0);" class="dropdown-item">Exportar Datos</a>
                        </div>
                    </div>
                </div>
                
                <div id="conversion-chart" class="apex-charts" data-colors="#0acf97"></div>
            </div>
        </div>
    </div>
</div>
                <!-- content -->

                <!-- Footer Start -->
                <footer class="footer">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-6">
                                <script>document.write(new Date().getFullYear())</script> © Hyper - Coderthemes.com
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
                <!-- end Footer -->

            </div>

            <!-- ============================================================== -->
            <!-- End Page content -->
            <!-- ============================================================== -->
            <style>
/* Ajustes específicos para el datepicker en móviles */
@media (max-width: 767.98px) {
    .daterangepicker {
        position: fixed !important;
        top: auto !important;
        bottom: 10px !important;
        left: 10px !important;
        right: 10px !important;
        width: auto !important;
        max-width: 100%;
        margin: 0;
    }
    
    .daterangepicker .calendar {
        max-width: 100%;
    }
    
    .daterangepicker:before,
    .daterangepicker:after {
        display: none !important;
    }
}                
     /* Estilos para el filtro responsivo */
.page-title-box {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    justify-content: space-between;
}

/* Asegurar que el datepicker sea visible en móviles */
.daterangepicker {
    max-width: 100% !important;
    width: auto !important;
}

/* Ajustes para pantallas pequeñas */
@media (max-width: 767.98px) {
    .page-title-box {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .page-title-right {
        width: 100%;
        margin-top: 0.5rem;
    }
    
    #mobile-daterange {
        font-size: 14px;
    }
    
    #mobile-btn-filtrar, #mobile-btn-reset {
        padding: 0.375rem 0.75rem;
        font-size: 14px;
    }
    
    /* Estilo para la tarjeta de filtro móvil */
    .widget-flat.filter-card {
        border: 1px solid rgba(0,0,0,0.1);
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }
}

/* Estilo para el estado de carga */
.card.widget-flat.loading {
    position: relative;
    opacity: 0.7;
}

.card.widget-flat.loading::after {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(255,255,255,0.7) url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" preserveAspectRatio="xMidYMid"><circle cx="50" cy="50" fill="none" stroke="%23727cf5" stroke-width="10" r="35" stroke-dasharray="164.93361431346415 56.97787143782138"><animateTransform attributeName="transform" type="rotate" repeatCount="indefinite" dur="1s" values="0 50 50;360 50 50" keyTimes="0;1"/></circle></svg>') no-repeat center;
    background-size: 50px 50px;
    z-index: 1;
}           
.card.widget-flat.loading {
    position: relative;
    opacity: 0.7;
}
.card.widget-flat.loading::after {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(255,255,255,0.7) url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" preserveAspectRatio="xMidYMid"><circle cx="50" cy="50" fill="none" stroke="%23727cf5" stroke-width="10" r="35" stroke-dasharray="164.93361431346415 56.97787143782138"><animateTransform attributeName="transform" type="rotate" repeatCount="indefinite" dur="1s" values="0 50 50;360 50 50" keyTimes="0;1"/></circle></svg>') no-repeat center;
    background-size: 50px 50px;
}
</style>

        </div>
        <!-- END wrapper -->

        <div class="rightbar-overlay"></div>
        <!-- /End-bar -->

        <!-- bundle -->
        <script src="assets/js/vendor.min.js"></script>
        <script src="assets/js/app.min.js"></script>

        <!-- third party js -->
        <script src="assets/js/vendor/apexcharts.min.js"></script>
        <script src="assets/js/vendor/jquery-jvectormap-1.2.2.min.js"></script>
        <script src="assets/js/vendor/jquery-jvectormap-world-mill-en.js"></script>
        <!-- third party js ends -->

        <!-- demo app -->
        <script src="assets/js/pages/demo.dashboard.js"></script>
        <!-- end demo js-->
    </body>
    <script>
document.addEventListener('DOMContentLoaded', function() {
    // Función para cargar datos
    function loadSalesData() {
        // Cargar porcentaje
        fetch('./get_sales_percentage.php')
            .then(response => {
                if(!response.ok) throw new Error('Error en la respuesta');
                return response.text();
            })
            .then(data => {
                document.getElementById('percentage-value').textContent = data + '%';
            })
            .catch(error => {
                console.error('Error:', error);
                document.getElementById('percentage-value').textContent = '0.00%';
            });

        // Cargar total
        fetch('./get_total_sales.php')
            .then(response => {
                if(!response.ok) throw new Error('Error en la respuesta');
                return response.text();
            })
            .then(data => {
                document.getElementById('ventas-total').textContent = data;
            })
            .catch(error => {
                console.error('Error:', error);
                document.getElementById('ventas-total').textContent = '0';
            });
    }

    // Cargar datos al inicio y cada 5 minutos
    loadSalesData();
    setInterval(loadSalesData, 300000);
});
</script>
<script>
$(document).ready(function() {
    // Inicializa el date range picker con configuración en español
    $('#dash-daterange').daterangepicker({
        locale: {
            format: 'YYYY-MM-DD',
            applyLabel: 'Aplicar',
            cancelLabel: 'Cancelar',
            fromLabel: 'Desde',
            toLabel: 'Hasta',
            customRangeLabel: 'Personalizado',
            daysOfWeek: ['Dom', 'Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb'],
            monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 
                        'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
            firstDay: 1
        },
        opens: 'left',
        autoUpdateInput: true,
        startDate: moment().startOf('month'),
        endDate: moment().endOf('month'),
        ranges: {
           'Hoy': [moment(), moment()],
           'Ayer': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
           'Últimos 7 días': [moment().subtract(6, 'days'), moment()],
           'Últimos 30 días': [moment().subtract(29, 'days'), moment()],
           'Este mes': [moment().startOf('month'), moment().endOf('month')],
           'Mes pasado': [moment().subtract(1, 'month').startOf('month'), 
                          moment().subtract(1, 'month').endOf('month')]
        }
    });

    // Función para cargar datos con filtro
    function loadDashboardData(fechaInicio = null, fechaFin = null) {
        let url = 'get_dashboard_data.php';
        
        // Mostrar carga
        $('.card.widget-flat').addClass('loading');
        
        // Si hay fechas, añadirlas como parámetros
        if(fechaInicio && fechaFin) {
            url += `?fecha_inicio=${fechaInicio}&fecha_fin=${fechaFin}`;
        }

        fetch(url)
            .then(response => {
                if(!response.ok) throw new Error('Error en la respuesta');
                return response.json();
            })
            .then(data => {
                // Actualiza todos los recuadros con los datos filtrados
                $('#empresas-concretadas').text(data.empresas_concretadas);
                $('#conversion-value').text(data.tasa_conversion + '%');
                $('#empresas-no-concretadas').text(data.empresas_no_concretadas);
                $('#total-pacientes').text(data.total_pacientes);
                $('#tasa-conversion').text(data.tasa_conversion + '%');
                $('#contactos-activos').text(data.contactos_activos);
                $('#empresas-medianas').text(data.empresas_medianas);
                $('#examen-top').text(data.examen_top);
                
                // Actualizar tendencias (puedes implementar esto según tus necesidades)
                $('.text-success .me-1').each(function() {
                    $(this).text('+5.27%'); // Ejemplo, deberías calcular esto
                });
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al cargar los datos. Por favor intenta nuevamente.');
            })
            .finally(() => {
                $('.card.widget-flat').removeClass('loading');
            });
    }

    // Evento cuando se aplica un rango de fechas
    $('#dash-daterange').on('apply.daterangepicker', function(ev, picker) {
        loadDashboardData(picker.startDate.format('YYYY-MM-DD'), picker.endDate.format('YYYY-MM-DD'));
    });

    // Evento para el botón Filtrar
    $('#btn-filtrar').click(function() {
        const dateRange = $('#dash-daterange').val();
        if(dateRange) {
            const dates = dateRange.split(' - ');
            loadDashboardData(dates[0], dates[1]);
        }
    });

    // Evento para el botón Reiniciar
    $('#btn-reset').click(function() {
        $('#dash-daterange').val('');
        loadDashboardData(); // Carga datos sin filtro
    });

    // Carga inicial de datos (mes actual)
    loadDashboardData(
        moment().startOf('month').format('YYYY-MM-DD'),
        moment().endOf('month').format('YYYY-MM-DD')
    );
});
// Configuración para móviles
function setupMobileFilter() {
    const mobileFilterBtn = document.getElementById('mobile-filter-btn');
    const mobileFilterCard = document.getElementById('mobile-filter-card');
    
    if (mobileFilterBtn && mobileFilterCard) {
        // Toggle del panel de filtro
        mobileFilterBtn.addEventListener('click', function() {
            mobileFilterCard.classList.toggle('d-none');
        });
        
        // Inicializar datepicker para móvil
        $('#mobile-daterange').daterangepicker({
            locale: {
                format: 'YYYY-MM-DD',
                applyLabel: 'Aplicar',
                cancelLabel: 'Cancelar',
                daysOfWeek: ['Dom', 'Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb'],
                monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 
                            'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
                firstDay: 1
            },
            opens: 'right',
            autoUpdateInput: true,
            drops: 'up'
        });
        
        // Evento para el botón aplicar en móvil
        $('#mobile-btn-filtrar').click(function() {
            const dateRange = $('#mobile-daterange').val();
            if (dateRange) {
                const dates = dateRange.split(' - ');
                loadDashboardData(dates[0], dates[1]);
                mobileFilterCard.classList.add('d-none');
            }
        });
        
        // Evento para el botón limpiar en móvil
        $('#mobile-btn-reset').click(function() {
            $('#mobile-daterange').val('');
            loadDashboardData();
            mobileFilterCard.classList.add('d-none');
        });
    }
}

// Inicialización cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', function() {
    // ... (tu código existente) ...
    
    // Inicializar filtro móvil
    setupMobileFilter();
});
$(document).ready(function() {
    // Configuración común para ambos datepickers
    const dateRangeConfig = {
        locale: {
            format: 'YYYY-MM-DD',
            applyLabel: 'Aplicar',
            cancelLabel: 'Cancelar',
            fromLabel: 'Desde',
            toLabel: 'Hasta',
            customRangeLabel: 'Personalizado',
            daysOfWeek: ['Dom', 'Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb'],
            monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 
                        'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
            firstDay: 1
        },
        opens: 'left',
        autoUpdateInput: true,
        startDate: moment().startOf('month'),
        endDate: moment().endOf('month'),
        ranges: {
           'Hoy': [moment(), moment()],
           'Ayer': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
           'Últimos 7 días': [moment().subtract(6, 'days'), moment()],
           'Últimos 30 días': [moment().subtract(29, 'days'), moment()],
           'Este mes': [moment().startOf('month'), moment().endOf('month')],
           'Mes pasado': [moment().subtract(1, 'month').startOf('month'), 
                          moment().subtract(1, 'month').endOf('month')]
        }
    };

    // Inicializar datepicker para desktop
    $('#dash-daterange').daterangepicker(dateRangeConfig);

    // Inicializar datepicker para móvil
    $('#mobile-daterange').daterangepicker({
    ...dateRangeConfig,  // Mantén la configuración común
    drops: 'down',       // Fuerza a que se abra hacia abajo
    opens: 'center',     // Centra el datepicker
    parentEl: '#mobile-filter-card'  // Asegura que se posicione correctamente
});

    // Función para cargar datos con filtro
    function loadDashboardData(fechaInicio = null, fechaFin = null) {
        let url = 'get_dashboard_data.php';
        
        // Mostrar estado de carga
        $('.card.widget-flat').addClass('loading');
        
        // Si hay fechas, añadirlas como parámetros
        if(fechaInicio && fechaFin) {
            url += `?fecha_inicio=${fechaInicio}&fecha_fin=${fechaFin}`;
        }

        fetch(url)
            .then(response => {
                if(!response.ok) throw new Error('Error en la respuesta');
                return response.json();
            })
            .then(data => {
                // Actualiza todos los recuadros con los datos
                $('#empresas-concretadas').text(data.empresas_concretadas);
                $('#conversion-value').text(data.tasa_conversion + '%');
                $('#empresas-no-concretadas').text(data.empresas_no_concretadas);
                $('#no-concretadas-trend').text(data.trend_no_concretadas + '%');
                $('#total-pacientes').text(data.total_pacientes);
                $('#pacientes-trend').text(data.trend_pacientes + '%');
                $('#ciclo-promedio').text(data.ciclo_promedio + ' días');
                $('#ciclo-trend').text(data.trend_ciclo + '%');
                $('#tasa-conversion').text(data.tasa_conversion + '%');
                $('#conversion-trend').text(data.trend_conversion + '%');
                $('#contactos-activos').text(data.contactos_activos);
                $('#contactos-trend').text(data.trend_contactos + '%');
                $('#empresas-medianas').text(data.empresas_medianas);
                $('#medianas-trend').text(data.trend_medianas + '%');
                $('#examen-top').text(data.examen_top);
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al cargar los datos. Por favor intenta nuevamente.');
            })
            .finally(() => {
                $('.card.widget-flat').removeClass('loading');
            });
    }

    // Eventos para el filtro desktop
    $('#dash-daterange').on('apply.daterangepicker', function(ev, picker) {
        loadDashboardData(picker.startDate.format('YYYY-MM-DD'), picker.endDate.format('YYYY-MM-DD'));
    });

    $('#btn-filtrar').click(function() {
        const dateRange = $('#dash-daterange').val();
        if(dateRange) {
            const dates = dateRange.split(' - ');
            loadDashboardData(dates[0], dates[1]);
        }
    });

    $('#btn-reset').click(function() {
        $('#dash-daterange').val('');
        loadDashboardData();
    });

    // Eventos para el filtro móvil
    $('#mobile-daterange').on('apply.daterangepicker', function(ev, picker) {
        loadDashboardData(picker.startDate.format('YYYY-MM-DD'), picker.endDate.format('YYYY-MM-DD'));
    });

    $('#mobile-btn-filtrar').click(function() {
        const dateRange = $('#mobile-daterange').val();
        if(dateRange) {
            const dates = dateRange.split(' - ');
            loadDashboardData(dates[0], dates[1]);
        }
    });

    $('#mobile-btn-reset').click(function() {
        $('#mobile-daterange').val('');
        loadDashboardData();
    });

    // Carga inicial de datos (mes actual)
    loadDashboardData(
        moment().startOf('month').format('YYYY-MM-DD'),
        moment().endOf('month').format('YYYY-MM-DD')
    );
});
// Función para inicializar los gráficos con manejo de datos vacíos
function initCharts(data) {
    try {
        // Verificar si hay datos
        if (!data || Object.keys(data).length === 0) {
            throw new Error("No se recibieron datos para los gráficos");
        }

        // 1. Gráfico de empresas por mes (con manejo de array vacío)
        const mesesLabels = ["Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic"];
        const empresasMesData = data.empresasPorMes && data.empresasPorMes.length > 0 ? 
            data.empresasPorMes.map(item => item.total) : [0];
        
        const empresasMesLabels = data.empresasPorMes && data.empresasPorMes.length > 0 ?
            data.empresasPorMes.map(item => mesesLabels[item.mes - 1]) : ["Sin datos"];

        var empresasMesOptions = {
            chart: {
                height: 320,
                type: 'line',
                toolbar: { show: false }
            },
            series: [{
                name: "Empresas Concretadas",
                data: empresasMesData
            }],
            xaxis: {
                categories: empresasMesLabels
            },
            noData: {
                text: "No hay datos disponibles",
                align: 'center',
                verticalAlign: 'middle'
            }
        };
        
        var empresasMesChart = new ApexCharts(
            document.querySelector("#empresas-mes-chart"),
            empresasMesOptions
        );
        empresasMesChart.render();

        // 2. Gráfico de distribución por tamaño (con manejo de valores NULL)
        const tamaniosData = {
            'Grande': 0,
            'Mediana': 0,
            'Pequeña': 0,
            'Micro': 0,
            'No especificado': 0
        };
        
        if (data.distribucionTamanio && data.distribucionTamanio.length > 0) {
            data.distribucionTamanio.forEach(item => {
                const key = item.tamaño || 'No especificado';
                tamaniosData[key] = item.total;
            });
        }
        
        // Filtrar categorías con valor 0
        const distribucionSeries = [];
        const distribucionLabels = [];
        Object.entries(tamaniosData).forEach(([key, value]) => {
            if (value > 0) {
                distribucionSeries.push(value);
                distribucionLabels.push(key);
            }
        });

        // Si no hay datos, mostrar un valor cero
        if (distribucionSeries.length === 0) {
            distribucionSeries.push(1);
            distribucionLabels.push("Sin datos");
        }
        
        var distribucionTamanioOptions = {
            chart: {
                height: 250,
                type: 'donut',
            },
            series: distribucionSeries,
            labels: distribucionLabels,
            noData: {
                text: "No hay datos disponibles"
            }
        };
        
        var distribucionTamanioChart = new ApexCharts(
            document.querySelector("#distribucion-tamanio-chart"),
            distribucionTamanioOptions
        );
        distribucionTamanioChart.render();

        // Resto de gráficos con el mismo patrón de manejo de errores...
        // [Aquí irían los otros gráficos con el mismo enfoque]

        return {
            empresasMesChart,
            distribucionTamanioChart,
            // ... otros gráficos
        };

    } catch (error) {
        console.error("Error al inicializar gráficos:", error);
        // Mostrar mensaje de error en cada contenedor de gráfico
        document.querySelectorAll('.apex-charts').forEach(el => {
            el.innerHTML = `<div class="text-center p-4 text-danger">
                <i class="mdi mdi-alert-circle-outline h2"></i>
                <p>${error.message}</p>
            </div>`;
        });
        return null;
    }
}

// Función para cargar datos de gráficos con mejor manejo de errores
function loadChartData(fechaInicio = null, fechaFin = null) {
    let url = 'get_chart_data.php';
    
    // Mostrar estado de carga
    $('.apex-charts').parent().parent().addClass('loading');
    
    // Añadir parámetros de fecha si existen
    if (fechaInicio && fechaFin) {
        url += `?fecha_inicio=${encodeURIComponent(fechaInicio)}&fecha_fin=${encodeURIComponent(fechaFin)}`;
    }

    fetch(url)
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.status === 'error') {
                throw new Error(data.message);
            }
            
            // Destruir gráficos existentes
            if (window.charts) {
                Object.values(window.charts).forEach(chart => {
                    if (chart && typeof chart.destroy === 'function') {
                        chart.destroy();
                    }
                });
            }
            
            // Inicializar nuevos gráficos
            window.charts = initCharts(data.data || data);
        })
        .catch(error => {
            console.error('Error al cargar datos:', error);
            
            // Mostrar mensaje de error en cada contenedor
            document.querySelectorAll('.apex-charts').forEach(el => {
                el.innerHTML = `<div class="text-center p-4 text-danger">
                    <i class="mdi mdi-alert-circle-outline h2"></i>
                    <p>${error.message}</p>
                </div>`;
            });
        })
        .finally(() => {
            $('.apex-charts').parent().parent().removeClass('loading');
        });
}

// Carga inicial
document.addEventListener('DOMContentLoaded', function() {
    loadChartData(
        moment().startOf('month').format('YYYY-MM-DD'),
        moment().endOf('month').format('YYYY-MM-DD')
    );
});
// Función para cargar datos de gráficos
function loadChartData(fechaInicio = null, fechaFin = null) {
    let url = 'get_chart_data.php';
    
    // Mostrar estado de carga
    $('.apex-charts').parent().parent().addClass('loading');
    
    // Añadir parámetros de fecha si existen
    if (fechaInicio && fechaFin) {
        url += `?fecha_inicio=${fechaInicio}&fecha_fin=${fechaFin}`;
    }

    fetch(url)
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.error) {
                throw new Error(data.error);
            }
            
            // Actualizar gráficos con los nuevos datos
            updateCharts(data);
        })
        .catch(error => {
            console.error('Error al cargar gráficos:', error);
            showChartError(error.message);
        })
        .finally(() => {
            $('.apex-charts').parent().parent().removeClass('loading');
        });
}

// Función para actualizar todos los gráficos
function updateCharts(data) {
    updateEmpresasMesChart(data.empresasPorMes);
    updateDistribucionTamanioChart(data.distribucionTamanio);
    updateExamenesTopChart(data.examenesTop);
    updateEvolucionPacientesChart(data.evolucionPacientes);
    updateTasaConversionChart(data.tasaConversion);
}

// 1. Gráfico de empresas por mes
function updateEmpresasMesChart(data) {
    const meses = ["Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic"];
    
    const chartData = {
        series: [{
            name: "Empresas Concretadas",
            data: data.map(item => item.total)
        }],
        labels: data.map(item => `${meses[item.mes - 1]} ${item.anio}`)
    };
    
    if (window.empresasMesChart) {
        window.empresasMesChart.updateOptions({
            series: chartData.series,
            xaxis: { categories: chartData.labels }
        });
    } else {
        window.empresasMesChart = new ApexCharts(
            document.querySelector("#empresas-mes-chart"),
            {
                chart: { type: 'line', height: 350 },
                series: chartData.series,
                xaxis: { categories: chartData.labels },
                colors: ['#727cf5'],
                stroke: { width: 3, curve: 'smooth' },
                tooltip: { y: { formatter: val => `${val} empresas` } }
            }
        );
        window.empresasMesChart.render();
    }
}

// 2. Gráfico de distribución por tamaño
function updateDistribucionTamanioChart(data) {
    const chartData = {
        series: data.map(item => item.total),
        labels: data.map(item => item.tamaño)
    };
    
    if (window.distribucionTamanioChart) {
        window.distribucionTamanioChart.updateOptions({
            series: chartData.series,
            labels: chartData.labels
        });
    } else {
        window.distribucionTamanioChart = new ApexCharts(
            document.querySelector("#distribucion-tamanio-chart"),
            {
                chart: { type: 'donut', height: 350 },
                series: chartData.series,
                labels: chartData.labels,
                colors: ['#727cf5', '#0acf97', '#fa5c7c', '#ffbc00'],
                legend: { position: 'bottom' }
            }
        );
        window.distribucionTamanioChart.render();
    }
}

// 3. Gráfico de exámenes más solicitados
function updateExamenesTopChart(data) {
    const chartData = {
        series: [{
            name: "Solicitudes",
            data: data.map(item => item.total)
        }],
        labels: data.map(item => item.examen)
    };
    
    if (window.examenesTopChart) {
        window.examenesTopChart.updateOptions({
            series: chartData.series,
            xaxis: { categories: chartData.labels }
        });
    } else {
        window.examenesTopChart = new ApexCharts(
            document.querySelector("#examenes-chart"),
            {
                chart: { type: 'bar', height: 350 },
                series: chartData.series,
                xaxis: { categories: chartData.labels },
                plotOptions: { bar: { horizontal: true } },
                colors: ['#5b69bc'],
                tooltip: { y: { formatter: val => `${val} solicitudes` } }
            }
        );
        window.examenesTopChart.render();
    }
}

// 4. Gráfico de evolución de pacientes
function updateEvolucionPacientesChart(data) {
    const chartData = {
        series: [{
            name: "Pacientes",
            data: data.map(item => item.total_pacientes)
        }],
        labels: data.map(item => `Sem ${item.semana} ${item.anio}`)
    };
    
    if (window.evolucionPacientesChart) {
        window.evolucionPacientesChart.updateOptions({
            series: chartData.series,
            xaxis: { categories: chartData.labels }
        });
    } else {
        window.evolucionPacientesChart = new ApexCharts(
            document.querySelector("#pacientes-chart"),
            {
                chart: { type: 'area', height: 350 },
                series: chartData.series,
                xaxis: { categories: chartData.labels },
                colors: ['#0acf97'],
                stroke: { width: 3, curve: 'smooth' },
                tooltip: { y: { formatter: val => `${val} pacientes` } }
            }
        );
        window.evolucionPacientesChart.render();
    }
}

// 5. Gráfico de tasa de conversión
function updateTasaConversionChart(data) {
    const meses = ["Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic"];
    
    const chartData = {
        series: [{
            name: "Tasa de Conversión",
            data: data.map(item => parseFloat(item.tasa_conversion).toFixed(2))
        }],
        labels: data.map(item => `${meses[item.mes - 1]} ${item.anio}`)
    };
    
    if (window.tasaConversionChart) {
        window.tasaConversionChart.updateOptions({
            series: chartData.series,
            xaxis: { categories: chartData.labels }
        });
    } else {
        window.tasaConversionChart = new ApexCharts(
            document.querySelector("#conversion-chart"),
            {
                chart: { type: 'line', height: 350 },
                series: chartData.series,
                xaxis: { categories: chartData.labels },
                colors: ['#fa5c7c'],
                stroke: { width: 3, curve: 'smooth' },
                yaxis: { 
                    min: 0,
                    max: 100,
                    labels: { formatter: val => `${val}%` }
                },
                tooltip: { y: { formatter: val => `${val}%` } }
            }
        );
        window.tasaConversionChart.render();
    }
}

// Mostrar error en los gráficos
function showChartError(message) {
    document.querySelectorAll('.apex-charts').forEach(chart => {
        chart.innerHTML = `
            <div class="text-center p-4 text-danger">
                <i class="mdi mdi-alert-circle-outline h2"></i>
                <p>${message}</p>
            </div>
        `;
    });
}

// Conectar al filtro de fechas
document.addEventListener('DOMContentLoaded', function() {
    // Carga inicial
    loadChartData();
    
    // Conectar al datepicker
    $('#dash-daterange, #mobile-daterange').on('apply.daterangepicker', function(ev, picker) {
        loadChartData(
            picker.startDate.format('YYYY-MM-DD'), 
            picker.endDate.format('YYYY-MM-DD')
        );
    });
    
    // Conectar al botón de reinicio
    $('#btn-reset, #mobile-btn-reset').click(function() {
        loadChartData(); // Cargar todos los datos sin filtro
    });
});
</script>
<!-- Mirrored from coderthemes.com/hyper/saas/index.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 29 Jul 2022 10:20:07 GMT -->
</html>