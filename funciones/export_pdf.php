<?php
require '../fpdf/fpdf.php';

class PDF extends FPDF {
    function Header() {
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(0, 10, 'Reporte de Dashboard', 0, 1, 'C');
        $this->Ln(5);
    }

    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Página ' . $this->PageNo(), 0, 0, 'C');
    }

    function Table($header, $data) {
        $this->SetFont('Arial', 'B', 10);
        foreach ($header as $col) {
            $this->Cell(60, 7, $col, 1);
        }
        $this->Ln();
        
        $this->SetFont('Arial', '', 10);
        foreach ($data as $row) {
            foreach ($row as $col) {
                $this->Cell(60, 6, $col, 1);
            }
            $this->Ln();
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $chartType = $_POST['chart_type'] ?? '';
    $data = json_decode($_POST['data'] ?? '[]', true);
    $fechaInicio = $_POST['fecha_inicio'] ?? 'N/A';
    $fechaFin = $_POST['fecha_fin'] ?? 'N/A';

    $pdf = new PDF();
    $pdf->AddPage();
    
    $pdf->SetFont('Arial', 'I', 10);
    $pdf->Cell(0, 10, "Periodo: $fechaInicio - $fechaFin", 0, 1, 'C');
    $pdf->Ln(5);

    switch ($chartType) {
        case 'empresas':
            $header = ['Mes', 'Año', 'Total Empresas'];
            $pdf->Table($header, array_map(function($row) {
                return [$row['mes'], $row['anio'], $row['total']];
            }, $data));
            break;
        case 'distribucion':
            $header = ['Tamaño', 'Total'];
            $pdf->Table($header, array_map(function($row) {
                return [$row['tamaño'], $row['total']];
            }, $data));
            break;
        case 'examenes':
            $header = ['Examen', 'Total Solicitudes'];
            $pdf->Table($header, array_map(function($row) {
                return [$row['examen'], $row['total']];
            }, $data));
            break;
        case 'pacientes':
            $header = ['Semana', 'Año', 'Total Pacientes'];
            $pdf->Table($header, array_map(function($row) {
                return [$row['semana'], $row['anio'], $row['total_pacientes']];
            }, $data));
            break;
        case 'conversion':
            $header = ['Mes', 'Año', 'Tasa de Conversión (%)'];
            $pdf->Table($header, array_map(function($row) {
                return [$row['mes'], $row['anio'], $row['tasa_conversion']];
            }, $data));
            break;
        default:
            $pdf->SetFont('Arial', '', 12);
            $pdf->Cell(0, 10, 'Tipo de gráfico no válido', 0, 1, 'C');
    }

    $pdf->Output('D', 'reporte_dashboard.pdf');
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Método no permitido']);
}
?>