<?php
session_start();
require '../db_connect.php';

$response = ['success' => false];

try {
    $sql = "SELECT 
                COUNT(*) as total,
                SUM(CASE WHEN estado = 'Borrador' THEN 1 ELSE 0 END) as borrador,
                SUM(CASE WHEN estado = 'Programada' THEN 1 ELSE 0 END) as programadas,
                SUM(CASE WHEN estado = 'Enviada' THEN 1 ELSE 0 END) as enviadas,
                SUM(CASE WHEN estado = 'Fallida' THEN 1 ELSE 0 END) as fallidas
            FROM campañas";
    
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if($row = $result->fetch_assoc()) {
        $response['success'] = true;
        $response['data'] = $row;
    }
} catch (Exception $e) {
    $response['error'] = $e->getMessage();
}

header('Content-Type: application/json');
echo json_encode($response);
?>