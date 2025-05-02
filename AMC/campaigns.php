<?php
header('Content-Type: application/json');
require '../db_connect.php';

// Configuración para subida de archivos
$uploadDir = '../uploads/campaigns/';
if (!file_exists($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

// Manejar diferentes métodos HTTP
$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        // Obtener listado de campañas
        $stmt = $conn->prepare("SELECT * FROM campañas ORDER BY fecha_creacion DESC");
        $stmt->execute();
        $campaigns = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        
        echo json_encode($campaigns);
        break;
        
    case 'POST':
        // Crear nueva campaña o borrador
        $name = $conn->real_escape_string($_POST['name']);
        $subject = $conn->real_escape_string($_POST['subject']);
        $message = $conn->real_escape_string($_POST['message']);
        $status = isset($_POST['status']) ? $conn->real_escape_string($_POST['status']) : 'Programada';
        $groupType = $conn->real_escape_string($_POST['group_type']);
        
        // Procesar adjuntos
        $attachments = [];
        if (!empty($_FILES['attachments'])) {
            foreach ($_FILES['attachments']['name'] as $key => $name) {
                if ($_FILES['attachments']['error'][$key] === UPLOAD_ERR_OK) {
                    $tmpName = $_FILES['attachments']['tmp_name'][$key];
                    $fileName = uniqid() . '_' . basename($name);
                    $filePath = $uploadDir . $fileName;
                    
                    if (move_uploaded_file($tmpName, $filePath)) {
                        $attachments[] = $fileName;
                    }
                }
            }
        }
        
        // Determinar destinatarios según el tipo de grupo
        $recipients = [];
        switch($groupType) {
            case 'all':
                $sql = "SELECT cod_cliente FROM todos_los_clientes WHERE correo IS NOT NULL AND correo != ''";
                break;
            case 'active':
                $sql = "SELECT cod_cliente FROM todos_los_clientes WHERE control_estado = 'Activo' AND correo IS NOT NULL AND correo != ''";
                break;
            case 'inactive':
                $sql = "SELECT cod_cliente FROM todos_los_clientes WHERE control_estado = 'Inactivo' AND correo IS NOT NULL AND correo != ''";
                break;
            case 'size':
                $size = $conn->real_escape_string($_POST['size_filter']);
                $sql = "SELECT cod_cliente FROM todos_los_clientes WHERE tamaño = '$size' AND correo IS NOT NULL AND correo != ''";
                break;
            case 'saved':
                $groupId = intval($_POST['saved_group_id']);
                $sql = "SELECT cliente_id FROM grupo_cliente WHERE grupo_id = $groupId";
                break;
            case 'custom':
                $customRecipients = json_decode($_POST['custom_recipients']);
                $ids = array_map([$conn, 'real_escape_string'], $customRecipients);
                $idsList = "'" . implode("','", $ids) . "'";
                $sql = "SELECT cod_cliente FROM todos_los_clientes WHERE cod_cliente IN ($idsList) AND correo IS NOT NULL AND correo != ''";
                break;
        }
        
        $result = $conn->query($sql);
        while ($row = $result->fetch_assoc()) {
            $recipients[] = $row['cod_cliente'];
        }
        $recipientsCount = count($recipients);
        
        // Guardar grupo si es necesario
        if (isset($_POST['save_as_group']) {
            $groupName = $conn->real_escape_string($_POST['new_group_name']);
            $conn->query("INSERT INTO grupos_correo (nombre_grupo, descripcion) VALUES ('$groupName', 'Creado desde campaña')");
            $groupId = $conn->insert_id;
            
            foreach ($recipients as $clientId) {
                $clientId = $conn->real_escape_string($clientId);
                $conn->query("INSERT INTO grupo_cliente (grupo_id, cliente_id) VALUES ($groupId, '$clientId')");
            }
        }
        
        // Guardar la campaña en la base de datos
        $attachmentsJson = json_encode($attachments);
        $now = date('Y-m-d H:i:s');
        $scheduleDate = isset($_POST['schedule_date']) ? $conn->real_escape_string($_POST['schedule_date']) : $now;
        
        $stmt = $conn->prepare("INSERT INTO campañas (nombre, asunto, mensaje, adjuntos, destinatarios, fecha_creacion, fecha_envio, estado, tipo_grupo) 
                               VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssissss", $name, $subject, $message, $attachmentsJson, $recipientsCount, $now, $scheduleDate, $status, $groupType);
        
        if ($stmt->execute()) {
            $campaignId = $conn->insert_id;
            
            // Guardar destinatarios
            foreach ($recipients as $clientId) {
                $clientId = $conn->real_escape_string($clientId);
                $emailQuery = $conn->query("SELECT correo FROM todos_los_clientes WHERE cod_cliente = '$clientId'");
                $email = $emailQuery->fetch_assoc()['correo'];
                
                $conn->query("INSERT INTO campaña_destinatarios (campaña_id, cliente_id, email) 
                              VALUES ($campaignId, '$clientId', '$email')");
            }
            
            echo json_encode(['message' => 'Campaña guardada correctamente', 'id' => $campaignId]);
        } else {
            http_response_code(500);
            echo json_encode(['message' => 'Error al guardar la campaña: ' . $conn->error]);
        }
        break;
        
    default:
        http_response_code(405);
        echo json_encode(['message' => 'Método no permitido']);
        break;
}

$conn->close();
?>