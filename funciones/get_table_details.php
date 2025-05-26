<?php
header('Content-Type: application/json');

$tableType = $_GET['table_type'] ?? '';
$fechaInicio = $_GET['fecha_inicio'] ?? '';
$fechaFin = $_GET['fecha_fin'] ?? '';

if (!$tableType) {
    http_response_code(400);
    echo json_encode(['error' => 'Tipo de tabla no especificado']);
    exit;
}

// Simulación de datos detallados (reemplazar con lógica de base de datos real)
$details = [];
switch ($tableType) {
    case 'empresas':
        $details = [
            ['mes' => 1, 'anio' => 2025, 'total' => 50, 'detalle' => '10 grandes, 20 medianas, 20 pequeñas'],
            ['mes' => 2, 'anio' => 2025, 'total' => 60, 'detalle' => '15 grandes, 25 medianas, 20 pequeñas']
        ];
        break;
    case 'examenes':
        $details = [
            ['examen' => 'Examen A', 'total' => 100, 'detalle' => '50 hombres, 50 mujeres'],
            ['examen' => 'Examen B', 'total' => 80, 'detalle' => '40 hombres, 40 mujeres']
        ];
        break;
    case 'pacientes':
        $details = [
            ['semana' => 1, 'anio' => 2025, 'total_pacientes' => 200, 'detalle' => '100 nuevos, 100 recurrentes'],
            ['semana' => 2, 'anio' => 2025, 'total_pacientes' => 250, 'detalle' => '120 nuevos, 130 recurrentes']
        ];
        break;
    default:
        http_response_code(400);
        echo json_encode(['error' => 'Tipo de tabla no válido']);
        exit;
}

// Filtrar por fechas si es necesario (implementar lógica real)
echo json_encode($details);
?>