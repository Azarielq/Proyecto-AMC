<?php
require_once '../db_connect.php';

// Consulta para el porcentaje (ejemplo adaptado)
$sql = "SELECT 
          (COUNT(*) / (SELECT COUNT(*) FROM todos_los_clientes WHERE estado_cliente = 'Concretado')) * 100 AS porcentaje 
        FROM empresas_concretadas
        WHERE MONTH(fecha) = MONTH(CURRENT_DATE())";

$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    echo number_format($row["porcentaje"], 2);
} else {
    echo "0.00";
}

$conn->close();
?>