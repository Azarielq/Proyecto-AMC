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
            header("Location: correos.php");
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
<head>
    <meta charset="utf-8" />
    <title>Panel de Correos</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="A fully featured admin theme which can be used to build CRM, CMS, etc." name="description" />
    <meta content="Coderthemes" name="author" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="assets/images/favicon.ico">

    <!-- App css -->
    <link href="assets/css/icons.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/app.min.css" rel="stylesheet" type="text/css" id="app-style"/>

    <!-- DataTables CSS -->
    <link href="assets/css/vendor/dataTables.bootstrap5.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/vendor/responsive.bootstrap5.css" rel="stylesheet" type="text/css" />
    
    <!-- Dropzone CSS -->
    <link href="assets/css/vendor/dropzone.min.css" rel="stylesheet" type="text/css" />
    
    <style>
        .gradient-9 {
    background: linear-gradient(135deg, #1a2980 0%, #26d0ce 100%);
}
.gradient-10 {
    background: linear-gradient(135deg, #4776E6 0%, #8E54E9 100%);
}
        .email-list {
            cursor: pointer;
            transition: all 0.3s;
        }
        .email-list:hover {
            background-color: #f8f9fa;
        }
        .email-list.active {
            background-color: #e9ecef;
        }
        .attachment-preview {
            max-width: 100px;
            max-height: 100px;
            margin-right: 10px;
            margin-bottom: 10px;
        }
        .campaign-card {
            transition: all 0.3s;
        }
        .campaign-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
    </style>
</head>

<body class="loading" data-layout-color="light" data-leftbar-theme="dark" data-layout-mode="fluid" data-rightbar-onstart="true">
    <div class="wrapper">
        <!-- ========== Left Sidebar Start ========== -->
        <div class="leftside-menu">
            <!-- Logo principal con superposición -->
            <a href="index.php" class="logo text-center logo-light transition-all relative inline-block">
                <span class="logo-lg relative inline-block">
                    <img src="assets/images/logo.png" alt="Company Logo" height="80" class="hover:scale-105 transition-transform relative z-0">
                </span>
                <span class="logo-sm">
                    <img src="assets/images/logo_sm.png" alt="Company Logo" height="41" class="hover:scale-105 transition-transform">
                </span>
            </a>

            <div class="h-100" id="leftside-menu-container" data-simplebar>
                <ul class="side-nav">
                    <li class="side-nav-title side-nav-item text-uppercase font-weight-bold text-muted"></li>
                    <li class="side-nav-title side-nav-item text-uppercase font-weight-bold text-muted">Navegación</li>

                    <li class="side-nav-item">
                        <a href="index.php" class="side-nav-link">
                            <i class="uil-home-alt"></i>
                            <span> Inicio </span>
                        </a>
                    </li>

                    <li class="side-nav-item">
                        <a href="correos.php" class="side-nav-link active">
                            <i class="uil-envelope"></i>
                            <span> Correo </span>
                            <span class="badge bg-danger rounded-pill float-end" id="unread-count">0</span>
                        </a>
                    </li>

                    <li class="side-nav-item">
                        <a data-bs-toggle="collapse" href="#sidebarCampaigns" aria-expanded="false" aria-controls="sidebarCampaigns" class="side-nav-link">
                            <i class="uil-megaphone"></i>
                            <span> Campañas </span>
                            <span class="menu-arrow"></span>
                        </a>
                        <div class="collapse" id="sidebarCampaigns">
                            <ul class="side-nav-second-level">
                                <li>
                                    <a href="#" class="flex items-center">
                                        <i class="uil-plus-circle mr-2"></i> Nueva Campaña
                                    </a>
                                </li>
                                <li>
                                    <a href="#" class="flex items-center">
                                        <i class="uil-history mr-2"></i> Historial
                                    </a>
                                </li>
                                <li>
                                    <a href="#" class="flex items-center">
                                        <i class="uil-chart-pie mr-2"></i> Estadísticas
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                </ul>

                <!-- User Profile Card at Bottom -->
                <div class="leftbar-user-card p-3 text-center position-absolute bottom-0 start-0 end-0">
                    <img src="assets/images/users/avatar-1.jpg" alt="user-image" class="rounded-circle shadow-sm img-thumbnail mb-2" width="80">
                    <h6 class="mb-1">John Doe</h6>
                    <p class="text-muted small">Administrador</p>
                    <a href="#" class="btn btn-sm btn-primary w-100">Mi perfil</a>
                </div>
            </div>
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
                                    <span>Mi Cuenta</span>
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
                                <input type="text" class="form-control dropdown-toggle" placeholder="Buscar contacto..." id="search-contacts">
                                <span class="mdi mdi-magnify search-icon"></span>
                                <button class="input-group-text btn-primary" type="submit">Buscar</button>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- end Topbar -->
                
                <!-- Start Content-->
                <div class="container-fluid">
                    <!-- start page title -->
                    <div class="row">
                        <div class="col-12">
                            <div class="page-title-box">
                                <div class="page-title-right">
                                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#new-email-modal">
                                        <i class="mdi mdi-email-plus-outline me-1"></i> Nuevo Correo
                                    </button>
                                    <button class="btn btn-success ms-1" data-bs-toggle="modal" data-bs-target="#new-campaign-modal">
                                        <i class="mdi mdi-megaphone me-1"></i> Nueva Campaña
                                    </button>
                                </div>
                                <h4 class="page-title">Gestión de Correos</h4>
                            </div>
                        </div>
                    </div>     
                    <!-- end page title --> 

                    <div class="row">
                        <div class="col-xl-12">
                            <div class="card">
                                <div class="card-body">
                                    <ul class="nav nav-tabs nav-bordered">
                                        <li class="nav-item">
                                            <a href="#contacts-tab" data-bs-toggle="tab" aria-expanded="false" class="nav-link active">
                                                <i class="mdi mdi-account-group-outline me-1"></i> Contactos
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="#campaigns-tab" data-bs-toggle="tab" aria-expanded="true" class="nav-link">
                                                <i class="mdi mdi-megaphone me-1"></i> Campañas
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="#templates-tab" data-bs-toggle="tab" aria-expanded="false" class="nav-link">
                                                <i class="mdi mdi-email-newsletter me-1"></i> Plantillas
                                            </a>
                                        </li>
                                    </ul>
                                    
                                    <div class="tab-content">
                                        <div class="tab-pane show active" id="contacts-tab">
                                            <div class="row mb-3">
                                                <div class="col-sm-12 col-md-6">
                                                    <div class="dt-buttons btn-group flex-wrap">
                                                        <button class="btn btn-secondary buttons-copy buttons-html5" type="button">
                                                            <span>Copiar</span>
                                                        </button>
                                                        <button class="btn btn-secondary buttons-excel buttons-html5" type="button">
                                                            <span>Excel</span>
                                                        </button>
                                                        <button class="btn btn-secondary buttons-pdf buttons-html5" type="button">
                                                            <span>PDF</span>
                                                        </button>
                                                        <button class="btn btn-secondary buttons-print" type="button">
                                                            <span>Imprimir</span>
                                                        </button>
                                                        <div class="btn-group">
                                                            <button type="button" class="btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                                                Acciones <i class="mdi mdi-chevron-down"></i>
                                                            </button>
                                                            <div class="dropdown-menu">
                                                                <a class="dropdown-item" href="#" id="activate-selected">Activar seleccionados</a>
                                                                <a class="dropdown-item" href="#" id="deactivate-selected">Desactivar seleccionados</a>
                                                                <div class="dropdown-divider"></div>
                                                                <a class="dropdown-item" href="#" id="export-selected">Exportar seleccionados</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-12 col-md-6">
                                                    <div id="contacts-datatable_filter" class="dataTables_filter">
                                                        <label>Buscar:
                                                            <input type="search" class="form-control form-control-sm" placeholder="" aria-controls="contacts-datatable">
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="table-responsive">
                                                <table id="contacts-datatable" class="table dt-responsive nowrap w-100">
                                                    <thead>
                                                        <tr>
                                                            <th style="width: 20px;">
                                                                <div class="form-check">
                                                                    <input type="checkbox" class="form-check-input" id="select-all">
                                                                    <label class="form-check-label" for="select-all">&nbsp;</label>
                                                                </div>
                                                            </th>
                                                            <th>Nombre</th>
                                                            <th>Email</th>
                                                            <th>Empresa</th>
                                                            <th>Estado</th>
                                                            <th>Último Contacto</th>
                                                            <th>Acciones</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                        // Conexión a la base de datos
                                                        require 'db_connect.php';
                                                        
                                                        // Consulta para obtener los contactos
                                                        $sql = "SELECT cod_cliente as id, nombre_cliente as nombre, correo as email 
        FROM todos_los_clientes 
        WHERE correo IS NOT NULL AND correo != '' 
        ORDER BY nombre_cliente ASC";
                                                        $result = $conn->query($sql);
                                                        
                                                        if ($result->num_rows > 0) {
                                                            while($row = $result->fetch_assoc()) {
                                                                echo '<tr>
                                                                    <td>
                                                                        <div class="form-check">
                                                                            <input type="checkbox" class="form-check-input contact-checkbox" id="contact-'.$row["id"].'" value="'.$row["id"].'">
                                                                            <label class="form-check-label" for="contact-'.$row["id"].'">&nbsp;</label>
                                                                        </div>
                                                                    </td>
                                                                    <td>'.$row["nombre"].'</td>
                                                                    <td>'.$row["email"].'</td>
                                                                    <td>'.$row["empresa"].'</td>
                                                                    <td>';
                                                                    
                                                                if($row["activo"] == 1) {
                                                                    echo '<span class="badge bg-success">Activo</span>';
                                                                } else {
                                                                    echo '<span class="badge bg-danger">Inactivo</span>';
                                                                }
                                                                    
                                                                echo '</td>
                                                                    <td>'.date("d/m/Y", strtotime($row["ultimo_contacto"])).'</td>
                                                                    <td>
                                                                        <a href="mailto:'.$row["email"].'" class="btn btn-sm btn-primary"><i class="mdi mdi-email-outline"></i></a>
                                                                        <button class="btn btn-sm btn-info edit-contact" data-id="'.$row["id"].'"><i class="mdi mdi-pencil-outline"></i></button>
                                                                        <button class="btn btn-sm btn-danger delete-contact" data-id="'.$row["id"].'"><i class="mdi mdi-delete-outline"></i></button>
                                                                    </td>
                                                                </tr>';
                                                            }
                                                        } else {
                                                            echo '<tr><td colspan="7" class="text-center">No hay contactos registrados</td></tr>';
                                                        }
                                                        $conn->close();
                                                        ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        
                                        <div class="tab-pane" id="campaigns-tab">
                                            <div class="row">
                                                <?php
                                                // Conexión a la base de datos
                                                require 'db_connect.php';
                                                
                                                // Consulta para obtener las campañas
                                                $sql = "SELECT id, nombre, asunto, fecha_envio, estado, destinatarios FROM campañas ORDER BY fecha_envio DESC";
                                                $result = $conn->query($sql);
                                                
                                                if ($result->num_rows > 0) {
                                                    while($row = $result->fetch_assoc()) {
                                                        $badge_class = '';
                                                        if($row["estado"] == "Enviada") $badge_class = 'bg-success';
                                                        elseif($row["estado"] == "Programada") $badge_class = 'bg-warning';
                                                        elseif($row["estado"] == "Borrador") $badge_class = 'bg-secondary';
                                                        elseif($row["estado"] == "Fallida") $badge_class = 'bg-danger';
                                                        
                                                        echo '<div class="col-md-6 col-xl-4">
                                                            <div class="card campaign-card">
                                                                <div class="card-body">
                                                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                                                        <h4 class="header-title">'.$row["nombre"].'</h4>
                                                                        <span class="badge '.$badge_class.'">'.$row["estado"].'</span>
                                                                    </div>
                                                                    
                                                                    <p class="text-muted">'.$row["asunto"].'</p>
                                                                    
                                                                    <div class="row mt-2">
                                                                        <div class="col-6">
                                                                            <p class="mb-1 text-muted">Fecha</p>
                                                                            <h5 class="mt-0 font-14">'.date("d/m/Y", strtotime($row["fecha_envio"])).'</h5>
                                                                        </div>
                                                                        <div class="col-6">
                                                                            <p class="mb-1 text-muted">Destinatarios</p>
                                                                            <h5 class="mt-0 font-14">'.$row["destinatarios"].'</h5>
                                                                        </div>
                                                                    </div>
                                                                    
                                                                    <div class="mt-3 d-flex justify-content-between">
                                                                        <button class="btn btn-sm btn-outline-primary view-campaign" data-id="'.$row["id"].'">
                                                                            <i class="mdi mdi-eye-outline"></i> Ver
                                                                        </button>
                                                                        <button class="btn btn-sm btn-outline-success resend-campaign" data-id="'.$row["id"].'">
                                                                            <i class="mdi mdi-send-outline"></i> Reenviar
                                                                        </button>
                                                                        <button class="btn btn-sm btn-outline-info stats-campaign" data-id="'.$row["id"].'">
                                                                            <i class="mdi mdi-chart-bar"></i> Estadísticas
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>';
                                                    }
                                                } else {
                                                    echo '<div class="col-12 text-center py-4">
                                                        <i class="mdi mdi-email-newsletter h1 text-muted"></i>
                                                        <h4 class="mt-2">No hay campañas creadas</h4>
                                                        <p class="text-muted">Crea tu primera campaña para comenzar a enviar correos masivos</p>
                                                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#new-campaign-modal">
                                                            <i class="mdi mdi-plus-circle-outline me-1"></i> Crear Campaña
                                                        </button>
                                                    </div>';
                                                }
                                                $conn->close();
                                                ?>
                                            </div>
                                        </div>
                                        
                                        <div class="tab-pane" id="templates-tab">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="card">
                                                        <div class="card-body">
                                                            <div class="template-preview" style="background-image: url('assets/images/email-templates/template1.jpg'); height: 200px; background-size: cover; background-position: center;"></div>
                                                            <h4 class="mt-3">Promoción de Verano</h4>
                                                            <p class="text-muted">Plantilla para promociones estacionales</p>
                                                            <div class="d-flex justify-content-between">
                                                                <button class="btn btn-sm btn-primary">Usar Plantilla</button>
                                                                <button class="btn btn-sm btn-outline-secondary">Previsualizar</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="col-md-4">
                                                    <div class="card">
                                                        <div class="card-body">
                                                            <div class="template-preview" style="background-image: url('assets/images/email-templates/template2.jpg'); height: 200px; background-size: cover; background-position: center;"></div>
                                                            <h4 class="mt-3">Newsletter Mensual</h4>
                                                            <p class="text-muted">Plantilla para newsletters informativos</p>
                                                            <div class="d-flex justify-content-between">
                                                                <button class="btn btn-sm btn-primary">Usar Plantilla</button>
                                                                <button class="btn btn-sm btn-outline-secondary">Previsualizar</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="col-md-4">
                                                    <div class="card">
                                                        <div class="card-body">
                                                            <div class="template-preview" style="background-image: url('assets/images/email-templates/template3.jpg'); height: 200px; background-size: cover; background-position: center;"></div>
                                                            <h4 class="mt-3">Invitación a Evento</h4>
                                                            <p class="text-muted">Plantilla para eventos y conferencias</p>
                                                            <div class="d-flex justify-content-between">
                                                                <button class="btn btn-sm btn-primary">Usar Plantilla</button>
                                                                <button class="btn btn-sm btn-outline-secondary">Previsualizar</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
    <div class="card widget-flat gradient-9">
        <div class="card-body p-2">
            <div class="float-end">
                <i class="mdi mdi-email-multiple widget-icon"></i>
            </div>
            <h5 class="text-white fw-normal mt-0 mb-1" style="font-size: 0.8rem; opacity: 0.9;">Campañas Totales</h5>
            <h3 class="mt-1 mb-1 text-white" style="font-size: 1.4rem; font-weight: 600;" id="total-campaigns">0</h3>
            <p class="mb-0 text-white" style="font-size: 0.7rem; opacity: 0.8;">
                <span class="text-nowrap">Historial completo</span>
            </p>
        </div>
    </div>
</div>

<div class="col-md-3">
    <div class="card widget-flat gradient-10">
        <div class="card-body p-2">
            <div class="float-end">
                <i class="mdi mdi-send widget-icon"></i>
            </div>
            <h5 class="text-white fw-normal mt-0 mb-1" style="font-size: 0.8rem; opacity: 0.9;">Campañas Enviadas</h5>
            <h3 class="mt-1 mb-1 text-white" style="font-size: 1.4rem; font-weight: 600;" id="sent-campaigns">0</h3>
            <p class="mb-0 text-white" style="font-size: 0.7rem; opacity: 0.8;">
                <span class="text-nowrap">Este año</span>
            </p>
        </div>
    </div>
</div>
                                            
                                            <div class="text-center mt-3">
                                                <button class="btn btn-primary">
                                                    <i class="mdi mdi-plus-circle-outline me-1"></i> Cargar Nueva Plantilla
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
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
        </div>
        <!-- ============================================================== -->
        <!-- End Page content -->
        <!-- ============================================================== -->
    </div>
    <!-- END wrapper -->

    <!-- Modal Nuevo Correo -->
    <div class="modal fade" id="new-email-modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Nuevo Correo</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="new-email-form">
                        <div class="mb-3">
                            <label for="email-to" class="form-label">Para</label>
                            <select class="form-control select2" id="email-to" multiple data-toggle="select2">
                                <?php
                                // Conexión a la base de datos
                                require 'db_connect.php';
                                
                                // Consulta para obtener los contactos activos
                                $sql = "SELECT id, nombre, email FROM contactos WHERE activo = 1 ORDER BY nombre ASC";
                                $result = $conn->query($sql);
                                
                                if ($result->num_rows > 0) {
                                    while($row = $result->fetch_assoc()) {
                                        echo '<option value="'.$row["email"].'">'.$row["nombre"].' ('.$row["email"].')</option>';
                                    }
                                }
                                $conn->close();
                                ?>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label for="email-subject" class="form-label">Asunto</label>
                            <input type="text" class="form-control" id="email-subject" placeholder="Asunto del correo">
                        </div>
                        
                        <div class="mb-3">
                            <label for="email-message" class="form-label">Mensaje</label>
                            <textarea class="form-control" id="email-message" rows="8" placeholder="Escribe tu mensaje aquí..."></textarea>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Adjuntos</label>
                            <div class="dropzone" id="email-attachments">
                                <div class="dz-message needsclick">
                                    <i class="h1 text-muted mdi mdi-cloud-upload"></i>
                                    <h3>Arrastra archivos aquí o haz clic para subir.</h3>
                                    <span class="text-muted font-13">Puedes subir hasta 5 archivos (max 5MB cada uno)</span>
                                </div>
                            </div>
                            <div class="mt-2" id="attachment-previews"></div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" id="send-email-btn">
                        <i class="mdi mdi-send-outline me-1"></i> Enviar Correo
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <div class="modal fade" id="new-campaign-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Nueva Campaña</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="new-campaign-form">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="campaign-name" class="form-label">Nombre de la Campaña *</label>
                                <input type="text" class="form-control" id="campaign-name" placeholder="Ej: Promoción Verano 2025" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="campaign-subject" class="form-label">Asunto del Correo *</label>
                                <input type="text" class="form-control" id="campaign-subject" placeholder="Asunto que verán los destinatarios" required>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="campaign-group-type" class="form-label">Seleccionar Destinatarios *</label>
                        <select class="form-control" id="campaign-group-type" required>
                            <option value="">-- Seleccione un grupo --</option>
                            <option value="all">Todos los contactos</option>
                            <option value="active">Solo contactos activos</option>
                            <option value="inactive">Solo contactos inactivos</option>
                            <option value="size">Por tamaño de empresa</option>
                            <option value="saved">Grupo guardado</option>
                            <option value="custom">Selección personalizada</option>
                        </select>
                    </div>

                    <div class="row" id="dynamic-filters-container">
                        <!-- Aquí se cargarán los filtros dinámicos -->
                    </div>

                    <div class="mb-3">
                        <label for="campaign-message" class="form-label">Mensaje *</label>
                        <textarea class="form-control" id="campaign-message" rows="8" required></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Adjuntos</label>
                        <div class="dropzone" id="campaign-attachments">
                            <div class="dz-message needsclick">
                                <i class="h1 text-muted mdi mdi-cloud-upload"></i>
                                <h3>Arrastra archivos aquí o haz clic para subir</h3>
                                <span class="text-muted font-13">Tipos permitidos: PDF, DOC, XLS, JPG, PNG (Max. 5MB cada uno)</span>
                            </div>
                        </div>
                        <div class="mt-2" id="campaign-attachment-previews"></div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="campaign-schedule" class="form-label">Programación *</label>
                                <select class="form-control" id="campaign-schedule" required>
                                    <option value="now">Enviar ahora</option>
                                    <option value="schedule">Programar para más tarde</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6" id="schedule-date-container" style="display: none;">
                            <div class="mb-3">
                                <label for="schedule-date" class="form-label">Fecha y Hora de Envío *</label>
                                <input type="datetime-local" class="form-control" id="schedule-date">
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="save-as-group">
                            <label class="form-check-label" for="save-as-group">Guardar estos destinatarios como nuevo grupo</label>
                        </div>
                    </div>

                    <div class="mb-3" id="group-name-container" style="display: none;">
                        <label for="new-group-name" class="form-label">Nombre del Nuevo Grupo *</label>
                        <input type="text" class="form-control" id="new-group-name" placeholder="Nombre descriptivo para este grupo">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-secondary" id="save-draft-btn">
                    <i class="mdi mdi-content-save-outline me-1"></i> Guardar Borrador
                </button>
                <button type="button" class="btn btn-primary" id="send-campaign-btn">
                    <i class="mdi mdi-send-outline me-1"></i> Enviar Campaña
                </button>
            </div>
        </div>
    </div>
</div>

    <!-- Modal Editar Contacto -->
    <div class="modal fade" id="edit-contact-modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Editar Contacto</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="edit-contact-form">
                        <input type="hidden" id="edit-contact-id">
                        <div class="mb-3">
                            <label for="edit-contact-name" class="form-label">Nombre</label>
                            <input type="text" class="form-control" id="edit-contact-name" placeholder="Nombre completo">
                        </div>
                        
                        <div class="mb-3">
                            <label for="edit-contact-email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="edit-contact-email" placeholder="Correo electrónico">
                        </div>
                        
                        <div class="mb-3">
                            <label for="edit-contact-company" class="form-label">Empresa</label>
                            <input type="text" class="form-control" id="edit-contact-company" placeholder="Nombre de la empresa">
                        </div>
                        
                        <div class="mb-3">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="edit-contact-active">
                                <label class="form-check-label" for="edit-contact-active">Contacto Activo</label>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" id="save-contact-btn">
                        <i class="mdi mdi-content-save-outline me-1"></i> Guardar Cambios
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- bundle -->
    <script src="assets/js/vendor.min.js"></script>
    <script src="assets/js/app.min.js"></script>
    
    <!-- DataTables JS -->
    <script src="assets/js/vendor/jquery.dataTables.min.js"></script>
    <script src="assets/js/vendor/dataTables.bootstrap5.js"></script>
    <script src="assets/js/vendor/dataTables.responsive.min.js"></script>
    <script src="assets/js/vendor/responsive.bootstrap5.min.js"></script>
    
    <!-- Select2 JS -->
    <script src="assets/js/vendor/select2.min.js"></script>
    
    <!-- Dropzone JS -->
    <script src="assets/js/vendor/dropzone.min.js"></script>
    
    <!-- TinyMCE JS -->
    <script src="assets/js/vendor/tinymce.min.js"></script>
    <script>
    $(document).ready(function() {
        // Inicializar DataTable
        $('#contacts-datatable').DataTable({
            responsive: true,
            language: {
                url: 'assets/js/vendor/dataTables.spanish.json'
            },
            dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>rt<"row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
            drawCallback: function() {
                $('.dataTables_paginate > .pagination').addClass('pagination-rounded');
            }
        });
        
        // Inicializar Select2
        // Inicializar Dropzone para adjuntos de correo
        Dropzone.autoDiscover = false;
        
        // Configuración para adjuntos de correo normal
        const emailDropzone = new Dropzone("#email-attachments", {
            url: "/upload-email-attachment",
            paramName: "file",
            maxFilesize: 5, // MB
            maxFiles: 5,
            addRemoveLinks: true,
            acceptedFiles: "image/*,.pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.txt",
            dictDefaultMessage: "Arrastra archivos aquí para adjuntar",
            dictFallbackMessage: "Tu navegador no soporta arrastrar y soltar para subir archivos.",
            dictFileTooBig: "El archivo es demasiado grande ({{filesize}}MB). Tamaño máximo: {{maxFilesize}}MB.",
            dictInvalidFileType: "No puedes subir archivos de este tipo.",
            dictResponseError: "El servidor respondió con código {{statusCode}}.",
            dictCancelUpload: "Cancelar subida",
            dictUploadCanceled: "Subida cancelada",
            dictRemoveFile: "Eliminar archivo",
            dictMaxFilesExceeded: "No puedes subir más archivos.",
            init: function() {
                this.on("success", function(file, response) {
                    file.previewElement.classList.add("dz-success");
                    file._id = response.id; // Guardar ID del archivo en el servidor
                });
                this.on("removedfile", function(file) {
                    // Eliminar archivo del servidor si ya se subió
                    if (file._id) {
                        $.ajax({
                            url: '/delete-email-attachment',
                            type: 'DELETE',
                            data: { id: file._id },
                            dataType: 'json'
                        });
                    }
                });
            }
        });

        // Configuración para adjuntos de campaña
        const campaignDropzone = new Dropzone("#campaign-attachments", {
            url: "/upload-campaign-attachment",
            paramName: "file",
            maxFilesize: 5, // MB
            maxFiles: 5,
            addRemoveLinks: true,
            acceptedFiles: "image/*,.pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.txt",
            dictDefaultMessage: "Arrastra archivos aquí para adjuntar",
            dictFallbackMessage: "Tu navegador no soporta arrastrar y soltar para subir archivos.",
            dictFileTooBig: "El archivo es demasiado grande ({{filesize}}MB). Tamaño máximo: {{maxFilesize}}MB.",
            dictInvalidFileType: "No puedes subir archivos de este tipo.",
            dictResponseError: "El servidor respondió con código {{statusCode}}.",
            dictCancelUpload: "Cancelar subida",
            dictUploadCanceled: "Subida cancelada",
            dictRemoveFile: "Eliminar archivo",
            dictMaxFilesExceeded: "No puedes subir más archivos.",
            init: function() {
                this.on("success", function(file, response) {
                    file.previewElement.classList.add("dz-success");
                    file._id = response.id; // Guardar ID del archivo en el servidor
                });
                this.on("removedfile", function(file) {
                    // Eliminar archivo del servidor si ya se subió
                    if (file._id) {
                        $.ajax({
                            url: '/delete-campaign-attachment',
                            type: 'DELETE',
                            data: { id: file._id },
                            dataType: 'json'
                        });
                    }
                });
            }
        });

        // Inicializar editor de texto enriquecido
        tinymce.init({
            selector: '#campaign-message',
            height: 300,
            menubar: false,
            plugins: [
                'advlist autolink lists link image charmap print preview anchor',
                'searchreplace visualblocks code fullscreen',
                'insertdatetime media table paste code help wordcount'
            ],
            toolbar: 'undo redo | formatselect | bold italic backcolor | ' +
                'alignleft aligncenter alignright alignjustify | ' +
                'bullist numlist outdent indent | removeformat | help',
            content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:14px }'
        });

        // Mostrar/ocultar campo de fecha según selección
        $('#campaign-schedule').change(function() {
            if ($(this).val() === 'schedule') {
                $('#schedule-date-container').show();
            } else {
                $('#schedule-date-container').hide();
            }
        });

        // Mostrar/ocultar selección personalizada de contactos
        $('#campaign-group').change(function() {
            if ($(this).val() === 'custom') {
                $('#custom-recipients-container').show();
            } else {
                $('#custom-recipients-container').hide();
            }
        });

        // Seleccionar/deseleccionar todos los contactos
        $('#select-all').change(function() {
            $('.contact-checkbox').prop('checked', $(this).prop('checked'));
        });

        // Activar contactos seleccionados
        $('#activate-selected').click(function(e) {
            e.preventDefault();
            const selectedIds = $('.contact-checkbox:checked').map(function() {
                return $(this).val();
            }).get();
            
            if (selectedIds.length === 0) {
                alert('Por favor selecciona al menos un contacto');
                return;
            }
            
            if (confirm(`¿Estás seguro de que quieres activar ${selectedIds.length} contactos?`)) {
                $.ajax({
                    url: '/activate-contacts',
                    type: 'POST',
                    data: { ids: selectedIds },
                    success: function(response) {
                        alert('Contactos activados correctamente');
                        location.reload();
                    },
                    error: function() {
                        alert('Error al activar contactos');
                    }
                });
            }
        });

        // Desactivar contactos seleccionados
        $('#deactivate-selected').click(function(e) {
            e.preventDefault();
            const selectedIds = $('.contact-checkbox:checked').map(function() {
                return $(this).val();
            }).get();
            
            if (selectedIds.length === 0) {
                alert('Por favor selecciona al menos un contacto');
                return;
            }
            
            if (confirm(`¿Estás seguro de que quieres desactivar ${selectedIds.length} contactos?`)) {
                $.ajax({
                    url: '/deactivate-contacts',
                    type: 'POST',
                    data: { ids: selectedIds },
                    success: function(response) {
                        alert('Contactos desactivados correctamente');
                        location.reload();
                    },
                    error: function() {
                        alert('Error al desactivar contactos');
                    }
                });
            }
        });

        // Exportar contactos seleccionados
        $('#export-selected').click(function(e) {
            e.preventDefault();
            const selectedIds = $('.contact-checkbox:checked').map(function() {
                return $(this).val();
            }).get();
            
            if (selectedIds.length === 0) {
                alert('Por favor selecciona al menos un contacto');
                return;
            }
            
            // Crear formulario temporal para enviar los IDs
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '/export-contacts';
            
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'ids';
            input.value = JSON.stringify(selectedIds);
            
            form.appendChild(input);
            document.body.appendChild(form);
            form.submit();
            document.body.removeChild(form);
        });

        // Editar contacto
        $(document).on('click', '.edit-contact', function() {
            const contactId = $(this).data('id');
            
            $.ajax({
                url: '/get-contact/' + contactId,
                type: 'GET',
                success: function(response) {
                    $('#edit-contact-id').val(response.id);
                    $('#edit-contact-name').val(response.nombre);
                    $('#edit-contact-email').val(response.email);
                    $('#edit-contact-company').val(response.empresa);
                    $('#edit-contact-active').prop('checked', response.activo == 1);
                    
                    $('#edit-contact-modal').modal('show');
                },
                error: function() {
                    alert('Error al cargar los datos del contacto');
                }
            });
        });

        // Guardar cambios en contacto
        $('#save-contact-btn').click(function() {
            const contactId = $('#edit-contact-id').val();
            const contactData = {
                nombre: $('#edit-contact-name').val(),
                email: $('#edit-contact-email').val(),
                empresa: $('#edit-contact-company').val(),
                activo: $('#edit-contact-active').is(':checked') ? 1 : 0
            };
            
            $.ajax({
                url: '/update-contact/' + contactId,
                type: 'PUT',
                data: contactData,
                success: function(response) {
                    alert('Contacto actualizado correctamente');
                    $('#edit-contact-modal').modal('hide');
                    location.reload();
                },
                error: function() {
                    alert('Error al actualizar el contacto');
                }
            });
        });

        // Eliminar contacto
        $(document).on('click', '.delete-contact', function() {
            const contactId = $(this).data('id');
            
            if (confirm('¿Estás seguro de que quieres eliminar este contacto?')) {
                $.ajax({
                    url: '/delete-contact/' + contactId,
                    type: 'DELETE',
                    success: function(response) {
                        alert('Contacto eliminado correctamente');
                        location.reload();
                    },
                    error: function() {
                        alert('Error al eliminar el contacto');
                    }
                });
            }
        });

        // Enviar correo
        $('#send-email-btn').click(function() {
            const to = $('#email-to').val();
            const subject = $('#email-subject').val();
            const message = $('#email-message').val();
            const attachments = emailDropzone.getAcceptedFiles().map(file => file._id);
            
            if (!to || to.length === 0) {
                alert('Por favor selecciona al menos un destinatario');
                return;
            }
            
            if (!subject) {
                alert('Por favor ingresa un asunto');
                return;
            }
            
            if (!message) {
                alert('Por favor escribe un mensaje');
                return;
            }
            
            const emailData = {
                to: to,
                subject: subject,
                message: message,
                attachments: attachments
            };
            
            $.ajax({
                url: '/send-email',
                type: 'POST',
                data: emailData,
                success: function(response) {
                    alert('Correo enviado correctamente');
                    $('#new-email-modal').modal('hide');
                    // Limpiar formulario
                    $('#email-to').val(null).trigger('change');
                    $('#email-subject').val('');
                    $('#email-message').val('');
                    emailDropzone.removeAllFiles(true);
                },
                error: function() {
                    alert('Error al enviar el correo');
                }
            });
        });

        // Enviar campaña
        $('#send-campaign-btn').click(function() {
            const name = $('#campaign-name').val();
            const subject = $('#campaign-subject').val();
            const group = $('#campaign-group').val();
            const customRecipients = $('#custom-recipients').val();
            const message = tinymce.get('campaign-message').getContent();
            const attachments = campaignDropzone.getAcceptedFiles().map(file => file._id);
            const scheduleType = $('#campaign-schedule').val();
            const scheduleDate = $('#schedule-date').val();
            
            if (!name) {
                alert('Por favor ingresa un nombre para la campaña');
                return;
            }
            
            if (!subject) {
                alert('Por favor ingresa un asunto');
                return;
            }
            
            if (group === 'custom' && (!customRecipients || customRecipients.length === 0)) {
                alert('Por favor selecciona al menos un destinatario');
                return;
            }
            
            if (!message) {
                alert('Por favor escribe un mensaje');
                return;
            }
            
            if (scheduleType === 'schedule' && !scheduleDate) {
                alert('Por favor selecciona una fecha de envío');
                return;
            }
            
            const campaignData = {
                name: name,
                subject: subject,
                group: group,
                custom_recipients: customRecipients,
                message: message,
                attachments: attachments,
                schedule_type: scheduleType,
                schedule_date: scheduleDate
            };
            
            $.ajax({
                url: '/create-campaign',
                type: 'POST',
                data: campaignData,
                success: function(response) {
                    alert('Campaña creada correctamente');
                    $('#new-campaign-modal').modal('hide');
                    // Limpiar formulario
                    $('#campaign-name').val('');
                    $('#campaign-subject').val('');
                    $('#campaign-group').val('all');
                    $('#custom-recipients').val(null).trigger('change');
                    tinymce.get('campaign-message').setContent('');
                    campaignDropzone.removeAllFiles(true);
                    $('#campaign-schedule').val('now');
                    $('#schedule-date-container').hide();
                    
                    // Recargar pestaña de campañas
                    $('.nav-tabs a[href="#campaigns-tab"]').tab('show');
                    location.reload();
                },
                error: function() {
                    alert('Error al crear la campaña');
                }
            });
        });

        // Guardar borrador de campaña
        $('#save-draft-btn').click(function() {
            const name = $('#campaign-name').val();
            const subject = $('#campaign-subject').val();
            const group = $('#campaign-group').val();
            const customRecipients = $('#custom-recipients').val();
            const message = tinymce.get('campaign-message').getContent();
            const attachments = campaignDropzone.getAcceptedFiles().map(file => file._id);
            
            if (!name) {
                alert('Por favor ingresa un nombre para la campaña');
                return;
            }
            
            const campaignData = {
                name: name,
                subject: subject,
                group: group,
                custom_recipients: customRecipients,
                message: message,
                attachments: attachments,
                status: 'draft'
            };
            
            $.ajax({
                url: '/save-draft-campaign',
                type: 'POST',
                data: campaignData,
                success: function(response) {
                    alert('Borrador guardado correctamente');
                    $('#new-campaign-modal').modal('hide');
                    // Limpiar formulario
                    $('#campaign-name').val('');
                    $('#campaign-subject').val('');
                    $('#campaign-group').val('all');
                    $('#custom-recipients').val(null).trigger('change');
                    tinymce.get('campaign-message').setContent('');
                    campaignDropzone.removeAllFiles(true);
                    
                    // Recargar pestaña de campañas
                    $('.nav-tabs a[href="#campaigns-tab"]').tab('show');
                    location.reload();
                },
                error: function() {
                    alert('Error al guardar el borrador');
                }
            });
        });

        // Ver detalles de campaña
        $(document).on('click', '.view-campaign', function() {
            const campaignId = $(this).data('id');
            window.location.href = '/campaign-details/' + campaignId;
        });

        // Reenviar campaña
        $(document).on('click', '.resend-campaign', function() {
            const campaignId = $(this).data('id');
            
            if (confirm('¿Estás seguro de que quieres reenviar esta campaña?')) {
                $.ajax({
                    url: '/resend-campaign/' + campaignId,
                    type: 'POST',
                    success: function(response) {
                        alert('Campaña reenviada correctamente');
                        location.reload();
                    },
                    error: function() {
                        alert('Error al reenviar la campaña');
                    }
                });
            }
        });
// Obtener estadísticas de campañas
$campaignStats = [];
$sql = "SELECT 
        COUNT(*) as total,
        SUM(CASE WHEN estado = 'Enviada' THEN 1 ELSE 0 END) as sent,
        SUM(CASE WHEN estado = 'Programada' THEN 1 ELSE 0 END) as scheduled,
        SUM(CASE WHEN estado = 'Borrador' THEN 1 ELSE 0 END) as drafts,
        SUM(destinatarios) as total_recipients
        FROM campañas";
$result = $conn->query($sql);
$campaignStats = $result->fetch_assoc();
        // Ver estadísticas de campaña
        $(document).on('click', '.stats-campaign', function() {
            const campaignId = $(this).data('id');
            window.location.href = '/campaign-stats/' + campaignId;
        });

        // Cargar contador de correos no leídos
        function loadUnreadCount() {
            $.ajax({
                url: '/get-unread-count',
                type: 'GET',
                success: function(response) {
                    $('#unread-count').text(response.count);
                }
            });
        }

        // Cargar inicialmente y cada 5 minutos
        loadUnreadCount();
        setInterval(loadUnreadCount, 300000);
        // Mostrar/ocultar campos según tipo de grupo seleccionado
$('#campaign-group-type').change(function() {
    const type = $(this).val();
    
    // Ocultar todos los contenedores primero
    $('#size-group-container').hide();
    $('#saved-group-container').hide();
    $('#custom-recipients-container').hide();
    
    // Mostrar el contenedor adecuado
    if (type === 'size') {
        $('#size-group-container').show();
    } else if (type === 'saved') {
        $('#saved-group-container').show();
    } else if (type === 'custom') {
        $('#custom-recipients-container').show();
    }
    
    // Mostrar opción para guardar grupo solo para selección personalizada
    if (type === 'custom') {
        $('#save-as-group').parent().show();
    } else {
        $('#save-as-group').parent().hide();
        $('#group-name-container').hide();
    }
});

// Mostrar campo de nombre de grupo cuando se marca la opción
$('#save-as-group').change(function() {
    if ($(this).is(':checked')) {
        $('#group-name-container').show();
    } else {
        $('#group-name-container').hide();
    }
});

// Función para enviar campaña actualizada
$('#send-campaign-btn').click(function() {
    const name = $('#campaign-name').val();
    const subject = $('#campaign-subject').val();
    const groupType = $('#campaign-group-type').val();
    const message = tinymce.get('campaign-message').getContent();
    const attachments = campaignDropzone.getAcceptedFiles().map(file => file._id);
    const scheduleType = $('#campaign-schedule').val();
    const scheduleDate = $('#schedule-date').val();
    const saveAsGroup = $('#save-as-group').is(':checked');
    const newGroupName = $('#new-group-name').val();
    
    // Validaciones
    if (!name) {
        alert('Por favor ingresa un nombre para la campaña');
        return;
    }
    
    if (!subject) {
        alert('Por favor ingresa un asunto');
        return;
    }
    
    if (!message) {
        alert('Por favor escribe un mensaje');
        return;
    }
    
    if (scheduleType === 'schedule' && !scheduleDate) {
        alert('Por favor selecciona una fecha de envío');
        return;
    }
    
    if (saveAsGroup && !newGroupName) {
        alert('Por favor ingresa un nombre para el nuevo grupo');
        return;
    }
    
    // Obtener destinatarios según el tipo de grupo
    let recipients = [];
    let groupFilter = {};
    
    switch(groupType) {
        case 'all':
            groupFilter = { type: 'all' };
            break;
        case 'active':
            groupFilter = { type: 'status', value: 'Activo' };
            break;
        case 'inactive':
            groupFilter = { type: 'status', value: 'Inactivo' };
            break;
        case 'size':
            const size = $('#company-size').val();
            groupFilter = { type: 'size', value: size };
            break;
        case 'saved':
            const groupId = $('#saved-group').val();
            groupFilter = { type: 'saved', value: groupId };
            break;
        case 'custom':
            recipients = $('#custom-recipients').val();
            groupFilter = { type: 'custom', value: recipients };
            break;
    }
    
    const campaignData = {
        name: name,
        subject: subject,
        group_filter: groupFilter,
        message: message,
        attachments: attachments,
        schedule_type: scheduleType,
        schedule_date: scheduleDate,
        save_as_group: saveAsGroup,
        new_group_name: newGroupName
    };
    
    $.ajax({
        url: '/create-campaign',
        type: 'POST',
        data: campaignData,
        success: function(response) {
            alert('Campaña creada correctamente');
            $('#new-campaign-modal').modal('hide');
            // Limpiar formulario
            $('#campaign-name').val('');
            $('#campaign-subject').val('');
            $('#campaign-group-type').val('all');
            $('#custom-recipients').val(null).trigger('change');
            tinymce.get('campaign-message').setContent('');
            campaignDropzone.removeAllFiles(true);
            $('#campaign-schedule').val('now');
            $('#schedule-date-container').hide();
            $('#save-as-group').prop('checked', false);
            $('#group-name-container').hide();
            $('#new-group-name').val('');
            
            // Recargar pestaña de campañas
            $('.nav-tabs a[href="#campaigns-tab"]').tab('show');
            location.reload();
        },
        error: function() {
            alert('Error al crear la campaña');
        }
    });
});
$(document).ready(function() {
    // Inicializar editor de texto enriquecido
    tinymce.init({
        selector: '#campaign-message',
        height: 300,
        menubar: false,
        plugins: 'advlist autolink lists link image charmap print preview anchor searchreplace visualblocks code fullscreen insertdatetime media table paste code help wordcount',
        toolbar: 'undo redo | formatselect | bold italic backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | help',
        content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:14px }'
    });

    // Inicializar Dropzone para adjuntos
    Dropzone.autoDiscover = false;
    const campaignDropzone = new Dropzone("#campaign-attachments", {
        url: "/upload-campaign-attachment",
        paramName: "file",
        maxFilesize: 5, // MB
        maxFiles: 5,
        addRemoveLinks: true,
        acceptedFiles: "image/*,.pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.txt",
        dictDefaultMessage: "Arrastra archivos aquí para adjuntar",
        dictFallbackMessage: "Tu navegador no soporta arrastrar y soltar para subir archivos.",
        dictFileTooBig: "El archivo es demasiado grande ({{filesize}}MB). Tamaño máximo: {{maxFilesize}}MB.",
        dictInvalidFileType: "No puedes subir archivos de este tipo.",
        dictResponseError: "El servidor respondió con código {{statusCode}}.",
        dictCancelUpload: "Cancelar subida",
        dictUploadCanceled: "Subida cancelada",
        dictRemoveFile: "Eliminar archivo",
        dictMaxFilesExceeded: "No puedes subir más archivos.",
        init: function() {
            this.on("success", function(file, response) {
                file.previewElement.classList.add("dz-success");
                file._id = response.id; // Guardar ID del archivo en el servidor
            });
            this.on("removedfile", function(file) {
                if (file._id) {
                    $.ajax({
                        url: '/delete-campaign-attachment/' + file._id,
                        type: 'DELETE'
                    });
                }
            });
        }
    });

    // Mostrar/ocultar fecha según programación
    $('#campaign-schedule').change(function() {
        if ($(this).val() === 'schedule') {
            $('#schedule-date-container').show();
            // Establecer fecha mínima como ahora + 1 hora
            const minDate = new Date();
            minDate.setHours(minDate.getHours() + 1);
            $('#schedule-date').attr('min', minDate.toISOString().slice(0, 16));
        } else {
            $('#schedule-date-container').hide();
        }
    });

    // Mostrar/ocultar campos según tipo de grupo seleccionado
    $('#campaign-group-type').change(function() {
        const type = $(this).val();
        $('#dynamic-filters-container').empty();
        
        let html = '';
        switch(type) {
            case 'size':
                html = `
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="company-size" class="form-label">Tamaño de Empresa *</label>
                        <select class="form-control" id="company-size" required>
                            <option value="">-- Seleccione --</option>
                            <option value="Grande">Grande</option>
                            <option value="Mediana">Mediana</option>
                            <option value="Pequeña">Pequeña</option>
                            <option value="Micro">Micro</option>
                        </select>
                    </div>
                </div>`;
                break;
                
            case 'saved':
                $.get('/api/groups', function(groups) {
                    html = `
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="saved-group" class="form-label">Grupo Guardado *</label>
                            <select class="form-control" id="saved-group" required>
                                <option value="">-- Seleccione --</option>`;
                    
                    groups.forEach(group => {
                        html += `<option value="${group.id}">${group.nombre_grupo}</option>`;
                    });
                    
                    html += `</select></div></div>`;
                    $('#dynamic-filters-container').html(html);
                });
                return;
                
            case 'custom':
                html = `
                <div class="col-md-12">
                    <div class="mb-3">
                        <label for="custom-recipients" class="form-label">Seleccionar Contactos *</label>
                        <select class="form-control select2-multiple" id="custom-recipients" multiple="multiple" required>
                            ${loadContactsAsOptions()}
                        </select>
                    </div>
                </div>`;
                break;
        }
        
        $('#dynamic-filters-container').html(html);
        
        // Mostrar opción para guardar grupo solo para selección personalizada
        if (type === 'custom') {
            $('#save-as-group').parent().show();
        } else {
            $('#save-as-group').prop('checked', false);
            $('#save-as-group').parent().hide();
            $('#group-name-container').hide();
        }
        
        // Inicializar Select2 si es necesario
        if ($('.select2-multiple').length > 0) {
            $('.select2-multiple').select2({
                placeholder: "Seleccione contactos",
                width: '100%'
            });
        }
    });

    // Mostrar campo de nombre de grupo cuando se marca la opción
    $('#save-as-group').change(function() {
        $('#group-name-container').toggle(this.checked);
    });

    // Función para cargar contactos como opciones
    function loadContactsAsOptions() {
        let options = '';
        $.ajax({
            url: '/api/contacts',
            type: 'GET',
            async: false,
            success: function(contacts) {
                contacts.forEach(contact => {
                    options += `<option value="${contact.cod_cliente}">${contact.nombre_cliente} (${contact.correo || 'sin email'})</option>`;
                });
            }
        });
        return options;
    }

    // Validar formulario antes de enviar
    function validateCampaignForm() {
        const name = $('#campaign-name').val();
        const subject = $('#campaign-subject').val();
        const groupType = $('#campaign-group-type').val();
        const message = tinymce.get('campaign-message').getContent();
        const scheduleType = $('#campaign-schedule').val();
        const scheduleDate = $('#schedule-date').val();
        const saveAsGroup = $('#save-as-group').is(':checked');
        const newGroupName = $('#new-group-name').val();

        if (!name) {
            alert('Por favor ingresa un nombre para la campaña');
            return false;
        }

        if (!subject) {
            alert('Por favor ingresa un asunto');
            return false;
        }

        if (!groupType) {
            alert('Por favor selecciona un tipo de destinatarios');
            return false;
        }

        // Validar filtros específicos
        if (groupType === 'size' && !$('#company-size').val()) {
            alert('Por favor selecciona un tamaño de empresa');
            return false;
        }

        if (groupType === 'saved' && !$('#saved-group').val()) {
            alert('Por favor selecciona un grupo guardado');
            return false;
        }

        if (groupType === 'custom' && $('#custom-recipients').val().length === 0) {
            alert('Por favor selecciona al menos un contacto');
            return false;
        }

        if (!message) {
            alert('Por favor escribe un mensaje');
            return false;
        }

        if (scheduleType === 'schedule' && !scheduleDate) {
            alert('Por favor selecciona una fecha de envío');
            return false;
        }

        if (saveAsGroup && !newGroupName) {
            alert('Por favor ingresa un nombre para el nuevo grupo');
            return false;
        }

        return true;
    }

    // Enviar campaña
    $('#send-campaign-btn').click(function() {
        if (!validateCampaignForm()) return;

        const formData = new FormData();
        formData.append('name', $('#campaign-name').val());
        formData.append('subject', $('#campaign-subject').val());
        formData.append('group_type', $('#campaign-group-type').val());
        formData.append('message', tinymce.get('campaign-message').getContent());
        formData.append('schedule_type', $('#campaign-schedule').val());
        
        // Agregar filtros específicos
        const groupType = $('#campaign-group-type').val();
        if (groupType === 'size') {
            formData.append('size_filter', $('#company-size').val());
        } else if (groupType === 'saved') {
            formData.append('saved_group_id', $('#saved-group').val());
        } else if (groupType === 'custom') {
            formData.append('custom_recipients', JSON.stringify($('#custom-recipients').val()));
        }

        // Agregar programación
        if ($('#campaign-schedule').val() === 'schedule') {
            formData.append('schedule_date', $('#schedule-date').val());
        }

        // Agregar opción de guardar grupo
        if ($('#save-as-group').is(':checked')) {
            formData.append('save_as_group', 'true');
            formData.append('new_group_name', $('#new-group-name').val());
        }

        // Agregar archivos adjuntos
        campaignDropzone.getAcceptedFiles().forEach((file, index) => {
            formData.append(`attachments[${index}]`, file);
        });

        // Mostrar carga
        $('#send-campaign-btn').prop('disabled', true).html('<i class="mdi mdi-loading mdi-spin me-1"></i> Enviando...');

        $.ajax({
            url: '/api/campaigns',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                alert(response.message);
                $('#new-campaign-modal').modal('hide');
                resetCampaignForm();
                // Recargar la tabla de campañas
                loadCampaignsTable();
            },
            error: function(xhr) {
                alert('Error: ' + (xhr.responseJSON?.message || 'Error al enviar la campaña'));
            },
            complete: function() {
                $('#send-campaign-btn').prop('disabled', false).html('<i class="mdi mdi-send-outline me-1"></i> Enviar Campaña');
            }
        });
    });

    // Guardar borrador
    $('#save-draft-btn').click(function() {
        if (!validateCampaignForm()) return;

        const formData = new FormData();
        formData.append('name', $('#campaign-name').val());
        formData.append('subject', $('#campaign-subject').val());
        formData.append('group_type', $('#campaign-group-type').val());
        formData.append('message', tinymce.get('campaign-message').getContent());
        formData.append('status', 'draft');
        
        // Agregar filtros específicos
        const groupType = $('#campaign-group-type').val();
        if (groupType === 'size') {
            formData.append('size_filter', $('#company-size').val());
        } else if (groupType === 'saved') {
            formData.append('saved_group_id', $('#saved-group').val());
        } else if (groupType === 'custom') {
            formData.append('custom_recipients', JSON.stringify($('#custom-recipients').val()));
        }

        // Agregar opción de guardar grupo
        if ($('#save-as-group').is(':checked')) {
            formData.append('save_as_group', 'true');
            formData.append('new_group_name', $('#new-group-name').val());
        }

        // Agregar archivos adjuntos
        campaignDropzone.getAcceptedFiles().forEach((file, index) => {
            formData.append(`attachments[${index}]`, file);
        });

        // Mostrar carga
        $('#save-draft-btn').prop('disabled', true).html('<i class="mdi mdi-loading mdi-spin me-1"></i> Guardando...');

        $.ajax({
            url: '/api/campaigns',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                alert(response.message);
                $('#new-campaign-modal').modal('hide');
                resetCampaignForm();
                // Recargar la tabla de campañas
                loadCampaignsTable();
            },
            error: function(xhr) {
                alert('Error: ' + (xhr.responseJSON?.message || 'Error al guardar el borrador'));
            },
            complete: function() {
                $('#save-draft-btn').prop('disabled', false).html('<i class="mdi mdi-content-save-outline me-1"></i> Guardar Borrador');
            }
        });
    });

    // Resetear formulario
    function resetCampaignForm() {
        $('#new-campaign-form')[0].reset();
        tinymce.get('campaign-message').setContent('');
        campaignDropzone.removeAllFiles(true);
        $('#dynamic-filters-container').empty();
        $('#save-as-group').prop('checked', false);
        $('#group-name-container').hide();
        $('#schedule-date-container').hide();
    }

    // Cargar tabla de campañas
    function loadCampaignsTable() {
        $.get('/api/campaigns', function(campaigns) {
            const tbody = $('#campaigns-table tbody');
            tbody.empty();
            
            campaigns.forEach(campaign => {
                let statusBadge = '';
                switch(campaign.estado) {
                    case 'Enviada': statusBadge = 'badge bg-success'; break;
                    case 'Programada': statusBadge = 'badge bg-warning'; break;
                    case 'Borrador': statusBadge = 'badge bg-secondary'; break;
                    default: statusBadge = 'badge bg-info';
                }
                
                tbody.append(`
                    <tr>
                        <td>${campaign.id}</td>
                        <td>${campaign.nombre}</td>
                        <td>${campaign.asunto}</td>
                        <td>${campaign.destinatarios}</td>
                        <td><span class="${statusBadge}">${campaign.estado}</span></td>
                        <td>${new Date(campaign.fecha_creacion).toLocaleDateString()}</td>
                        <td>
                            <button class="btn btn-sm btn-outline-primary view-campaign" data-id="${campaign.id}">
                                <i class="mdi mdi-eye-outline"></i>
                            </button>
                            ${campaign.estado === 'Borrador' ? `
                            <button class="btn btn-sm btn-outline-success send-campaign" data-id="${campaign.id}">
                                <i class="mdi mdi-send-outline"></i>
                            </button>
                            ` : ''}
                            <button class="btn btn-sm btn-outline-danger delete-campaign" data-id="${campaign.id}">
                                <i class="mdi mdi-delete-outline"></i>
                            </button>
                        </td>
                    </tr>
                `);
            });
        });
    }
});
function loadDashboardData() {
    fetch('get_dashboard_data.php')
        .then(response => response.json())
        .then(data => {
            // ... tus otras actualizaciones ...
            
            // Actualizar estadísticas de campañas
            document.getElementById('total-campaigns').textContent = data.campaign_stats.total;
            document.getElementById('sent-campaigns').textContent = data.campaign_stats.sent;
            document.getElementById('scheduled-campaigns').textContent = data.campaign_stats.scheduled;
            document.getElementById('email-recipients').textContent = data.campaign_stats.total_recipients;
        })
        .catch(error => {
            console.error('Error:', error);
        });
}
    });
    </script>
</body>
</html>        