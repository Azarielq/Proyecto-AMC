<?php
require_once '../db_connect.php';
session_start();

$response = ['success' => false, 'message' => ''];

try {
    if(empty($_POST['id'])) {
        throw new Exception('ID de campaña no proporcionado');
    }

    // Primero eliminar los destinatarios asociados
    $stmt = $conn->prepare("DELETE FROM campaña_destinatarios WHERE campaña_id = ?");
    $stmt->bind_param("i", $_POST['id']);
    $stmt->execute();
    
    // Luego eliminar la campaña
    $stmt = $conn->prepare("DELETE FROM campañas WHERE id = ?");
    $stmt->bind_param("i", $_POST['id']);
    
    if($stmt->execute()) {
        $response['success'] = true;
        $response['message'] = 'Campaña eliminada correctamente';
    } else {
        throw new Exception('Error al eliminar la campaña de la base de datos: ' . $conn->error);
    }
} catch (Exception $e) {
    $response['message'] = $e->getMessage();
}

header('Content-Type: application/json');
echo json_encode($response);
?>