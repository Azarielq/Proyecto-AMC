<?php
// apps/campanas/generar_reporte.php

session_start();
if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../login.php");
    exit();
}

require '../db_connect.php';

// Obtener parámetros
$fecha_inicio = $_POST['fecha_inicio'];
$fecha_fin = $_POST['fecha_fin'];
$formato = $_POST['formato'];

// Consulta a la base de datos
$result = $conn->query("SELECT * FROM campañas 
                      WHERE fecha_envio BETWEEN '$fecha_inicio' AND '$fecha_fin'
                      ORDER BY fecha_envio DESC");

// Generar reporte según formato
if ($formato === 'excel') {
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment; filename="reporte_campañas.xls"');
    
    echo "<table border='1'>";
    echo "<tr><th>Nombre</th><th>Estado</th><th>Fecha Envío</th><th>Destinatarios</th></tr>";
    
    while($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>".$row['nombre']."</td>";
        echo "<td>".$row['estado']."</td>";
        echo "<td>".$row['fecha_envio']."</td>";
        echo "<td>".$row['destinatarios']."</td>";
        echo "</tr>";
    }
    echo "</table>";
    
} elseif ($formato === 'pdf') {
    require('../fpdf/fpdf.php');
    
    $pdf = new FPDF();
    $pdf->AddPage();
    $pdf->SetFont('Arial','B',12);
    
    $pdf->Cell(60,10,'Nombre',1);
    $pdf->Cell(40,10,'Estado',1);
    $pdf->Cell(50,10,'Fecha Envío',1);
    $pdf->Cell(40,10,'Destinatarios',1);
    $pdf->Ln();
    
    while($row = $result->fetch_assoc()) {
        $pdf->Cell(60,10,$row['nombre'],1);
        $pdf->Cell(40,10,$row['estado'],1);
        $pdf->Cell(50,10,$row['fecha_envio'],1);
        $pdf->Cell(40,10,$row['destinatarios'],1);
        $pdf->Ln();
    }
    
    $pdf->Output('D', 'reporte_campañas.pdf');
}