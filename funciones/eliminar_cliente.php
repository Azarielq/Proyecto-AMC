<?php
require '../db_connect.php';

$response = ['success' => false, 'message' => ''];

try {
    if(empty($_POST['id'])) {
        throw new Exception('ID de cliente no proporcionado');
    }
    
    $id = $_POST['id'];
    
    $sql = "DELETE FROM todos_los_clientes WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id);
    
    if($stmt->execute()) {
        $response['success'] = true;
        $response['message'] = 'Cliente eliminado correctamente';
    } else {
        throw new Exception('Error al eliminar cliente');
    }
} catch (Exception $e) {
    $response['message'] = $e->getMessage();
}

header('Content-Type: application/json');
echo json_encode($response);
?>