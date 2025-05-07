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
$pagina_titulo = "Gestión de Clientes";
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
?>
<!DOCTYPE html>
<html lang="es">
<head>
        <meta charset="utf-8" />
        <title>Panel de Control</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta content="A fully featured admin theme which can be used to build CRM, CMS, etc." name="description" />
        <meta content="Coderthemes" name="author" />
        <!-- App favicon -->
        <link rel="shortcut icon" href="assets/images/favicon.ico">

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
                    <a href="campañas.php" class="flex items-center">
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
                        <!-- Divider -->


                    </ul>

                    <!-- User Profile Card at Bottom -->


                    <div class="clearfix"></div>
                </div>
            </div>
                <!-- Contenido principal -->
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <div class="page-title-box">
                                <h4 class="page-title">Gestión de Clientes</h4>
                            </div>
                        </div>
                    </div>

                    <!-- Card de Filtros y Botones -->
                    <div class="row mb-3">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <h5 class="card-title mb-md-0">Filtros Avanzados</h5>
                            </div>
                            <div class="col-md-6 text-md-end">
                                <button class="btn btn-primary me-1" data-bs-toggle="modal" data-bs-target="#nuevo-cliente-modal">
                                    <i class="mdi mdi-plus-circle-outline me-1"></i> Nuevo Cliente
                                </button>
                                <button type="button" class="btn btn-secondary me-1" id="reset-filtros">
                                    <i class="mdi mdi-filter-remove me-1"></i> Limpiar
                                </button>
                                <button type="button" class="btn btn-success me-1" id="recargar-tabla">
                                    <i class="mdi mdi-reload me-1"></i> Recargar
                                </button>
                            </div>
                        </div>
                        <hr>
                        <form id="filtros-form">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label class="form-label">Estado</label>
                                        <select class="form-control select2" name="filtro_estado" data-toggle="select2">
                                            <option value="">Todos</option>
                                            <option value="Activo">Activo</option>
                                            <option value="Inactivo">Inactivo</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label class="form-label">Estado Cliente</label>
                                        <select class="form-control select2" name="filtro_estado_cliente" data-toggle="select2">
                                            <option value="">Todos</option>
                                            <option value="Concretado">Concretado</option>
                                            <option value="No concretado">No concretado</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label class="form-label">Rango de Fechas</label>
                                        <input type="text" class="form-control date-range" name="filtro_fecha" placeholder="Seleccione rango">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label class="form-label">Tamaño</label>
                                        <select class="form-control select2" name="filtro_tamano" data-toggle="select2">
                                            <option value="">Todos</option>
                                            <option value="Pequeña">Pequeña</option>
                                            <option value="Mediana">Mediana</option>
                                            <option value="Grande">Grande</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 text-center">
                                    <button type="button" class="btn btn-primary" id="aplicar-filtros">
                                        <i class="mdi mdi-filter me-1"></i> Aplicar Filtros
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabla de clientes -->
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="clientes-datatable" class="table dt-responsive nowrap w-100">
                                <thead>
                                    <tr>
                                        <th>Código</th>
                                        <th>Nombre</th>
                                        <th>RUC</th>
                                        <th>Tamaño</th>
                                        <th>Examen</th>
                                        <th>Correo</th>
                                        <th>Teléfono</th>
                                        <th>Estado</th>
                                        <th>Estado Cliente</th>
                                        <th>Último Contacto</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    try {
                                        $sql = "SELECT 
                                                    id,
                                                    cod_cliente,
                                                    nombre_cliente,
                                                    ruc,
                                                    tamaño,
                                                    examen,
                                                    correo,
                                                    movil,
                                                    control_estado,
                                                    estado_cliente,
                                                    f_fecha as ultimo_contacto
                                                FROM todos_los_clientes
                                                ORDER BY nombre_cliente ASC";
                                        
                                        $stmt = $conn->prepare($sql);
                                        $stmt->execute();
                                        $result = $stmt->get_result();
                                        
                                        if ($result->num_rows > 0) {
                                            while($row = $result->fetch_assoc()) {
                                                echo '<tr>
                                                    <td>'.htmlspecialchars($row["cod_cliente"] ?? 'N/A').'</td>
                                                    <td>'.htmlspecialchars($row["nombre_cliente"] ?? 'N/A').'</td>
                                                    <td>'.htmlspecialchars($row["ruc"] ?? 'N/A').'</td>
                                                    <td>'.htmlspecialchars($row["tamaño"] ?? 'N/A').'</td>
                                                    <td>'.htmlspecialchars($row["examen"] ?? 'N/A').'</td>
                                                    <td>'.htmlspecialchars($row["correo"] ?? 'N/A').'</td>
                                                    <td>'.htmlspecialchars($row["movil"] ?? 'N/A').'</td>
                                                    <td>';
                                                
                                                if($row["control_estado"] == "Activo") {
                                                    echo '<span class="badge bg-success">Activo</span>';
                                                } else {
                                                    echo '<span class="badge bg-danger">Inactivo</span>';
                                                }
                                                
                                                echo '</td>
                                                    <td>';
                                                
                                                if($row["estado_cliente"] == "Concretado") {
                                                    echo '<span class="badge bg-primary">Concretado</span>';
                                                } else {
                                                    echo '<span class="badge bg-warning">No concretado</span>';
                                                }
                                                
                                                echo '</td>
                                                    <td>'.(!empty($row["ultimo_contacto"]) ? date("d/m/Y", strtotime($row["ultimo_contacto"])) : 'N/A').'</td>
                                                    <td>
                                                        <div class="btn-group">
                                                            <button class="btn btn-sm btn-info edit-cliente" data-id="'.$row["id"].'" title="Editar">
                                                                <i class="mdi mdi-pencil-outline"></i>
                                                            </button>
                                                            <button class="btn btn-sm btn-danger delete-cliente" data-id="'.$row["id"].'" title="Eliminar">
                                                                <i class="mdi mdi-delete-outline"></i>
                                                            </button>
                                                        </div>
                                                    </td>
                                                </tr>';
                                            }
                                        } else {
                                            echo '<tr><td colspan="11" class="text-center">No hay clientes registrados</td></tr>';
                                        }
                                    } catch (Exception $e) {
                                        echo '<tr><td colspan="11" class="text-center text-danger">Error al cargar los clientes: '.$e->getMessage().'</td></tr>';
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Modal Nuevo Cliente -->
    <div class="modal fade" id="nuevo-cliente-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Registrar Nuevo Cliente</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="nuevo-cliente-form">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Código Cliente*</label>
                                <input type="text" class="form-control" name="cod_cliente" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Nombre Completo*</label>
                                <input type="text" class="form-control" name="nombre_cliente" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">N° de Pacientes</label>
                                <input type="number" class="form-control" name="pacientes">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Teléfono Móvil</label>
                                <input type="tel" class="form-control" name="movil">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">RUC</label>
                                <input type="text" class="form-control" name="ruc">
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Tamaño Empresa</label>
                                <select class="form-select" name="tamaño">
                                    <option value="">Seleccionar</option>
                                    <option value="Pequeña">Pequeña</option>
                                    <option value="Mediana">Mediana</option>
                                    <option value="Grande">Grande</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Correo Electrónico</label>
                                <input type="email" class="form-control" name="correo">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">LinkedIn</label>
                                <input type="url" class="form-control" name="linkedin">
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Persona de Contacto</label>
                                <input type="text" class="form-control" name="contacto">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Cargo</label>
                                <input type="text" class="form-control" name="cargo">
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Estado*</label>
                                <select class="form-select" name="control_estado" required>
                                    <option value="Activo">Activo</option>
                                    <option value="Inactivo">Inactivo</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Mes</label>
                                <select class="form-select" name="mes">
                                    <option value="">Seleccionar</option>
                                    <option value="Enero">Enero</option>
                                    <option value="Febrero">Febrero</option>
                                    <option value="Marzo">Marzo</option>
                                    <option value="Abril">Abril</option>
                                    <option value="Mayo">Mayo</option>
                                    <option value="Junio">Junio</option>
                                    <option value="Julio">Julio</option>
                                    <option value="Agosto">Agosto</option>
                                    <option value="Septiembre">Septiembre</option>
                                    <option value="Octubre">Octubre</option>
                                    <option value="Noviembre">Noviembre</option>
                                    <option value="Diciembre">Diciembre</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Año</label>
                                <input type="number" class="form-control" name="año" min="2000" max="2099" step="1" value="<?php echo date('Y'); ?>">
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Estado Cliente*</label>
                                <select class="form-select" name="estado_cliente" required>
                                    <option value="Concretado">Concretado</option>
                                    <option value="No concretado">No concretado</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Canal</label>
                                <input type="text" class="form-control" name="canal">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Examen</label>
                                <input type="text" class="form-control" name="examen">
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Fecha Último Contacto</label>
                                <input type="date" class="form-control" name="f_fecha">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label class="form-label">Ciclo</label>
                                <input type="text" class="form-control" name="ciclo">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label class="form-label">N° Día</label>
                                <input type="number" class="form-control" name="num_dia" min="1" max="31">
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Semana</label>
                                <input type="number" class="form-control" name="semana" min="1" max="52">
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Motivos</label>
                        <textarea class="form-control" name="motivos" rows="2"></textarea>
                    </div>

                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="guardar-cliente-btn">Guardar</button>
            </div>
        </div>
    </div>
</div>

    <!-- Modal Editar Cliente -->
    <!-- Modal Editar Cliente - Versión Completa sin observaciones -->
<div class="modal fade" id="editar-cliente-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Editar Cliente</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editar-cliente-form">
                    <input type="hidden" name="id">
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Código Cliente*</label>
                                <input type="text" class="form-control" name="cod_cliente" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Nombre Completo*</label>
                                <input type="text" class="form-control" name="nombre_cliente" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">N° de Pacientes</label>
                                <input type="number" class="form-control" name="pacientes">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Teléfono Móvil</label>
                                <input type="tel" class="form-control" name="movil">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">RUC</label>
                                <input type="text" class="form-control" name="ruc">
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Tamaño Empresa</label>
                                <select class="form-select" name="tamaño">
                                    <option value="">Seleccionar</option>
                                    <option value="Pequeña">Pequeña</option>
                                    <option value="Mediana">Mediana</option>
                                    <option value="Grande">Grande</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Correo Electrónico</label>
                                <input type="email" class="form-control" name="correo">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">LinkedIn</label>
                                <input type="url" class="form-control" name="linkedin">
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Persona de Contacto</label>
                                <input type="text" class="form-control" name="contacto">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Cargo</label>
                                <input type="text" class="form-control" name="cargo">
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Estado*</label>
                                <select class="form-select" name="control_estado" required>
                                    <option value="Activo">Activo</option>
                                    <option value="Inactivo">Inactivo</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Mes</label>
                                <select class="form-select" name="mes">
                                    <option value="">Seleccionar</option>
                                    <option value="Enero">Enero</option>
                                    <option value="Febrero">Febrero</option>
                                    <option value="Marzo">Marzo</option>
                                    <option value="Abril">Abril</option>
                                    <option value="Mayo">Mayo</option>
                                    <option value="Junio">Junio</option>
                                    <option value="Julio">Julio</option>
                                    <option value="Agosto">Agosto</option>
                                    <option value="Septiembre">Septiembre</option>
                                    <option value="Octubre">Octubre</option>
                                    <option value="Noviembre">Noviembre</option>
                                    <option value="Diciembre">Diciembre</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Año</label>
                                <input type="number" class="form-control" name="año" min="2000" max="2099" step="1">
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Estado Cliente*</label>
                                <select class="form-select" name="estado_cliente" required>
                                    <option value="Concretado">Concretado</option>
                                    <option value="No concretado">No concretado</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Canal</label>
                                <input type="text" class="form-control" name="canal">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Examen</label>
                                <input type="text" class="form-control" name="examen">
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Fecha Último Contacto</label>
                                <input type="date" class="form-control" name="f_fecha">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label class="form-label">Ciclo</label>
                                <input type="text" class="form-control" name="ciclo">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label class="form-label">N° Día</label>
                                <input type="number" class="form-control" name="num_dia" min="1" max="31">
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Semana</label>
                                <input type="number" class="form-control" name="semana" min="1" max="52">
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Motivos</label>
                        <textarea class="form-control" name="motivos" rows="2"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="actualizar-cliente-btn">Actualizar</button>
            </div>
        </div>
    </div>
</div>

    <!-- Loading Overlay -->
    <div class="loading-overlay">
        <div class="loading-spinner">
            <i class="mdi mdi-loading mdi-spin"></i> Procesando...
        </div>
    </div>

    <!-- Scripts -->
    <script src="../assets/js/vendor.min.js"></script>
    <script src="../assets/js/app.min.js"></script>
    
    <!-- Scripts adicionales -->
    <?php foreach ($scripts_adicionales as $script): ?>
        <script src="<?php echo $script; ?>"></script>
    <?php endforeach; ?>
    
    <script>
    $(document).ready(function() {
        // Mostrar overlay de carga
        function showLoading() {
            $('.loading-overlay').show();
        }
        
        // Ocultar overlay de carga
        function hideLoading() {
            $('.loading-overlay').hide();
        }
        
        // Inicializar select2
        $('.select2').select2();
        
        // Inicializar date range picker
        $('.date-range').daterangepicker({
            locale: {
                format: 'DD/MM/YYYY',
                applyLabel: 'Aplicar',
                cancelLabel: 'Cancelar',
                fromLabel: 'Desde',
                toLabel: 'Hasta',
                customRangeLabel: 'Personalizado',
                daysOfWeek: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sa'],
                monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
                firstDay: 1
            },
            opens: 'right',
            autoUpdateInput: false
        });
        
        $('.date-range').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
        });
        
        $('.date-range').on('cancel.daterangepicker', function(ev, picker) {
            $(this).val('');
        });
        
        // Inicializar DataTable de clientes con botones de exportación
        // Inicializar DataTable de clientes con botones de exportación
var table = $('#clientes-datatable').DataTable({
    responsive: true,
    language: {
        url: '../assets/js/vendor/dataTables.spanish.json'
    },
    dom: '<"row"<"col-sm-12 col-md-4"l><"col-sm-12 col-md-4"B><"col-sm-12 col-md-4"f>>' +
         '<"row"<"col-sm-12"tr>>' +
         '<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
    buttons: [
        {
            extend: 'excel',
            text: '<i class="mdi mdi-file-excel me-1"></i> Excel',
            className: 'btn btn-success buttons-excel',
            exportOptions: {
                columns: ':visible:not(:last-child)'
            }
        },
        {
            extend: 'pdf',
            text: '<i class="mdi mdi-file-pdf me-1"></i> PDF',
            className: 'btn btn-danger buttons-pdf',
            exportOptions: {
                columns: ':visible:not(:last-child)'
            },
            customize: function(doc) {
                doc.content[1].table.widths = Array(doc.content[1].table.body[0].length + 1).join('*').split('');
                doc.defaultStyle.alignment = 'center';
                doc.styles.tableHeader.alignment = 'center';
            }
        },
        {
            extend: 'print',
            text: '<i class="mdi mdi-printer me-1"></i> Imprimir',
            className: 'btn btn-info buttons-print',
            exportOptions: {
                columns: ':visible:not(:last-child)'
            }
        }
    ],
    columnDefs: [
        { targets: -1, orderable: false, searchable: false }
    ],
    initComplete: function() {
        // Asegurarse de que los botones se muestren correctamente
        this.api().buttons().container()
            .appendTo('#clientes-datatable_wrapper .col-md-4:eq(1)');
    }
});
        // Botón para recargar la tabla
        $('#recargar-tabla').click(function() {
            table.ajax.reload(null, false);
            $('#filtros-form')[0].reset();
            $('.select2').val(null).trigger('change');
            $('.date-range').val('');
            toastr.success('Tabla recargada correctamente');
        });
        // Función para aplicar filtros
        function aplicarFiltros() {
            var estado = $('[name="filtro_estado"]').val();
            var estadoCliente = $('[name="filtro_estado_cliente"]').val();
            var fecha = $('[name="filtro_fecha"]').val();
            var tamano = $('[name="filtro_tamano"]').val();
            
            // Limpiar filtros anteriores
            table.columns().search('').draw();
            
            // Aplicar filtros individuales
            if (estado) {
                table.column(7).search('^' + estado + '$', true, false).draw();
            }
            
            if (estadoCliente) {
                table.column(8).search('^' + estadoCliente + '$', true, false).draw();
            }
            
            if (tamano) {
                table.column(3).search('^' + tamano + '$', true, false).draw();
            }
            
            // Filtro por fecha (requiere lógica adicional)
            if (fecha) {
                var fechas = fecha.split(' - ');
                var fechaInicio = moment(fechas[0], 'DD/MM/YYYY').format('YYYY-MM-DD');
                var fechaFin = moment(fechas[1], 'DD/MM/YYYY').format('YYYY-MM-DD');
                
                // Filtrado por rango de fechas
                $.fn.dataTable.ext.search.push(
                    function(settings, data, dataIndex) {
                        var fechaTabla = data[9]; // Columna de fecha
                        if (!fechaTabla) return false;
                        
                        // Convertir fecha de la tabla a formato comparable
                        var partesFecha = fechaTabla.split('/');
                        var fechaTablaFormato = partesFecha[2] + '-' + partesFecha[1] + '-' + partesFecha[0];
                        
                        return (fechaInicio <= fechaTablaFormato && fechaTablaFormato <= fechaFin);
                    }
                );
                
                table.draw();
                $.fn.dataTable.ext.search.pop(); // Limpiar el filtro para futuras búsquedas
            }
        }
        
        // Aplicar filtros al hacer clic en el botón
        $('#aplicar-filtros').click(aplicarFiltros);
        
        // Resetear filtros
        $('#reset-filtros').click(function() {
            $('#filtros-form')[0].reset();
            $('.select2').val(null).trigger('change');
            $('.date-range').val('');
            table.columns().search('').draw();
        });
        
        // Registrar nuevo cliente
        $('#guardar-cliente-btn').click(function() {
            const formData = $('#nuevo-cliente-form').serialize();
            
            showLoading();
            $.ajax({
                url: '../funciones/registrar_cliente.php',
                type: 'POST',
                data: formData,
                success: function(response) {
                    if(response.success) {
                        alert('Cliente registrado correctamente');
                        $('#nuevo-cliente-modal').modal('hide');
                        $('#nuevo-cliente-form')[0].reset();
                        table.ajax.reload(null, false); // Recargar sin resetear paginación
                    } else {
                        alert(response.message || 'Error al registrar cliente');
                    }
                },
                error: function(xhr, status, error) {
                    alert('Error en la solicitud: ' + error);
                },
                complete: hideLoading
            });
        });
        
        // Editar cliente
        // Editar cliente - Versión corregida
$(document).on('click', '.edit-cliente', function() {
    const clienteId = $(this).data('id');
    
    // Resetear el formulario primero
    $('#editar-cliente-form')[0].reset();
    $('.select2').val(null).trigger('change');
    
    showLoading();
    $.ajax({
        url: '../funciones/obtener_cliente.php',
        type: 'GET',
        data: {id: clienteId},
        success: function(response) {
            if(response.success) {
                // Llenar el formulario con los datos del cliente
                $('#editar-cliente-form input[name="id"]').val(response.data.id);
                $('#editar-cliente-form input[name="cod_cliente"]').val(response.data.cod_cliente);
                $('#editar-cliente-form input[name="nombre_cliente"]').val(response.data.nombre_cliente);
                $('#editar-cliente-form input[name="pacientes"]').val(response.data.pacientes);
                $('#editar-cliente-form input[name="movil"]').val(response.data.movil);
                $('#editar-cliente-form input[name="ruc"]').val(response.data.ruc);
                $('#editar-cliente-form select[name="tamaño"]').val(response.data.tamaño).trigger('change');
                $('#editar-cliente-form input[name="correo"]').val(response.data.correo);
                $('#editar-cliente-form input[name="linkedin"]').val(response.data.linkedin);
                $('#editar-cliente-form input[name="contacto"]').val(response.data.contacto);
                $('#editar-cliente-form input[name="cargo"]').val(response.data.cargo);
                $('#editar-cliente-form select[name="control_estado"]').val(response.data.control_estado).trigger('change');
                $('#editar-cliente-form select[name="mes"]').val(response.data.mes).trigger('change');
                $('#editar-cliente-form input[name="año"]').val(response.data.año);
                $('#editar-cliente-form select[name="estado_cliente"]').val(response.data.estado_cliente).trigger('change');
                $('#editar-cliente-form input[name="canal"]').val(response.data.canal);
                $('#editar-cliente-form input[name="examen"]').val(response.data.examen);
                $('#editar-cliente-form textarea[name="motivos"]').val(response.data.motivos);
                
                // Formatear fecha para el input date
                if(response.data.f_fecha) {
                    var fecha = new Date(response.data.f_fecha);
                    var fechaFormateada = fecha.toISOString().split('T')[0];
                    $('#editar-cliente-form input[name="f_fecha"]').val(fechaFormateada);
                } else {
                    $('#editar-cliente-form input[name="f_fecha"]').val('');
                }
                
                $('#editar-cliente-form input[name="ciclo"]').val(response.data.ciclo);
                $('#editar-cliente-form input[name="num_dia"]').val(response.data.num_dia);
                $('#editar-cliente-form input[name="semana"]').val(response.data.semana);
                
                $('#editar-cliente-modal').modal('show');
            } else {
                alert(response.message || 'Error al cargar cliente');
            }
        },
        error: function(xhr, status, error) {
            alert('Error en la solicitud: ' + error);
        },
        complete: hideLoading
    });
});
        
        // Actualizar cliente
        $('#actualizar-cliente-btn').click(function() {
            const formData = $('#editar-cliente-form').serialize();
            
            showLoading();
            $.ajax({
                url: '../funciones/actualizar_cliente.php',
                type: 'POST',
                data: formData,
                success: function(response) {
                    if(response.success) {
                        alert('Cliente actualizado correctamente');
                        $('#editar-cliente-modal').modal('hide');
                        table.ajax.reload(null, false); // Recargar sin resetear paginación
                    } else {
                        alert(response.message || 'Error al actualizar cliente');
                    }
                },
                error: function(xhr, status, error) {
                    alert('Error en la solicitud: ' + error);
                },
                complete: hideLoading
            });
        });
        
        // Eliminar cliente
        $(document).on('click', '.delete-cliente', function() {
            const clienteId = $(this).data('id');
            
            if(confirm('¿Está seguro de eliminar este cliente? Esta acción no se puede deshacer.')) {
                showLoading();
                $.ajax({
                    url: '../funciones/eliminar_cliente.php',
                    type: 'POST',
                    data: {id: clienteId},
                    success: function(response) {
                        if(response.success) {
                            alert('Cliente eliminado correctamente');
                            table.ajax.reload(null, false); // Recargar sin resetear paginación
                        } else {
                            alert(response.message || 'Error al eliminar cliente');
                        }
                    },
                    error: function(xhr, status, error) {
                        alert('Error en la solicitud: ' + error);
                    },
                    complete: hideLoading
                });
            }
        });
    });
    </script>
</body>
</html>