<?php
function limitarTexto($texto, $limite = 10) {
    if (mb_strlen($texto) > $limite) {
        return mb_substr($texto, 0, $limite) . '...';
    }
    return $texto;
}

include '../../includes/db_connect.php'; 
require('fpdf186/fpdf.php'); 

$periodo = "Anual";

$query = "SELECT 
            p.*, 
            e.titulo AS projeto_titulo 
          FROM etapas p 
          LEFT JOIN projetos e ON p.projeto_id = e.projeto_id"; 
$result = mysqli_query($conn, $query);

class PDF extends FPDF {
    function Header() {
        $this->Image('../../imgs/obraplanner1.png',15, 11, 35); 
        $this->SetFont('Arial', 'B', 14);
        $this->SetX(10);
        $this->Cell(0, 10, utf8_decode('Relatório de Etapas - Anual'), 0, 1, 'C');
        $this->Ln(10);
    }

    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, '' . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }
}

$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial', '', 6);

$pdf->SetFillColor(0, 86, 179); 
$pdf->SetTextColor(255, 255, 255); 
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(8, 10, 'ID', 1, 0, 'C', true);
$pdf->Cell(25, 10, utf8_decode('Projeto'), 1, 0, 'C', true);
$pdf->Cell(25, 10, utf8_decode('Título'), 1, 0, 'C', true);
$pdf->Cell(20, 10, utf8_decode('Descrição'), 1, 0, 'C', true);
$pdf->Cell(20, 10, utf8_decode('Observações'), 1, 0, 'C', true);
$pdf->Cell(20, 10, utf8_decode('Data Início'), 1, 0, 'C', true);
$pdf->Cell(20, 10, utf8_decode('Data Término'), 1, 0, 'C', true);
$pdf->Cell(20, 10, utf8_decode('Dt/prev/ini'), 1, 0, 'C', true);
$pdf->Cell(20, 10, utf8_decode('Dt/prev/ter'), 1, 1, 'C', true);

$pdf->SetFillColor(255, 255, 255);
$pdf->SetTextColor(0, 0, 0);
$pdf->SetFont('Arial', '', 6);

$linhaAltura = 10;

while ($etapa = $result->fetch_assoc()) {
    $pdf->Cell(8, 10, $etapa['etapa_id'], 1, 0, 'C');
    $pdf->Cell(25, $linhaAltura, utf8_decode(limitarTexto($etapa['projeto_titulo'] ?: 'N/A')), 1, 0, 'C');
    $pdf->Cell(25, $linhaAltura, utf8_decode(limitarTexto($etapa['titulo'])), 1, 0, 'C');
    $pdf->Cell(20, $linhaAltura, utf8_decode(limitarTexto($etapa['descricao'])), 1, 0, 'C');
    $pdf->Cell(20, $linhaAltura, utf8_decode(limitarTexto($etapa['observacoes'])), 1, 0, 'C');
    $pdf->Cell(20, $linhaAltura, date("d/m/Y", strtotime($etapa['data_inicio'])), 1, 0, 'C');
    $pdf->Cell(20, $linhaAltura, date("d/m/Y", strtotime($etapa['data_termino'])), 1, 0, 'C');
    $pdf->Cell(20, $linhaAltura, date("d/m/Y", strtotime($etapa['data_previa_inicio'])), 1, 0, 'C');
    $pdf->Cell(20, $linhaAltura, date("d/m/Y", strtotime($etapa['data_previa_termino'])), 1, 1, 'C'); 

}

$pdf->Output('D', 'relatorio_etapas_' . strtolower($periodo) . '.pdf');
?>
