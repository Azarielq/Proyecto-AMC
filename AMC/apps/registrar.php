<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../login.php");
    exit();
}
require '../db_connect.php';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8" />
    <title>Registrar Campaña</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="CRM para gestión de campañas" name="description" />
    <meta content="Coderthemes" name="author" />
    <link rel="shortcut icon" href="../assets/images/favicon.ico">
    
    <link href="../assets/css/icons.min.css" rel="stylesheet" type="text/css" />
    <link href="../assets/css/app.min.css" rel="stylesheet" type="text/css" />
    <link href="../assets/css/vendor/select2.min.css" rel="stylesheet" type="text/css" />
    
    <script src="https://cdn.jsdelivr.net/npm/jquery/dist/jquery.min.js"></script>
    <script src="../assets/js/vendor/select2.min.js"></script>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <h4 class="page-title">Registrar Nueva Campaña</h4>
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <form id="createCampaignForm" method="POST" action="campañas.php">
                            <input type="hidden" name="action" value="crear">
                            <div class="mb-3">
                                <label for="nombre" class="form-label">Nombre</label>
                                <input type="text" class="form-control" id="nombre" name="nombre" required>
                            </div>
                            <div class="mb-3">
                                <label for="asunto" class="form-label">Asunto</label>
                                <input type="text" class="form-control" id="asunto" name="asunto" required>
                            </div>
                            <div class="mb-3">
                                <label for="mensaje" class="form-label">Mensaje</label>
                                <textarea class="form-control" id="mensaje" name="mensaje" rows="5" required></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="tipo_grupo" class="form-label">Grupo</label>
                                <select class="form-select" id="tipo_grupo" name="tipo_grupo">
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
                                <label for="destinatarios" class="form-label">Destinatarios</label>
                                <select class="form-select" id="destinatarios" name="destinatarios[]" multiple required>
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
                            <button type="submit" class="btn btn-primary">Crear Campaña</button>
                            <a href="campañas.php" class="btn btn-secondary">Cancelar</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="../assets/js/app.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#destinatarios').select2();
        });
    </script>
</body>
</html>