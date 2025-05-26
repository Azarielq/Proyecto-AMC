<?php
header('Content-Type: application/json');
require '../db_connect.php';

$stmt = $conn->prepare("SELECT id, nombre_grupo FROM grupos_correo ORDER BY nombre_grupo ASC");
$stmt->execute();
$groups = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

echo json_encode($groups);
$conn->close();
?>