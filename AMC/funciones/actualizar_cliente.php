<?php
require '../db_connect.php';

$response = ['success' => false, 'message' => ''];

try {
    $data = $_POST;
    
    // Validar datos obligatorios
    if(empty($data['id']) || empty($data['cod_cliente']) || empty($data['nombre_cliente']) || 
       empty($data['control_estado']) || empty($data['estado_cliente'])) {
        throw new Exception('Campos obligatorios no completados');
    }
    
    // Preparar los campos y valores para la actualización
    $fields = [
        'cod_cliente', 'nombre_cliente', 'pacientes', 'movil', 'ruc', 'tamaño',
        'correo', 'linkedin', 'contacto', 'cargo', 'control_estado', 'mes',
        'año', 'estado_cliente', 'canal', 'examen', 'motivos', 'f_fecha',
        'ciclo', 'num_dia', 'semana'
    ];
    
    $setParts = [];
    $values = [];
    $types = '';
    
    foreach($fields as $field) {
        $setParts[] = "{$field} = ?";
        $values[] = $data[$field] ?? null;
        $types .= is_int($data[$field] ?? null) ? 'i' : 's';
    }
    
    // Agregar el ID al final para la condición WHERE
    $values[] = $data['id'];
    $types .= 'i';
    
    // Construir la consulta SQL
    $sql = "UPDATE todos_los_clientes SET " . implode(', ', $setParts) . " WHERE id = ?";
    
    $stmt = $conn->prepare($sql);
    if(!$stmt) {
        throw new Exception('Error al preparar la consulta: ' . $conn->error);
    }
    
    // Vincular parámetros dinámicamente
    $bindParams = array_merge([$types], $values);
    $refs = [];
    foreach($bindParams as $key => $value) {
        $refs[$key] = &$bindParams[$key]; 
    }
    
    call_user_func_array([$stmt, 'bind_param'], $refs);
    
    if($stmt->execute()) {
        $response['success'] = true;
        $response['message'] = 'Cliente actualizado correctamente';
    } else {
        throw new Exception('Error al ejecutar la actualización: ' . $stmt->error);
    }
} catch (Exception $e) {
    $response['message'] = $e->getMessage();
}

header('Content-Type: application/json');
echo json_encode($response);
?>