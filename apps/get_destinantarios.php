<?php
require '../db_connect.php';

if (isset($_GET['campaña_id'])) {
    $campaña_id = $_GET['campaña_id'];
    $sql = "SELECT cd.*, tlc.nombre_cliente FROM campaña_destinatarios cd 
            LEFT JOIN todos_los_clientes tlc ON cd.cliente_id = tlc.cod_cliente 
            WHERE cd.campaña_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $campaña_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $destinatarios = [];
    while ($row = $result->fetch_assoc()) {
        $destinatarios[] = $row;
    }
    
    echo json_encode($destinatarios);
}
?>