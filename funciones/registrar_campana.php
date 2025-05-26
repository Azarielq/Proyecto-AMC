<?php
require_once '../db_connect.php';
session_start();

$response = ['success' => false, 'message' => ''];

try {
    // Validar datos de entrada
    if(empty($_POST['nombre']) || empty($_POST['asunto']) || empty($_POST['mensaje']) || empty($_POST['tipo_grupo'])) {
        throw new Exception('Todos los campos marcados como obligatorios deben ser completados');
    }

    // Procesar adjuntos
    $adjuntos = [];
    if(!empty($_FILES['adjuntos'])) {
        $uploadDir = '../adjuntos_campanas/';
        if(!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        
        foreach($_FILES['adjuntos']['tmp_name'] as $key => $tmp_name) {
            $fileName = basename($_FILES['adjuntos']['name'][$key]);
            $filePath = $uploadDir . uniqid() . '_' . $fileName;
            
            if(move_uploaded_file($tmp_name, $filePath)) {
                $adjuntos[] = $fileName;
            }
        }
    }

    // Determinar destinatarios según el tipo de grupo
    $destinatarios = 0;
    $filtroGrupo = null;
    
    if($_POST['tipo_grupo'] == 'saved' && !empty($_POST['grupo_id'])) {
        // Contar clientes en el grupo seleccionado
        $stmt = $conn->prepare("SELECT COUNT(*) FROM grupo_cliente WHERE grupo_id = ?");
        $stmt->bind_param("i", $_POST['grupo_id']);
        $stmt->execute();
        $result = $stmt->get_result();
        $destinatarios = $result->fetch_row()[0];
        
        $filtroGrupo = json_encode($_POST['grupo_id']);
    } elseif($_POST['tipo_grupo'] == 'custom') {
        // Para grupos personalizados, se asume que se añadirán después
        $destinatarios = 0;
        $filtroGrupo = json_encode([]);
    }

    // Insertar la campaña en la base de datos
    $stmt = $conn->prepare("INSERT INTO campañas (
        nombre, asunto, mensaje, adjuntos, destinatarios, fecha_creacion, fecha_envio, 
        estado, tipo_grupo, filtro_grupo
    ) VALUES (?, ?, ?, ?, ?, NOW(), ?, ?, ?, ?)");
    
    $fechaEnvio = !empty($_POST['fecha_envio']) ? $_POST['fecha_envio'] : null;
    $adjuntosJson = !empty($adjuntos) ? json_encode($adjuntos) : null;
    
    $stmt->bind_param("ssssissss", 
        $_POST['nombre'],
        $_POST['asunto'],
        $_POST['mensaje'],
        $adjuntosJson,
        $destinatarios,
        $fechaEnvio,
        $_POST['estado'],
        $_POST['tipo_grupo'],
        $filtroGrupo
    );
    
    if($stmt->execute()) {
        $response['success'] = true;
        $response['message'] = 'Campaña registrada correctamente';
    } else {
        throw new Exception('Error al registrar la campaña en la base de datos: ' . $conn->error);
    }
} catch (Exception $e) {
    $response['message'] = $e->getMessage();
}

header('Content-Type: application/json');
echo json_encode($response);
?>