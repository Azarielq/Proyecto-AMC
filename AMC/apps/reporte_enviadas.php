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
    <title>Reporte de Campañas Enviadas</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="CRM para gestión de campañas" name="description" />
    <meta content="Coderthemes" name="author" />
    <link rel="shortcut icon" href="../assets/images/favicon.ico">
    
    <link href="../assets/css/icons.min.css" rel="stylesheet" type="text/css" />
    <link href="../assets/css/app.min.css" rel="stylesheet" type="text/css" />
    <link href="../assets/css/vendor/dataTables.bootstrap5.css" rel="stylesheet" type="text/css" />
    
    <script src="https://cdn.jsdelivr.net/npm/jquery/dist/jquery.min.js"></script>
    <script src="../assets/js/vendor/jquery.dataTables.min.js"></script>
    <script src="../assets/js/vendor/dataTables.bootstrap5.js"></script>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <h4 class="page-title">Reporte de Campañas Enviadas</h4>
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <table id="reporteTable" class="table table-striped table-bordered dt-responsive nowrap">
                            <thead>
                                <tr>
                                    <th>Campaña</th>
                                    <th>Asunto</th>
                                    <th>Destinatarios</th>
                                    <th>Enviados</th>
                                    <th>Fecha Envío</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $sql = "SELECT c.nombre, c.asunto, c.destinatarios, 
                                       COUNT(cd.id) as enviados, c.fecha_envio, c.estado
                                       FROM campañas c
                                       LEFT JOIN campaña_destinatarios cd ON c.id = cd.campaña_id AND cd.enviado = 1
                                       WHERE c.estado = 'Enviada'
                                       GROUP BY c.id
                                       ORDER BY c.fecha_envio DESC";
                                $result = $conn->query($sql);
                                while ($row = $result->fetch_assoc()):
                                ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['nombre']); ?></td>
                                    <td><?php echo htmlspecialchars($row['asunto']); ?></td>
                                    <td><?php echo $row['destinatarios']; ?></td>
                                    <td><?php echo $row['enviados']; ?></td>
                                    <td><?php echo date('d/m/Y H:i', strtotime($row['fecha_envio'])); ?></td>
                                    <td><span class="badge bg-success"><?php echo $row['estado']; ?></span></td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="../assets/js/app.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#reporteTable').DataTable({
                responsive: true,
                dom: 'Bfrtip',
                buttons: ['copy', 'excel', 'pdf', 'print'],
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.10.24/i18n/Spanish.json'
                }
            });
        });
    </script>
</body>
</html>