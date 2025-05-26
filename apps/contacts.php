<?php
header('Content-Type: application/json');
require '../db_connect.php';

$stmt = $conn->prepare("SELECT cod_cliente, nombre_cliente, correo FROM todos_los_clientes WHERE correo IS NOT NULL AND correo != '' ORDER BY nombre_cliente ASC");
$stmt->execute();
$contacts = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

echo json_encode($contacts);
$conn->close();
?>