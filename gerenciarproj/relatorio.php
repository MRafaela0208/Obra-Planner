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
            e.nome AS empresa_nome 
          FROM projetos p 
          LEFT JOIN empresas e ON p.empresa_id = e.empresa_id"; 
$result = mysqli_query($conn, $query);


class PDF extends FPDF {
    function Header() {
        $this->Image('../../imgs/obraplanner1.png',15, 11, 35); 
        $this->SetFont('Arial', 'B', 14);
        $this->SetX(10);
        $this->Cell(0, 10, utf8_decode('Relatório de Projetos - Anual'), 0, 1, 'C');
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
$pdf->Cell(25, 10, utf8_decode('Empresa'), 1, 0, 'C', true);
$pdf->Cell(25, 10, utf8_decode('Título'), 1, 0, 'C', true);
$pdf->Cell(20, 10, utf8_decode('Status'), 1, 0, 'C', true);
$pdf->Cell(10, 10, utf8_decode('Fiscal'), 1, 0, 'C', true);
$pdf->Cell(20, 10, utf8_decode('Data Início'), 1, 0, 'C', true);
$pdf->Cell(20, 10, utf8_decode('Data Término'), 1, 0, 'C', true);
$pdf->Cell(20, 10, utf8_decode('Dt/prev/ini'), 1, 0, 'C', true);
$pdf->Cell(20, 10, utf8_decode('Dt/prev/ter'), 1, 0, 'C', true);
$pdf->Cell(20, 10, 'Img', 1, 1, 'C', true);  

$pdf->SetFillColor(255, 255, 255);
$pdf->SetTextColor(0, 0, 0);
$pdf->SetFont('Arial', '', 6);

$linhaAltura = 10;

while ($projeto = $result->fetch_assoc()) {
    $pdf->Cell(8, 10, $projeto['projeto_id'], 1, 0, 'C');
    $pdf->Cell(25, $linhaAltura, utf8_decode(limitarTexto($projeto['empresa_nome'] ?: 'N/A')), 1, 0, 'C');
    $pdf->Cell(25, $linhaAltura, utf8_decode(limitarTexto($projeto['titulo'])), 1, 0, 'C');
    $pdf->Cell(20, $linhaAltura, utf8_decode(limitarTexto($projeto['status'])), 1, 0, 'C');
    $pdf->Cell(10, $linhaAltura, isset($projeto['fiscal_id']) ? utf8_decode(limitarTexto($projeto['fiscal_id'])) : 'N/A', 1, 0, 'C'); 
    $pdf->Cell(20, $linhaAltura, date("d/m/Y", strtotime($projeto['data_inicio'])), 1, 0, 'C');
    $pdf->Cell(20, $linhaAltura, date("d/m/Y", strtotime($projeto['data_termino'])), 1, 0, 'C');
    $pdf->Cell(20, $linhaAltura, date("d/m/Y", strtotime($projeto['data_prev_ini'])), 1, 0, 'C');
    $pdf->Cell(20, $linhaAltura, date("d/m/Y", strtotime($projeto['data_prev_ter'])), 1, 0, 'C');
     
    if (!empty($projeto['imagem'])) {
        $imagem_caminho = "uploads/" . $projeto['imagem'];
        if (file_exists($imagem_caminho)) {
            $largura_maxima = 20;
            $altura_maxima = 10;
    
            list($largura_imagem, $altura_imagem) = getimagesize($imagem_caminho);
    
            $proporcao = min($largura_maxima / $largura_imagem, $altura_maxima / $altura_imagem);
    
            $nova_largura = $largura_imagem * $proporcao;
            $nova_altura = $altura_imagem * $proporcao;
    
            $pdf->Image($imagem_caminho, $pdf->GetX(), $pdf->GetY(), $nova_largura, $nova_altura);
        } else {
            $pdf->Cell(20, 10, 'Sem Img', 1, 1, 'C');
        }
    } else {
        $pdf->Cell(20, 10, 'Sem Img', 1, 1, 'C');
    }
    
    $pdf->Ln();
    
}

$pdf->Output('D', 'relatorio_projetos_' . strtolower($periodo) . '.pdf');
?>
