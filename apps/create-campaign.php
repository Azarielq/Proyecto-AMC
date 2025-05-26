<?php
// create-campaign.php
require 'db_connect.php';

// Obtener datos del POST
$data = json_decode(file_get_contents('php://input'), true);

// Validar y sanitizar datos
$name = $conn->real_escape_string($data['name']);
$subject = $conn->real_escape_string($data['subject']);
$message = $conn->real_escape_string($data['message']);
$scheduleType = $conn->real_escape_string($data['schedule_type']);
$scheduleDate = $scheduleType === 'schedule' ? $conn->real_escape_string($data['schedule_date']) : null;
$saveAsGroup = $data['save_as_group'];
$newGroupName = $saveAsGroup ? $conn->real_escape_string($data['new_group_name']) : null;
$groupFilter = $data['group_filter'];

// Obtener destinatarios según el filtro
$recipients = [];
switch($groupFilter['type']) {
    case 'all':
        $sql = "SELECT cod_cliente, correo FROM todos_los_clientes WHERE correo IS NOT NULL AND correo != ''";
        break;
    case 'status':
        $status = $conn->real_escape_string($groupFilter['value']);
        $sql = "SELECT cod_cliente, correo FROM todos_los_clientes WHERE control_estado = '$status' AND correo IS NOT NULL AND correo != ''";
        break;
    case 'size':
        $size = $conn->real_escape_string($groupFilter['value']);
        $sql = "SELECT cod_cliente, correo FROM todos_los_clientes WHERE tamaño = '$size' AND correo IS NOT NULL AND correo != ''";
        break;
    case 'saved':
        $groupId = intval($groupFilter['value']);
        $sql = "SELECT gc.cod_cliente, tlc.correo 
                FROM grupo_cliente gc
                JOIN todos_los_clientes tlc ON gc.cliente_id = tlc.cod_cliente
                WHERE gc.grupo_id = $groupId AND tlc.correo IS NOT NULL AND tlc.correo != ''";
        break;
    case 'custom':
        $ids = array_map([$conn, 'real_escape_string'], $groupFilter['value']);
        $idsList = "'" . implode("','", $ids) . "'";
        $sql = "SELECT cod_cliente, correo FROM todos_los_clientes WHERE cod_cliente IN ($idsList) AND correo IS NOT NULL AND correo != ''";
        break;
}

$result = $conn->query($sql);
while ($row = $result->fetch_assoc()) {
    $recipients[] = [
        'id' => $row['cod_cliente'],
        'email' => $row['correo']
    ];
}

// Guardar grupo si es necesario
if ($saveAsGroup && $newGroupName) {
    $conn->query("INSERT INTO grupos_correo (nombre_grupo, descripcion) VALUES ('$newGroupName', 'Creado automáticamente desde campaña')");
    $groupId = $conn->insert_id;
    
    foreach ($recipients as $recipient) {
        $clientId = $conn->real_escape_string($recipient['id']);
        $conn->query("INSERT INTO grupo_cliente (grupo_id, cliente_id) VALUES ($groupId, '$clientId')");
    }
}

// Guardar la campaña en la base de datos
$attachments = json_encode($data['attachments']);
$recipientsCount = count($recipients);
$status = $scheduleType === 'now' ? 'Enviando' : 'Programada';
$now = date('Y-m-d H:i:s');

$conn->query("INSERT INTO campañas (nombre, asunto, mensaje, adjuntos, destinatarios, fecha_creacion, fecha_envio, estado) 
              VALUES ('$name', '$subject', '$message', '$attachments', $recipientsCount, '$now', " . 
              ($scheduleDate ? "'$scheduleDate'" : "NULL") . ", '$status')");
$campaignId = $conn->insert_id;

// Guardar los destinatarios de la campaña
foreach ($recipients as $recipient) {
    $clientId = $conn->real_escape_string($recipient['id']);
    $email = $conn->real_escape_string($recipient['email']);
    $conn->query("INSERT INTO campaña_destinatarios (campaña_id, cliente_id, email) VALUES ($campaignId, '$clientId', '$email')");
}

// Si es envío inmediato, poner en cola para enviar
if ($scheduleType === 'now') {
    // Aquí iría el código para poner en cola de envío
}

echo json_encode(['success' => true, 'campaign_id' => $campaignId]);
$conn->close();