<?php
require_once '../db_connect.php';
session_start();

$response = ['success' => false, 'message' => ''];

try {
    if(empty($_POST['id']) || empty($_POST['adjunto'])) {
        throw new Exception('Datos incompletos para eliminar adjunto');
    }

    // Obtener los adjuntos actuales
    $stmt = $conn->prepare("SELECT adjuntos FROM campañas WHERE id = ?");
    $stmt->bind_param("i", $_POST['id']);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if($result->num_rows === 0) {
        throw new Exception('No se encontró la campaña');
    }
    
    $campana = $result->fetch_assoc();
    $adjuntos = json_decode($campana['adjuntos'], true);
    
    if(!empty($adjuntos)) {
        // Eliminar el adjunto del array
        $key = array_search($_POST['adjunto'], $adjuntos);
        if($key !== false) {
            unset($adjuntos[$key]);
            
            // Actualizar la base de datos
            $adjuntosJson = !empty($adjuntos) ? json_encode(array_values($adjuntos)) : null;
            
            $stmt = $conn->prepare("UPDATE campañas SET adjuntos = ? WHERE id = ?");
            $stmt->bind_param("si", $adjuntosJson, $_POST['id']);
            
            if($stmt->execute()) {
                // Eliminar el archivo físico
                $filePath = '../adjuntos_campanas/' . $_POST['adjunto'];
                if(file_exists($filePath)) {
                    unlink($filePath);
                }
                
                $response['success'] = true;
                $response['message'] = 'Adjunto eliminado correctamente';
            } else {
                throw new Exception('Error al actualizar la base de datos');
            }
        } else {
            throw new Exception('El adjunto no existe en esta campaña');
        }
    } else {
        throw new Exception('No hay adjuntos para eliminar');
    }
} catch (Exception $e) {
    $response['message'] = $e->getMessage();
}

header('Content-Type: application/json');
echo json_encode($response);
?>