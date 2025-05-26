<?php
require_once '../db_connect.php';
session_start();

$response = ['success' => false, 'message' => ''];

try {
    if(empty($_POST['id'])) {
        throw new Exception('ID de campaña no proporcionado');
    }

    // Obtener información de la campaña
    $stmt = $conn->prepare("SELECT * FROM campañas WHERE id = ?");
    $stmt->bind_param("i", $_POST['id']);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if($result->num_rows === 0) {
        throw new Exception('No se encontró la campaña con el ID proporcionado');
    }
    
    $campana = $result->fetch_assoc();
    
    // Verificar que la campaña no haya sido enviada ya
    if($campana['estado'] === 'Enviada') {
        throw new Exception('Esta campaña ya ha sido enviada');
    }
    
    // Obtener destinatarios según el tipo de grupo
    $destinatarios = [];
    
    if($campana['tipo_grupo'] === 'saved' && !empty($campana['filtro_grupo'])) {
        $grupoId = json_decode($campana['filtro_grupo'], true);
        
        $stmt = $conn->prepare("
            SELECT c.cod_cliente, c.nombre_cliente, c.correo 
            FROM grupo_cliente gc
            JOIN todos_los_clientes c ON gc.cliente_id = c.cod_cliente
            WHERE gc.grupo_id = ?
        ");
        $stmt->bind_param("i", $grupoId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        while($row = $result->fetch_assoc()) {
            $destinatarios[] = $row;
        }
    } elseif($campana['tipo_grupo'] === 'custom' && !empty($campana['filtro_grupo'])) {
        $clientesIds = json_decode($campana['filtro_grupo'], true);
        
        if(is_array($clientesIds) && !empty($clientesIds)) {
            $placeholders = implode(',', array_fill(0, count($clientesIds), '?'));
            $types = str_repeat('s', count($clientesIds));
            
            $stmt = $conn->prepare("
                SELECT cod_cliente, nombre_cliente, correo 
                FROM todos_los_clientes 
                WHERE cod_cliente IN ($placeholders)
            ");
            $stmt->bind_param($types, ...$clientesIds);
            $stmt->execute();
            $result = $stmt->get_result();
            
            while($row = $result->fetch_assoc()) {
                $destinatarios[] = $row;
            }
        }
    }
    
    // Actualizar estado de la campaña a "Enviando"
    $stmt = $conn->prepare("UPDATE campañas SET estado = 'Enviando' WHERE id = ?");
    $stmt->bind_param("i", $_POST['id']);
    $stmt->execute();
    
    // Registrar destinatarios (si no existen ya)
    foreach($destinatarios as $destinatario) {
        $stmt = $conn->prepare("
            INSERT IGNORE INTO campaña_destinatarios 
            (campaña_id, cliente_id, email, enviado, estado) 
            VALUES (?, ?, ?, 0, 'Pendiente')
        ");
        $stmt->bind_param("iss", $_POST['id'], $destinatario['cod_cliente'], $destinatario['correo']);
        $stmt->execute();
    }
    
    // Aquí iría el código real para enviar los correos
    // Por simplicidad, simulamos el envío
    
    // Simular envío (en un sistema real, esto sería un proceso en segundo plano)
    $enviados = 0;
    $fallidos = 0;
    
    foreach($destinatarios as $destinatario) {
        // Simular éxito o fallo aleatorio (en un sistema real, esto sería el resultado real)
        $exito = rand(0, 1);
        
        if($exito) {
            $stmt = $conn->prepare("
                UPDATE campaña_destinatarios 
                SET enviado = 1, fecha_envio = NOW(), estado = 'Enviado' 
                WHERE campaña_id = ? AND cliente_id = ?
            ");
            $enviados++;
        } else {
            $stmt = $conn->prepare("
                UPDATE campaña_destinatarios 
                SET enviado = 0, fecha_envio = NOW(), estado = 'Fallido' 
                WHERE campaña_id = ? AND cliente_id = ?
            ");
            $fallidos++;
        }
        
        $stmt->bind_param("is", $_POST['id'], $destinatario['cod_cliente']);
        $stmt->execute();
    }
    
    // Actualizar estado final de la campaña
    $estadoFinal = ($fallidos > 0) ? 'Fallida' : 'Enviada';
    $stmt = $conn->prepare("
        UPDATE campañas 
        SET estado = ?, fecha_envio = NOW() 
        WHERE id = ?
    ");
    $stmt->bind_param("si", $estadoFinal, $_POST['id']);
    $stmt->execute();
    
    $response['success'] = true;
    $response['message'] = "Campaña enviada: $enviados exitosos, $fallidos fallidos";
} catch (Exception $e) {
    // Revertir estado si hubo error
    if(!empty($_POST['id'])) {
        $stmt = $conn->prepare("UPDATE campañas SET estado = 'Fallida' WHERE id = ?");
        $stmt->bind_param("i", $_POST['id']);
        $stmt->execute();
    }
    
    $response['message'] = $e->getMessage();
}

header('Content-Type: application/json');
echo json_encode($response);
?>