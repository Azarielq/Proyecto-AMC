<?php
require '../db_connect.php';

$response = ['success' => false, 'message' => ''];

try {
    if(empty($_GET['id'])) {
        throw new Exception('ID de cliente no proporcionado');
    }
    
    $id = $_GET['id'];
    
    $sql = "SELECT * FROM todos_los_clientes WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if($result->num_rows > 0) {
        $response['success'] = true;
        $response['data'] = $result->fetch_assoc();
    } else {
        throw new Exception('Cliente no encontrado');
    }
} catch (Exception $e) {
    $response['message'] = $e->getMessage();
}

header('Content-Type: application/json');
echo json_encode($response);
?>