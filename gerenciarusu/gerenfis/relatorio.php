<?php
include('../../../includes/db_connect.php'); 
require_once('fpdf186/fpdf.php'); 
function limitarTexto($texto, $limite = 14) {
    if (mb_strlen($texto) > $limite) {
        return mb_substr($texto, 0, $limite) . '...';
    }
    return $texto;
}
$periodo = "Anual"; 

$query = "SELECT * FROM fiscais";
$result = mysqli_query($conn, $query);

class PDF extends FPDF {
    function Header() {
        $this->Image('../../../imgs/obraplanner1.png',15, 11, 35); 
        $this->SetFont('Arial', 'B', 14);
        $this->SetX(10);
        $this->Cell(0, 10, utf8_decode('Relatório de Fiscais - Anual'), 0, 1, 'C');
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
$pdf->Cell(8, 10, utf8_decode('ID'), 1, 0, 'C',true);
$pdf->Cell(54, 10, utf8_decode('Nome'), 1, 0, 'C', true);
$pdf->Cell(40, 10, utf8_decode('Email'), 1, 0, 'C', true);
$pdf->Cell(25, 10, utf8_decode('Telefone'), 1, 0, 'C', true);
$pdf->Cell(30, 10, utf8_decode('CPF'), 1, 0, 'C', true);
$pdf->Cell(18, 10, utf8_decode('Ativa'), 1, 1, 'C',true);

$pdf->SetFillColor(255, 255, 255);
$pdf->SetTextColor(0, 0, 0);
$pdf->SetFont('Arial', '', 6);

while ($row = mysqli_fetch_assoc($result)) {
    $pdf->Cell(8, 10, utf8_decode($row['fiscal_id']), 1, 0, 'C');
    $pdf->Cell(54, 10, utf8_decode($row['nome']), 1, 0, 'C');
    $pdf->Cell(40, 10, utf8_decode($row['email']), 1, 0, 'C');
    $pdf->Cell(25, 10, utf8_decode($row['telefone']), 1, 0, 'C');
    $pdf->Cell(30, 10, utf8_decode($row['cpf']), 1, 0, 'C');
    $pdf->Cell(18, 10, utf8_decode($row['ativa']), 1, 1, 'C');
}
$pdf->Output('D', 'relatorio_fiscais_' . strtolower($periodo) . '.pdf');

exit;
?>
