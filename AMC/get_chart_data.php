<?php
header('Content-Type: application/json');
require_once 'db_connect.php';

// Obtener parámetros de fecha si existen
$fechaInicio = isset($_GET['fecha_inicio']) ? $_GET['fecha_inicio'] : null;
$fechaFin = isset($_GET['fecha_fin']) ? $_GET['fecha_fin'] : null;

// Validar fechas
if ($fechaInicio && !DateTime::createFromFormat('Y-m-d', $fechaInicio)) {
    die(json_encode(['error' => 'Formato de fecha inicio inválido']));
}
if ($fechaFin && !DateTime::createFromFormat('Y-m-d', $fechaFin)) {
    die(json_encode(['error' => 'Formato de fecha fin inválido']));
}

// Inicializar array de datos
$data = [
    'empresasPorMes' => [],
    'distribucionTamanio' => [],
    'examenesTop' => [],
    'evolucionPacientes' => [],
    'tasaConversion' => []
];

try {
    // Construir condiciones WHERE según fechas
    $whereCondition = "";
    if ($fechaInicio && $fechaFin) {
        $whereCondition = "WHERE fecha BETWEEN '$fechaInicio' AND '$fechaFin'";
    } elseif ($fechaInicio) {
        $whereCondition = "WHERE fecha >= '$fechaInicio'";
    } elseif ($fechaFin) {
        $whereCondition = "WHERE fecha <= '$fechaFin'";
    }

    // 1. Empresas concretadas por mes
    $query = "SELECT 
                MONTH(fecha) as mes, 
                COUNT(*) as total,
                YEAR(fecha) as anio
              FROM empresas_concretadas
              $whereCondition
              GROUP BY YEAR(fecha), MONTH(fecha)
              ORDER BY anio, mes";
    
    $result = $conn->query($query);
    while ($row = $result->fetch_assoc()) {
        $data['empresasPorMes'][] = $row;
    }

    // 2. Distribución por tamaño de empresa
    $query = "SELECT 
                IFNULL(tamaño, 'No especificado') as tamaño, 
                COUNT(*) as total
              FROM empresas_concretadas
              $whereCondition
              GROUP BY tamaño";
    
    $result = $conn->query($query);
    while ($row = $result->fetch_assoc()) {
        $data['distribucionTamanio'][] = $row;
    }

    // 3. Exámenes más solicitados (top 5)
    $query = "SELECT 
                IFNULL(examen, 'No especificado') as examen, 
                COUNT(*) as total
              FROM empresas_concretadas
              $whereCondition
              GROUP BY examen
              ORDER BY total DESC
              LIMIT 5";
    
    $result = $conn->query($query);
    while ($row = $result->fetch_assoc()) {
        $data['examenesTop'][] = $row;
    }

    // 4. Evolución de pacientes por semana
    $query = "SELECT 
                WEEK(fecha) as semana, 
                SUM(pacientes) as total_pacientes,
                YEAR(fecha) as anio
              FROM empresas_concretadas
              $whereCondition
              GROUP BY YEAR(fecha), WEEK(fecha)
              ORDER BY anio, semana";
    
    $result = $conn->query($query);
    while ($row = $result->fetch_assoc()) {
        $data['evolucionPacientes'][] = $row;
    }

    // 5. Tasa de conversión mensual
    $whereTodosClientes = "";
    if ($fechaInicio && $fechaFin) {
        $whereTodosClientes = "WHERE f_fecha BETWEEN '$fechaInicio' AND '$fechaFin'";
    } elseif ($fechaInicio) {
        $whereTodosClientes = "WHERE f_fecha >= '$fechaInicio'";
    } elseif ($fechaFin) {
        $whereTodosClientes = "WHERE f_fecha <= '$fechaFin'";
    }

    $query = "SELECT 
                MONTH(f_fecha) as mes,
                YEAR(f_fecha) as anio,
                SUM(CASE WHEN estado_cliente = 'Concretado' THEN 1 ELSE 0 END) as concretados,
                COUNT(*) as total,
                (SUM(CASE WHEN estado_cliente = 'Concretado' THEN 1 ELSE 0 END) / COUNT(*) * 100) as tasa_conversion
              FROM todos_los_clientes
              $whereTodosClientes
              GROUP BY YEAR(f_fecha), MONTH(f_fecha)
              ORDER BY anio, mes";
    
    $result = $conn->query($query);
    while ($row = $result->fetch_assoc()) {
        $data['tasaConversion'][] = $row;
    }

} catch (mysqli_sql_exception $e) {
    $data['error'] = $e->getMessage();
}

$conn->close();
echo json_encode($data);
?>