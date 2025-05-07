<?php
header('Content-Type: application/json');

// Sample data simulating the expected structure
$data = [
    'empresasPorMes' => [
        ['mes' => 1, 'anio' => 2025, 'total' => 50],
        ['mes' => 2, 'anio' => 2025, 'total' => 60],
        ['mes' => 3, 'anio' => 2025, 'total' => 70]
    ],
    'distribucionTamanio' => [
        ['tamaño' => 'Grande', 'total' => 30],
        ['tamaño' => 'Mediana', 'total' => 20],
        ['tamaño' => 'Pequeña', 'total' => 15],
        ['tamaño' => 'Micro', 'total' => 10]
    ],
    'examenesTop' => [
        ['examen' => 'Examen A', 'total' => 100],
        ['examen' => 'Examen B', 'total' => 80],
        ['examen' => 'Examen C', 'total' => 60]
    ],
    'evolucionPacientes' => [
        ['semana' => 1, 'anio' => 2025, 'total_pacientes' => 200],
        ['semana' => 2, 'anio' => 2025, 'total_pacientes' => 250],
        ['semana' => 3, 'anio' => 2025, 'total_pacientes' => 300]
    ],
    'tasaConversion' => [
        ['mes' => 1, 'anio' => 2025, 'tasa_conversion' => '75.50'],
        ['mes' => 2, 'anio' => 2025, 'tasa_conversion' => '80.00'],
        ['mes' => 3, 'anio' => 2025, 'tasa_conversion' => '82.30']
    ]
];

// Handle date filters (optional, implement as needed)
if (isset($_GET['fecha_inicio']) && isset($_GET['fecha_fin'])) {
    // Add logic to filter data by date range
    // For now, return the same sample data
}

echo json_encode($data);
?>