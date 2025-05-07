<?php
require_once '../db_connect.php';
session_start();

$response = ['success' => false, 'message' => '', 'data' => [
    'total' => 0,
    'enviados' => 0,
    'fallidos' => 0,
    'destinatarios' => []
]];

try {
    if(empty($_GET['id'])) {
        throw new Exception('ID de campaña no proporcionado');
    }

    // Obtener conteos generales
    $stmt = $conn->prepare("
        SELECT 
            COUNT(*) as total,
            SUM(CASE WHEN enviado = 1 THEN 1 ELSE 0 END) as enviados,
            SUM(CASE WHEN estado = 'Fallido' THEN 1 ELSE 0 END) as fallidos
        FROM campaña_destinatarios 
        WHERE campaña_id = ?
    ");
    $stmt->bind_param("i", $_GET['id']);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if($result->num_rows > 0) {
        $counts = $result->fetch_assoc();
        $response['data']['total'] = $counts['total'];
        $response['data']['enviados'] = $counts['enviados'];
        $response['data']['fallidos'] = $counts['fallidos'];
    }
    
    // Obtener detalles de destinatarios
    $stmt = $conn->prepare("
        SELECT cd.*, c.nombre_cliente 
        FROM campaña_destinatarios cd
        LEFT JOIN todos_los_clientes c ON cd.cliente_id = c.cod_cliente
        WHERE cd.campaña_id = ?
        ORDER BY cd.fecha_envio DESC
    ");
    $stmt->bind_param("i", $_GET['id']);
    $stmt->execute();
    $result = $stmt->get_result();
    
    while($row = $result->fetch_assoc()) {
        $response['data']['destinatarios'][] = $row;
    }
    
    $response['success'] = true;
} catch (Exception $e) {
    $response['message'] = $e->getMessage();
}

header('Content-Type: application/json');
echo json_encode($response);
?>