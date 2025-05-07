<?php
require '../db_connect.php';

// Limpiar buffer de salida y forzar cabecera JSON
ob_start();
header('Content-Type: application/json');

$response = ['success' => false, 'message' => '', 'id' => null];

try {
    // Obtener datos de entrada (POST o JSON)
    $data = $_SERVER['REQUEST_METHOD'] === 'POST' ? $_POST : json_decode(file_get_contents('php://input'), true);
    
    if($data === null) {
        throw new Exception('Datos de entrada no válidos');
    }

    // Validar datos obligatorios
    $camposObligatorios = ['cod_cliente', 'nombre_cliente', 'control_estado', 'estado_cliente'];
    foreach($camposObligatorios as $campo) {
        if(empty($data[$campo])) {
            throw new Exception("El campo $campo es obligatorio");
        }
    }
    
    // Preparar datos para inserción
    $fields = [
        'cod_cliente', 'nombre_cliente', 'pacientes', 'movil', 'ruc', 'tamaño',
        'correo', 'linkedin', 'contacto', 'cargo', 'control_estado', 'mes',
        'año', 'estado_cliente', 'canal', 'examen', 'motivos', 'f_fecha',
        'ciclo', 'num_dia', 'semana'
    ];
    
    $values = [];
    $types = '';
    $params = [];
    
    foreach($fields as $field) {
        $value = $data[$field] ?? null;
        
        // Convertir vacíos a NULL
        $values[$field] = ($value === '' || $value === null) ? null : $value;
        
        // Determinar tipo de dato
        if(is_int($value)) {
            $types .= 'i';
        } elseif(is_float($value)) {
            $types .= 'd';
        } else {
            $types .= 's';
        }
        
        $params[] = &$values[$field];
    }
    
    // Construir consulta SQL
    $columns = '`' . implode('`, `', $fields) . '`';
    $placeholders = implode(', ', array_fill(0, count($fields), '?'));
    
    $sql = "INSERT INTO `todos_los_clientes` ($columns) VALUES ($placeholders)";
    
    $stmt = $conn->prepare($sql);
    if(!$stmt) {
        throw new Exception('Error al preparar la consulta: ' . $conn->error);
    }
    
    // Vincular parámetros
    array_unshift($params, $types);
    $bindResult = call_user_func_array([$stmt, 'bind_param'], $params);
    
    if(!$bindResult) {
        throw new Exception('Error al vincular parámetros: ' . $stmt->error);
    }
    
    if($stmt->execute()) {
        $response['success'] = true;
        $response['message'] = 'Cliente registrado correctamente';
        $response['id'] = $conn->insert_id;
    } else {
        throw new Exception('Error al ejecutar la consulta: ' . $stmt->error);
    }
} catch (Exception $e) {
    $response['message'] = $e->getMessage();
    error_log('Error en registrar_cliente.php: ' . $e->getMessage());
}

// Limpiar buffer y enviar respuesta
ob_end_clean();
echo json_encode($response);
exit();
?>