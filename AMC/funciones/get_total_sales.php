<?php
require_once '../db_connect.php';

// Consulta para el total
$sql = "SELECT COUNT(*) AS total FROM empresas_concretadas WHERE MONTH(fecha) = MONTH(CURRENT_DATE())";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    echo $row["total"];
} else {
    echo "0";
}

$conn->close();
?>