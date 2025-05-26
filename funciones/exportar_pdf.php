<?php
require('../fpdf/fpdf.php');
require '../db_connect.php';

// Crear PDF
$pdf = new FPDF();
$pdf->AddPage('L'); // Orientación horizontal
$pdf->SetFont('Arial','B',12);

// Título
$pdf->Cell(0,10,'Reporte de Clientes',0,1,'C');
$pdf->Ln(10);

// Cabeceras
$pdf->SetFont('Arial','B',10);
$pdf->Cell(30,10,'Codigo',1,0,'C');
$pdf->Cell(50,10,'Nombre',1,0,'C');
$pdf->Cell(30,10,'RUC',1,0,'C');
$pdf->Cell(30,10,'Tamaño',1,0,'C');
$pdf->Cell(50,10,'Contacto',1,0,'C');
$pdf->Cell(50,10,'Correo',1,0,'C');
$pdf->Cell(30,10,'Telefono',1,0,'C');
$pdf->Cell(30,10,'Estado',1,0,'C');
$pdf->Cell(30,10,'Estado Cliente',1,1,'C');

// Contenido
$pdf->SetFont('Arial','',8);

$sql = "SELECT * FROM todos_los_clientes ORDER BY nombre_cliente";
$result = $conn->query($sql);

while($row = $result->fetch_assoc()) {
    $pdf->Cell(30,10,$row['cod_cliente'],1,0,'C');
    $pdf->Cell(50,10,$row['nombre_cliente'],1,0,'L');
    $pdf->Cell(30,10,$row['ruc'],1,0,'C');
    $pdf->Cell(30,10,$row['tamaño'],1,0,'C');
    $pdf->Cell(50,10,$row['contacto'],1,0,'L');
    $pdf->Cell(50,10,$row['correo'],1,0,'L');
    $pdf->Cell(30,10,$row['movil'],1,0,'C');
    $pdf->Cell(30,10,$row['control_estado'],1,0,'C');
    $pdf->Cell(30,10,$row['estado_cliente'],1,1,'C');
}

$pdf->Output('D','reporte_clientes.pdf'); // Forzar descarga
?>