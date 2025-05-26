<?php
require '../db_connect.php';

if (isset($_GET['campana_id'])) {
    $campana_id = $_GET['campana_id'];
    
    $sql = "SELECT * FROM campaña_destinatarios WHERE campaña_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $campana_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $destinatarios = [];
    while ($row = $result->fetch_assoc()) {
        $destinatarios[] = $row;
    }
    
    header('Content-Type: application/json');
    echo json_encode($destinatarios);
} else {
    http_response_code(400);
    echo json_encode(['error' => 'ID de campaña no proporcionado']);
}
?>