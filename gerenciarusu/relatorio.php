<?php
include '../../includes/db_connect.php'; 
require('fpdf186/fpdf.php'); 

function limitarTexto($texto, $limite = 10) {
    if (mb_strlen($texto) > $limite) {
        return mb_substr($texto, 0, $limite) . '...';
    }
    return $texto;
}

$periodo = "Mensal";  

function getUsuarios($conn, $periodo) {
    $data_limite = '';
    if ($periodo == "Anual") {
        $data_limite = "DATE_SUB(CURDATE(), INTERVAL 1 YEAR)";
    } elseif ($periodo == "Mensal") {
        $data_limite = "DATE_SUB(CURDATE(), INTERVAL 1 MONTH)";
    } elseif ($periodo == "Semana") {
        $data_limite = "DATE_SUB(CURDATE(), INTERVAL 1 WEEK)";
    }

    $sql = "SELECT usu_id, nome, email, tipo, data_cadastro FROM usuarios WHERE data_cadastro >= $data_limite";
    $result = $conn->query($sql);

    $usuarios = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $usuarios[] = $row;
        }
    }
    return $usuarios;
}

$usuarios = getUsuarios($conn, $periodo);

class PDF extends FPDF {
    function Header() {
        $this->Image('../../imgs/obraplanner1.png',15, 11, 35); 
        $this->SetFont('Arial', 'B', 14);
        $this->SetX(10);
        $this->Cell(0, 10, utf8_decode('Relatório de Usuários - Anual'), 0, 1, 'C');
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
$pdf->Cell(10, 10, 'ID', 1, 0, 'C', true);
$pdf->Cell(60, 10, 'Nome', 1, 0, 'C', true);
$pdf->Cell(50, 10, 'Email', 1, 0, 'C', true);
$pdf->Cell(20, 10, 'Tipo', 1, 0, 'C', true);
$pdf->Cell(40, 10, 'Data de Cadastro', 1, 1, 'C', true);

$pdf->SetFillColor(255, 255, 255);
$pdf->SetTextColor(0, 0, 0);
$pdf->SetFont('Arial', '', 6);

foreach ($usuarios as $usuario) {
    $pdf->Cell(10, 10, $usuario['usu_id'], 1, 0, 'C');
    $pdf->Cell(60, 10, utf8_decode(limitarTexto($usuario['nome'])), 1, 0, 'C'); 
    $pdf->Cell(50, 10, utf8_decode(limitarTexto($usuario['email'])), 1, 0, 'C');
    $pdf->Cell(20, 10, utf8_decode($usuario['tipo']), 1, 0, 'C'); 
    $pdf->Cell(40, 10, utf8_decode($usuario['data_cadastro']), 1, 1, 'C'); 
}

$pdf->Output('D', 'relatorio_usuarios_' . strtolower($periodo) . '.pdf');

$conn->close();
?>
