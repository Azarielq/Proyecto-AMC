<?php
header('Content-Type: application/json');
require_once '../db_connect.php';

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
    'empresas_concretadas' => 0,
    'empresas_no_concretadas' => 0,
    'total_pacientes' => 0,
    'tasa_conversion' => 0,
    'examen_top' => 'N/A',
    'empresas_medianas' => 0,
    'contactos_activos' => 0
];

try {
    // Construir condiciones WHERE para cada tabla según las fechas
    
    // 1. Empresas concretadas
    $whereConcretadas = "";
    if ($fechaInicio && $fechaFin) {
        $whereConcretadas = "WHERE fecha BETWEEN '$fechaInicio' AND '$fechaFin'";
    }
    $query = "SELECT COUNT(*) as total FROM empresas_concretadas $whereConcretadas";
    $result = $conn->query($query);
    $data['empresas_concretadas'] = $result->fetch_assoc()['total'];

    // 2. Empresas no concretadas
    $whereNoConcretadas = "";
    if ($fechaInicio && $fechaFin) {
        $whereNoConcretadas = "WHERE f_fecha BETWEEN '$fechaInicio' AND '$fechaFin'";
    }
    $query = "SELECT COUNT(*) as total FROM empresas_no_concretadas $whereNoConcretadas";
    $result = $conn->query($query);
    $data['empresas_no_concretadas'] = $result->fetch_assoc()['total'];

    // 3. Total de pacientes
    $query = "SELECT COALESCE(SUM(pacientes), 0) as total FROM empresas_concretadas $whereConcretadas";
    $result = $conn->query($query);
    $data['total_pacientes'] = $result->fetch_assoc()['total'];

    // 4. Tasa de conversión
    $totalEmpresas = $data['empresas_concretadas'] + $data['empresas_no_concretadas'];
    $data['tasa_conversion'] = $totalEmpresas > 0 ? 
        round(($data['empresas_concretadas'] / $totalEmpresas) * 100, 2) : 0;

    // 5. Examen más solicitado
    $query = "SELECT examen, COUNT(*) as total FROM empresas_concretadas $whereConcretadas
              GROUP BY examen ORDER BY total DESC LIMIT 1";
    $result = $conn->query($query);
    if ($result && $result->num_rows > 0) {
        $data['examen_top'] = $result->fetch_assoc()['examen'];
    }

    // 6. Empresas medianas
    $whereMedianas = $whereConcretadas ? 
        "$whereConcretadas AND tamaño = 'Mediana'" : 
        "WHERE tamaño = 'Mediana'";
    $query = "SELECT COUNT(*) as total FROM empresas_concretadas $whereMedianas";
    $result = $conn->query($query);
    $data['empresas_medianas'] = $result->fetch_assoc()['total'];

    // 7. Contactos activos
    $whereContactos = "WHERE control_estado = 'Activo'";
    if ($fechaInicio && $fechaFin) {
        $whereContactos = "WHERE f_fecha BETWEEN '$fechaInicio' AND '$fechaFin' 
                          AND control_estado = 'Activo'";
    }
    $query = "SELECT COUNT(*) as total FROM todos_los_clientes $whereContactos";
    $result = $conn->query($query);
    $data['contactos_activos'] = $result->fetch_assoc()['total'];

} catch (mysqli_sql_exception $e) {
    $data['error'] = $e->getMessage();
}

$conn->close();
echo json_encode($data);
?>