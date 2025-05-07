<?php
require '../db_connect.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    $sql = "SELECT * FROM campañas WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $campana = $result->fetch_assoc();
        header('Content-Type: application/json');
        echo json_encode($campana);
    } else {
        http_response_code(404);
        echo json_encode(['error' => 'Campaña no encontrada']);
    }
} else {
    http_response_code(400);
    echo json_encode(['error' => 'ID no proporcionado']);
}
?>